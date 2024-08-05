<?php

function initiateStkPush($amount, $phone, $reference) {
    $consumerKey = 'PULGVG1pZvhHvO9PMf1AiLgx4GBqXxyijkysIiMNKqGH4PyU'; // Replace with your Consumer Key
    $consumerSecret = '75tjJlcIQNtG8X7UhvVhNA0EGoZMX246p92gpxF5ApAzRdVT6u1BJsVsQbm6BMr5'; // Replace with your Consumer Secret
    $shortcode = '5011870'; // Replace with your Shortcode
    $passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'; // Replace with your Passkey
    $callbackUrl = 'https://yourdomain.com/stk/callback.php'; // Replace with your Callback URL
    $timestamp = date('YmdHis');
    $password = base64_encode($shortcode . $passkey . $timestamp);

    $accessTokenUrl = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $stkPushUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

    // Get access token
    $credentials = base64_encode($consumerKey . ':' . $consumerSecret);
    $headers = [
        'Authorization: Basic ' . $credentials,
        'Content-Type: application/x-www-form-urlencoded'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $accessTokenUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        die('Curl error: ' . curl_error($ch));
    }

    curl_close($ch);

    $result = json_decode($response);
    if (!$result || !isset($result->access_token)) {
        die('Failed to obtain access token.');
    }
    $accessToken = $result->access_token;

    // Initiate STK Push
    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ];
    $data = [
        'BusinessShortCode' => $shortcode,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'TransactionType' => 'CustomerBuyGoodsOnline',
        'Amount' => $amount,
        'PartyA' => $phone,
        'PartyB' => $shortcode,
        'PhoneNumber' => $phone,
        'CallBackURL' => $callbackUrl,
        'AccountReference' => $reference,
        'TransactionDesc' => 'Payment for Internet Access'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $stkPushUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        die('Curl error: ' . curl_error($ch));
    }

    curl_close($ch);

    $result = json_decode($response);

    // Log the result for debugging
    file_put_contents('stk_push.log', print_r($result, true), FILE_APPEND);

    return $result;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['plan'];
    $phone = $_POST['phone'];
    $reference = 'NetworkAccess_' . time();

    $stkResponse = initiateStkPush($amount, $phone, $reference);

    // Check the response
    if (isset($stkResponse->ResponseCode) && $stkResponse->ResponseCode == '0') {
        header('Location: thank_you.html');
    } else {
        die('STK Push failed: ' . (isset($stkResponse->errorMessage) ? $stkResponse->errorMessage : 'Unknown error'));
    }

    exit();
}
?>
