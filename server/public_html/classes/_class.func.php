<?PHP
class func{

	public $UserIP = "Undefined";
	public $UserCode = "Undefined";
	public $TableID = -1;
	public $UserAgent = "Undefined";

	public function __construct(){
		$this->UserIP = $this->GetUserIp();
		$this->UserCode = $this->IpCode();
		$this->UserAgent = $this->UserAgent();
	}
	
	public function __destruct(){	
	}

	public function IpToInt($ip){ 
	
		$ip = ip2long($ip); 
		($ip < 0) ? $ip+=4294967296 : true; 
		return $ip; 
	}

	public function IntToIP($int){ 
  		return long2ip($int);  
	}

	public function GetUserIp(){
	
		if($this->UserIP == "Undefined"){
			
			if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND !empty($_SERVER['HTTP_X_FORWARDED_FOR']))
   			{
				
			$client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : "unknown" );
      		$entries = explode('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);

      		reset($entries);
				
				while (list(, $entry) = each($entries))
				{
				$entry = trim($entry);
					if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
				 	{					
					$private_ip = array(
						  '/^0\./',
						  '/^127\.0\.0\.1/',
						  '/^192\.168\..*/',
						  '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
						  '/^10\..*/');		
						$found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
		
						if ($client_ip != $found_ip)
						{
					   	$client_ip = $found_ip;
					   	break;
						}						
					}
				}
			$this->UserIP = $client_ip;
			return $client_ip;
			}else return ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : "unknown" );
		}else return $this->UserIP;
	}	

	public function IsLogin($login, $mask = "^[a-zA-Z0-9]", $len = "{2,24}")
	{
		return (is_array($login)) ? false : (preg_match("/{$mask}{$len}$/", $login)) ? $login : false;
	}
	public function IsName($login, $mask = "^[a-zA-Z0-9а-яА-Я]", $len = "{2,24}")
	{
		return (is_array($login)) ? false : (preg_match("/{$mask}{$len}$/", $login)) ? $login : false;
	}

	public function IsPassword($password, $mask = "^[a-zA-Z0-9]", $len = "{6,20}")
	{
		return (is_array($password)) ? false : (preg_match("/{$mask}{$len}$/", $password)) ? $password : false;
	}
	
	public function IsMail($mail){
				//if(is_array($mail) && empty($mail) && strlen($mail) > 255 && strpos($mail,'@') > 64) return false;
		//	return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $mail)) ? false : strtolower($mail);

        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
	}
	
	public function IpCode()
	{
		$arr_mask = explode(".",$this->GetUserIp());
		return $arr_mask[0].".".$arr_mask[1].".".$arr_mask[2].".0";
	}
	
	public function GetTime($time, $template = "d.m.Y в H:i:s"){
        return date($template,$time);
	}
	
	public function UserAgent(){
		
		return $this->TextClean($_SERVER['HTTP_USER_AGENT']);
		
	}
	
	public function TextClean($text){
		$array_find = array("`", "<", ">", "^", '"', "~", "\\");
		$array_replace = array("&#96;", "&lt;", "&gt;", "&circ;", "&quot;", "&tilde;", "");
		return str_replace($array_find, $array_replace, $text);
	}
	
	public function ShowError($errorArray = array(), $title = "Fix following errors"){
		
		if(count($errorArray) > 0){
		
		$string_a = "<div class='Error'><div class='ErrorTitle'>".$title."</div><ul>";
		
			foreach($errorArray as $number => $value){
				
				$string_a .= "<li>".($number+1)." - ".$value."</li>";
				
			}
			
		$string_a .= "</ul></div><BR />";
		return $string_a;
		}else return "Unknown error";
		
	}
	
	
	public function md5Password($pass){
		//$pass = strtolower($pass);
		//return md5("shark_md5"."-".$pass);
		return md5(strtolower($pass));
	}

	public function ControlCode($time = 0){
		
		return ($time > 0) ? date("Ymd", $time) : date("Ymd");
		
	}
	
	public function SumCalc($per_h, $sum_tree, $last_sbor){
		
		if($last_sbor > 0){
		
			if($sum_tree > 0 AND $per_h > 0){
			
				$last_sbor = ($last_sbor < time()) ? (time() - $last_sbor) : 0;
			
				$per_sec = $per_h / 3600;
				
				return round( ($per_sec * $sum_tree) * $last_sbor);
				
			}else return 0;
		
		}else return 0;
		
	}
	

    public function rand_string (  $length = 8,
        $repeat = false,
        $UpperCase = true,
        $LowerCase = true,
        $Symbols = false,
        $SymbolsList__ = '~!#$%^&*()0123456789_+=?,.',
        $UpperCaseList = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        $LowerCaseList = 'abcdefghijklmnopqrstuvwxyz'

    ) {
        if ($UpperCase) {
            $UpperCase = $UpperCaseList;
        }
        if ($LowerCase) {
            $LowerCase = $LowerCaseList;
        }
        if ($Symbols) {

            $Symbols = $SymbolsList__;
        }
        unset ($UpperCaseList, $LowerCaseList, $SymbolsList__);
        switch (rand(0, 5)) {
            case 0: $All = $UpperCase. $LowerCase . $Symbols; break;
            case 1: $All = $UpperCase. $Symbols . $LowerCase; break;
            case 2: $All = $Symbols . $LowerCase .$UpperCase; break;
            case 3: $All = $Symbols . $UpperCase . $LowerCase; break;
            case 4: $All = $LowerCase .$Symbols . $UpperCase; break;
            case 5: $All = $LowerCase . $UpperCase . $Symbols; break;
        }
        unset ($UpperCase, $LowerCase, $Symbols);

        $totalLength = strlen($All) - 1;
        $i = 0;
        $string = "";
        if (!$repeat) {
            $totalLength++;
            if($length > $totalLength) {
                #       echo "Error while generating the string: the maximum length is exceeded ($length instead of $totalLength characters)";
                return false;
            }
            $totalLength--;
            while ($i++ < $length) {
                $Current = $All{rand(0, $totalLength--)};
                $All = str_replace($Current, '', $All);
                $string .= $Current;
            }
        } else {
            while ($i++ < $length) {
                $string .= $All{rand(0, $totalLength)};
            }
       }
        unset ($All, $i, $length, $totalLength, $repeat);
        return $string;
    }

    public function IsNull($string){
        return empty($string);

    }

    public function injection($string)
    {
        $string = addslashes($string);
        $string = htmlspecialchars($string);
        $string = stripslashes($string);
        $string = strip_tags($string);
        $string = htmlentities($string, ENT_QUOTES, "UTF-8");
        $string = htmlspecialchars($string, ENT_QUOTES);
        return $string;
    }
    public function logging($string, $file = "success.txt")
    {
        $b = fopen($file, "at+");
        fwrite($b, $string.chr(10));
        fclose($b);
    }
    public function getOS() {
        $os_platform    =   "Unknown OS Platform";
        $os_array       =   array(
            '/windows nt 10/i'     =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );
        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $this->UserAgent())) {
                $os_platform    =   $value;
            }
        }
        return $os_platform;
    }
    public function getBrowser($user_agent) {
        if($this->IsNull($user_agent)) {$user_agent =  $this->UserAgent();}
        $matches = array();
        $browser        =   "Unknown Browser";
        $browser_array  =   array(
            '/msie/i'       =>  'Internet Explorer',
            '/firefox/i'    =>  'Firefox',
            '/safari/i'     =>  'Safari',
            '/chrome\/([0-9\.]+)/i'     =>  'Chrome',
            '/opr\/([0-9\.]+)/i' =>  'Opera',
            '/netscape/i'   =>  'Netscape',
            '/maxthon/i'    =>  'Maxthon',
            '/konqueror/i'  =>  'Konqueror',
            '/mobile/i'     =>  'Handheld Browser'
        );
        foreach ($browser_array as $regex => $value) {
            if (preg_match($regex,$user_agent, $matches)) {
                $browser = $value.' Версия '. $matches[1];

            }
        }
        return $browser;
    }
    public function CheckIpControl($user_ip_db, $user_ip, $control)
    {
        if($control == 4) return true;
        else
        {
            $array_1 = explode($user_ip_db, ".");
            $array_2 = explode($user_ip, ".");
            for($i = 0; $i <= $control; $i++)
            {
                if($array_2[$i] != $array_1[$i]) return false;
            }
            return true;
        }

    }
    public function CheckPass($string, $param = "", $_param = "" ){
	    if(!$this->IsNull($string)){
            if($this->IsPassword($string)){
                return false;
            }
            else
                return $_param;
        }
        else{
            return $param;
        }

    }
    public function IsCorrectNameProduct($string, $mask = "#^([A-Za-zА-Яa-zёЁіІїЇ \-]*)", $len = "{2,24}") {
        return (is_array($string)) ? false : (preg_match("{$mask}{$len}$#ui", $string)) ? $string : false;
    }
    public function GUID() {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
}
?>