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
      $var_ctr_cd = $_GET['ctrcd'];
	  $var_menucode = $_GET['menucd'];
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menucd'];
         $backloc = "../main_mas/m_ctr_mas.php?menucd=".$var_menucode;
	
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }
    
     if ($_POST['Submit'] == "Update") {
       $var_ctr_cd = $_POST['ctrcd'];
       if ($var_ctr_cd <> "") {

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

       $ctrmoby= $var_loginid;
       $ctrmoon= date("Y-m-d H:i:s");

		 $var_menucode  = $_POST['menucd'];
               
         $sql = "Update counter set Name ='$ctrname', ";
         $sql .= " fd_status = '$ctrstat', Adr1 = '$ctradd1', ";
         $sql .= " Adr2 = '$ctradd2', Adr3 = '$ctradd3', Adr4 ='$ctradd4', ";
         $sql .= " Price_Gr = '$ctrpri', Zone ='$ctrzone', ";         
         $sql .= " Contact = '$ctrconppl1', Tel1 = '$ctrtel1', Tel2 = '$ctrtel2', Tel3 = '$ctrtel3',  ";
         $sql .= " Fax1 = '$ctrfax1', Fax2 = '$ctrfax2', opendate = '$sdte', enddate = '$edte', ";
         $sql .= " modified_by='$ctrmoby',";
         $sql .= " modified_on='$ctrmoon' WHERE counter = '$var_ctr_cd'";
         
         mysql_query($sql) or die ("Cant update : ".mysql_error());
         $backloc = "../main_mas/m_ctr_mas.php?menucd=".$var_menucode;
	
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
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>

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
 
<body OnLoad="document.InpSuppMas.ctrnmid.focus(); setup();">
<?php include("../topbarm.php"); ?> 
    <!--<?php include("../sidebarm.php"); ?>--> 
 <?php
        $sql = "select Name, Adr1, Adr2, Adr3, Adr4, Contact, Tel1, Tel2, Tel3, Fax1, Fax2, ";
        $sql .= " fd_Status, Price_Gr, Zone, opendate, enddate, ";
        $sql .= " modified_by, modified_on ";
        $sql .= " from counter";
        $sql .= " where counter ='".$var_ctr_cd."'";
        
        //echo $sql;
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $ctrde = $row[0];
        $ctradd1 = $row[1];
        $ctradd2 = $row[2];
        $ctradd3 = $row[3];
        $ctradd4 = $row[4];
        $ctrconppl1  = $row[5];
        $ctrtel1  = $row[6];
        $ctrtel2  = $row[7];
        $ctrtel3  = $row[8];                
        $ctrfax1  = $row[9];
        $ctrfax2  = $row[10];             
        $ctrstat = $row[11];
        $ctrpri = $row[12];
        $ctrzone = $row[13];
        $sdte = $row[14];
        $edte = $row[15];
        
        if(strlen($sdte) > 5) { $sdte = date("d-m-Y", strtotime($sdte)); } else { $sdte = ""; }
        if(strlen($edte) > 5) { $edte = date("d-m-Y", strtotime($edte)); } else { $edte = ""; }
        
    ?>		
   
    <div class="contentc">

	  <fieldset name="Group1" style="width: 993px; height: 790px">
	  <legend class="title">EDIT COUNTER MASTER - <?php echo $var_ctr_cd; php?></legend>

	  <form name="InpSuppMas" onsubmit="return validateForm()" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px; width: 970px;">
	    <input name="menucd" type="hidden" value="<?php echo $var_menucode;?>">
	   <table>
	    <tr>
	      <td></td>
	      <td class="tdlabel">Counter</td>
	      <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="ctrcd" id ="ctrcdid" readonly="readonly" type="text" style="width: 161px" value="<?php echo $var_ctr_cd; ?>">
		  </td>
		  <td></td>
		  <td class="tdlabel">Status</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="selactive" style="width: 125px">
		    <option <?php if ($ctrstat == "A") { echo "selected"; } ?> value="A">ACTIVE</option>
		    <option <?php if ($ctrstat == "I") { echo "selected"; } ?> value="I">DEACTIVE</option>
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
	  	  <td class="tdlabel">Name</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="ctrname" id ="ctrnmid" type="text" maxlength="50" style="width: 396px" value="<?php echo $ctrde; ?>">
		  </td>
		  <td></td>
		  <td class="tdlabel"></td>
	  	  <td></td>
	  	  <td>      
		  </td>
	  	 </tr>
         <tr><td></td></tr>
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
             echo "<option value = '".$row['price_code']."'";
             if ($ctrpri == $row['price_code']) { echo "selected"; }
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
             echo "<option value = '".$row['zone_code']."'";
             if ($ctrzone == $row['zone_code']) { echo "selected"; }
             echo ">".$row['zone_code']." - ".$row['zone_desc']."</option>";
           
           }
          
         }
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
			<input class="inputtxt" name="ctradd1" id ="ctradd1id" type="text" maxlength="100" style="width: 396px" value="<?php echo $ctradd1; ?>">
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
			<input class="inputtxt" name="ctradd2" id ="ctradd2id" type="text" maxlength="50" style="width: 396px" value="<?php echo $ctradd2; ?>">
			</td>
			<td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 3</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="ctradd3" id ="ctradd3id" type="text" maxlength="50" style="width: 396px" value="<?php echo $ctradd3; ?>">
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
	  	    <input class="inputtxt" name="ctradd4" id ="ctradd4id" type="text" maxlength="50" style="width: 151px" value="<?php echo $ctradd4; ?>"></td>
			<td></td>
			<td class="tdlabel" style="width: 81px"></td>
            <td></td>
            <td>
		   </td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone 1</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="ctrtel1" id ="ctrtelid1" type="text" maxlength="50" style="width: 161px" value="<?php echo $ctrtel1;?>">
		   </td>
		   <td></td>
           <td style="width: 81px">Fax 1</td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="ctrfax1" id ="ctrfaxid1" type="text" maxlength="50" style="width: 294px" value="<?php echo $ctrfax1;?>"></td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone 2</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="ctrtel2" id ="ctrtelid2" type="text" maxlength="50" style="width: 161px" value="<?php echo $ctrtel2;?>">
		   </td>
		   <td></td>
           <td style="width: 81px">Fax 2</td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="ctrfax2" id ="ctrfaxid2" type="text" maxlength="50" style="width: 294px" value="<?php echo $ctrfax2;?>"></td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone 3</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="ctrtel3" id ="ctrtelid3" type="text" maxlength="50" style="width: 161px" value="<?php echo $ctrtel3;?>">
		   </td>
		   <td></td>
           <td style="width: 81px"></td>
           <td></td>
           <td>
		  </tr>            
		  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Contact<br>Person</td>
	  	    <td style="width: 8px; height: 30px;">:</td>
	  	    <td style="height: 30px">
			<input class="inputtxt" name="ctrconppl" id ="ctrconpplid" type="text" maxlength="50" style="width: 345px" value="<?php echo $ctrconppl1;?>">
			</td>
			<td style="height: 30px"></td>
			<td style="width: 81px; height: 30px;"></td>
			<td style="height: 30px"></td>
			<td style="height: 30px">
		  </tr>

<?php        
            $sql = " select create_by, create_on, modified_by, modified_on";
            $sql .= " from counter ";
            $sql .= " where counter = '".$var_ctr_cd."'";
            
            $tmp = mysql_query($sql) or die("Cant get info : ".mysql_error());
            
            if (mysql_numrows($tmp) > 0) {
               $rst = mysql_fetch_object($tmp);
               $createby = $rst->create_by;
               $createon = $rst->create_on;
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
