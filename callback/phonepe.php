<?php
$currentUrl = $_SERVER['REQUEST_URI'];

$currentUrl = trim($currentUrl, '/');

$segments = explode('/', $currentUrl);

$segmentValue = end($segments);

if($_POST["code"] == "PAYMENT_SUCCESS"){
     
     $Status="Paid";
     
require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';

$gatewayModuleName = basename(__FILE__, '.php');

$gatewayParams = getGatewayVariables("phonepe");

if (!$gatewayParams['type']) {
    die("Module Not Activated");
}

$success = $_POST['code'];
$merchantId = $_POST['merchantId'];
$transactionId = $_POST['transactionId'];
$amount = $_POST['amount']/100;
$providerReferenceId = $_POST['providerReferenceId'];
$checksum = $_POST['checksum'];
$invoiceId =  $segmentValue;
$paymentmethod = $_POST['paymentmethod'];

// print_r($merchantId); exit;

$transactionStatus = $success ? 'Success' : 'Failure';

$secretKey = $gatewayParams['secretKey'];
if ($hash != md5($invoiceId . $transactionId . $amount . $secretKey)) {
    $transactionStatus = 'Hash Verification Failure';
    $success = false;
}

$servername = "localhost";
$username = "hostingh";
$password = "R$^QoAxe$4mx3ybXC3pUj8";
$dbname = "hostingh_whmc150";


$mysqli  = new mysqli($servername, $username, $password, $dbname);


if($mysqli->connect_errno ) {
            printf("Connect failed: %s<br />", $mysqli->connect_error);
            exit();
         }
      
        
$query = "SELECT * FROM tblinvoiceitems WHERE invoiceid='".$invoiceId."'";

$result = $mysqli->query($query);

if ($result === false) {
    echo "Error: " . $mysqli->error;
} else {
  
    while ($row = $result->fetch_assoc()) {
        $invoiceid = $row['invoiceid'];
        $paymentMethod = $row['paymentmethod'];
        
        $query =  "UPDATE tblinvoiceitems  SET paymentmethod = 'phonepe'  WHERE invoiceid ='".$invoiceId."'";
        
        $manoj = $mysqli->query($query);
    }
}
//Second Query

$query2 = "SELECT * FROM tblinvoices WHERE id='".$invoiceId."'";

$result2 = $mysqli->query($query2);
$invoice_row = $result2->fetch_assoc();
$userid = $invoice_row['userid'];


$query = "INSERT INTO tblaccounts (invoiceid, gateway, date, amountin, transid, userid ,currency , description ) VALUES ('".$invoiceId."', 'phonepe', NOW(),'".$amount."', '".$transactionId."' , '".$userid."' , 0 , 'Invoice payment')";
$result = $mysqli->query($query);
          
if ($result2 === false) {
    echo "Error: " . $mysqli->error;
} else {
    
	if ($result) {
		$last_insert_id_result = $mysqli->query("SELECT MAX(CAST(invoicenum AS SIGNED)) AS LAST_INVOICENUM_ID FROM `tblinvoices` where invoicenum REGEXP '^[0-9]+$' AND invoicenum IS NOT NULL");
		$row = $last_insert_id_result->fetch_assoc();
		$last_insert_id = '0'.strval(intval($row['LAST_INVOICENUM_ID']) + 1);
		$next_insert_id = '0'.strval(intval($row['LAST_INVOICENUM_ID']) + 2);
	} else {
		echo "Error executing query: " . $mysqli->error;
	}

	$query2 =  "UPDATE tblinvoices  SET status ='".$Status."', paymentmethod = 'phonepe', datepaid = NOW(), invoicenum = '".$last_insert_id."' WHERE id ='".$invoiceid."'";
	$manoj = $mysqli->query($query2);
	$query3 = "UPDATE `tblconfiguration` SET `value` = '".$next_insert_id."' WHERE `tblconfiguration`.`setting` = 'SequentialInvoiceNumberValue'";
	$manoj = $mysqli->query($query3);
}

$redirect_url = "https://www.hostinghome.in/login/viewinvoice.php?id=$invoiceId";

 $mysqli->close();


 echo "<script>window.location.href='".$redirect_url."'</script>";
}else{
     $Status="Unpaid";
     $invoiceId =  $segmentValue;
     $redirect_url = "https://www.hostinghome.in/login/viewinvoice.php?id=$invoiceId";

     echo "<script>window.location.href='".$redirect_url."'</script>";
 }
