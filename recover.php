<?php 
include 'core/init.php';
logged_in_redirect();
include 'includes/overall/header.php';
?>   
<h1>Recover</h1>

<?php

if(isset($_GET['success']) && empty($_GET['success'])){	
?>
<p>Thanks We Have Emailed You!</p>
<?php
	
}else{
	
	$mode_allowed = array('username','password');

	if(isset($_GET['mode']) === true && in_array($_GET['mode'], $mode_allowed) === true){
		if(isset($_POST['email']) && !empty($_POST['email'])){
			if(email_exists($_POST['email']) === true){
				recover($_GET['mode'], $_POST['email']);
				
				//header('Location:recover.php?success');
			}else{
				echo '<p>We Could not find the Email Address</p>';
			}
		}
	?>

	<form action ="" method="POST">
		<ul>
		  <li>
		  Please Enter Your Email Address:<br>
		  <input type="text" name="email">
		  </li>
		  <li>
		   <input type="submit" value="Recover">
		  </li>
		</ul>
	</form>

	<?php

		
	}else{
		header('Location:index.php');
		exit();
	}
}
?>

<?php include 'includes/overall/footer.php';?>    