<?php
       	
    if(!isset($_GET['email']) || !$method = $_GET['email']) exit;
         
	$email=$_GET['email'];
	
    if (!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $email)){
      echo "<font color=red> Invalid email</font>";
    }else{
      echo "<font color=green> Valid Email</font>";
    }
    
?> 