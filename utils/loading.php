<?php
	$sex = $row['sex'];
	$active = $row['active'];
	$major = $row['major'];
	$pob = $row['pob'];
	$pob_flag = $row['pob_flag']; //show place of birth
	$dob_ = $row['dob'];	
	$dob_flag = $row['dob_flag']; //show date of birth
	if ($dob_flag != 0) { //show dob
		$dob = calculate_dob($dob_);		//get the Age Information
	}
	$headline = $row['headline'];
	$about = nl2br($row['about']);
	$quad_ = $row['quad'];
	$quad_flag = $row['quad_flag']; //show residence information
	if ($quad_flag != 0) { //show quad
		$quad = calculate_quad($quad_);		//get the Residence Information
	}
	$life_prior_ = $row['life_prior'];
	$life_prior = calculate_life($life_prior_); //get the Life Priorities Information
	$look_for_ = $row['look_for'];
	$look_for = calculate_look_for($look_for_); //get the Looking For Information
	$look_age_from = $row['look_age_from'];
	$look_age_to = $row['look_age_to'];
	$look_age = calculate_look_age($look_age_from, $look_age_to); //get the Looking For AGE Information
	$look_what_ = $row['look_what'];
	$look_what = calculate_look_what($look_what_); //get the Looking For WHAT Information
	
	$foreign = $row['foreign_lang'];
	$body_type_ = $row['body'];
	$body_type = calculate_body($body_type_);
	$tattoo_ = $row['tattoo'];
	$tattoo = calculate_tattoo($tattoo_);
	$haircolor_ = $row['haircolor'];
	$haircolor = calculate_haircolor($haircolor_);
	$alcohol = $row['alcohol'];
	$status_ = $row['status'];
	$facebook = $row['facebook'];
	$gplus = $row['gplus'];
	$twitter = $row['twitter'];
	$css = $row['css'];
	$chat_color = $row['chat_color'];
	$status = calculate_status($status_);
	$sorfer = $row['sorfer'];
	
	$_SESSION['chat_color'] = $chat_color;
	
?>