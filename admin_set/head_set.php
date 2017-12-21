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
   
    if ($_POST['Submit'] == "Update") {
      $pheatxt = $_POST['hea_txt'];
      $pheacol = $_POST['hea_col'];
      $var_menucode  = $_POST['menudcode'];
          
      if ($pheatxt <> ""){
        if ($_FILES['uploadfile']['name'] <> "") {  
	      $dir = '../images/';
	      $imgnm = "complogo";
	      include("../Setting/uploadFuc.php");
	      $imgnm = "complogo".$ext;
	    }else{
	      $sql = "select apphea_log from apphea_set ";
       	  $sql_result = mysql_query($sql);
       	  $row = mysql_fetch_array($sql_result);
       	  $imgnm= $row[0];    
	    }  		
       
        $moby= $var_loginid;
        $moon= date("Y-m-d H:i:s");
        $sql = "Update apphea_set Set apphea_txt ='$pheatxt', ";
        $sql .= " appbg_col = '$pheacol', modified_by='$moby', apphea_log='$imgnm',";
        $sql .= " modified_on='$moon'";
        mysql_query($sql);
       
        header("location: ".$_SERVER['PHP_SELF']."?stat=1&menucd=".$var_menucode);
      }else{
       header("location: ".$_SERVER['PHP_SELF']."?stat=4&menucd=".$var_menucode);
      }
    }
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<link rel="stylesheet" href="../css/lightbox.css" type="text/css" media="screen">	
<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";

.style2 {
	margin-right: 0px;
}
.style3 {
	color: #FF0000;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="../js/jscolor.js"></script>


<script type="text/javascript" charset="utf-8"> 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function readURL(input) {

	if (input.files && input.files[0]) {
       var reader = new FileReader();

       reader.onload = function (e) {
       $('#heaimgpre')
          .attr('src', e.target.result)
          .width(290)
          .height(100);
       };
       reader.readAsDataURL(input.files[0]);
    }
}

function validateForm()
{
    var x=document.forms["InpProCDMas"]["hea_txt"].value;
	if(!x.match(/\S/)){	
		alert("Header Text Cannot Not Be Blank");
		document.InpProCDMas.hea_txt.focus();
		return false;
	}
	
	var x=document.forms["InpProCDMas"]["hea_col"].value;
	if(!x.match(/\S/)){	
		alert("Program Name Cannot Not Be Blank");
		document.InpProCDMas.hea_col.focus();
		return false;
	}
	
	var lengx = x.length;
	if (lengx != 6){
		alert("Header Color Color Code Must In 6 hex Code");
		document.InpProCDMas.hea_col.focus();
		return false;
	}
}

</script>
</head>

<body onload="document.InpProCDMas.hea_txt.focus()">
<?php include("../topbarm.php"); ?> 
<!--<?php include("../sidebarm.php"); ?>-->

<?php
   $sql = "select apphea_txt, appbg_col, apphea_log, modified_by, modified_on from apphea_set ";
   $sql_result = mysql_query($sql);
   $row = mysql_fetch_array($sql_result);
   $hea_txtd = $row[0];
   $hea_cold = $row[1];
   $hea_logd = $row[2];
   $hea_lupdb = $row[3];
   $hea_lupdo = $row[4];
   
   $dirimg = "../images/";
   $imgname = $dirimg.$hea_logd;
?>
<div class ="contentc">

	<fieldset name="Group1" class="style2" style="width: 800px; height: 410px;">
	 <legend class="title">PRODUCT CODE MASTER</legend>
	  <br>
	  <form name="InpProCDMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" enctype="multipart/form-data" style="height: 200px; width: 837px;" onsubmit="return validateForm()">
		<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
		   <tr>
	  	    <td></td>
	  	    <td>Last Update By</td>
	  	    <td>:</td>
	  	    <td>
   			<input class="inputtxt" name="hea_lby" id ="hea_lbyid" type="text" style="width: 179px;" readonly="readonly" value="<?php echo $hea_lupdb; ?>">
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
	  	    <td>Last Update On</td>
	  	    <td>:</td>
	  	    <td>
   			<input class="inputtxt" name="hea_lon" id ="hea_lonid" type="text" maxlength="80" style="width: 201px;" readonly="readonly" value="<?php echo $hea_lupdo; ?>">
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
	  	    <td>Header Text</td>
	  	    <td>:</td>
	  	    <td>
   			<input class="inputtxt" name="hea_txt" id ="hea_txtid" type="text" maxlength="80" style="width: 506px;"  onchange ="upperCase(this.id)" value="<?php echo $hea_txtd; ?>">
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
	  	    <td>Header Backgroup Color </td>
	  	    <td>:</td>
	  	    <td>
			<input class="color" name="hea_col" id ="hea_colid" type="text" maxlength="6" value="<?php echo $hea_cold; ?>" style="width: 67px">&nbsp; 
			(eg. ffffff)</td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 224px">Header Logo<br>(Max Image Size 2MB)</td>
	  	   <td>:</td>
	  	   <td>
		   <input name="uploadfile" style="width: 315px" type="file" onchange="readURL(this);">
		   </td> 	
	  	  </tr>
    	  <tr>
	  	    <td></td>
	  	    <td>(.jpg, .png, .gif only)</td>
	  	    <td></td>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	    <td></td>
	  	    <td></td>
	  	    <td>
	  	    <img id="heaimgpre" height="100" width="290" src="<?php echo $imgname; ?>">
	  	   </td>  
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	    <td></td>
            <td></td>
			<td>
   			&nbsp;</td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
		   <td></td>
		   <td></td>
	  	   <td>
	  	   <?php
	  	   include("../Setting/btnupdate.php");
	  	   ?>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	  </tr>
	  	   <tr>
	  	    <td></td>
	  	    <td></td>
	  	    <td></td>
	  	    <td style="height: 17px;" colspan="7"><span style="color:#FF0000">
			Message :</span>
            <?php
			  
			  if (isset($var_stat)){
			    switch ($var_stat)
				{
				case 1:
  					echo("<span>Success Process</span>");
  					break;
				case 4:
 					echo("<span>Header Text Cannot Be Blank</span>");
					break;
				default:
  					echo "";
				}
			  }	
			?>
            <span class="style3">&nbsp;</span></td>
	  	  </tr>

	  	</table>
	   </form>	
	
	     <br/>
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
