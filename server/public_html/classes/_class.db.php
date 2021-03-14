<?PHP

class db{


	private $con = false;
	private $Queryes = 0;
	private $MySQLErrors = array();
	private $TimeQuery = 0;
	private $MaxExTime = 0;
	private $ListQueryes = "";
	private $HardQuery = "";
	private $LastQuery = false;
	private $ConnectData = array();
    public $logs = "";
	
	public function __construct($host, $user, $pass, $base){
		$this->Connect($host, $user, $pass, $base);
		$this->query("SET NAMES 'utf8'");
		// $this->query("SET CHARACTER SET 'utf8'");
	}
	
	public function Stats(){
		
		$sD = array();
		$sD["TimeQuery"] = $this->TimeQuery;
		$sD["MaxExTime"] = $this->MaxExTime;
		$sD["ListQueryes"] = $this->ListQueryes;
		$sD["HardQuery"] = $this->HardQuery;
		$sD["Queryes"] = $this->Queryes;
		return $sD;
	}

	private function GetError($TextError){
		$this->MySQLErrors[] = $TextError;
		die($TextError);
	}
		
	public function query($query, $FreeMemory = false, $write_last = true){
		$this->logs .= "<pre>".$query."</pre>";
		$TimeA = $this->get_time();
		$xxt_res = mysqli_query($this->con, $query) or $this->GetError(mysqli_error($this->con));

		if($write_last) $this->LastQuery = $xxt_res;
		
		$TimeB = $this->get_time() - $TimeA;
		$this->TimeQuery += $TimeB;
		
			if($TimeB > $this->MaxExTime){$this->HardQuery = $query; $this->MaxExTime = $TimeB;}
			
				if( empty($this->ListQueryes) ) $this->ListQueryes = $query; else $this->ListQueryes .= "\n".$query;
			
		$this->Queryes++;
		
		if(!$FreeMemory){
			return $this->LastQuery;
		}else return $this->FreeMemory();
		
		
	}
	
	private function Connect($host, $user, $pass, $base){
		$this->con =  @mysqli_connect($host, $user, $pass, $base) or $this->GetError(mysqli_connect_error());
	} 
	
	function MultiQuery($query){
	
		$TimeA = $this->get_time();

		mysqli_multi_query($this->con, $query) or $this->GetError(mysqli_connect_error());
    	$TimeB = $this->get_time() - $TimeA;	
		$ret_data = array();
		$counter = 0;
			do{
        
				if ($result = mysqli_store_result($this->con)) {
					
					while ($row = mysqli_fetch_array($result)) {
					$ret_data[$counter][] = $row;
					}
					mysqli_free_result($result);
					$counter++;
				}

				
    		}while(mysqli_next_result($this->con));

		
		
		$this->TimeQuery += $TimeB;
			
			if($TimeB > $this->MaxExTime){$this->HardQuery = $query; $this->MaxExTime = $TimeB;}
			
				if( empty($this->ListQueryes) ) $this->ListQueryes = $query; else $this->ListQueryes .= "\n".$query;
			
		$this->Queryes++;
		
		return $ret_data;
	}
		
	private function get_time()
	{
		list($seconds, $microSeconds) = explode(' ', microtime());
		return ((float) $seconds + (float) $microSeconds);
	}
	
	function __destruct(){
		
		if( !count($this->MySQLErrors) ) mysqli_close($this->con);
	
	}
	
	function FreeMemory()
	{
		$tr = ($this->LastQuery) ? true : false;
		@mysqli_free_result($this->LastQuery);
		return $tr;
	}
	
	function RealEscape($string)
	{
		if ($this->con) return mysqli_real_escape_string ($this->con, $string);
		else return mysql_escape_string($string);
	}
	
	function NumRows()
	{
		return mysqli_num_rows($this->LastQuery);
	}
	
	function FetchArray(){
		//if($this->LastQuery)
		return mysqli_fetch_array($this->LastQuery);
	}
	
	function FetchRow(){
		$xres = mysqli_fetch_row($this->LastQuery);
		
		return (count($xres) > 1) ? $xres :  $xres[0];
	}
	
	function LastInsert(){
		
		return @mysqli_insert_id($this->con);
		
	}
    private  function GetUserIp(){


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
    }

    private function TextClean($text){
        $array_find = array("`", "<", ">", "^", '"', "~", "\\");
        $array_replace = array("&#96;", "&lt;", "&gt;", "&circ;", "&quot;", "&tilde;", "");
        return str_replace($array_find, $array_replace, $text);

    }
    private function UserAgent()
    {
        return $this->TextClean($_SERVER['HTTP_USER_AGENT']);
    }

}
?>