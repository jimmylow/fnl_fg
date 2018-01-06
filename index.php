<?php
	if(isset($_GET['stat'])) { $var_stat = $_GET['stat']; }
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
  <link rel="stylesheet" type="text/css" href="css/styles.css"/>
  <title>NL SYSTEM</title>
</head>
<body onload="document.loginp.username.focus()">

<div align="center">
<h3>User Login</h3>


<table border="0">
<form name="loginp" method="POST" action="./admin_set/loginproc.php">
	 <tr>
	  <td>Username</td>
	  <td>:</td>
	  <td>
	    <input type="text" name="username" size="20"/>
	  </td>
	 </tr>
	 <tr>
	  <td>Password</td>
	  <td>:</td>
	  <td>
	    <input type="password" name="password" size="20"/>
	  </td>
	 </tr>
	 <tr>
	   <td></td>
	   <td></td>
	   <td align="left">
		<input type="submit" value="Login" class="butsub" style="width: 62px; height: 32px;"/>
	   </td>
	 </tr>
<tr>
<td></td>
<td></td>
<td>
<?php
			  
			  if (isset($var_stat)){
			    switch ($var_stat)
				{
				case 1:
  					echo("<span>User Does Not Exits</span>");
  					break;
				case 2:
 					echo("<span>User Does Not Exits</span>");
					break;
				case 3:
				    echo("<span>Password Cannot Be Blank</span>");
				    break;
  				case 4:
				    echo("<span>User Not ACTIVE</span>");
				    break;
				default:
  					echo "";
				}
			  }	
			?>
</td>
</tr>
</form>
</table>
</div>
</body>

</html>
