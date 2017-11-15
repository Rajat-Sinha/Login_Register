<?php

function  email($to, $subject, $body){
	mail($to, $subject, $body,'From: sinharajat.858@gmail.com');
}

function protect_page(){
	if(logged_in() === false){
		header('Location:protected.php');
	}
}
function admin_protect(){
	global $user_data;
	if(has_access($user_data['user_id'], 1) === false){
		header('Location:index.php');
		exit();
	}
}

function sanitize($data){
		
	require 'core/database/connect.php';
	return htmlentities(strip_tags(mysqli_real_escape_string($con, $data)));
	
}
function array_sanitize(&$item){
	require 'core/database/connect.php';

	$item = htmlentities(strip_tags(mysqli_real_escape_string($con, $item)));
}

function output_errors($errors){
	$output = array();
	foreach($errors as $error){
		$output[] = '
						<li>'.$error.'</li>
					'; 
		return '<ul>'.implode('',$output).'</ul>';			
	}
}
?>