<?php
include 'core/init.php';
logged_in_redirect();


if(empty($_POST) === false){
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if(empty($username) === true || empty($password) === true){
		$errors[] = 'You need to enter username and password';
	}else if(user_exists($username) === false){
		 $errors[] = 'We can\'t find that username.Have You registered?';
	}else if(user_active($username) == false){
		$errors[] = 'You have not Active your account!';
	}else{
		
		if(strlen($password) > 32){
			$errors[] = 'Password is too long';
		}
		
		$login = login($username, $password);
		
		if($login == false){
			$errors[] = 'Incorrect username/password';
		}else{
			$_SESSION['user_id'] = $login;
			
			header('Location:index.php');
			exit();
		}
	}

}else{
	$errors[] = 'No Data Received';
}

include 'includes/overall/header.php';

if(empty($errors) === false){
?>
  <h2>We Tried To Log You in, But...</h2>
<?php	
   echo output_errors($errors);
}

include 'includes/overall/footer.php';
?>