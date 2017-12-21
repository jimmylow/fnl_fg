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
     $suppcd   = $_POST['suppcd'];
     $suppstat = $_POST['selactive'];
     $suppname =  mysql_real_escape_string($_POST['suppname']);
     $suppweb = $_POST['suppweb'];
     $supprmk = $_POST['supprmk'];
     
     $suppadd1_1 = $_POST['suppadd1'];
     $suppadd2_1 = $_POST['suppadd2'];
     $suppadd3_1 = $_POST['suppadd3'];
     $suppadd4_1 = $_POST['suppadd4'];
     $supptel1  = $_POST['supptel'];
     $suppfax1  = $_POST['suppfax'];
     $suppmob1  = $_POST['suppmob'];
     $suppconppl1 = $_POST['suppconppl'];
     $suppeml1    = $_POST['suppeml1'];
     $suppterms = $_POST['terms'];
     $suppcurr = $_POST['curr'];    
     
     if ($suppcd <> "") {
 
      $var_sql = " SELECT count(*) as cnt from supplier_master ";
      $var_sql .= " WHERE SuppNo = '$suppcd'";

      $query_id = mysql_query($var_sql) or die ("Cant Check Supplier Code ".mysql_error());
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	     $backloc = "../main_mas/supp_mas.php?stat=3&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }else {
         $vartoday = date("Y-m-d H:i:s");
         $sql = "INSERT INTO supplier_master "; 
         $sql .= " (SuppNo, Name, Add1, Add2, Add3, Add4, Contact, Tel, Fax, Mobile, ";
         $sql .= "  Status, Email, Homepage, remark, create_by, creation_time, modified_by, modified_on, ";
         $sql .= " terms, currency ) values ";
         $sql .= "  ('$suppcd', '$suppname', '$suppadd1_1','$suppadd2_1', ";
         $sql .= "   '$suppadd3_1','$suppadd4_1','$suppconppl1','$supptel1','$suppfax1', '$suppmob1', ";
         $sql .= "   '$suppstat', '$suppeml1', '$suppweb', '$supprmk', ";
         $sql .= "   '$var_loginid', '$vartoday','$var_loginid', '$vartoday', '$suppterms', '$suppcurr')";
         mysql_query($sql); 
              
     	 $backloc = "../main_mas/m_supp_mas.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";      
       } 
     }else{
       $backloc = "../main_mas/supp_mas.php?stat=4&menucd=".$var_menucode;
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
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>


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
	
	var url="aja_chk_supp.php";
	
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
    var x=document.forms["InpSuppMas"]["suppcdid"].value;
	if (x==null || x=="")
	{
	alert("Supplier Code Cannot Be Blank");
	document.InpSuppMas.suppcdid.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["suppnmid"].value;
	if (x==null || x=="")
	{
	alert("Supplier Name Cannot Be Blank");
	document.InpSuppMas.suppnmid.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["suppadd1id"].value;
	if (x==null || x=="")
	{
	alert("Supplier Address1 Cannot Be Blank");
	document.InpSuppMas.suppadd1id.focus();
	return false;
	}
		
	var x=document.forms["InpSuppMas"]["supptelid"].value;
	if (x==null || x=="")
	{
	alert("Supplier Telephone Cannot Be Blank");
	document.InpSuppMas.supptelid.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["suppconpplid"].value;
	if (x==null || x=="")
	{
	alert("Supplier Contact Person Cannot Be Blank");
	document.InpSuppMas.suppconpplid.focus();
	return false;
	}	
}	
</script>
</head>

<body onload="document.InpSuppMas.suppcdid.focus();">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->
  <div class="contentc">

	<fieldset name="Group1" style=" width: 1065px;" class="style2">
	 <legend class="title">SUPPLIER MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 1043px; height: 750px">
	  <form name="InpSuppMas" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 970px;">
	   <table>
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 200px">Supplier No<span class="style4">*</span></td>
	      <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="suppcd" id ="suppcdid" type="text" maxlength="10" onchange ="upperCase(this.id)" style="width: 94px" onBlur="AjaxFunctioncd(this.value);">
		  </td>
		  <td></td>
		  <td class="tdlabel">Status</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="selactive" style="width: 125px">
		    <option value= "A">ACTIVE</option>
		    <option value= "D">DEACTIVATE</option>
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
	  	  <td class="tdlabel">Supplier Name<span class="style4">*</span></td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="suppname" id ="suppnmid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 396px">
		  </td>
		  <td></td>
		  <td class="tdlabel">Terms</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="terms" style="width: 125px">
       <option value="a">-SELECT-</option>
       <?php
         
         $sql = "select term_code, term_desc from term_master";
         $sql .= " order by term_code desc";
         
         $tmp = mysql_query($sql) or die ("Cant get term : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['term_code']."'";
             //if ($suppterms == $row['term_code']) { echo "selected"; }
             echo ">".$row['term_code']." - ".$row['term_desc']."</option>";
           
           }
          
         }
       ?>
		   </select>
	  	 </tr>
         <tr>
          <td></td>
          <td>Homepage</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="suppweb" id ="suppwebid" type="text" maxlength="50" style="width: 345px"></td>
		  </td>
          <td></td>
          <td>Currency</td>
          <td>:</td>
          <td>
		   <select name="curr" id = "currid" >
       <option value="a">-SELECT-</option>
       <?php
         
         $sql = "select currcode, currdesc from currency_master";
         $sql .= " order by currcode";
         
         $tmp = mysql_query($sql) or die ("Cant get currency : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['currcode']."'";
             //if ($suppcurr == $row['currcode']) { echo "selected"; }
             echo ">".$row['currcode']." - ".$row['currdesc']."</option>";
           
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
          <td>Remark</td>
          <td>:</td>
          <td>
		  <input class="inputtxt" name="supprmk" id ="supprmkid" type="text" maxlength="80" style="width: 396px"></td>
          <td></td>
          <td></td>
          <td></td>
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
			<input class="inputtxt" name="suppadd1" id ="suppadd1id" type="text" maxlength="100" style="width: 396px">
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
			<input class="inputtxt" name="suppadd2" id ="suppadd2id" type="text" maxlength="50" style="width: 396px">
			</td>
			<td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 3<span class="style4"></span></td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="suppadd3" id ="suppadd3id" type="text" maxlength="50" style="width: 396px">
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
	  	    <input class="inputtxt" name="suppadd4" id ="suppadd4id" type="text" maxlength="50" style="width: 396px"></td>
			<td></td>
           <td style="width: 81px">Mobile Tel</td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="suppmob" id ="suppmobid" type="text" maxlength="50" style="width: 294px"></td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone<span class="style4">*</span></td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="supptel" id ="supptelid" type="text" maxlength="50" style="width: 161px">
		   </td>
		   <td></td>
           <td style="width: 81px">Fax</td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="suppfax" id ="suppfaxid" type="text" maxlength="50" style="width: 294px"></td>
		  </tr>
		  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Contact<br>Person<span class="style4">*</span></td>
	  	    <td style="width: 8px; height: 30px;">:</td>
	  	    <td style="height: 30px">
			<input class="inputtxt" name="suppconppl" id ="suppconpplid" type="text" maxlength="50" style="width: 345px">
			</td>      
			<td style="height: 30px"></td>     
			<td style="width: 81px; height: 30px;">Email</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
			<input class="inputtxt" name="suppeml1" id ="suppeml1id" type="text" maxlength="50" style="width: 345px" onBlur="AjaxFunction(this.value);"></td>
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
	  	   $locatr = "m_supp_mas.php?menucd=".$var_menucode;			
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
