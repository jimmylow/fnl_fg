<?php

include("../Setting/Configifx.php");
include("../Setting/Connection.php");

$uname = $_POST['username']; 
if($uname != ""){
	if ($_POST['password'] != ""){
		// Retrieve username and password from database according to user's input	
		$sql  = " SELECT username FROM user_account ";
		$sql .= "  WHERE (username = '".mysql_real_escape_string($_POST['username'])."') "; 
		$sql .= "  and   (userpass = '".mysql_real_escape_string(md5($_POST['password']))."')";
		$login = mysql_query($sql) or die(mysql_error());
					   
		// Check username and password match
		if (mysql_num_rows($login) == 1) {
			$row = mysql_fetch_array($login);
			$uname = $row[0];
	
			if ($_POST['username'] == $uname){
			  
			  $sql2  = " SELECT status FROM user_account ";
			  $sql2 .= "  WHERE (username = '".mysql_real_escape_string($_POST['username'])."') "; 
			  $sql2 .= "  and   (userpass = '".mysql_real_escape_string(md5($_POST['password']))."')";
			  $login2 = mysql_query($sql2) or die(mysql_error());
			  $row2 = mysql_fetch_array($login2);
			  $stat = $row2[0];
			  
			  if ($stat == 'ACTIVE'){
						// Set username session variable
						$_SESSION['sid'] = $_POST['username'];
						// Jump to secured page
				
						header('Location: ../home.php');
			  }else{
			  	header('Location: ../index.php?stat=4');
			  }  			
			}else{
				header('Location: ../index.php?stat=2');
			}	
		}else {
			// Jump to login page
			header('Location: ../index.php?stat=1');
		}
	}else{
		header('Location: ../index.php?stat=3');
	}
}else{
	 header('Location: ../index.php');
}
?>
