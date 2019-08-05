
# PayPal-Php-Mysql

Paypal Payment with Php Mysql

<p align="center">
  <img   src="http://s8.picofile.com/file/8368808834/paypal_php.png">
</p>

## Quick Start

Download with Git : ```git clone https://github.com/mahdihmd/PayPal-Php-Mysql.git```

<b>Introduction</b> 

You can use this script for PayPal Business Payments. In this script all payments and transactions are stored in my sql database and you can manage and track all transactions.

You must complete the ```config.php``` file before running the program
Config DB Info
```
$dns = "mysql";
$dbName = "paypal";
$username = "root";
$password = "";

$_pdo = new PDO($dns . ':host=localhost;dbname=' . $dbName, $username, $password);
```
Paypal info and return urls
```
$paypalConfig = [
    'client_id' => 'Your Client id',
    'client_secret' => 'Your Client Secret',
    'return_url' => 'http://localhost/paypal/verify.php?back=success',
    'cancel_url' => 'http://localhost/paypal/verify.php?back=cancel'
];
```

<b>What does it do?</b> 

You first run this page and enter the amount and currency type and press the Submit button to send the payment to Paypal
```/paypal/index.php```
and after the payment is completed the transaction will return to the ```verify.php``` page with payment and confirmation information in the database. Saved, keep in mind that when a payment is made, a transaction record is created in the database and then sent to PayPal.

If your payment is successful you will receive the following message
<br>
 <b>Successfull Payment</b>
 <br>
 <b>Transactions Payment Code : 123456 <b>

###  more helps
Email : q6q4@yahoo.com
