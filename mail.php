<?php 
include 'core/init.php';

protect_page();
admin_protect();
include 'includes/overall/header.php';
?>   
<h1>Email To The User</h1>
<?php
if(isset($_GET['success']) && empty($_GET['success'])){
?>
<p>Email Has Been Sent to Users</p>
<?php
}else{
	
	if(empty($_POST) === false){
		if(empty($_POST['subject']) === true){
			$errors[] = 'Subject Is Required';
		}
		if(empty($_POST['body']) === true){
			$errors[] = 'Body Is Required';
		}
		
		if(empty($errors) === false){
			echo output_errors($errors);
		}else{
			mail_users($_POST['subject'], $_POST['body']);
			header('Location:mail.php?success');
			exit();
		}
	}
?>

<form action="" method="POST">
	<ul>
	  <li>
		Subject*:<br>
		<input type="text" name="subject">
	  </li>
	  <li>
		Body*:<br>
		<textarea name="body" style="width:400px;height:150px;"></textarea>
	  </li>
	  <li>
		<input type="submit" value="Mail">
	  </li>
	</ul>
</form>
<?php 
}
include 'includes/overall/footer.php';
?>    