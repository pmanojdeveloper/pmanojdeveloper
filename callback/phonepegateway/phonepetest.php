<?php

// echo "hello"; 

// print_r($_POST); exit;
$transactionId = generateUniqueMerchantTransactionId();
 
 
function generateUniqueMerchantTransactionId() {
   
    $timestamp = time();
    $randomNumber = rand(1000, 9999);
    return "HH" . $timestamp . $randomNumber;
}


     $amount = $_POST['amount'];
     $invoiceId = $_POST['invoiceid'];
    //  $invoicenum = $_POST["invoicenum"];
     $MerchantId = $_POST['merchantID'];
     $secretKey = $_POST['secretKey'];
     $username = $_POST['username'];
     $phone = $_POST['phone'];
     $currencyCode  = $_POST['currency'] ;
     $firstname = $_POST['first_name'];
     $lastname = $_POST['last_name'];
     $email = $_POST['email'];
     $address1 = $_POST['address1'];
     $address2 = $_POST['address2'];
     $city = $_POST['city'];
     $state = $_POST['state'];
     $postcode = $_POST['postcode'];
     $country = $_POST['country'];
     $moduleName = $_POST['paymentmethod'];
//   $transactionId = $_POST['transid'];


  $data = array(
      "merchantId" => $MerchantId,
      "merchantUserId" => "Ravi",
      "merchantTransactionId" => $transactionId,
    //   "invoicenum" => $invoicenum,
      "username" => $username,
      "invoiceid" => $invoiceId,
      "amount" => $amount*100,
      "secretKey" => $secretKey,
      "currency" => $currencyCode,
      "first_name" => $firstname,
      "last_name" => $lastname,
      "email" => $email,
      "address1" => $address1,
      "address2" => $address2,
      "city" => $city,
      "state" => $state,
      "postcode" => $postcode,
      "country" => $country,
      "phone" => $phone,
      "paymentmethod" => $moduleName,
    //   "transid" => $transactionId,
      "redirectUrl" => "https://www.hostinghome.in/login/modules/gateways/callback/phonepe.php/$invoiceId",
      "redirectMode" => "POST",
      "callbackUrl" => "",
      "paymentInstrument" => array(
          "type" => "PAY_PAGE"
      )
  );
  
//   print_r($data); exit;
  
  // Convert the Payload to JSON and encode as Base64
  $payloadMain = base64_encode(json_encode($data));

  $payload = $payloadMain."/pg/v1/pay".$secretKey;
  $Checksum = hash('sha256', $payload);
  $Checksum = $Checksum.'###1';

//X-VERIFY  -	SHA256(base64 encoded payload + "/pg/v1/pay" + salt key) + ### + salt index

  $curl = curl_init();
  
  curl_setopt_array($curl, [
     CURLOPT_URL => "https://api.phonepe.com/apis/hermes/pg/v1/pay",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
      'request' => $payloadMain
    ]),
    CURLOPT_HTTPHEADER => [
      "Content-Type: application/json",
      "X-VERIFY: ".$Checksum,
      "accept: application/json"
    ],
  ]);

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
     
      header('Location: paymentfailed.php?cURLError='.$err);
  } else {
      $responseData = json_decode($response, true);
    
      $url = $responseData['data']['instrumentResponse']['redirectInfo']['url'];
     
    //   print_r($responseData); exit;
    
      echo "<script>window.location.href='".$url."'</script>";
   
   
  }

?>