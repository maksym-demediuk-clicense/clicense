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

if(isset($_GET['product_id'])) {
		
        $product_id = $_GET['product_id'];

        $db->query(sprintf("SELECT transaction_id, provider_id FROM `db_products` WHERE `id` = %d",$product_id));
        $sql_array = $db->FetchArray();
        $provider_id = $sql_array['provider_id'];
        $tx_id = $sql_array['transaction_id'];

        $db->query(sprintf("SELECT host FROM `db_eth_providers` WHERE `id` = %d",$provider_id));
        $sql_array = $db->FetchArray();
        $api_host = $sql_array['host'];

        $web3 = new Web3(new HttpProvider(new HttpRequestManager($api_host)));

        $web3->eth->getTransactionReceipt($tx_id, function ($err, $transaction) use (&$array, $db, $product_id) {
            if ($err !== null) {
                echo json_encode(array("error" => $err->getMessage()));
                return;
            }

            if (!empty($transaction))
            {
                $db->query(sprintf("UPDATE `db_products` SET `contract_address`='%s' WHERE `id` = %d",
                $transaction->contractAddress, $product_id));
            }
                
            $db->query(sprintf("UPDATE `db_products` SET `sync_date`=now() WHERE `id` = %d", $product_id));

            $array = array("success" => "Product has been added");
        });
}
echo json_encode($array);	
?>