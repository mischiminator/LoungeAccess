<?php

require_once('apis/nuki.php');
require_once('apis/lox24.php');

$duedate = date("Y-m-d\\TH:i:sP", strtotime('+1 day'));
$phone = $_POST['inputPhone'];
$nukiid = GetNukiID();

if ($phoneID > 0) {
    $DB->IncreamentSeen($phoneID);
} else {
    $phoneID = $DB->InsertPhone($_POST['inputPhone']);
}

$code = GetNewCode();
SendCodeToNuki($nukiid, $code, $phone, $duedate);
do {
    $pinid = GetPinID($nukiid, $_POST['inputPhone']);
} while ($pinid == 0);
$DB->InsertCode($phoneID, $pinid, $code);
SendSMS($code, $phone);
DisplayMessage($phone);

function GetNewCode()
{
    global $DB;
    $code = 0;
    do {
        do {
            $code = rand(100000, 999999);
        } while (!preg_match('/^[1-9]+$/', $code));
    } while ($DB->CodeKnown($code));
    return $code;
}

function DisplayMessage($phone)
{
    global $config;
?>
<form class="form-signin" method="POST">
    <div class="text-center mb-4">
        <img class="mb-4" src="<?= $config['logofile'] ?>" alt="" width="72" height="72">
        <h1 class="h1 mb-3">Gigamot Charger Lounge</h1>
        <h1 class="h3 mb-3" lang="de">Hallo,<br />
            Dein Code wurde gesendet an<br />
            <strong><?= $phone ?></strong><br />
            Schaue in deine Nachrichten/SMS App wie er lautet.
        </h1>
        <h1 class="h3 mb-3" lang="en">Hello,<br />
            Your code was sent to<br />
            <strong><?= $phone ?></strong><br />
            Please check your Messages/SMS app.
        </h1>
    </div>
    <p class=" mt-5 mb-3 text-muted text-center">&copy; Gigamot GmbH <?= date("Y"); ?>
    </p>
</form>
    <?php
}
    ?>