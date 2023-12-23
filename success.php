<?php
session_start();

include 'connection.php';
require 'vendor/autoload.php';


const PAYMENT_KEY = '4HmdGc6KheV7d0zfcSiW1Iuh6TZ01NvbNUVopPiXQf2NubmD9a5R5CQQLs7FxBtTrTg6VBfPlgLgUzNG84p3mhJSFFzkVx2AuP7oTweJYypDAMvF0j1h8kAnUsdpAGEH';
const MERCHANT_UUID = '5f9fdc5c-91ca-405a-a11b-6788f7e1dfe6';
const PAYOUT_KEY = 'ORj6Uxbpe8Ef2eXg7idhNjoHu7JWEjCIanPCgRubTTSVxmtFWdHV9E5b7lOF9GuHLlpPiWsXMTFAyNTMiyDUvVrA5zbkm347vlN6wbUsQ7NG6Tk2nsYz0obij6TVKHAh';

$payment = \Cryptomus\Api\Client::payment(PAYMENT_KEY, MERCHANT_UUID);
$payout = \Cryptomus\Api\Client::payout(PAYOUT_KEY, MERCHANT_UUID);





if(isset($_GET['id']))
{
    $data = ["order_id" => $_GET['id']];

    $result = $payment->info($data);
    $amount = $result['amount'];

    if($result['status']=='confirmed')
    {

        $q = "UPDATE `users` SET `balance` = `balance` + '$amount' WHERE `user_name`=''";
        $queryResult = mysqli_query($con,$q);

        echo '<h1>Payment success</h1>';
        exit();
    }

}




?>