<?php 
include 'core/init.php';
logged_in_redirect();
include 'includes/overall/header.php';

if(empty($_POST) == false){
	
	$required_fields = array('username','password','password_again','first_name','email');
	foreach($_POST as $key=>$value){
		
		if(empty($value) && in_array($key, $required_fields) === true){
			$errors[] = 'Fields Marked WIth * Are Required';
			break 1;
		}
		
	}
	
	if(empty($errors) === true){
		if(user_exists($_POST['username']) === true){
			$errors[] = 'Sorry The Username  <b>'.$_POST['username'].'</b> already exists';
		}
		
		if(preg_match("/\\s/", $_POST['username']) == true){
			
			$errors[] = 'Your Username Must not Contain Space';
		}
		
		if(strlen($_POST['password']) < 6){
			$errors[] = 'Password must be greater than 6 characters';
		}
		if($_POST['password'] !== $_POST['password_again']){
			$errors[] = 'Password Does not Match';
		}
		
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
			$errors[] = 'A Validate Email Address is Required';
		}
		
		if(email_exists($_POST['email']) === true){
			$errors[] = 'Sorry The Email  <b>'.$_POST['email'].'</b> already exists';
		}
	}
}


?>   
<h1>Register</h1>

<?php

if(isset($_GET['success']) && empty($_GET['success'])){
	echo 'You Have Registered Succefully ! Please Check Your Email To Activate Your Account';
}else{

	if(empty($_POST) === false && empty($errors) === true){
		
		
		echo $register_data = array(
							'username' => $_POST['username'], 
							'password' => $_POST['password'], 
							'first_name' => $_POST['first_name'], 
							'last_name' => $_POST['last_name'], 
							'email' => $_POST['email'],
							'email_code' => md5($_POST['username'] + microtime())
								
							  );
	echo register_user($register_data);
		header('Location:register.php?success');
		exit();
		
	}else if(empty($errors) === false ){
		
		
		echo output_errors($errors);
	}
?>

<form action="" method="POST">

   <ul>
	 <li>
		Username*: <br>
		<input type="text" name="username" />
	 </li>
	 <li>
		Password*: <br>
		<input type="password" name="password" />
	 </li>
	 <li>
		Confirm Password*: <br>
		<input type="password" name="password_again" />
	 </li>
	 <li>
		FirstName*: <br>
		<input type="text" name="first_name" />
	 </li>
	 <li>
		Last Name: <br>
		<input type="text" name="last_name" />
	 </li>
	 <li>
		Email*: <br>
		<input type="text" name="email" />
	 </li>
	 <li>
		<input type="Submit" value="Register" />
	 </li>
   </ul>
</form>

<?php 
}
include 'includes/overall/footer.php';?>    