<?php

function contain ($str1, $str2) {
	if(strpos($str1,$str2) === false) {
		return false;
	} else {
		return true;	
	}
}

function encode($someid) {
	$line = $someid."uasocial";
	$line = md5($line);
	return $line;
}

//calculate years of age (input string: YYYY-MM-DD)
function getAge( $p_strDate ) {
    list($Y,$m,$d)    = explode("-",$p_strDate);
    return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
}

function calculate_dob($dob) {
	if (!$dob) {
		return "0";		
	} else {
		return getAge($dob);		
	}	
	return "0";	
}

function  calculate_quad($quad) {
	if (!$quad) {
		return "0";		
	} 	elseif ($quad == 1)
			return "Colonial Quad";
		elseif ($quad == 2)
			return "State Quad";
		elseif ($quad == 3)
			return "Dutch Quad";
		elseif ($quad == 4)
			return "Indian Quad";
		elseif ($quad == 5)
			return "Alumni Quad";
		elseif ($quad == 6)
			return "Freedom Apartments";
		elseif ($quad == 7)
			return "Empire Commons";
		elseif ($quad == 8)
			return "Off Campus";
	return "0";	
}


function calculate_life($life_prior) {
	$out = "";
	if (!$life_prior) {
		return "No answer";		
	} 	
	if (contain($life_prior,"1")) {
			$out = "Career";
	}
	if (contain($life_prior,"2")) {
			if (strlen($out)>1) $out .= ", ";
			$out .= "Finiancial Independence";
		}
	if (contain($life_prior,"3")) {
			if (strlen($out)>1) $out .= ", ";
			$out .=  "Family";		
		}
	if (contain($life_prior,"4")) {
			if (strlen($out)>1) $out .= ", ";
			$out .= "Peace of mind";			
		}
	if (contain($life_prior,"5")) {
			if (strlen($out)>1) $out .= ", ";
			$out .= "Sexual life";			
		}
	if (contain($life_prior,"6")) {
			if (strlen($out)>1) $out .= ", ";
			$out .= "Creativity";			
		}
	if (contain($life_prior,"7")) {
			if (strlen($out)>1) $out .= ", ";
			$out .= "Freedom";			
		}
	return $out;	
}

function calculate_look_for($look_for) {
	if (!$look_for) {
		return "No answer";		
	} 	elseif ($look_for == 1)
			return "Boys";
		elseif ($look_for == 2)
			return "Girls";	
		elseif ($look_for == 3)
			return "Boys and Girls";	
		elseif ($look_for == 4)
			return "Nobody";	
	return "No answer";	
}

function calculate_look_age($look_age_from, $look_age_to) {
	if ((!$look_age_from)||($look_age_from == 0)) {
		return "No answer";		
	} 	return $look_age_from." - ".$look_age_to." years old";		
}

function calculate_look_what($look_what) {
	return $look_what;		
}

function calculate_body($body_type) {
	if (!$body_type) {
		return "No answer";		
	} 	elseif ($body_type == 1)
			return "Slim";
		elseif ($body_type == 2)
			return "Average";	
		elseif ($body_type == 3)
			return "Athletic";	
		elseif ($body_type == 4)
			return "Muscular";	
		elseif ($body_type == 5)
			return "Large";	
	return "No answer";	
}

function calculate_haircolor($haircolor) {
	if (!$haircolor) {
		return "No answer";		
	} 	elseif ($haircolor == 1)
			return "Black";
		elseif ($haircolor == 2)
			return "Blond";	
		elseif ($haircolor == 3)
			return "Brown";	
		elseif ($haircolor == 4)
			return "Red";	
		elseif ($haircolor == 5)
			return "Grey";	
		elseif ($haircolor == 6)
			return "White";	
		elseif ($haircolor == 7)
			return "Bald";	
		elseif ($haircolor == 8)
			return "Mixed";	
		elseif ($haircolor == 9)
			return "Shaved";	
	return "No answer";	
}

function calculate_tattoo($tattoo) {
	if (!$tattoo) {
		return "None";		
	} 	elseif ($tattoo == 1)
			return "Tattoo";
		elseif ($tattoo == 2)
			return "Piercing";	
		elseif ($tattoo == 3)
			return "Tattoo and piercing";	
	return "None";	
}

function calculate_status($status){
	if(!$status){
		return "No answer";
		} elseif ($status == 1)
			return "Single";
		  elseif($status == 2)
		    return "In a Relationship";
		  elseif($status == 3)
		    return "Engaged";
		  elseif($status == 4)
		    return "Married";
	}
	
function calculate_message($read){
	if($read == '1'){
		return 0;
		}else if($read == '0'){
		$new = $new + 1;
		  return $new;
	   }
	}
	
function isAdmin($admins, $myid) {	// '1,2,3,4' , 3 = returns true
	$admins = explode(',',$admins);
	$flag = false;
	foreach ($admins as $a)
      if ($a==$myid) $flag = true;
	return $flag;
}

function getFirstAdmin($admins) {	// '1,2,3,4' , returns '1'
	$admins = explode(',',$admins);
	$myid = $admins[0];
	return $myid;
}

function processCorrectLink($link, $code){
//		$facebook = processCorrectLink($facebook,1);
//		$gplus = processCorrectLink($gplus,2);
//		$twitter = processCorrectLink($twitter,3);
	$result = $link;
	if (!$code) {
		exit;
	}
	if ($code==1 || $code==3) {
		$pos1 = stripos($link, '/');
		if ($pos1 !== false) {
			$pos1 = strrpos($link, '/');
			$result = substr($link, $pos1+1);
		}
	}
	if ($code==2) {
		$pos1 = stripos($link, 'google.com/');
		if ($pos1 !== false) {
			$pos1 = strrpos($link, 'google.com/');
			$result = substr($link, $pos1+11);
		}
	}
	return $result;	
}


	
function IsChecked($chkname,$value)
    {
        if(!empty($_POST[$chkname]))
        {
            foreach($_POST[$chkname] as $chkval)
            {
                if($chkval == $value)
                {
                    return true;
                }
            }
        }
        return false;
    }

?>