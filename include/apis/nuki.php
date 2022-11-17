<?php

function GetNukiID()
{
    global $config;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.nuki.io/smartlock');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Authorization: Bearer ' . $config['nuki_api_token'];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    return json_decode($result)[0]->smartlockId;

}

function GetPinID($slid, $name)
{
    global $config;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.nuki.io/smartlock/$slid/auth");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Authorization: Bearer ' . $config['nuki_api_token'];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    foreach (json_decode($result) as $key => $value) {
        if ($value->name == $name) {
            return $value->id;
        }
    }
    return 0;
}

function SendCodeToNuki($slid, $code, $phone, $duedate)
{
    global $config;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.nuki.io/smartlock/$slid/auth");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

    $options = array(
        'name' => $phone,
        'type' => 13,
        'code' => $code,
        'remoteAllowed' => false,
        'smartActionsEnabled' => false,
        'allowedUntilDate' => $duedate
    );

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($options));

    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Accept: application/json';
    $headers[] = 'Authorization: Bearer ' . $config['nuki_api_token'];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    SyncLock($slid);
}

function DeleteCode($slid, $pinid)
{
    global $config;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.nuki.io/smartlock/$slid/auth/$pinid");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');


    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Authorization: Bearer ' . $config['nuki_api_token'];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    SyncLock($slid);
}

function SyncLock($slid)
{
    global $config;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.nuki.io/smartlock/$slid/sync");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Authorization: Bearer ' . $config['nuki_api_token'];
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
}