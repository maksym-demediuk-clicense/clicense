<?php
@session_start();

require_once("../vendor/autoload.php"); 
require_once("../classes/_class.config.php");
require_once("../classes/_class.func.php");
require_once("../classes/_class.db.php");

//error_reporting(E_ERROR); // reports all errors
//ini_set("display_errors", "1"); // shows all errors
//ini_set("log_errors", 1);
//ini_set("error_log", "/tmp/php-error.log");

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
use Elliptic\EC;

$array = [];

if(isset($_GET['old_pass']) && isset($_GET['new_pass']) && isset($_GET['rep_pass'])) {
    $old = $func->injection($_GET['old_pass']);
    $new = $func->injection($_GET['new_pass']);
    $rep = $func->injection($_GET['rep_pass']);
    $check = $func->CheckPass($old, "Enter old password", "Invalid characters found");
    if (!$check) {
        $check = $func->CheckPass($new, "Enter new password", "Invalid characters found");
        if (!$check) {
            $check = $func->CheckPass($rep, "Repeat new password", "Invalid characters found");
            if (!$check) {
                if ($old != $new) {
                    if ($new == $rep) {
                        $query = sprintf("SELECT `password` FROM `db_users` WHERE `id` = %d", $_SESSION['user_id']);
                        $db->query($query);
                        $data = $db->FetchArray();
                        $old = $func->md5Password($old);
                        if ($data['password'] == $old) {
                            $query = sprintf("UPDATE `db_users` SET `password` = '%s' WHERE `id` = %d", $func->md5Password($new), $_SESSION['user_id']);
                            $db->query($query);
                            $array = array("success" => "Password was changed");
                        } else {
                            $array = array("error" => "Incorrect old password");
                        }

                    } else {
                        $array = array("error" => "Password mismatch");
                    }
                } else {
                    $array = array("error" => "New and old password cannot be the same");
                }
            } else {
                $array = array("error" => $check);
            }
        } else {
            $array = array("error" => $check);
        }
    } else {
        $array = array("error" => $check);
    }
}
else {
    if(isset($_GET['name']) && isset($_GET['version']) && isset($_GET['contract']) && isset($_GET['api']) && isset($_GET['account'])) {
		
		$version = $_GET['version'];
        //$version = !$func->IsNull($version) == true ? $version : 0;
        $name = $func->injection($_GET['name']);
		$contract_id = $_GET['contract'];
		$api_id = $_GET['api'];
		$account_id = $_GET['account'];

        if(!$func->IsNull($name) && !$func->IsNull($contract_id) && !$func->IsNull($api_id)){
            if($func->IsCorrectNameProduct($name)) {

				$db->query(sprintf("SELECT address, pkey FROM `db_eth_accounts` WHERE `id` = %d",$account_id));
				$sql_array = $db->FetchArray();
				$account_address = $sql_array['address'];
				$account_key = $sql_array['pkey'];
				
				$db->query(sprintf("SELECT host, chain_id FROM `db_eth_providers` WHERE `id` = %d",$api_id));
				$sql_array = $db->FetchArray();
				$api_host = $sql_array['host'];
				$chain_id = $sql_array['chain_id'];
				
				$db->query(sprintf("SELECT abi, bytecode FROM `db_eth_contracts` WHERE `id` = %d",$contract_id));
				$sql_array = $db->FetchArray();
				$contract_abi = $sql_array['abi'];
				$contract_bytecode = $sql_array['bytecode'];
				
				$web3 = new Web3(new HttpProvider(new HttpRequestManager($api_host)));
				
				$gasPrice = '';
				$web3->eth->gasPrice( function ($err, $_gasPrice) use(&$gasPrice) {
					if ($err !== null) {
						echo json_encode(array("error" => $err->getMessage()));
						return;
					}
					$gasPrice = Utils::toHex($_gasPrice, true);
				});
				
				
				$gasLimit = Utils::toHex(3000000, true);
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
				$contract_data = $contract_token->bytecode('0x'. $contract_bytecode)->getData($name, (int)$version);
				
				
				$transaction_raw = [
					'nonce' => $nonce,
					'gasPrice' => $gasPrice,
					'gasLimit' => $gasLimit,
					'data' => '0x' . $contract_data,
					'chainId' => $chain_id,
					'from' => $account_address
				];
				

                /*$secp256k1 = new EC('secp256k1');
                $privateKey = $secp256k1->keyFromPrivate($account_key, 'hex');
                $transaction = new Transaction($transaction_raw);
                $txHash = $transaction->hash(false);
                $bytes = $secp256k1->n->byteLength();
                $bkey = $privateKey->getPrivate()->toArray("be", $bytes);
                //$signature = $privateKey->sign($txHash, ['canonical' => true]);
				
				echo json_encode(array("error" => 'dd' . $bkey));
				//echo json_encode(array("error" => $transaction_raw['nonce']));
				return;*/
				
				$transaction = new Transaction($transaction_raw);
				$transaction_signed = $transaction->sign($account_key);
				//$transaction_serialized = $transaction_signed->serialize()->toString('hex');
				//echo json_encode(array("error" => $transaction_raw));	
				//echo json_encode(array("error" => $transaction_raw['nonce']));
				//return;
				$web3->eth->sendRawTransaction('0x' . $transaction_signed, function ($err, $hash) use (&$array, $web3, $db, $name, $version, $api_id, $contract_id, $account_id) {
					if ($err !== null) {
						echo json_encode(array("error" => $err->getMessage()));
						return;
					}
					
					$tx_id = $hash;
					
					$web3->eth->getTransactionReceipt($tx_id, function ($err, $transaction) use (&$array, $tx_id, $db, $name, $version, $api_id, $contract_id, $account_id) {
						if ($err !== null) {
							echo json_encode(array("error" => $err->getMessage()));
							return;
						}

						$db->query(sprintf("SELECT * FROM `db_products` WHERE `name` = '%s' AND `user_id` = %d ",$name, $_SESSION['user_id']));
							if($db->NumRows() == 0) {
								$db->query(sprintf("INSERT INTO `db_products` (`user_id`, `contract_id`, `account_id`, `provider_id`, `name`, `version`, `transaction_id`) VALUES (%d, %d, %d, %d, '%s', '%s', '%s')",
									$_SESSION['user_id'], $contract_id, $account_id, $api_id, $name, $version, $tx_id));
			
								$array = array("success" => "Product has been added");
								if ($transaction)
								{
									$contract_address = $transaction->contractAddress;
									$db->query(sprintf("UPDATE `db_products` SET `contract_address` = '%s' WHERE `tx_id` = '%s'", $contract_address, $tx_id));
								}
							}
							else {
								$array = array("error" => "Product already exists");
							}
					});

				});
				
            }
            else{
                $array = array("error" => "Invalid symbols found");
            }
        }
        else{
            $array = array("error" => "Enter product name");
        }
		

    }
    else {
        if(isset($_GET['cid']) && isset($_GET['cname']) && isset($_GET['cver'])){
            $id = intval($_GET['cid']);
            $name = $func->injection($_GET['cname']);
            $version = $func->injection($_GET['cver']);
            $version = !$func->IsNull($version) == true ? $version : "0";
            $result = $db->query(sprintf("SELECT * FROM `db_products` WHERE `id`= %d", $id));
            $info = $db->FetchArray();
            if($info['name'] != $name || $info['version'] != $version){
                $db->query(sprintf("UPDATE `db_products` SET `name` = '%s' , `version` = '%s', `last_change_date`= NOW() WHERE `id` = %d",
                    $name, $version, $id));
                $array = array("success" => "Product information was changed.");
            }
            else{
                $array = array("error" => "One of parameters must be changed.");
            }
        }
        else {
            if(isset($_GET['delid'])){
                $id = intval($_GET['delid']);
                $db->query(sprintf("DELETE FROM `db_products` WHERE `id` = %d", $id));
                $db->query(sprintf("DELETE FROM `db_keys` WHERE `product_id` = %d", $id));
                $db->query(sprintf("DELETE FROM `db_logs` WHERE `product_id` = %d", $id));
                $array = array("success" => "Product was deleted");

            }
            else {
                    if(isset($_GET['keyid'])){
                        $result = $db->query(sprintf("SELECT fn_BanKey(%d)", intval($_GET['keyid'])))->fetch_array();
                        $array = array($result[0] == 1 ? "error" : "success" => $result[0] == 1 ? "Ban" : "Unban");
                    }else{
                    if(isset($_GET['key_id']) && isset($_GET['key_val'])){
                        $key_id = intval($_GET['key_id']);
                        $key_value = $func->injection($_GET['key_val']);
                        if(!empty($key_value)){
                            if(preg_match("/^[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}$/", $key_value)){

                                $result = $db->query(sprintf("SELECT `value` FROM `db_keys` WHERE `id` = %d",$key_id))->fetch_array();
                                if($result[0] != $key_value) {
                                    $db->query(sprintf("UPDATE `db_keys` SET `value` = '%s' WHERE `id` = %d", $key_value, $key_id));
                                    $array = array("success" => "Key was changed");
                                }
                                else{
                                    $array = array("error" => "New key cannot be equial to old.");
                                }
                            } else {
                                $array = array("error" => "Key must have format XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX");
                            }
                        } else {
                            $array = array("error" => "Key cannot be empty");
                        }
                    }else{
                        if(isset($_GET['keyaid'])){
                            $result = $db->query(sprintf("SELECT fn_DeactiveKey(%d)", intval($_GET['keyaid'])))->fetch_array();
                            $array = array($result[0] == 1 ? "error" : "success" => $result[0] == 1 ? "Activated" : "Not activated");
                        }
                        else {
                            if(isset($_GET['newkey'])){
                                $plugin_id = intval($_GET['newkey']);
                                $db->query(sprintf("INSERT INTO `db_keys`(`product_id`, `value`, `activated`, `active`) VALUES ('%d','%s',0, 1);",$plugin_id ,$func->GUID()));
                                $query = sprintf("SELECT `id`, `value` FROM `db_keys` WHERE `product_id` = %d ORDER BY `id` DESC LIMIT 1",$plugin_id);
                                $result = $db->query($query)->fetch_array();
                                $array = array("id" => $result[0], "value" => $result[1]);
                            }else {
                                $array = array("error" => "Unknown error");
                            }
                        }

                    }
                }

            }
        }
    }
}

echo json_encode($array);	

?>