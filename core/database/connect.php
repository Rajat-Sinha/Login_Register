<?php

$con_error = 'Soory ! Connection Issues';

$con = mysqli_connect('localhost','root','') or die($con_error);
mysqli_select_db($con, 'Login_System');


?>