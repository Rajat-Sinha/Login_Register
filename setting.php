<?php 
include 'core/init.php';
protect_page();

include 'includes/overall/header.php';

if(empty($_POST) === false){
	$required_fields = array('username','password','password_again','first_name','email');
	
	$required_fields = array('first_name','email');
	foreach($_POST as $key=>$value){
		
		if(empty($value) && in_array($key, $required_fields) === true){
			$errors[] = 'Fields Marked WIth * Are Required';
			break 1;
		}		
	}
	
	if(empty($errors) === true){
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
			$errors[] = 'A Validate Email Address is Required';
		}else if(email_exists($_POST['email']) === true && $user_data['email'] !== $_POST['email']){
			$errors[] = 'Sorry, the email <b>'.$_POST['email'].' </b> already exists!';
		}
	}
}
?>
<h1>Setting</h1>

<?php
if(isset($_GET['success']) === true && empty($_GET['success']) === true){
	echo 'Your details has been Updated';
}else{
	

	if(empty($_POST) === false && empty($errors) === true){
		$update_data = array(
							  'first_name' => $_POST['first_name'],
							  'last_name' => $_POST['last_name'],
							  'email' => $_POST['email'],
							  'allow_email' => ($_POST['allow_email'] === 'on') ? 1 : 0
							);

		update_user($session_user_id, $update_data);
		header('Location:setting.php?success');
		exit();
	}else if(empty($errors) === false){
		echo output_errors($errors);
	}

?>
<form action="" method="POST">
	<ul>
	  <li>
		First Name*:<br>
		<input type="text" name="first_name" value="<?php echo $user_data['first_name']?>">
	  </li>
	  <li>
		Last Name:<br>
		<input type="text" name="last_name" value="<?php echo $user_data['last_name']?>">
	  </li>
	  <li>
		Email*:<br>
		<input type="text" name="email" value="<?php echo $user_data['email']?>">
	  </li>
	  <li>
	    <input type="checkbox" name="allow_email" <?php if($user_data['allow_email'] == 1){echo 'checked="checked"';}?>"> Would You Like to receive email from us?
	  </li>
	  <li>
	   <input type="submit" value="Update" />
	  </li>
	</ul>
</form>

<?php
}
'includes/overall/footer.php';
?>    