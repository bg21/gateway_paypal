<?php 
	require_once 'vendor/autoload.php';
	//https://github.com/paypal/PayPal-PHP-SDK/wiki/Installation-Composer


	$api = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            'ClientID',     // ClientID
            'ClientSecret'      // ClientSecret
        )
	);

	$paymentId = $_GET['paymentId'];
	$payment = \PayPal\Api\Payment::get($paymentId, $api);

	$execute = new \PayPal\Api\PaymentExecution();

	$execute->setPayerId($_GET['PayerID']);

	try {
		$result = $payment->execute($execute, $api);
		try {
			$payment = \PayPal\Api\Payment::get($paymentId, $api);
			$status = $payment->getState();

			if($status == 'approved'){
				//Pagamento aprovado
				echo 'Pagamento aprovado.';
				$info = current($payment->getTransactions());
				$info = $info->toArray();
				$referencia = $info['invoice_number'];
				echo '<br>';
				echo 'Minha referência: '.$referencia;
			}else{
				//Não aprovado.
			}

		} catch (Exception $e) {
			echo $e->getMessage();
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}

?>