<?php
/**
 * Created by PhpStorm.
 * User: M4hdi
 * Date: 7/21/2019
 * Time: 8:03 PM
 */
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 0);

require 'vendor/autoload.php';

////////////////Database Config ///////////////
$dns = "mysql";
$dbName = "paypal";
$username = "root";
$password = "";

$_pdo = new PDO($dns . ':host=localhost;dbname=' . $dbName, $username, $password);



////Paypal Config///////////

function getApiContext($clientId, $clientSecret, $enableSandbox = false)
{
    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential($clientId, $clientSecret)
    );
    $apiContext->setConfig([
        'mode' => $enableSandbox ? 'sandbox' : 'live'
    ]);
    return $apiContext;
}

//If you change the value of this option and set "False" Then A real payment becomes
$enableSandbox = true;

$paypalConfig = [
    'client_id' => 'Your Client id',
    'client_secret' => 'Your Client Secret',
    'return_url' => 'http://localhost/paypal/verify.php?back=success',
    'cancel_url' => 'http://localhost/paypal/verify.php?back=cancel'
];

$apiContext = getApiContext(
    $paypalConfig['client_id'],
    $paypalConfig['client_secret'],
    $enableSandbox
);

$apiContext->setConfig([
    'mode' => $enableSandbox ? 'sandbox' : 'live',
    'log.FileName' => '../some-paypal-log-file.log',
    'log.LogLevel' => $enableSandbox ? 'DEBUG' : 'INFO'
]);
