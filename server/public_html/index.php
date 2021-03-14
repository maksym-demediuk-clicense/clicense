<?php 
require_once("vendor/autoload.php"); 

/* Start to develop here. Best regards https://php-download.com/ */

function TimerSet(){
	list($seconds, $microSeconds) = explode(' ', microtime());
	return $seconds + (float) $microSeconds;
}
function GetPage($nav)
{
	$string = "";
	if(!strcmp($nav, $_GET['menu']))
	{
		$string = 'active';
	}
	return $string;
}

$_timer_a = TimerSet();
ini_set('date.timezone', 'Europe/Kiev');
@session_start();
if(!isset($_SESSION["user_id"]) and !isset($_SESSION['login']) and isset($_GET['menu']) and $_GET['menu'] != 'login')
{
	Header("Location: /login");
}

@ob_start();
function __autoload($name){ include("classes/_class.".$name.".php");}
require_once("classes/_class.config.php");
require_once("classes/_class.func.php");
require_once("classes/_class.db.php");
$config = new config;
$func = new func;
$db = new db($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);

@include("inc/_header.php");
		if(isset($_GET["menu"])){
		$menu = strval($_GET["menu"]);
			switch($menu){
				case "404": include("pages/_404.php"); break; 
				case "account": include("pages/_account.php"); break; 
				case "login": include("pages/_login.php"); break;
				default: @include("pages/_404.php"); break;
			}
		}else @include("pages/_index.php");
@include("inc/_footer.php");
$content = ob_get_contents();
ob_end_clean();
$content = str_replace("{!title!}", $title ,$content);
echo $content;
?>
