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
      $var_cust_cd = $_GET['custcd'];
	  $var_menucode = $_GET['menucd'];
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menucd'];
         $backloc = "../main_mas/m_cust_mas.php?menucd=".$var_menucode;
	
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }
    
     if ($_POST['Submit'] == "Update") {
       $var_cust_cd = $_POST['custcd'];
       if ($var_cust_cd <> "") {

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
     
       $custadd1 = mysql_real_escape_string($_POST['custadd1']);
       $custadd2 = mysql_real_escape_string($_POST['custadd2']);
       $custadd3 = mysql_real_escape_string($_POST['custadd3']);
       $custadd4 = mysql_real_escape_string($_POST['custadd4']);
       $custtel1  = $_POST['custtel'];
       $custtel2  = $_POST['custtel2'];
       $custtel3  = $_POST['custtel3']; 
       $custtel4  = $_POST['custtel4'];       
       $custfax1  = $_POST['custfax'];
       $custconppl1 = $_POST['custconppl'];
       $custeml1    = $_POST['custeml1'];
       $custsuper = $_POST['supervisor'];     

       $custmoby= $var_loginid;
       $custmoon= date("Y-m-d H:i:s");

		 $var_menucode  = $_POST['menucd'];
               
         $sql = "Update customer_master set Name ='$custname', ";
         $sql .= " status = '$custstat', type = '$custtype', Add1 = '$custadd1', ";
         $sql .= " Add2 = '$custadd2', Add3 = '$custadd3', Add4 ='$custadd4', ";
         $sql .= " Terms = '$custterm', PriceGroup = '$custpri', Zone ='$custzone', ";         
         $sql .= " Contact = '$custconppl1', Tel = '$custtel1', Fax = '$custfax1', ";
         $sql .= " Tel1 = '$custtel2', Tel2 = '$custtel3', Tel3 = '$custtel4', "; 
         $sql .= " Email = '$custeml1', Homepage = '$custweb', remark = '$custrmk', supervisor_code = '$custsuper', ";
         $sql .= " currency = '$custcurr', sortby = '$custsort', modified_by='$custmoby', gstno = '$gstno', ";
         $sql .= " modified_on='$custmoon' WHERE CustNo = '$var_cust_cd'";
         
         mysql_query($sql) or die("Error upd mas : ".mysql_error());
         $backloc = "../main_mas/m_cust_mas.php?menucd=".$var_menucode;
	
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

<style media="all" type="text/css">@import "../css/styles.css";
.style2 {
	margin-right: 0px;
}
.style3 {
	font-size: x-small;
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
	var x=document.forms["InpSuppMas"]["custnmid"].value;
	if (x==null || x=="")
	{
	alert("Customer Name Cannot Be Blank");
	document.InpSuppMas.custnmid.focus();
	return false;
	}
  
	var x=document.forms["InpSuppMas"]["ctype"].value;
	if (x==null || x=="a")
	{
	alert("Customer Type Cannot Be Blank");
	document.InpSuppMas.ctype.focus();
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
 
<body OnLoad="document.InpSuppMas.custnmid.focus();">
<?php include("../topbarm.php"); ?> 
    <!--<?php include("../sidebarm.php"); ?>--> 
 <?php
        $sql = "select Name, Add1, Add2, Add3, Add4, Contact, Tel, Fax, ";
        $sql .= " Terms, Status, PriceGroup, Zone, Email, Homepage, supervisor_code, ";
        $sql .= " remark, Tel1, Tel2, Tel3, Type, currency, sortby, gstno, modified_by, modified_on ";
        $sql .= " from customer_master";
        $sql .= " where CustNo ='".$var_cust_cd."'";
        
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $custde = $row[0];
        $custadd1 = $row[1];
        $custadd2 = $row[2];
        $custadd3 = $row[3];
        $custadd4 = $row[4];
        $custconppl1  = $row[5];
        $custtel1  = $row[6];
        $custfax1  = $row[7];
        $custterm = $row[8];
        $custstat = $row[9];
        $custpri = $row[10];
        $custzone = $row[11];
        $custeml1 = $row[12];
        $custweb = $row[13];
        $custsuper = $row[14];
        $custrmk = $row[15];
        $custtel2  = $row[16];
        $custtel3  = $row[17];
        $custtel4  = $row[18];
        $custtype = $row[19]; 
        $custcurr = $row[20];  
        $custsort = $row[21]; 
        $gstno    = $row[22];             

    ?>		
   
    <div class="contentc">

	  <fieldset name="Group1" style="width: 993px; height: 790px">
	  <legend class="title">EDIT CUSTOMER MASTER - <?php echo $var_cust_cd; php?></legend>

	  <form name="InpSuppMas" onsubmit="return validateForm()" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px; width: 970px;">
	    <input name="menucd" type="hidden" value="<?php echo $var_menucode;?>">
	   <table>
	    <tr>
	      <td></td>
	      <td class="tdlabel">Customer No</td>
	      <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="custcd" id ="custcdid" readonly="readonly" type="text" style="width: 161px" value="<?php echo $var_cust_cd; ?>">
		  </td>
		  <td></td>
		  <td class="tdlabel">Status</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="selactive" style="width: 125px">
		    <option <?php if ($custstat == "A") { echo "selected"; } ?> value="A">ACTIVE</option>
		    <option <?php if ($custstat == "D") { echo "selected"; } ?> value="D">DEACTIVE</option>
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
	  	  <td class="tdlabel">Customer Name</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="custname" id ="custnmid" type="text" maxlength="50" style="width: 396px" value="<?php echo $custde; ?>">
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
             echo "<option value = '".$row['term_code']."'";
             if ($custterm == $row['term_code']) { echo "selected"; }
             echo ">".$row['term_code']." - ".$row['term_desc']."</option>";
           
           }
          
         }
       ?>
		   </select>        
		  </td>
	  	 </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td>Homepage</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="custweb" id ="custwebid" type="text" maxlength="50" style="width: 345px" value="<?php echo $custweb; ?>"></td>
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
             echo "<option value = '".$row['price_code']."'";
             if ($custpri == $row['price_code']) { echo "selected"; }
             echo " >".$row['price_code']." - ".$row['price_desc']."</option>";
           
           }
          
         }
       ?>
		   </select>          
          </td>
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td>Remark</td>
          <td>:</td>
          <td>
		   <input class="inputtxt" name="custrmk" id ="custrmkid" type="text" maxlength="80" style="width: 396px" value="<?php echo $custrmk; ?>"></td>
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
             echo "<option value = '".$row['zone_code']."'";
             if ($custzone == $row['zone_code']) { echo "selected"; }
             echo ">".$row['zone_code']." - ".$row['zone_desc']."</option>";
           
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
       <option value="a">-SELECT-</option>
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
          <td>Shipment Type</td>
          <td>:</td>
          <td>
		   <select name="ctype" >
       <option value="a">-SELECT-</option>
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
		        <input class="inputtxt" name="gstno" id ="gstnoid" type="text" maxlength="15" style="width: 150px" value="<?php echo $gstno; ?>"></td>          
          </td>
          <td></td>
          <td>Sales Entry Sorting</td>
          <td>:</td>
          <td>
		   <select name="sort"  id ="sortid">
       <option value="a">-SELECT-</option>
       <?php

             echo "<option value = '1'";
             if ($custsort == '1') { echo "selected"; }
             echo ">PRODUCT</option>";
             echo "<option value = '2'";
             if ($custsort == '2') { echo "selected"; }
             echo ">PRICE</option>";   
             echo "<option value = '3'";
             if ($custsort == '3') { echo "selected"; }
             echo ">SELL TYPE</option>";            

       ?>
		   </select>          
          </td>
         </tr>
         
        </table>
        <br>
        <fieldset name="Group1" class="style2" style="width: 980px">
	     <legend class="style3"><strong>Contact Information</strong></legend>
	      <table>
	  	  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 1</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="custadd1" id ="custadd1id" type="text" maxlength="100" style="width: 396px" value="<?php echo $custadd1; ?>">
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
			<input class="inputtxt" name="custadd2" id ="custadd2id" type="text" maxlength="50" style="width: 396px" value="<?php echo $custadd2; ?>">
			</td>
			<td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 3</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="custadd3" id ="custadd3id" type="text" maxlength="50" style="width: 396px" value="<?php echo $custadd3; ?>">
			</td>
			<td></td>
			<td style="width: 81px"></td>
			<td></td>
            <td>
			</td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 4</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
	  	    <input class="inputtxt" name="custadd4" id ="custadd4id" type="text" maxlength="50" style="width: 151px" value="<?php echo $custadd4; ?>"></td>
			<td></td>
			<td class="tdlabel" style="width: 81px"></td>
            <td></td>
            <td>
		   </td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="custtel" id ="custtelid" type="text" maxlength="50" style="width: 161px" value="<?php echo $custtel1;?>">
		   </td>
		   <td></td>
           <td style="width: 81px">Telephone 2 </td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="custtel2" id ="custtel2id" type="text" maxlength="50" style="width: 294px" value="<?php echo $custtel2;?>"></td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone 3</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="custtel3" id ="custtel3id" type="text" maxlength="50" style="width: 161px" value="<?php echo $custtel3;?>">
		   </td>
		   <td></td>
           <td style="width: 81px">Telephone 4 </td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="custtel4" id ="custtel4id" type="text" maxlength="50" style="width: 294px" value="<?php echo $custtel4;?>"></td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Fax</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="custfax" id ="custfaxid" type="text" maxlength="50" style="width: 294px" value="<?php echo $custfax1;?>">
		   </td>
		   <td></td>
           <td style="width: 81px"></td>
           <td></td>
           <td></td>
		  </tr>      

		  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Contact<br>Person</td>
	  	    <td style="width: 8px; height: 30px;">:</td>
	  	    <td style="height: 30px">
			<input class="inputtxt" name="custconppl" id ="custconpplid" type="text" maxlength="50" style="width: 345px" value="<?php echo $custconppl1;?>">
			</td>
			<td style="height: 30px"></td>
			<td style="width: 81px; height: 30px;">Email</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
			<input class="inputtxt" name="custeml1" id ="custeml1id" type="text" maxlength="50" style="width: 345px" onBlur="AjaxFunction(this.value);" value="<?php echo $custeml1;?>"></td>
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
             echo "<option value = '".$row['supervisor_code']."'";
             if ($custsuper == $row['supervisor_code']) { echo "selected"; }
             echo ">".$row['supervisor_code']." - ".$row['supervisor_name']."</option>";
           
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
<?php        
            $sql = " select create_by, creation_time, modified_by, modified_on";
            $sql .= " from customer_master ";
            $sql .= " where CustNo = '".$var_cust_cd."'";
            
            $tmp = mysql_query($sql) or die("Cant get info : ".mysql_error());
            
            if (mysql_numrows($tmp) > 0) {
               $rst = mysql_fetch_object($tmp);
               $createby = $rst->create_by;
               $createon = $rst->creation_time;
               $modiby = $rst->modified_by;
               $modion = $rst->modified_on;
            
            }  
	  	    ?>
	  	  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Create By</td>
	  	    <td>:</td>
	  	    <td>
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$createby.'" size="20">';
	  	    ?>
          </td>
			<td style="height: 30px"></td>
			<td style="width: 81px; height: 30px;">Create On</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
      
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$createon.'" size="20">';
	  	    ?>          
			</td>
	  	  </tr>  
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Modified By</td>
	  	    <td>:</td>
	  	    <td>
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$modiby.'" size="20">';
	  	    ?>
          </td>
			<td style="height: 30px"></td>
			<td style="width: 81px; height: 30px;">Modified On</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px"> 
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$modion.'" size="20">';
	  	    ?>          
			</td>
	  	  </tr>                         
                  
		  <tr>
	  	    <td></td>
	  	    <td></td><td></td><td></td><td></td><td></td><td></td><td><div id="msg"></div></td>
		  </tr>

		  </table>
		  </fieldset>
	  	 <table>
	  	 <tr><td style="width: 1198px"></td></tr>
	  	 <tr>
	  	   <td align="center" style="width: 1198px">
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" >
	  	   <input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	 </table>
	   </form>	
	   </fieldset>
	   </div>
</body>
</html>
