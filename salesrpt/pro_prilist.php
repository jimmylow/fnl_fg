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
      $var_menucode = $_GET['menucd'];
      set_time_limit(180);
      include("../Setting/ChqAuth.php");
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
     	$frprd  = $_POST['selfprod'];
     	$toprd  = $_POST['seltprod'];
     	
     	//$prnpath = "pro_prirpt.php?fp=".$frprd."&tp=".$toprd."&menuc=".$var_menucode;
		// Redirect browser
        $fname = "pro_prirpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&fp=".$frprd."&tp=".$toprd."&usernm=".$var_loginid."&dbsel=".$varrpturldb."&menuc=".$var_menucode;
        $dest .= urlencode(realpath($fname));
		echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        
        //header("Location: $dest" );
        $backloc = "../salesrpt/pro_prilist.php?menucd=".$var_menucode;
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
@import "../css/demo_table.css";

.style2 {
	margin-right: 8px;
}
</style>
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>

<script type="text/javascript" charset="utf-8"> 

function setup() {

	document.InpRawOpen.selfprod.focus();
								
}

function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}		 	
		return xmlhttp;
}

function chkSubmit()
{
	var x=document.forms["InpRawOpen"]["selfprod"].value;
	if (x==null || x=="")
	{
		alert("From Product Code Must Not Be Blank");
		document.InpRawOpen.selfprod.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["seltprod"].value;
	if (x==null || x=="")
	{
		alert("To Product Code Must Not Be Blank");
		document.InpRawOpen.seltprod.focus();
		return false;
	}
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 894px; height: 198px;" class="style2">
	 <legend class="title">PRODUCT PRICE LIST</legend>
	  <br />
	  <form name="InpRawOpen" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 527px;">
		<table style="width: 877px">
		  <tr>
		  	<td></td>
		  	<td style="width: 130px">From Product</td>
		  	<td>:</td>
		  	<td>
		  		<select name="selfprod" id ="selfprod" style="width: 251px">
			    <?php
                   $sql = "select ProductCode, Description from product where status not in ('D') ORDER BY ProductCode";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['ProductCode'].'">'.$row['ProductCode']." | ".$row['Description'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  	<td></td>
		  	<td style="width: 130px">To Product</td>
		  	<td>:</td>
		  	<td>
		  		<select name="seltprod" id ="seltprod" style="width: 251px">
			    <?php
                   $sql = "select ProductCode, Description from product where status not in ('D') ORDER BY ProductCode";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['ProductCode'].'">'.$row['ProductCode']." | ".$row['Description'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>

		  </tr>
	   	  <tr><td></td></tr>	
	  	  <tr>
	  	  	 <td colspan="8" align="center">
	  	  		 <?php
	  	  	 		include("../Setting/btnprint.php");
	  	  		 ?>
	  	  	 </td>
	  	  </tr>
	  	   <tr><td style="width: 6px"></td></tr>
	  	</table>
	   </form>	
	</fieldset>
	 </div>
    <div class="spacer"></div>
</body>

</html>
