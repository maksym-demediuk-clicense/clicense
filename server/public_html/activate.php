<?php

require_once("vendor/autoload.php"); 
require_once("classes/_class.config.php");
require_once("classes/_class.func.php");
require_once("classes/_class.db.php");

use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Contract;
use Web3\Utils;
use Web3p\EthereumTx\Transaction;

$RESPONSE_INVALID_DATA = "0";
$RESPONSE_ALREADY_ACTIVATED = "1";
$RESPONSE_ACTIVATED = "2";

$response = $RESPONSE_INVALID_DATA;

if (!isset($_SERVER['HTTP_DATA']) || !isset($_SERVER['HTTP_KEY']))
        exit($RESPONSE_INVALID_DATA);

$data = hex2bin($_SERVER['HTTP_DATA']);
$key = hex2bin($_SERVER['HTTP_KEY']);

$config = new config;
$func = new func;
$db = new db($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);

$method = 'aes-128-cbc';
$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

$decr_data = openssl_decrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);

$KEY_LENGTH = 16;
$HWID_LENGTH = 32;

if (strlen($decr_data) != $KEY_LENGTH + $KEY_LENGTH + $HWID_LENGTH)
{
    exit($RESPONSE_INVALID_DATA);
}

$public_key = substr($decr_data, 0, $KEY_LENGTH);
$private_key = substr($decr_data, $KEY_LENGTH, $KEY_LENGTH);
$hwid = '0x' . substr($decr_data, $KEY_LENGTH * 2, $HWID_LENGTH);

$sql = "SELECT product_id, activated FROM `db_licenses` WHERE `public_key` = '$public_key' AND `private_key` = '$private_key'";
$db->Query($sql);

if ($db->NumRows())
{
    $row = $db->FetchArray();
    if ($row['activated'] == TRUE)
    {
        exit($RESPONSE_ALREADY_ACTIVATED);
    }

    $product_id = $row['product_id'];

    $sql = "SELECT * FROM `db_products` WHERE `id` = '$product_id'";
    $db->Query($sql);
    $sql_array = $db->FetchArray();

    $account_id = $sql_array['account_id'];
    $contract_id = $sql_array['contract_id'];
    $contract_address = $sql_array['contract_address'];
    $provider_id = $sql_array['provider_id'];

    $db->query(sprintf("SELECT address, pkey FROM `db_eth_accounts` WHERE `id` = %d",$account_id));
	$sql_array = $db->FetchArray();
	$account_address = $sql_array['address'];
    $account_key = $sql_array['pkey'];
    
    $db->query(sprintf("SELECT host, chain_id FROM `db_eth_providers` WHERE `id` = %d",$provider_id));
    $sql_array = $db->FetchArray();
    $api_host = $sql_array['host'];
    $chain_id = $sql_array['chain_id'];
    
    $db->query(sprintf("SELECT abi FROM `db_eth_contracts` WHERE `id` = %d",$contract_id));
    $sql_array = $db->FetchArray();
    $contract_abi = $sql_array['abi'];

    $web3 = new Web3(new HttpProvider(new HttpRequestManager($api_host)));
			
	$gasPrice = '';
	$web3->eth->gasPrice( function ($err, $_gasPrice) use(&$gasPrice) {
		if ($err !== null) {
			echo json_encode(array("error" => $err->getMessage()));
			return;
		}
		$gasPrice = Utils::toHex($_gasPrice, true);
	});
	
	
	$gasLimit = Utils::toHex(772136, true);
	$block = '';
	$web3->eth->getBlockByNumber('latest', false, function ($err, $_block) use(&$block){
		if ($err !== null) {
			echo json_encode(array("error" => $err->getMessage()));
			return;
		}
		$block = $_block;
	});
		
	$nonce = '';
	$web3->eth->getTransactionCount($account_address, 'pending', function ($err, $count) use(&$nonce) {
		if ($err !== null) {
			echo json_encode(array("error" => $err->getMessage()));
			return;
		}
		$nonce = Utils::toHex($count, true);
	});
    $contract_token = new Contract($web3->provider, $contract_abi);
    
	$contract_data = $contract_token->at($contract_address)->getData("activateLicense", '0x' . $public_key, strtolower($hwid));

    $transaction_raw = [
        'nonce' => $nonce,
        'gasPrice' => $gasPrice,
        'gasLimit' => $gasLimit,
        'data' => '0x' . $contract_data,
        'chainId' => $chain_id,
        'from' => $account_address,
        'to' => $contract_address
    ];

    $transaction = new Transaction($transaction_raw);
    $transaction_signed = $transaction->sign('0x'.$account_key);

    $web3->eth->sendRawTransaction('0x' . $transaction_signed, function ($err, $hash) use (&$response, $web3, $db, $public_key, $private_key) {
        if ($err !== null) {
            echo json_encode(array("error" => $err->getMessage()));
            return;
        }
        
        $tx_id = $hash;

        $db->query(sprintf("UPDATE `db_licenses` SET `activated` = TRUE, `transaction_activate` = '%s' WHERE `public_key` = '%s' AND `private_key` = '%s'",
        $tx_id, $public_key, $private_key));


    });

    $response = $RESPONSE_ACTIVATED;
    exit($RESPONSE_ACTIVATED);
}
else
{
    exit($RESPONSE_INVALID_DATA);
}

exit ($response);        
?>