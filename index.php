<?php 
	require_once 'vendor/autoload.php';
	//https://github.com/paypal/PayPal-PHP-SDK/wiki/Installation-Composer


	$api = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            'ClientID',     // ClientID
            'ClientSecret'      // ClientSecret
        )
	);

	$payer = new \PayPal\Api\Payer();
	$payer->setPaymentMethod('paypal');

	$amount = new \PayPal\Api\Amount();
	$amount->setTotal('200');
	$amount->setCurrency('BRL');

	$transaction = new \PayPal\Api\Transaction();
	$transaction->setAmount($amount);

	//Gerar id único
	$transaction->setInvoiceNumber(time());

	$redirectUrls = new \PayPal\Api\RedirectUrls();
	$redirectUrls->setReturnUrl("http://localhost/paypal/obrigado.php")
	    ->setCancelUrl("http://localhost/paypal/cancelar.php");

	$payment = new \PayPal\Api\Payment();
	$payment->setIntent('sale')
    ->setPayer($payer)
    ->setTransactions(array($transaction))
    ->setRedirectUrls($redirectUrls);


    try {
    	$payment->create($api);
    	echo $payment;
    	header('Location: '.$payment->getApprovalLink());

	}
	catch (\PayPal\Exception\PayPalConnectionException $ex) {
	    echo $ex->getData();
	}

	//conferir o email da sua conta de testes em: https://developer.paypal.com/developer/accounts/


?>