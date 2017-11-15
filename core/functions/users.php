<?php
function change_profile_image($user_id, $file_tmp, $file_extn){
	require 'core/database/connect.php';
	$file_name = substr(md5(time()), 0, 10).'.'.$file_extn;
	$file_path = 'images/profile/'.$file_name;
	move_uploaded_file($file_tmp, $file_path);
	$query = "UPDATE `users` SET `profile`='".mysqli_real_escape_string($con, $file_path)."' WHERE `user_id`='$user_id'";
	$query_run = mysqli_query($con, $query);
}
function mail_users($subject, $body){
	require 'core/database/connect.php';
	$query = "SELECT `email`,`first_name` FROM `users` WHERE `allow_email`=1";
	$query_run = mysqli_query($con, $query);
	while($data = mysqli_fetch_assoc($query_run)){
		
		email($data['email'], $subject, "Hello ".$data['firstname'].",\n\n".$body."\n\n\n\n -Rajat Sinha");
	}
}

function has_access($user_id, $type){
	require 'core/database/connect.php';
	$user_id = (int)$user_id;
	$type = (int)$type;
	
	$query = "SELECT `user_id` FROM `users` WHERE `user_id`='$user_id' AND `type`=$type";
	$query_run = mysqli_query($con, $query);
	$mysqli_num_rows = mysqli_num_rows($query_run);
	return ($mysqli_num_rows == 1 ? true : false);
}

function recover($mode, $email){
	$mode = sanitize($mode);
	$email = sanitize($email);
	
	require 'core/database/connect.php';
	
	$user_id = user_id_from_email($email);
	$user_data = user_data($user_id, 'first_name','username','user_id');
	
	if($mode == 'username'){
		
		email($email,'Your Username',"
		Hello, ".$user_data['first_name'].",\n\nYour Username is:".$user_data['username']."\n\n -Rajat Sinha");
		
	}else if($mode == 'password'){
		
		echo $generated_password = substr(md5(rand(9999,99999)),0, 8);
		change_password($user_data['user_id'], $generated_password);
		
		update_user($user_data['user_id'], array('password_recover'=>'1'));
		
		email($email,'Your Password Recovery',"
		Hello, ".$user_data['first_name'].",\n\nYour New Password is:".$generated_password."\n\n -Rajat Sinha");
		
	}
}
function update_user($user_id, $update_data){
	
	require 'core/database/connect.php';
	
	$update = array();
	
	array_walk($update_data,'array_sanitize');
	
	foreach($update_data as $field=>$data){
		$update[] = '`'.$field.'` = \''.$data.'\'';
	}
	
	$session_user_id;
	
	$query = "UPDATE `users` SET ".implode(', ',$update)." WHERE `user_id`='$user_id'";
	
	$query_run = mysqli_query($con, $query);
	
}
function  activate($email, $email_code){
	
	require 'core/database/connect.php';
	
	$email = sanitize($email);
	$email_code = sanitize($email_code);
	
	$query = "SELECT * FROM `users` WHERE `email`='$email' AND `email_code`='$email_code' AND `active`=0";
	$query_run = mysqli_query($con, $query);
	if($query_run){
		$query_num_rows = mysqli_num_rows($query_run);
		if($query_num_rows == 1){
			 $query = "UPDATE `users` SET `active`=1 WHERE `email`='$email'";
			 $query_run = mysqli_query($con, $query);
			 if($query_run){
				 return true;
			 }else{
				return false;
			 }
		}else{
			return false;
		}
	}
		
}

function  change_password($user_id, $password){
	require 'core/database/connect.php';
	$user_id = (int)$user_id;
	$password = md5($password);
	
	$query = "UPDATE `users` SET `password`='$password' ,`password_recover`='0' WHERE `user_id`='$user_id'";
	$query_run = mysqli_query($con, $query);
	if($query_run){
		echo 'TRUE';
	}else{
		echo mysqli_error($con);
	}
}

function logged_in_redirect(){
	if(logged_in() === true){
		header('Location: index.php');
		exit();
	}
}

function register_user($register_data){
	
	require 'core/database/connect.php';
	
	array_walk($register_data,'array_sanitize');
	
	$register_data['password'] = md5($register_data['password']); 
	
	$fields = '`'.implode('`, `',array_keys($register_data)).'`';
	
	$data = '\''.implode('\', \'',$register_data).'\'';
	
	$query = "INSERT INTO `users` ($fields) VALUES($data)";
	$query_run = mysqli_query($con, $query);
	
	email($register_data['email'],'Activate Your Account',"
		Hello, ".$register_data['first_name'].",\n\nYou Need To Active Your Account,so use the Link Below:\n\nhttp://localhost/Login/activate.php?email=".$register_data['email']."&email_code=".$register_data['email_code']."\n\n -Rajat Sinha");
}

function user_data($user_id){
	
	require 'core/database/connect.php';
	
	$data = array();	
	$user_id = (int)$user_id;
	
	$func_num_args = func_num_args();
	
	$func_get_args = func_get_args();
	
	if($func_num_args > 1){
		unset($func_get_args[0]);
		$fields = '`'.implode('`, `',$func_get_args).'`';
		
			
		$query = "SELECT $fields FROM `users` WHERE `user_id`='$user_id'";	
		
		$query_run = mysqli_query($con, $query);
		
		$data = mysqli_fetch_assoc($query_run);
		
		return $data;
	}
	
}

function logged_in(){
	return (isset($_SESSION['user_id'])) ? true : false;
}

function user_exists($username){
	require 'core/database/connect.php';
	$username  = sanitize($username);
	$query = "SELECT `username` FROM `users` WHERE `username`='$username'";
	$query_run = mysqli_query($con, $query);
	
	if($query_run){
			$query_num_rows = mysqli_num_rows($query_run);
			if($query_num_rows == 1){
				return True;
			}else{
				return False;
			}
	}
	
	
}

function email_exists($email){
	require 'core/database/connect.php';
	$email  = sanitize($email);
	$query = "SELECT `email` FROM `users` WHERE `email`='$email'";
	$query_run = mysqli_query($con, $query);
	
	if($query_run){
			$query_num_rows = mysqli_num_rows($query_run);
			if($query_num_rows == 1){
				return True;
			}else{
				return False;
			}
	}
	
	
}

function user_active($username){
	
	require 'core/database/connect.php';
	
	$username  = sanitize($username);
	$query = "SELECT `username` FROM `users` WHERE `username`='$username' AND `active`= 1";
	$query_run = mysqli_query($con, $query);
	
	if($query_run){
			$query_num_rows = mysqli_num_rows($query_run);
			if($query_num_rows == 1){
				return True;
			}else{
				return False;
			}
	}
	
	
}

function user_id_from_username($username){
	
	require 'core/database/connect.php';
	
	$username  = sanitize($username);
	
	$query = "SELECT `user_id` FROM `users` WHERE `username`='$username'";
	$query_run = mysqli_query($con, $query);
	
	$data = mysqli_fetch_assoc($query_run);
	
	return $data['user_id'];
	
	
}
function user_id_from_email($email){
	
	require 'core/database/connect.php';
	
	$email  = sanitize($email);
	
	$query = "SELECT `user_id` FROM `users` WHERE `email`='$email'";
	$query_run = mysqli_query($con, $query);
	
	$data = mysqli_fetch_assoc($query_run);
	
	return $data['user_id'];
	
	
}

function login($username, $password){
	
	require 'core/database/connect.php';
	
	$username  = sanitize($username);
	$password  = sanitize($password);	
	$password = md5($password);
	
	$query = "SELECT `username` FROM `users` WHERE `username`='$username' AND `password`= '$password'";
	$query_run = mysqli_query($con, $query);
	
	if($query_run){
			$query_num_rows = mysqli_num_rows($query_run);
			if($query_num_rows == 1){
				
				$user_id = user_id_from_username($username);
				return $user_id;
				
			}else{
				return False;
			}
	}
	
	
}
?>