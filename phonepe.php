<?php

if (!defined("WHMCS")) {
     die("This file cannot be accessed directly");
 }
 
 
 function phonepe_MetaData()
 {
     return array(
         'DisplayName' => 'PhonePe',
         'APIVersion' => '1.1', // Use API Version 1.1
         'DisableLocalCreditCardInput' => true,
         'TokenisedStorage' => false,
     );
 }
 
 
 function phonepe_config()
 {
     return array(
         'FriendlyName' => array(
             'Type' => 'System',
             'Value' => 'PhonePe',
         ),
         
         'merchantID' => array(
             'FriendlyName' => 'Merchant ID',
             'Type' => 'text',
             'Size' => '25',
             'Default' => '',
             'Description' => 'Enter your Merchant ID here',
         ),
        
         'secretKey' => array(
             'FriendlyName' => 'Secret Key',
             'Type' => 'password',
             'Size' => '25',
             'Default' => '',
             'Description' => 'Enter secret key here',
         ),
         
         'textareaField' => array(
             'FriendlyName' => 'Textarea Field',
             'Type' => 'textarea',
             'Rows' => '3',
             'Cols' => '60',
             'Description' => 'Freeform multi-line text input field',
         ),
     );
 }
 
 
 function phonepe_link($params)
 {
     
     $MerchantId = $params['merchantID'];
     $secretKey = $params['secretKey'];
     $textareaField = $params['textareaField'];
 
     
     $invoiceId = $params['invoiceid'];
     $description = $params["description"];
     $amount = $params['amount'];
     $currencyCode = $params['currency'];
 
     
     $firstname = $params['clientdetails']['firstname'];
     $lastname = $params['clientdetails']['lastname'];
     $email = $params['clientdetails']['email'];
     $address1 = $params['clientdetails']['address1'];
     $address2 = $params['clientdetails']['address2'];
     $city = $params['clientdetails']['city'];
     $state = $params['clientdetails']['state'];
     $postcode = $params['clientdetails']['postcode'];
     $country = $params['clientdetails']['country'];
     $phone = $params['clientdetails']['phonenumber'];
 
     
     $companyName = $params['companyname'];
     $systemUrl = $params['systemurl'];
     $returnUrl = $params['returnurl'];
     $langPayNow = $params['langpaynow'];
     $moduleDisplayName = $params['name'];
     $moduleName = $params['paymentmethod'];
     $whmcsVersion = $params['whmcsVersion'];
 
     $url = 'https://www.hostinghome.in/login/modules/gateways/phonepegateway/phonepetest.php';
 
     $postfields = array();
     $postfields['username'] = $username;
     $postfields['invoiceid'] = $invoiceId;
     $postfields['description'] = $description;
     $postfields['amount'] = $amount;
     $postfields['currency'] = $currencyCode;
     $postfields['first_name'] = $firstname;
     $postfields['last_name'] = $lastname;
     $postfields['email'] = $email;
     $postfields['address1'] = $address1;
     $postfields['address2'] = $address2;
     $postfields['city'] = $city;
     $postfields['state'] = $state;
     $postfields['postcode'] = $postcode;
     $postfields['country'] = $country;
     $postfields['phone'] = $phone;
     $postfields['merchantID'] = $MerchantId;
     $postfields['secretKey'] = $secretKey;
     $postfields['merchantTransactionId'] = $transactionId;
     $postfields['paymentmethod']  = $moduleName;
     $postfields['callback_url'] = $systemUrl . 'login/modules/gateways/callback/' . $moduleName . '.php';
     $postfields['return_url'] = $returnUrl;
 
     $htmlOutput = '<form method="post" action="' . $url . '">';
     foreach ($postfields as $k => $v) {
         $htmlOutput .= '<input type="hidden" name="' . $k . '" value="' . urlencode($v) . '" />';
     }
     $htmlOutput .= '<input type="submit" value="' . $langPayNow . '" />';
     $htmlOutput .= '</form>';
 
     return $htmlOutput;
 }
 
 
 
//  function phonepe_refund($params)
//  {
//      // Gateway Configuration Parameters
//      $MerchantId = $params['merchantID'];
//      $secretKey = $params['secretKey'];
//      $textareaField = $params['textareaField'];
 
//      // Transaction Parameters
//      $transactionIdToRefund = $params['transid'];
//      $refundAmount = $params['amount'];
//      $currencyCode = $params['currency'];
 
//      // Client Parameters
//      $firstname = $params['clientdetails']['firstname'];
//      $lastname = $params['clientdetails']['lastname'];
//      $email = $params['clientdetails']['email'];
//      $address1 = $params['clientdetails']['address1'];
//      $address2 = $params['clientdetails']['address2'];
//      $city = $params['clientdetails']['city'];
//      $state = $params['clientdetails']['state'];
//      $postcode = $params['clientdetails']['postcode'];
//      $country = $params['clientdetails']['country'];
//      $phone = $params['clientdetails']['phonenumber'];
 
//      // System Parameters
//      $companyName = $params['companyname'];
//      $systemUrl = $params['systemurl'];
//      $langPayNow = $params['langpaynow'];
//      $moduleDisplayName = $params['name'];
//      $moduleName = $params['paymentmethod'];
//      $whmcsVersion = $params['whmcsVersion'];
 
//      // perform API call to initiate refund and interpret result
 
//      return array(
//          // 'success' if successful, otherwise 'declined', 'error' for failure
//          'status' => 'success',
//          // Data to be recorded in the gateway log - can be a string or array
//          'rawdata' => $responseData,
//          // Unique Transaction ID for the refund transaction
//          'transid' => $refundTransactionId,
//          // Optional fee amount for the fee value refunded
//          'fees' => $feeAmount,
//      );
//  }
 
 
//  function phonepe_cancelSubscription($params)
//  {
//      // Gateway Configuration Parameters
//      $MerchantId = $params['merchantID'];
//      $secretKey = $params['secretKey'];
//      $textareaField = $params['textareaField'];
 
//      // Subscription Parameters
//      $subscriptionIdToCancel = $params['subscriptionID'];
 
//      // System Parameters
//      $companyName = $params['companyname'];
//      $systemUrl = $params['systemurl'];
//      $langPayNow = $params['langpaynow'];
//      $moduleDisplayName = $params['name'];
//      $moduleName = $params['paymentmethod'];
//      $whmcsVersion = $params['whmcsVersion'];
 
//      // perform API call to cancel subscription and interpret result
 
//      return array(
//          // 'success' if successful, any other value for failure
//          'status' => 'success',
//          // Data to be recorded in the gateway log - can be a string or array
//          'rawdata' => $responseData,
//      );
//  }
 