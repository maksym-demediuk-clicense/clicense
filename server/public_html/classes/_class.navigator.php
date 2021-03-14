<?PHP

class navigator{

	
	private function ParseURLNavigation($str, $page){
	
	$array_a = array("/page/{$page}/", "/page/{$page}", "#");
	
		return str_replace($array_a, "", $str);
	
	}
	
	
	function Navigation($max, $page, $AllPages, $strURI){
	
	$strReturn = "";
	$left = false;
	$right = false;
	
	$strURI = $this->ParseURLNavigation($strURI, $page);
	
	$page = (intval($page) > 0) ? intval($page) : 1;
	
	
		if($AllPages <= $max){
		
			for($i = 1; $i <= $AllPages; $i++){
				
				if($i == $page){
					
					$strReturn .= "<li class='active'><a>[$page]</a></li>";
					
				}else $strReturn .= "<li><a href=\"{$strURI}{$i}\">{$i}</a></li>";
				
			}
		
		}else{
			
			for($i = 1; $i <= $AllPages; $i++){
				
				
				
				if($i == $page OR ($i == $page-1) OR ($i == $page-2)  OR ($i == $page-3) OR ($i == $page-4) OR ($i == $page+1) OR 
				($i == $page+2)  OR ($i == $page+3) OR ($i == $page+4)){
					
					if($i == $page){
					
						$strReturn .= "<li class='active'><a>[$page]</a><li>";
					
					}else{
							
						$strReturn .= "<li><a href=\"{$strURI}{$i}\">{$i}</a></li>";
						
					}
					
				}else{
					
					if($i > $page){
								
						if(!$right){ 
						
							if($page <= $AllPages-6){
							
								$strReturn .= " <li><a>...</a> <a href=\"{$strURI}{$AllPages}\">{$AllPages}</a></li>"; $right = true;
							
							}else{
							
								$strReturn .= " <li><a href=\"{$strURI}{$AllPages}\">{$AllPages}</a></li>"; $right = true;
							
							}
						
						}
					
					}else{
						
						if(!$left){
							
							if($page > 6){
							
								$strReturn .= "<li><a href=\"{$strURI}1\">1</a> <a>...</a> </li>"; $left = true;
							
							}else{
							
								$strReturn .= "<li><a href=\"{$strURI}1\">1</a> </li>"; $left = true;
							
							}
							
						}
					
					}
					
				}
				
				
			}
		
		}		
		$left_str = ($page > 1) ? "<li><a href=\"{$strURI}".($page-1)."\">&laquo;</a></li>" : "<li><a>&laquo;</a></li>";
		$right_str = ($page < $AllPages) ? "<li><a href=\"{$strURI}".($page+1)."\">&raquo;</a></li>" : "<li><a>&raquo;</a></li>";		
		return '<ul class="pagination text-center">'.$left_str.$strReturn.$right_str.'</ul>';
		
	}

}

?>