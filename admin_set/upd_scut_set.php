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
      $var_shpcode = $_GET['shrcode'];
      $var_shseq = $_GET['shrseq'];
      $var_shpname = $_GET['shrpname'];
    }
    
    if ($_POST['Submit'] == "Update") {
     $shcusenr   = $_POST['usernm'];
     $shcprog = $_POST['shcpcode'];
     $shcseq = $_POST['shcseq'];
            
     if ($shcseq <> "") {
      
       	$sql = "Update shou_user Set sh_seq='".$shcseq."'";
        $sql .= " where usern ='".$shcusenr."'";
        $sql .= " and prog_code ='".$shcprog."'";
        mysql_query($sql);      	           
     	 
     	$backloc = "../admin_set/user_scut_set.php";
        echo "<script>";
        echo 'location.replace("'.$backloc.'")';
        echo "</script>";
     }else{
       header("location: ".$_SERVER['PHP_SELF']."?stat=4");
     }
    }
     
     if ($_POST['Submit'] == "Back") {
         
         $backloc = "../admin_set/user_scut_set.php";
        
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";

.style2 {
	margin-right: 0px;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>

<script type="text/javascript" charset="utf-8"> 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function is_int(value){

  if (value != ""){
  	if((parseFloat(value) == parseInt(value)) && !isNaN(value)){   
        return true;
    }else {
        alert ("Sequence Number Must Be In Integer");
        document.InpMenuMas.shcseqid.focus();
        return false;
    }
  }  
}

function validateForm()
{
	var x=document.forms["InpMenuMas"]["shcseq"].value;
	if(!x.match(/\S/)){	
		alert("Sequence No Cannot Not Be Blank");
		document.InpMenuMas.shcseq.focus();
		return false;
	}
}
</script>
</head>

<body OnLoad="document.InpMenuMas.shcseq.focus();">
<?php include("../topbarm.php"); ?> 
<!-- <?php include("../sidebarm.php"); ?>--> 
<div class ="contentc">

	<fieldset name="Group1" style=" width: 700px;" class="style2">
	 <legend class="title">MY SHORTCUT SETTING</legend>
	  <br>
	  <fieldset name="Group1" style="height:200px; width: 650px;">
	  <form name="InpMenuMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" style="height: 134px; width: 650px;" onsubmit="return validateForm()">
		<input name="shcpcode" type="hidden" value="<?php echo $var_shpcode;?>">
		<table style="width: 884px">
	  	  <tr>
	  	    <td></td>
	  	    <td>User Name</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="usernm" id ="usernmid" type="text" maxlength="45" readonly="readonly" style="width: 188px" value="<?php echo $var_loginid; ?>">
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td></td>
	  	    <td></td>
	  	    <td></td> 
	   	  </tr> 
	   	  <tr>
	   	    <td></td>
	  	    <td>Program Name</td>
	  	    <td>:</td>
	  	    <td>
			  <input class="inputtxt" name="progname" id ="prognameid" type="text" readonly="readonly" style="width: 483px" value="<?php echo $var_shpname; ?>"></td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	    <td></td>
	  	    <td>Sequence No</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="shcseq" id ="shcseqid" type="text" maxlength="3" style="width: 63px" onBlur="is_int(this.value);" value="<?php echo $var_shseq; ?>">
			</td>
 		  </tr>
		  <tr><td></td></tr>
		  <tr>
	  	    <td></td>
	  	    <td></td>
	  	    <td></td>
	  	    <td></td>
			<td></td>
		  </tr>
		  <tr>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td>
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px">
	  	   <input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
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
	    <br>
	  
	</fieldset>
    </div>
     <div class="spacer"></div>
</body>

</html>
