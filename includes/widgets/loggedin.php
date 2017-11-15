<div class="widget">
	<h2>Hello <?php echo $user_data['first_name'];?>!</h2>
	<div class="inner">
	    <div class="profile">
			<?php 
			    if(isset($_FILES['profile']) === true){
					if(empty($_FILES['profile']['name']) === true	){
						echo 'Please Choose a File';
					}else{
						
						$allowed = array('jpg','jpeg','png');
						$file_name = $_FILES['profile']['name'];
						$file_tmp = $_FILES['profile']['tmp_name'];
						$file_extn = @strtolower(end(explode('.',$file_name)));
						
						if(in_array($file_extn, $allowed) === true){
							change_profile_image($session_user_id, $file_tmp, $file_extn);
							header('Location:'.$current_file);
						
						}else{
							echo 'File Type must be ';
							echo implode(',',$allowed);
						}
						
					}
				}
				if(empty($user_data['profile']) === false){
					$profile = $user_data['profile'];
					echo '<img src="'.$profile.'" alt="'.$profile.'">';
				}
			
			?>
		   <form action="" method="post" enctype="multipart/form-data">
			  <input type="file" name="profile">
			  <br />
			  <input type="submit">
		   </form>
		</div>
		<ul>
		  <li><a href="logout.php">Logout</a></li>
		  <li><a href="<?php echo $user_data['username'];?>">Profile</a></li>
		  <li><a href="changepassword.php">Change Password</a></li>
		  <li><a href="setting.php">Setting</a></li>
		</ul>
		
		
	</div>
</div>
<style>
.profile{
	background-color:#f9f9f9;
	border:1px dashed #ccc;
	padding:5px;
}
.profile input{
	margin-top:1vh;
}
.profile img{
	width:100%;
}
</style>