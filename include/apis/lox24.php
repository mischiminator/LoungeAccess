<?php

function SendSMS($code, $phone)
{

    global $config;

    $uri = 'https://api.lox24.eu/sms';

    $token = $config['lox24_api_token'];

    $body = [
        'sender_id' => 'Gigamot',
        'text' => "Hallo!\nWillkommen bei Gigamot\nDein Code lautet: $code",
        'service_code' => 'direct',
        'phone' => $phone,
        'is_unicode' => true,
        'voice_lang' => 'DE',
    ];

    if (!$body = json_encode($body)) {
        die('JSON encoding error!');
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_URL => $uri,
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => [
            "X-LOX24-AUTH-TOKEN: {$token}",
            'Accept: application/json',
            // or application/ld+json
            'Content-Type: application/json',
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    ]);

    $response = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $data = json_decode($response, JSON_OBJECT_AS_ARRAY);

    if (201 === $code) {
        echo 'Success: response data = ' . var_export($data, true);
    } else {
        echo "Error: code = {$code}, data = " . var_export($data, true);
    }
}