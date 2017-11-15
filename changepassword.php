<?php 
include 'core/init.php';
protect_page();

if(empty($_POST) === false){
	$required_fields = array('current_password','password','password_again');
	
	foreach($_POST as $key=>$value){
		
		if(empty($value) && in_array($key, $required_fields) === true){
			$errors[] = 'Fields Marked WIth * Are Required';
			break 1;
		}
		
	}
	if(md5($_POST['current_password']) !== $user_data['password']){
		$errors[] = 'Your Current Password is Incorrect';
	}else{
		
		if(trim($_POST['password']) !== trim($_POST['password_again'])){
			$errors[] = 'Your New Password do not match';
		}else if(strlen($_POST['password']) < 6){
			$errors[] = 'Your New Password must be greater than 6 Characters';
		}
		
	}
}

include 'includes/overall/header.php';



?>   
<h1>Change Password</h1>

<?php

if(isset($_GET['success']) && empty($_GET['success'])){
	echo 'Password Changed Succeefully';
}else{
	if(isset($_GET['force']) && empty($_GET['force'])){
?>
	<p>You must Change Your Passsword.</p>
<?php
	}
	if(empty($_POST) === false && empty($errors) === true){
		change_password($session_user_id,$_POST['password']);
		header('Location:changepassword.php?success');
		
	}else if(empty($errors) === false){
		echo output_errors($errors);
	}

?>

<form action="" method="POST">
	<ul>
		<li>
			Current Password*:<br>
			<input type="password" name="current_password">
		</li>
		<li>
			New Password*:<br>
			<input type="password" name="password">
		</li>
		<li>
			New Password Again*:<br>
			<input type="password" name="password_again">
		</li>
		<li>
			<input type="submit" Value="Change Password">
		</li>
	</ul>
</form>
<?php
}
?>

<?php include 'includes/overall/footer.php';?>    