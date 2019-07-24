<?php
/**
 * Created by PhpStorm.
 * User: M4hdi
 * Date: 7/21/2019
 * Time: 7:31 PM
 */

include "config.php";

use PayPal\Api\Payment as Payment;
use PayPal\Api\PaymentExecution as PaymentExecution;


if(isset($_GET['back'])){

    if($_GET['back'] == "success"){
        if (empty($_GET['paymentId']) || empty($_GET['PayerID'])) {
            throw new Exception('The response is missing the paymentId and PayerID');
        }else{
            $paymentId = $_GET['paymentId'];
            $payerid = $_GET['PayerID'];
            $payment = Payment::get($paymentId, $apiContext);
            $execution = new PaymentExecution();
            $execution->setPayerId($payerid);
            try {
                $payment->execute($execution, $apiContext);
                 $state = $payment->getState();
                 $trans = $payment->getId();
                 $code = $payment->transactions[0]->invoice_number;echo "<br>";
                if($_pdo->query("SELECT * FROM `payments` WHERE pay_code='$code' AND pay_status!='1'")->rowCount()>0){
                    if($state == "approved"){
                        $_pdo->query("UPDATE `payments` SET `payment_id`='$paymentId',`payer_id`='$payerid',`date`='".time()."',`pay_status`='1' WHERE pay_code='$code'");
                        echo "Successfull Payment"."<br><br>";
                        echo "Transactions Payment Code :".$code;
                        exit();
                    } else {
                        echo "This Payment is Duplicate Transaction Or Not Compalte Payment";
                    }
                }else{
                    echo "This Payment Not Found Or Confirmed";
                }

            } catch (Exception $ex) {
                echo  "Error in Confirm Payment"."<br>";
                echo $ex."<br>";
                echo $ex->getCode()."<br>"; // Prints the Error Code
                echo $ex->getData()."<br>"; // Prints the detailed error message
            }
        }
        exit();
    }else if($_GET['back'] == "cancel") {
        echo "Cancel Payment by User";
    }

}