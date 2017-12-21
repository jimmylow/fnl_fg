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
        $ctrcd   = $_POST['ctrcd'];
        $ctrname = mysql_real_escape_string($_POST['ctrname']);
        $ctradd1 = $_POST['ctradd1'];
        $ctradd2 = $_POST['ctradd2'];
        $ctradd3 = $_POST['ctradd3'];
        $ctradd4 = $_POST['ctradd4'];
        $ctrtel1  = $_POST['ctrtel1'];
        $ctrtel2  = $_POST['ctrtel3'];
        $ctrtel3  = $_POST['ctrtel3'];
        $ctrfax1  = $_POST['ctrfax1'];   
        $ctrfax2  = $_POST['ctrfax2'];                                       
        $ctrconppl1 = $_POST['ctrconppl'];     
        $ctrstat = $_POST['selactive'];
        $ctrpri = $_POST['price']; 
        $ctrzone = $_POST['zone'];
        $sdte = $_POST['sdte'];
        $edte = $_POST['edte']; 
        
        if(strlen($sdte) > 5) { $sdte = date("Y-m-d", strtotime($sdte)); } else { $sdte = ""; }
        if(strlen($edte) > 5) { $edte = date("Y-m-d", strtotime($edte)); } else { $edte = ""; }
     
     
     if ($ctrcd <> "") {
 
      $var_sql = " SELECT count(*) as cnt from counter ";
      $var_sql .= " WHERE counter = '$ctrcd'";

      $query_id = mysql_query($var_sql) or die ("Cant Check Counter ".mysql_error());
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	     $backloc = "../main_mas/ctr_mas.php?stat=3&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }else {
         $vartoday = date("Y-m-d H:i:s");
                  
         $sql = "INSERT INTO counter "; 
         $sql .= " (counter, Name, Adr1, Adr2, Adr3, Adr4, Contact, Tel1, Tel2, ";
         $sql .= " Tel3, Fax1, Fax2, fd_status, Price_Gr, Zone, opendate, enddate, ";
         $sql .= " create_by, create_on, modified_by, modified_on ) values ";
         $sql .= "  ('$ctrcd', '$ctrname', '$ctradd1','$ctradd2', '$ctradd3','$ctradd4','$ctrconppl1', ";
         $sql .= "   '$ctrtel1','$ctrtel2', '$ctrtel3','$ctrfax1', '$ctrfax2', '$ctrstat', ";
         $sql .= "   '$ctrpri', '$ctrzone', '$sdte', '$edte', ";
         $sql .= "   '$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
         mysql_query($sql) or die ("Insert failed : ".mysql_error()); 
              
     	 $backloc = "../main_mas/m_ctr_mas.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";      
       } 
     }else{
       $backloc = "../main_mas/ctr_mas.php?stat=4&menucd=".$var_menucode;
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
	margin-right: 0px;
}
.style3 {
	font-size: x-small;
}
.style4 {
	color: #FF0000;
	font-weight:bold;
}
</style>
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>

<script type="text/javascript" charset="utf-8"> 
function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function AjaxFunctioncd(suppcd)
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
	
	var url="aja_chk_ctr.php";
	
	url=url+"?suppcdg="+suppcd;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
	httpxml.send(null);
}	

function AjaxFunction(email)
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
		document.getElementById("msg").innerHTML=httpxml.responseText;
	  }
    }
	
	var url="../Setting/email-ajax.php";
	
	url=url+"?email="+email;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
	httpxml.send(null);
}	

function validateForm()
{
    var x=document.forms["InpSuppMas"]["ctrcdid"].value;
	if (x==null || x=="")
	{
	alert("Counter Cannot Be Blank");
	document.InpSuppMas.ctrcdid.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["ctrnmid"].value;
	if (x==null || x=="")
	{
	alert("Name Cannot Be Blank");
	document.InpSuppMas.ctrnmid.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["ctradd1id"].value;
	if (x==null || x=="")
	{
	alert("Address1 Cannot Be Blank");
	document.InpSuppMas.ctradd1id.focus();
	return false;
	}
		
	var x=document.forms["InpSuppMas"]["ctrtelid"].value;
	if (x==null || x=="")
	{
	alert("Telephone Cannot Be Blank");
	document.InpSuppMas.ctrtelid.focus();
	return false;
	}
	
}

function setup() {

		//document.InpSalesF.saordnoid.focus();
				
 		//Set up the date parsers
        var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks		
		var dateMask2 = new DateMask("dd-MM-yyyy", "sdte");
		dateMask2.validationMessage = errorMessage;  
    
		var dateMask1 = new DateMask("dd-MM-yyyy", "edte");
		dateMask1.validationMessage = errorMessage;     
}
	
</script>
</head>

<body onload="document.InpSuppMas.ctrcdid.focus(); setup();">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->
  <div class="contentc">

	<fieldset name="Group1" style=" width: 1065px;" class="style2">
	 <legend class="title">COUNTER MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 1043px; height: 750px">
	  <form name="InpSuppMas" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 970px;">
	   <table>
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 200px">Counter<span class="style4">*</span></td>
	      <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="ctrcd" id ="ctrcdid" type="text" maxlength="10" onchange ="upperCase(this.id)" style="width: 94px" onBlur="AjaxFunctioncd(this.value);">
		  </td>
		  <td></td>
		  <td class="tdlabel">Status</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="selactive" style="width: 125px">
		    <option value="A">ACTIVE</option>
		    <option value="I">DEACTIVATE</option>
		   </select>
		  </td>
	  	 </tr>
	  	 <tr>
	  	  <td></td> 
	  	  <td></td>
	  	  <td></td> 
          <td><div id="msgcd"></div></td>
	   	 </tr> 
	   	 <tr>
	   	  <td></td>
	  	  <td class="tdlabel">Name<span class="style4">*</span></td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="ctrname" id ="ctrnmid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 396px">
		  </td>
		  <td></td>
		  <td class="tdlabel"></td>
	  	  <td></td>
	  	  <td>
		  </td>
	  	 </tr>
         <tr>
          <td></td>
          <td>Start Date</td>
          <td>:</td>
          <td>
		   <input class="inputtxt" name="sdte" id ="sdte" type="text" style="width: 128px;" value="<?php  echo $sdte; ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('sdte','ddMMyyyy')" style="cursor:pointer"></td>
        </td>
          <td></td>
          <td>Price Group</td>
          <td>:</td>
          <td>
		   <select name="price" >

       <?php
         
         $sql = "select price_code, price_desc from price_master";
         $sql .= " order by price_code";
         
         $tmp = mysql_query($sql) or die ("Cant get price : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['price_code']."'>".$row['price_code']." - ".$row['price_desc']."</option>";
           
           }
          
         }
       ?>
		   </select>          
          </td>
         </tr>
         <tr><td></td></tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td>End Date</td>
          <td>:</td>
          <td>
		   <input class="inputtxt" name="edte" id ="edte" type="text" style="width: 128px;" value="<?php  echo $edte; ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('edte','ddMMyyyy')" style="cursor:pointer"></td>
          <td></td>
          <td>Zone</td>
          <td>:</td>
          <td>
		   <select name="zone" >

       <?php
         
         $sql = "select zone_code, zone_desc from zone_master";
         $sql .= " order by zone_code";
         
         $tmp = mysql_query($sql) or die ("Cant get zone : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['zone_code']."'>".$row['zone_code']." - ".$row['zone_desc']."</option>";
           
           }
          
         }
       ?>
		   </select>          
          </td>
         </tr>
        </table>
        <br>
        <fieldset name="Group1" class="style2" style="width: 975px">
	     <legend class="style3"><strong>Contact Information</strong></legend>
	      <table>
	  	  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 1<span class="style4">*</span></td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="ctradd1" id ="ctradd1id" type="text" maxlength="100" style="width: 396px">
			</td>
			<td></td>
			<td style="width: 81px"></td>
			<td></td>
            <td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 2</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="ctradd2" id ="ctradd2id" type="text" maxlength="50" style="width: 396px">
			</td>
			<td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 3<span class="style4"></span></td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="ctradd3" id ="ctradd3id" type="text" maxlength="50" style="width: 396px">
			</td>
			<td></td>
			<td style="width: 81px"><span class="style4"></span></td>
			<td></td>
            <td>
			</td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 4<span class="style4"></span></td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
	  	    <input class="inputtxt" name="ctradd4" id ="ctradd4id" type="text" maxlength="50" style="width: 396px"></td>
			<td></td>
			<td class="tdlabel" style="width: 81px"><span class="style4"></span></td>
            <td></td>
            <td>
		   </td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone 1</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="ctrtel1" id ="ctrtelid1" type="text" maxlength="50" style="width: 161px">
		   </td>
		   <td></td>
           <td style="width: 81px">Fax 1</td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="ctrfax1" id ="ctrfaxid1" type="text" maxlength="50" style="width: 294px"></td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone 2</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="ctrtel2" id ="ctrtelid2" type="text" maxlength="50" style="width: 161px">
		   </td>
		   <td></td>
           <td style="width: 81px">Fax 2</td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="ctrfax2" id ="ctrfaxid2" type="text" maxlength="50" style="width: 294px"></td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone 3</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="ctrtel3" id ="ctrtelid3" type="text" maxlength="50" style="width: 161px">
		   </td>
		   <td></td>
           <td style="width: 81px"></td>
           <td></td>
           <td>
		  </tr>
		  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Contact<br>Person<span class="style4">*</span></td>
	  	    <td style="width: 8px; height: 30px;">:</td>
	  	    <td style="height: 30px">
			<input class="inputtxt" name="ctrconppl" id ="ctrconpplid" type="text" maxlength="50" style="width: 345px">
			</td>      
			<td style="height: 30px"></td>     
			<td style="width: 81px; height: 30px;"></td>
			<td style="height: 30px"></td>
			<td style="height: 30px">
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td></td><td></td><td></td><td></td><td></td><td></td><td><div id="msg"></div></td>
		  </tr>

		  </table>
		  </fieldset>
	  	 <table>
	  	 <tr><td></td></tr>
	  	 <tr><td colspan="8" align="center">
	  	   <?php
	  	   $locatr = "m_ctr_mas.php?menucd=".$var_menucode;			
		   echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
	  	   include("../Setting/btnsave.php");
	  	   ?>
	  	   </td>
	  	  </tr>
		   <tr>
	  	  <td></td>
	  	              <td style="width: 1160px" colspan="7"><span style="color:#FF0000">Message :</span>
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
				    echo("<span>Fail! Duplicated Supplier Code Found</span>");
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
	</fieldset>
	</div>
	 <div class="spacer"></div>
</body>

</html>
