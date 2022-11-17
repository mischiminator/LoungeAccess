<?php

require_once("config.php");
require_once("DB.php");
require_once('apis/nuki.php');

$slid = GetNukiID();
$codes = $DB->GetExpiredCodes();

foreach ($codes as $c) {
    DeleteCode($slid, $c[1]);
    $DB->SetDeleted($c[0]);
}