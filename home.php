<?php

	include("./Setting/Configifx.php");
	include("./Setting/Connection.php");	
	$var_loginid = $_SESSION['sid'];
	    
    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "./index.php"';
      echo "</script>";
    } else {
      $var_stat = $_GET['stat'];
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>

<style media="all" type="text/css">
@import "./css/styles.css";
@import "./css/demo_table.css";

.style2 {
	margin-right: 8px;
}
</style>
<script type="text/javascript" language="javascript" src="./media/js/jquery-1.4.4.min.js"></script>

</head>
<body>
 <?php include("topbarm.php"); ?> 
 <!--<?php include("sidebarm.php"); ?> -->
 
 <div class="contentc">		
<iframe id="canlender" name="frame1"
        src="welcome.htm"
		width="100%" height="2000" 
		frameborder="0">
</iframe>
</div>
</body>
</html>


