<?php

// Read the incoming JSON from MPesa
$callbackData = file_get_contents('php://input');
$callback = json_decode($callbackData, true);

// You might want to log this data for debugging purposes
file_put_contents('payment_callback.log', print_r($callback, true), FILE_APPEND);

// Check if the payment was successful
if (isset($callback['Body']['stkCallback']['ResultCode']) && $callback['Body']['stkCallback']['ResultCode'] == 0) {
    // Payment was successful
    $metadata = $callback['Body']['stkCallback']['CallbackMetadata']['Item'];
    $amount = $metadata[0]['Value'];
    $transactionID = $metadata[1]['Value'];
    $phone = $metadata[4]['Value'];

    // Here, you should update your database to mark this payment as successful
    // and possibly activate the user's access based on the phone number

    // Example:
    // updateUserPaymentStatus($phone, $transactionID, $amount);

    // Log success
    file_put_contents('payment_success.log', "Payment successful for $phone: TransactionID: $transactionID, Amount: $amount\n", FILE_APPEND);
} else {
    // Payment failed or was canceled
    // Handle the failure case
    file_put_contents('payment_failure.log', "Payment failed or canceled: " . print_r($callback, true) . "\n", FILE_APPEND);
}

?>
