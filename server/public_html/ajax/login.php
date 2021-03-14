<?php
@session_start();

function __autoload($name){ include("../classes/_class.".$name.".php");}
$config = new config;
$func = new func;
$login = $func->injection( $_GET['username']);
$password = $func->injection( $_GET['password']);


$db = new db($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);
$login = $func->IsLogin($login);
$password = $func->IsPassword($password);
$passwordmd = $func->md5Password($password);
$db->Query(sprintf("SELECT * FROM `db_users` WHERE `username` = '%s'", $login));
if($db->NumRows() == 1) {
	$data = $db->FetchArray();
	if(strtolower($data["password"]) == strtolower($passwordmd)) {
        $_SESSION["user_id"] = $data['id'];
		$_SESSION['username'] = $data['username'];
        $array = array("success" => "You signed in successfully");
	}
	else {
		$array = array("error" => "Invalid username or password");
	}
}
else
{
	$array = array("error" => "Invalid username or password");
}
echo json_encode($array);
?>