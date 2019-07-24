<?php
/**
 * Created by PhpStorm.
 * User: M4hdi
 * Date: 7/21/2019
 * Time: 7:31 PM
 */


include "config.php";

use PayPal\Api\Payer as Payer;
use PayPal\Api\Amount as Amount;
use PayPal\Api\Transaction as Transaction;
use PayPal\Api\RedirectUrls as RedirectUrls;
use PayPal\Api\Payment as Payment;
use PayPal\Api\ItemList as ItemList;
use PayPal\Api\Item as Item;

function filter($data) {
    $data = str_replace(array("'","\"","*","+",",","^","!"),"",$data);
    $data = trim(htmlentities(strip_tags($data)));
    if (get_magic_quotes_gpc())
        $data = stripslashes($data);
    return $data;
}

if(isset($_POST['send_pay'])){
    $pay_code = time();
    $amount = filter($_POST['amount']);
    $currency = filter($_POST['currency']);

    $_pdo->query("INSERT INTO `payments`(`currency`, `price`, `payment_id`, `payer_id`, `date`, `pay_code`, `pay_status`)
VALUES ('$currency','$amount','0','0','".time()."','$pay_code','0')");


    $payer = new Payer();
    $payer->setPaymentMethod('paypal');
    $amountPayable = $amount;
    $invoiceNumber = $pay_code;

    $amount = new Amount();
    $amount->setCurrency($currency)
        ->setTotal($amountPayable);

    $item = new Item();
    $item->setName('Call')
        ->setCurrency($currency)
        ->setQuantity(1)
        ->setPrice($amountPayable);

    $itemList = new ItemList();
    $itemList->setItems(array($item));

    $transaction = new Transaction();
    $transaction->setAmount($amount)
        ->setDescription('Call')
        ->setInvoiceNumber($invoiceNumber)
        ->setItemList($itemList);

    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl($paypalConfig['return_url'])
        ->setCancelUrl($paypalConfig['cancel_url']);

    $payment = new Payment();
    $payment->setIntent('sale')
        ->setPayer($payer)
        ->setTransactions([$transaction])
        ->setRedirectUrls($redirectUrls);
    try {
        $payment->create($apiContext);
    } catch (Exception $e) {
        throw new Exception('Unable to create link for payment: '.$e);
    }
    header('location:'.$payment->getApprovalLink());
    exit;

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Paypal Payment</title>
</head>
<body>
<form action="" style="padding: 50px;" method="post">
    Amount :<br>
    <input type="text" name="amount" placeholder="Exampple: 5.00">
    <br><br>
    Currency :<br>
    <select name="currency">
        <option value="USD">USD</option>
        <option value="EUR">EUR</option>
    </select>
    <br><br>
    <input type="submit" value="Submit" name="send_pay">
</form>
</body>
</html>

