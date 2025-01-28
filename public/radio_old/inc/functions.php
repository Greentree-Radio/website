<?php
function convertTime($seconds) {
	$sec = $seconds;
    // Time conversion
    $hours = intval(intval($sec) / 3600);
    $padHours = True;
    $hms = ($padHours)
        ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
        : $hours. ':';
    $minutes = intval(($sec / 60) % 60);
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';
    $seconds = intval($sec % 60);
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

	return $hms;
}
function getRealIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
      //check ip from share internet
	  $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      //to check ip is pass from proxy
	  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
function pagination($total, $per_page = 10,$page = 1, $url = '?'){        
        $adjacents = "2"; 
 
        $page = ($page == 0 ? 1 : $page);  
        $start = ($page - 1) * $per_page;                               
         
        $prev = $page - 1;                          
        $next = $page + 1;
        $lastpage = ceil($total/$per_page);
        $lpm1 = $lastpage - 1;
         
        $pagination = "";
        if($lastpage > 1)
        {   
            $pagination .= "<ul class='pagination'>";
                    $pagination .= "<li class='details'>Page $page of $lastpage <small>($total records)</small></li>";
            if ($lastpage < 7 + ($adjacents * 2))
            {   
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";                    
                }
            }
            elseif($lastpage > 5 + ($adjacents * 2))
            {
                if($page < 1 + ($adjacents * 2))     
                {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<li><a class='current'>$counter</a></li>";
                        else
                            $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";                    
                    }
                    $pagination.= "<li class='dot'>...</li>";
                    $pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
                    $pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";      
                }
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $pagination.= "<li><a href='{$url}page=1'>1</a></li>";
                    $pagination.= "<li><a href='{$url}page=2'>2</a></li>";
                    $pagination.= "<li class='dot'>...</li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<li><a class='current'>$counter</a></li>";
                        else
                            $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";                    
                    }
                    $pagination.= "<li class='dot'>..</li>";
                    $pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
                    $pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";      
                }
                else
                {
                    $pagination.= "<li><a href='{$url}page=1'>1</a></li>";
                    $pagination.= "<li><a href='{$url}page=2'>2</a></li>";
                    $pagination.= "<li class='dot'>..</li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<li><a class='current'>$counter</a></li>";
                        else
                            $pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";                    
                    }
                }
            }
             
            if ($page < $counter - 1){ 
                $pagination.= "<li><a href='{$url}page=$next'>Next</a></li>";
                $pagination.= "<li><a href='{$url}page=$lastpage'>Last</a></li>";
            }else{
                $pagination.= "<li><a class='current'>Next</a></li>";
                $pagination.= "<li><a class='current'>Last</a></li>";
            }
            $pagination.= "</ul>\n";      
        }
		else {
		 $pagination .= "<ul class='pagination'>";
                    $pagination .= "<li class='details'>Page $page of $lastpage <small>($total records)</small></li>";
		 $pagination.= "</ul>\n";   
		}
     
     
        return $pagination;
    } 
?>