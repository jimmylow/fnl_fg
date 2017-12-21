<?php

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];
	  
    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "../index.php"';
      echo "</script>";
    } else {
      $var_stat = $_GET['stat'];
	  $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }
    
    if ($_POST['Submit'] == "Save") {
     $uname = $_POST['uname'];
     $opass = $_POST['opass'];
     $npass = $_POST['npass'];
     $repass = $_POST['repass'];
     
     if ($uname <> "" && $opass <> "" && $npass <> "" && $repass <> "") {
     
      	$var_sql = " SELECT count(*) as cnt from user_account ";
      	$var_sql .= " WHERE username = '$uname' ";

      	$query_id = mysql_query($var_sql) or die ("Cant User Code");
      	$res_id = mysql_fetch_object($query_id);

      	if ($res_id->cnt == 0 ) {
	  	   $backloc = "../admin_set/reset_pass.php?stat=6&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
      	}else {
      	  
      	   $sql = "select userpass from user_account ";
	       $sql .= " where username ='".$uname."'";
    	   $sql_result = mysql_query($sql);
           $row = mysql_fetch_array($sql_result);
           $oldpassen = $row[0];

           $oldpassde = mysql_real_escape_string(md5($opass));
      	 
      	   if ($oldpassen <> $oldpassde){
      	   		$backloc = "../admin_set/reset_pass.php?stat=7&menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
           		echo "</script>";
      	   }else{
      	   
      	   		$enc_userpass=md5($npass);	
      	   		$vartoday = date("Y-m-d H:i:s");
      	   		$sql = "Update user_account set userpass = '".$enc_userpass."'";
      	   		$sql .= "Where username = '".$uname."'";
     	   		mysql_query($sql); 
     	   
     	   		$backloc = "../admin_set/reset_pass.php?stat=1&menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
           		echo "</script>";
     		}
     	}		 
	 }else{
       $backloc = "../admin_set/reset_pass.php?stat=4&menucd=".$var_menucode;
       echo "<script>";
       echo 'location.replace("'.$backloc.'")';
       echo "</script>";
     }
    }
       
   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import "../css/styles.css";

.style2 {
	margin-right: 0px;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>

<script type="text/javascript" charset="utf-8"> 
function AjaxFunction(passcd)
{
      
		var httpxml;
		try	{
			// Firefox, Opera 8.0+, Safari
			httpxml=new XMLHttpRequest();
		}catch (e){
		  // Internet Explorer
		  try{
			  httpxml=new ActiveXObject("Msxml2.XMLHTTP");
		  }catch (e){
		    try{
			   httpxml=new ActiveXObject("Microsoft.XMLHTTP");
		    }catch (e){
			   alert("Your browser does not support AJAX!");
			   return false;
		    }
		}
		
}

function stateck()
{
		if(httpxml.readyState==4)
		{
			document.getElementById("msgcd").innerHTML=httpxml.responseText;
		}
}
	var uname=document.forms["InpResetMas"]["uname"].value;
	var url="aja_chk_ps.php";
	
	url=url+"?ps="+passcd+"&un="+uname
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",encodeURI(url),true);
	httpxml.send(null);
}	

function validateForm()
{
	var x=document.forms["InpResetMas"]["opass"].value;
	if (x==null || x=="")
	{
	alert("Old Password Must Not Be Blank");
	return false;
	}
	
	var x=document.forms["InpResetMas"]["npass"].value;
	if (x==null || x=="")
	{
	alert("New Password Must Not Be Blank");
	return false;
	}
	
	var x=document.forms["InpResetMas"]["repass"].value;
	if (x==null || x=="")
	{
	alert("Retype New Password Must Not Be Blank");
	return false;
	}
	
	var x=document.forms["InpResetMas"]["npass"].value;
	var y=document.forms["InpResetMas"]["repass"].value;
	if (x != y)
	{
	alert("New Password & Retype New Password Must Be Same");
	return false;
	}

}
</script>
</head>

<body OnLoad="document.InpResetMas.opass.focus();">
<?php include("../topbarm.php"); ?> 
 <!--<?php include("../sidebarm.php"); ?>-->
<div class ="contentc">

	<fieldset name="Group1" style=" width: 911px; height: 260px;" class="style2">
	 <legend class="title">RESET PASSWORD</legend>
	  <br>
	   <form name="InpResetMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 800px;" onsubmit="return validateForm()">
		<table style="width: 884px">
	  	  <tr>
	   	    <td style="width: 8px"></td>
	  	    <td style="width: 135px">User Name</td>
	  	    <td style="width: 6px">:</td>
	  	    <td>
			<input class="inputtxt" name="uname" id ="unameid" type="text" style="width: 381px;" value="<?php echo $var_loginid; ?>">
			</td>
		  </tr>
	  	  <tr>
	  	    <td style="width: 8px"></td>
	  	    <td style="width: 135px"></td>
	  	    <td></td>
	  	    <td></td>
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 8px"></td>
	  	    <td style="width: 135px">Old Password</td>
	  	    <td style="width: 6px">:</td>
	  	    <td>
			<input class="inputtxt" name="opass" id ="opassid" type="password" maxlength="30" style="width: 267px" onBlur="AjaxFunction(this.value);">
			</td>
		  </tr>
		  <tr>
		   <td style="width: 8px"></td>
		   <td></td>
		   <td></td>
		   <td><div id="msgcd"></div></td>
		  </tr>
		  <tr>
	  	    <td style="width: 8px"></td>
	  	    <td style="width: 135px">New Password</td>
	  	    <td style="width: 6px">:</td>
	  	    <td>
			<input class="inputtxt" name="npass" id ="npassid" type="password" maxlength="30" style="width: 267px">
			</td>
		  </tr>
		  <tr>
		  	<td style="width: 8px"></td>
		  </tr>
		  <tr>
	  	    <td style="width: 8px"></td>
	  	    <td style="width: 135px">Retype New Password</td>
	  	    <td style="width: 6px">:</td>
	  	    <td>
			<input class="inputtxt" name="repass" id ="repassid" type="password" maxlength="30" style="width: 267px"></td>
		  </tr>
		  <tr>
		  	<td style="width: 8px"></td>
		  </tr>
		  <tr>
		   <td></td>
		   <td style="width: 135px"></td>
		   <td style="width: 6px"></td>
	  	   <td style="width: 8px">
	  	   <?php
	  	   	include("../Setting/btnsave.php");
	  	   ?>
	  	   </td>
	  	  </tr>
	  	  <tr>
	  	  <td></td>
	  	  <td></td>
	  	  <td></td>
	  	              <td style="width: 505px" colspan="7"><span style="color:#FF0000">Message :</span>
            <?php
			  
			  if (isset($var_stat)){
			    switch ($var_stat)
				{
				case 1:
  					echo("<span>Success Process</span>");
  					break;
				case 0:
 					echo("<span>Process Fail</span>");
					break;
				case 3:
				    echo("<span>Fail! Duplicated Found</span>");
  					break;
  				case 4:
				    echo("<span>Please Fill In The Data To Save</span>");
  					break;
				case 5:
				    echo("<span>New Password & Retype New Password Not Same</span>");
					break;	
				case 6:
				    echo("<span>This User Name Does Not Exist</span>");
					break;
				case 7:
				    echo("<span>Your Old Password Not Correct. No Process!!</span>");
					break;		
				default:
  					echo "";
				}
			  }	
			?>
           </td>
	  	  </tr>
	  	</table>
	   </form>	
	  </fieldset>
	  </div>
</body>

</html>
