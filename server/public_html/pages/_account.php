<?PHP
if(!isset($_SESSION["user_id"])){ Header("Location: /"); return; }

if(isset($_GET["sel"])){
	$second_menu = $func->injection($_GET["sel"]);
	switch($second_menu){
		case "404": include("pages/_404.php"); break;
		case "setting": include("pages/account/_setting.php"); break;
        case "products": include("pages/account/_products.php"); break;
        case "new": include("pages/account/_new.php"); break;
        case "licenses": include("pages/account/_licenses.php"); break;
        case "new_license": include("pages/account/_new_license.php"); break;

		case "exit":
            @session_destroy();
            Header("Location: /");
            return; break;
				
	default: @include("pages/_404.php"); break;
			
	}
			
}else @include("pages/account/_user_account.php");

?>