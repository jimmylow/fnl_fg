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
     $custcd   = $_POST['custcd'];
     $custstat = $_POST['selactive'];
     $custname =  mysql_real_escape_string($_POST['custname']);
     $custweb = $_POST['custweb'];
     $custrmk = $_POST['custrmk'];
     $custterm = $_POST['terms'];
     $custpri = $_POST['price']; 
     $custzone = $_POST['zone'];
     $custtype = $_POST['ctype']; 
     $custcurr = $_POST['curr'];
     $custsort = $_POST['sort']; 
     $gstno    = $_POST['gstno'];                   
     
     $custadd1_1 = $_POST['custadd1'];
     $custadd2_1 = $_POST['custadd2'];
     $custadd3_1 = $_POST['custadd3'];
     $custadd4_1 = $_POST['custadd4'];
     $custtel1  = $_POST['custtel'];
     $custtel2  = $_POST['custtel2'];
     $custtel3  = $_POST['custtel3']; 
     $custtel4  = $_POST['custtel4'];              
     $custfax1  = $_POST['custfax'];
     $custconppl1 = $_POST['custconppl'];
     $custeml1    = $_POST['custeml1'];
     $custsuper = $_POST['supervisor'];     
     
     if ($custcd <> "") {
 
      $var_sql = " SELECT count(*) as cnt from customer_master ";
      $var_sql .= " WHERE CustNo = '$custcd'";

      $query_id = mysql_query($var_sql) or die ("Cant Check Customer ".mysql_error());
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	     $backloc = "../main_mas/cust_mas.php?stat=3&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }else {
         $vartoday = date("Y-m-d H:i:s");
         $sql = "INSERT INTO customer_master "; 
         $sql .= " (CustNo, Name, Add1, Add2, Add3, Add4, Contact, Tel, Tel1, Tel2, Tel3, Fax, ";
         $sql .= " Terms, Status, Type, PriceGroup, Zone, Email, Homepage, supervisor_code, ";
         $sql .= " remark, create_by, creation_time, modified_by, modified_on, currency, sortby, gstno ) values ";
         $sql .= "  ('$custcd', '$custname', '$custadd1_1','$custadd2_1', ";
         $sql .= "   '$custadd3_1','$custadd4_1','$custconppl1','$custtel1','$custtel2', ";
         $sql .= "   '$custtel3','$custtel4','$custfax1', ";
         $sql .= "   '$custterm', '$custstat', '$custtype', '$custpri', '$custzone', '$custeml1', '$custweb',  ";
         $sql .= "   '$custsuper', '$custrmk', '$var_loginid', '$vartoday','$var_loginid', '$vartoday', '$custcurr', '$custsort', '$gstno')";
         mysql_query($sql) or die ("Insert failed : ".mysql_error()); 
              
     	 $backloc = "../main_mas/m_cust_mas.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";      
       } 
     }else{
       $backloc = "../main_mas/cust_mas.php?stat=4&menucd=".$var_menucode;
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
	
	var url="aja_chk_cust.php";
	
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
	alert("Customer No Cannot Be Blank");
	document.InpSuppMas.custcdid.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["custnmid"].value;
	if (x==null || x=="")
	{
	alert("Customer Name Cannot Be Blank");
	document.InpSuppMas.custnmid.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["custadd1id"].value;
	if (x==null || x=="")
	{
	alert("Customer Address1 Cannot Be Blank");
	document.InpSuppMas.custadd1id.focus();
	return false;
	}
		
	var x=document.forms["InpSuppMas"]["custtelid"].value;
	if (x==null || x=="")
	{
	alert("Customer Telephone Cannot Be Blank");
	document.InpSuppMas.custtelid.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["custconpplid"].value;
	if (x==null || x=="")
	{
	alert("Customer Contact Person Cannot Be Blank");
	document.InpSuppMas.custconpplid.focus();
	return false;
	}	
  
	var x=document.forms["InpSuppMas"]["currid"].value;
	if (x==null || x=="a")
	{
	alert("Customer Currency Cannot Be Blank");
	document.InpSuppMas.currid.focus();
	return false;
	}	
  
	var x=document.forms["InpSuppMas"]["sortid"].value;
	if (x==null || x=="a")
	{
	alert("Customer Sales Entry Sorting By Cannot Be Blank");
	document.InpSuppMas.sortid.focus();
	return false;
	}		  
}	
</script>
</head>

<body onload="document.InpSuppMas.custcdid.focus();">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->
  <div class="contentc">

	<fieldset name="Group1" style=" width: 1065px;" class="style2">
	 <legend class="title">CUSTOMER MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 1043px; height: 750px">
	  <form name="InpSuppMas" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 970px;">
	   <table>
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 200px">Customer No<span class="style4">*</span></td>
	      <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="custcd" id ="custcdid" type="text" maxlength="10" onchange ="upperCase(this.id)" style="width: 94px" onBlur="AjaxFunctioncd(this.value);">
		  </td>
		  <td></td>
		  <td class="tdlabel">Status</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="selactive" style="width: 125px">
		    <option value="A">ACTIVE</option>
		    <option value="D">DEACTIVATE</option>
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
	  	  <td class="tdlabel">Customer Name<span class="style4">*</span></td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="custname" id ="custnmid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 396px">
		  </td>
		  <td></td>
		  <td class="tdlabel">Terms</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="terms" style="width: 125px">

       <?php
         
         $sql = "select term_code, term_desc from term_master";
         $sql .= " order by term_code desc";
         
         $tmp = mysql_query($sql) or die ("Cant get term : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['term_code']."'>".$row['term_code']." - ".$row['term_desc']."</option>";
           
           }
          
         }
       ?>
		   </select>        
		  </td>
	  	 </tr>
         <tr>
          <td></td>
          <td>Homepage</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="custweb" id ="custwebid" type="text" maxlength="50" style="width: 345px"></td>
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
          <td>Remark</td>
          <td>:</td>
          <td>
		  <input class="inputtxt" name="custrmk" id ="custrmkid" type="text" maxlength="80" style="width: 396px"></td>
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
         <tr>
          <td></td>
          <td>Currency</td>
          <td>:</td>
          <td>
		   <select name="curr" id = "currid" >
       <!-- <option value="a">-SELECT-</option>  -->
       <?php
         
         $sql = "select currcode, currdesc from currency_master";
         $sql .= " order by currcode";
         
         $tmp = mysql_query($sql) or die ("Cant get currency : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['currcode']."'";
             if ($custcurr == $row['currcode']) { echo "selected"; }
             echo ">".$row['currcode']." - ".$row['currdesc']."</option>";
           
           }
          
         }
       ?>                   
		   </select>                
          </td>
          <td></td>
          <td>Type</td>
          <td>:</td>
          <td>
		   <select name="ctype" >
       <!-- <option value="a">-SELECT-</option> -->
       <?php

             echo "<option value = 'C'";
             if ($custtype == 'C') { echo "selected"; }
             echo ">CONSIGNMENT</option>";
             echo "<option value = 'O'";
             if ($custtype == 'O') { echo "selected"; }
             echo ">OUTRIGHT</option>";             

       ?>
		   </select>          
          </td>
         </tr>
         
         <tr>
          <td></td>
          <td>GST No</td>
          <td>:</td>
          <td>
		        <input class="inputtxt" name="gstno" id ="gstnoid" type="text" maxlength="15" style="width: 150px"></td>          
          </td>
          <td></td>
          <td>Sales Entry Sorting</td>
          <td>:</td>
          <td>
		   <select name="sort"  id ="sortid">
       <!-- <option value="a">-SELECT-</option> -->
       <?php

             echo "<option value = '1'";
             //if ($custsort == '1') { echo "selected"; }
             echo ">PRODUCT</option>";
             echo "<option value = '2'";
             //if ($custsort == '2') { echo "selected"; }
             echo ">PRICE</option>";   
             echo "<option value = '3'";
             //if ($custsort == '3') { echo "selected"; }
             echo ">SELL TYPE</option>";                         

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
			<input class="inputtxt" name="custadd1" id ="custadd1id" type="text" maxlength="100" style="width: 396px">
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
			<input class="inputtxt" name="custadd2" id ="custadd2id" type="text" maxlength="50" style="width: 396px">
			</td>
			<td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 3<span class="style4"></span></td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="custadd3" id ="custadd3id" type="text" maxlength="50" style="width: 396px">
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
	  	    <input class="inputtxt" name="custadd4" id ="custadd4id" type="text" maxlength="50" style="width: 396px"></td>
			<td></td>
			<td class="tdlabel" style="width: 81px"><span class="style4"></span></td>
            <td></td>
            <td>
		   </td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone<span class="style4">*</span></td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="custtel" id ="custtelid" type="text" maxlength="50" style="width: 161px">
		   </td>
		   <td></td>
           <td style="width: 81px">Telephone 2</td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="custtel2" id ="custtel2id" type="text" maxlength="50" style="width: 294px"></td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone 3</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="custtel3" id ="custtel3id" type="text" maxlength="50" style="width: 161px">
		   </td>
		   <td></td>
           <td style="width: 81px">Telephone 4</td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="custtel4" id ="custtel4id" type="text" maxlength="50" style="width: 161px"></td>
		  </tr> 
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Fax</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="custfax" id ="custfaxid" type="text" maxlength="50" style="width: 294px">
		   </td>
		   <td></td>
           <td style="width: 81px"></td>
           <td></td>
           <td></td>
		  </tr>           
		  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Contact<br>Person<span class="style4">*</span></td>
	  	    <td style="width: 8px; height: 30px;">:</td>
	  	    <td style="height: 30px">
			<input class="inputtxt" name="custconppl" id ="custconpplid" type="text" maxlength="50" style="width: 345px">
			</td>      
			<td style="height: 30px"></td>     
			<td style="width: 81px; height: 30px;">Email</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
			<input class="inputtxt" name="custeml1" id ="custeml1id" type="text" maxlength="50" style="width: 345px" onBlur="AjaxFunction(this.value);"></td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Supervisor<span class="style4"></span></td>
           <td style="width: 8px">:</td>
           <td>
		   <select name="supervisor" >

       <?php
         
         $sql = "select supervisor_code, supervisor_name from supervisor_master";
         $sql .= " order by supervisor_code";
         
         $tmp = mysql_query($sql) or die ("Cant get supervisor : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['supervisor_code']."'>".$row['supervisor_code']." - ".$row['supervisor_name']."</option>";
           
           }
          
         }
       ?>
		   </select>
		   </td>
		   <td></td>
           <td style="width: 81px"></td>
           <td></td>
           <td>
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
	  	   $locatr = "m_cust_mas.php?menucd=".$var_menucode;			
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
