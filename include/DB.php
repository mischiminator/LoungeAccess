<?php
class DB
{
    private $conn = null;

    function __construct()
    {
        global $config;
        try {
            $this->conn = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']}", $config['db_user'], $config['db_pass']);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    function CodeKnown($code): bool
    {
        $param = array(":code" => $code);
        $query = $this->Query("SELECT 1 FROM codes WHERE code = :code", $param);
        return $query->rowCount() > 0;
    }

    function GetExpiredCodes()
    {
        $param = array(":date" => date("Y-m-d H:i:s", strtotime("-1 day")));
        $sql = "SELECT id, nuki_id FROM codes WHERE deleted = 0 AND checkin < :date";
        return $this->Query($sql, $param)->fetchAll(PDO::FETCH_NUM);
    }

    function HasValidCode($phoneid): bool
    {
        $param = array(":date" => date("Y-m-d H:i:s", strtotime("-1 day")));
        $sql = "SELECT 1 FROM codes WHERE deleted = 0 AND checkin > :date";
        $query = $this->Query($sql, $param);
        return $query->rowCount() > 0;
    }

    function IncreamentSeen($phoneID): void
    {
        $param = array(":phoneid" => $phoneID);
        $this->Query("UPDATE phone SET seen = seen + 1 WHERE id = :phoneid", $param);
    }

    function InsertCode($phoneid, $pinid, $code): void
    {
        $param = array(
            ":phoneid" => $phoneid,
            ":pinid" => $pinid,
            ":code" => $code,
            ":ip" => "'" . $_SERVER['REMOTE_ADDR'] . "'",
        );

        $sql = "INSERT INTO codes(ip, phone_id, code, nuki_id) VALUES (:ip, :phoneid, :code, :pinid)";

        $query = $this->Query($sql, $param);
    }

    function InsertPhone($phone): int
    {
        $param = array(":phone" => $phone);
        $sql = "INSERT INTO phone (number) VALUES (:phone)";
        $this->Query($sql, $param);
        return $this->conn->lastInsertId();
    }

    function PhoneKnown($phone): int
    {
        $param = array(":num" => $phone);
        $query = $this->Query("SELECT id FROM phone WHERE number = :num", $param);
        return $query->rowCount() > 0 ? $query->fetch(PDO::FETCH_ASSOC)['id'] : 0;
    }

    private function Query($sql, $params): PDOStatement
    {
        $query = $this->conn->prepare($sql);
        try {
            $query->execute($params);
        } catch (PDOException $e) {
            var_dump($e->getMessage());
        }
        return $query;
    }

    function SetDeleted($id)
    {
        $param = array(":id" => $id);
        $sql = "UPDATE codes SET deleted = 1 WHERE id = :id";
        $this->Query($sql, $param);
    }
}

$DB = new DB();

?>