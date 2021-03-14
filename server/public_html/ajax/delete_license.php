<?php
@session_start();

require_once("../vendor/autoload.php"); 
require_once("../classes/_class.config.php");
require_once("../classes/_class.func.php");
require_once("../classes/_class.db.php");

function __autoload($name){ include("../classes/_class.".$name.".php");}
$config = new config;
$func = new func;
$db = new db($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);

use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Contract;
use Web3\Utils;
use Web3p\EthereumTx\Transaction;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$array = [];

if(isset($_GET['public_key']) && isset($_GET['product_id'])) {
		
    $public_key = $_GET['public_key'];
    $product_id = $_GET['product_id'];

    $db->query(sprintf("SELECT * FROM `db_products` WHERE `id` = %d",$product_id));
    $sql_array = $db->FetchArray();
    $account_id = $sql_array['account_id'];
    $contract_id = $sql_array['contract_id'];
    $contract_address = $sql_array['contract_address'];
    $provider_id = $sql_array['provider_id'];
    $product_name = $sql_array['name'];

    $db->query(sprintf("SELECT address, pkey FROM `db_eth_accounts` WHERE `id` = %d",$account_id));
    $sql_array = $db->FetchArray();
    $account_address = $sql_array['address'];
    $account_key = $sql_array['pkey'];
    
    $db->query(sprintf("SELECT host, chain_id, tx_info_api FROM `db_eth_providers` WHERE `id` = %d",$provider_id));
    $sql_array = $db->FetchArray();
    $api_host = $sql_array['host'];
    $chain_id = $sql_array['chain_id'];
    $etherscan_url = $sql_array['tx_info_api'];
    
    $db->query(sprintf("SELECT abi FROM `db_eth_contracts` WHERE `id` = %d",$contract_id));
    $sql_array = $db->FetchArray();
    $contract_abi = $sql_array['abi'];

    $db->query(sprintf("SELECT email, public_key FROM `db_licenses` WHERE `id` = '%s'",$public_key));
    $sql_array = $db->FetchArray();
    $email = $sql_array['email'];
    $public_key = $sql_array['public_key'];
    
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
    $contract_data = $contract_token->at($contract_address)->getData("deleteLicense", '0x' . $public_key);
    
    
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
    $transaction_signed = $transaction->sign($account_key);
    
    $web3->eth->sendRawTransaction('0x' . $transaction_signed, function ($err, $hash) use (&$array, $web3, $db, $product_id, $email, $public_key, $product_name, $api_host, $contract_address, $etherscan_url) {
        if ($err !== null) {
            echo json_encode(array("error" => $err->getMessage()));
            return;
        }
        
        $tx_id = $hash;
        
        $web3->eth->getTransactionReceipt($tx_id, function ($err, $transaction) use (&$array, $tx_id, $db, $product_id, $email, $public_key, $product_name, $api_host, $contract_address, $etherscan_url) {
            if ($err !== null) {
                echo json_encode(array("error" => $err->getMessage()));
                return;
            }
                
            $db->query(sprintf("DELETE FROM `db_licenses` WHERE `public_key` = '%s' AND `product_id` = %d",
                $public_key, $product_id));

            $mailer = new PHPMailer();
            $mailer->isSMTP(); 
            $mailer->Host = "smtp.gmail.com"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
            $mailer->Port = 587; // TLS only
            $mailer->SMTPSecure = 'tls'; // ssl is deprecated
            $mailer->SMTPAuth = true;
            $mailer->Username = 'clicensemail@gmail.com'; // email
            $mailer->Password = 'CLicense123!@#'; // password
            $mailer->SetFrom('clicensemail@gmail.com', 'CLicense'); //Name is optional
            $mailer->Subject   = 'Your License for ' . $product_name . ' was removed';
            $mailer->Body      = "Your license " . $public_key. " was removed.\r\n\r\n Transaction info:\r\n" . $etherscan_url . 'tx/' . $tx_id;
            $mailer->AddAddress( $email);

            $mailer->Send();

            $array = array("success" => "License has been deleted");
        });
    });	

}



echo json_encode($array);	

?>