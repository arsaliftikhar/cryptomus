<?php
session_start();

include 'connection.php';
require 'vendor/autoload.php';


const PAYMENT_KEY = '4HmdGc6KheV7d0zfcSiW1Iuh6TZ01NvbNUVopPiXQf2NubmD9a5R5CQQLs7FxBtTrTg6VBfPlgLgUzNG84p3mhJSFFzkVx2AuP7oTweJYypDAMvF0j1h8kAnUsdpAGEH';
const MERCHANT_UUID = '5f9fdc5c-91ca-405a-a11b-6788f7e1dfe6';
const PAYOUT_KEY = 'ORj6Uxbpe8Ef2eXg7idhNjoHu7JWEjCIanPCgRubTTSVxmtFWdHV9E5b7lOF9GuHLlpPiWsXMTFAyNTMiyDUvVrA5zbkm347vlN6wbUsQ7NG6Tk2nsYz0obij6TVKHAh';

$payment = \Cryptomus\Api\Client::payment(PAYMENT_KEY, MERCHANT_UUID);
$payout = \Cryptomus\Api\Client::payout(PAYOUT_KEY, MERCHANT_UUID);



if(isset($_POST['submit']))
{

    // print_r($_POST);
    // die();
    $method = mysqli_real_escape_string($con,$_POST['method']);
    $amount = $_POST['amount'];

    if(empty($amount) || $amount <= 0)
    {
          $_SESSION['errorMsg'] = "Invalid amount"; 
          header("location: ./"); 
          exit();
    }
    

    //random string generate function
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; // Characters to use
        $charLength = strlen($characters);
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charLength - 1)];
        }
    
        return $randomString;
    }
    $randomString = generateRandomString(20);
    
    
    if($method=='eth')
    {

        //create payment
        $data = [
            'amount' => $amount,
            'currency' => 'USD',
            'network' => 'ETH',
            'order_id' => $randomString,
            'url_return' => 'http://localhost/cryptomus/success.php?orderId='.$randomString,
            'url_callback' => 'http://localhost/cryptomus/success.php?orderId='.$randomString,
            'is_payment_multiple' => false,
            'lifetime' => '7200',
            'to_currency' => 'ETH'
        ];

        $result = $payment->create($data);
        
        if($result)
        {
            $q = "INSERT INTO `payments`(`uuid`, `order_id`, `amount`, `payer_amount`, `payer_currency`, `currency`, `network`, `address`, `payment_status`, `url`, `created_at`, `updated_at`)
                  VALUES ('" . $result['uuid'] . "', '" . $result['order_id'] . "', '" . $result['amount'] . "', '" . $result['payer_amount'] . "', '" . $result['payer_currency'] . "', '" . $result['currency'] . "', '" . $result['network'] . "', '" . $result['address'] . "', '" . $result['payment_status'] . "', '" . $result['url'] . "', '".$result['created_at']."', '".$result['updated_at']."')";
            $queryResult = mysqli_query($con,$q);

            echo "<pre>";
            print_r($result);
            echo "</pre>";
            die();

        }
        
    }
    else if($method=='btc')
    {
        //create payment
        $data = [
            'amount' => $amount,
            'currency' => 'USD',
            'network' => 'ETH',
            'order_id' => $randomString,
            'url_return' => 'http://localhost/cryptomus/success.php?orderId='.$randomString,
            'url_callback' => 'http://localhost/cryptomus/success.php?orderId='.$randomString,
            'is_payment_multiple' => false,
            'lifetime' => '7200',
            'to_currency' => 'ETH'
        ];

        $result = $payment->create($data);
        
        if($result)
        {
            $q = "INSERT INTO `payments`(`uuid`, `order_id`, `amount`, `payer_amount`, `payer_currency`, `currency`, `network`, `address`, `payment_status`, `url`, `created_at`, `updated_at`)
                  VALUES ('" . $result['uuid'] . "', '" . $result['order_id'] . "', '" . $result['amount'] . "', '" . $result['payer_amount'] . "', '" . $result['payer_currency'] . "', '" . $result['currency'] . "', '" . $result['network'] . "', '" . $result['address'] . "', '" . $result['payment_status'] . "', '" . $result['url'] . "', '".$result['created_at']."', '".$result['updated_at']."')";
            $queryResult = mysqli_query($con,$q);

            echo "<pre>";
            print_r($result);
            echo "</pre>";
            die();

        }
    }
    else
    {
        $_SESSION['errorMsg'] = 'Invalid payment method';
        header("location: ./");
        exit();
    }


}


// echo "<pre>";

//create payment

// $data = [
//     'amount' => '1000',    
//     'currency' => 'USD',    
//     'network' => 'ETH',    
//     'order_id' => '555123',   
//     'url_return' => 'https://example.com/return',    
//     'url_callback' => 'https://example.com/callback',    
//     'is_payment_multiple' => false,    
//     'lifetime' => '7200',    
//     'to_currency' => 'ETH'
// ];

// $result = $payment->create($data);

// print_r($result);



// $uuId = $result['uuid'];


// $q = "INSERT INTO `payments`(`uuid`, `order_id`, `amount`, `payer_amount`, `payer_currency`, `currency`, `network`, `address`, `payment_status`, `url`, `created_at`, `updated_at`)
//       VALUES ('" . $result['uuid'] . "', '" . $result['order_id'] . "', '" . $result['amount'] . "', '" . $result['payer_amount'] . "', '" . $result['payer_currency'] . "', '" . $result['currency'] . "', '" . $result['network'] . "', '" . $result['address'] . "', '" . $result['payment_status'] . "', '" . $result['url'] . "', '".$result['created_at']."', '".$result['updated_at']."')";
// $queryResult = mysqli_query($con,$q);







//check payment status with uuid
// $data = ["uuid" => "c53ec81e-7a85-4a91-98dc-358bd84b00e7"];

// $result = $payment->info($data);
// $amount = $result['amount'];

// if($result['status']=='confirmed')
// {
//     $q = "UPDATE `users` SET `balance` = `balance` + '$amount' WHERE `user_name`=''";
//     $queryResult = mysqli_query($con,$q);
// }

// print_r($result);



?>



<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
      

        <div class="container">
            <div class="row align-items-center" style="height: 70vh;">
                <div class="offset-md-4 col-md-4">
                    <?php
                    if(isset($_SESSION['errorMsg']))
                    {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> '.$_SESSION['errorMsg'].'
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
                      unset($_SESSION['errorMsg']);
                    }
                    ?>

                    <div class="card">
                        <div class="card-header">
                            Select Payment
                        </div>
                        <div class="card-body">
                            <form action="" method="post">
                                <label>Select Payment Method</label>
                                <select class="form-control mb-3" name="method">
                                    <option value="" hidden>--Select--</option>
                                    <option value="btc">BTC</option>
                                    <option value="eth">ETH</option>
                                </select>
                                <label>Enter amount</label>
                                <input type="number" class="form-control mb-3" name="amount">
                                <input type="submit" name="submit" class="btn btn-success w-100">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>




