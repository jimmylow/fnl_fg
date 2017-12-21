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
     $owncd   = $_POST['owncd'];
     $category = $_POST['category'];
     $desc =  mysql_real_escape_string($_POST['desc']);
     $groupcd = $_POST['groupcd'];
     $prodcd = $_POST['prodcd'];
     $size = $_POST['size'];
     $col = $_POST['col']; 
     $location = $_POST['location'];              
     
     $selltype = $_POST['selltype'];
     $exunit = $_POST['exunit'];
     $expri = $_POST['expri'];
     //$exdoz = $_POST['exdoz'];
     //$agenunit  = $_POST['agenunit'];
     //$agenpri  = $_POST['agenpri'];
     //$agendoz = $_POST['agendoz'];
     
     if ($prodcd <> "") {
 
      $var_sql = " SELECT count(*) as cnt from product ";
      $var_sql .= " WHERE ProductCode = '$prodcd'";

      $query_id = mysql_query($var_sql) or die ("Cant Check Product ".mysql_error());
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	     $backloc = "../main_mas/prod_mas.php?stat=3&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }else {
         $vartoday = date("Y-m-d H:i:s");
         if ($expri == "" or empty($expri)){$expri = 0;}
         if ($exdoz == "" or empty($exdoz)){$exdoz = 0;}
         $sql = "INSERT INTO product "; 
         $sql .= " (ProductCode, GroupCode, OwnCode, Category, Description, Size, Color, ";
         $sql .= " Selltype, ExFacPrice, ExUnit, Location, ";
         $sql .= " created_by, created_on, modified_by, modified_on, status ) values ";
         $sql .= " ('$prodcd', '$groupcd', '$owncd','$category', ";
         $sql .= " '$desc','$size','$col','$selltype','$expri', ";
         $sql .= " '$exunit', '$location', ";
         $sql .= " '$var_loginid', '$vartoday','$var_loginid', '$vartoday', 'A')";
         mysql_query($sql) or die ("Insert failed : ".mysql_error()); 
              
     	 $backloc = "../main_mas/upd_prod_mas.php?prodcd=".$prodcd."&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";      
       } 
     }else{
       $backloc = "../main_mas/prod_mas.php?stat=4&menucd=".$var_menucode;
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
	
	var url="aja_chk_prod.php";
	
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
    var x=document.forms["InpSuppMas"]["prodcdid"].value;
	if (x==null || x=="")
	{
	alert("Product Code Cannot Be Blank");
	document.InpSuppMas.prodcdid.focus();
	return false;
	}
	
}	
</script>
</head>

<body onload="document.InpSuppMas.owncdid.focus();">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->
  <div class="contentc">

	<fieldset name="Group1" style=" width: 1065px;" class="style2">
	 <legend class="title">PRODUCT MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 1043px; height: 250px">
	  <form name="InpSuppMas" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 970px;">
	   <table>
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 200px">Own Code</td>
	      <td>:</td>
	  	  <td>
		   <select name="owncd" id="owncdid" >

       <?php
         
         $sql = "select category_code, category_desc from mdfcategory_master";
         $sql .= " order by category_code";
         
         $tmp = mysql_query($sql) or die ("Cant get category : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['category_code']."'>".$row['category_code']." - ".$row['category_desc']."</option>";
           
           }
          
         }
       ?>
		   </select>  

		  </td>
		  <td></td>
		  <td class="tdlabel">Category</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="category" >

       <?php
         
         $sql = "select category_code, category_desc from category_master";
         $sql .= " order by category_code";
         
         $tmp = mysql_query($sql) or die ("Cant get category : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['category_code']."'>".$row['category_code']." - ".$row['category_desc']."</option>";
           
           }
          
         }
       ?>
		   </select>  
		  </td>
	  	 </tr>     
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 200px">Group Code</td>
	      <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="groupcd" id ="groupcdid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 396px" >
		  </td>
		  <td></td>
		  <td class="tdlabel">Size</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="size" id ="sizeid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 200px"></td>
		  </td>
	  	 </tr>
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 200px">Product Code<span class="style4">*</span></td>
	      <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="prodcd" id ="prodcdid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 396px" onBlur="AjaxFunctioncd(this.value);">
		  </td>
		  <td></td>
		  <td class="tdlabel">Color</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="col" id ="colid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 200px"></td>
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
	  	  <td class="tdlabel">Description</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="desc" id ="descid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 396px">
		  </td>
		  <td></td>
		  <td class="tdlabel">Location</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="location" >

       <?php
         
         $sql = "select location_code, location_desc from location_master";
         $sql .= " order by location_code desc";
         
         $tmp = mysql_query($sql) or die ("Cant get location : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['location_code']."'>".$row['location_code']." - ".$row['location_desc']."</option>";
           
           }
          
         }
       ?>
		   </select>        
		  </td>
	  	 </tr>
         <tr>
          <td></td>
          <td>Sell Type</td>
          <td>:</td>
          <td>
		   <select name="selltype" >

       <?php
         
         $sql = "select salestype_code, salestype_desc from salestype_master";
         $sql .= " order by salestype_code";
         
         $tmp = mysql_query($sql) or die ("Cant get sales type : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['salestype_code']."'>".$row['salestype_code']." - ".$row['salestype_desc']."</option>";
           
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
          <td colspan="2"></td>
          <td >
          <table width="100%" >
          <tr>
          <td>Unit</td>
          <td>Price</td>
          <!-- <td>DOZ</td>  -->
          </tr>
          </table>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
         </tr>
         <tr>
          <td></td>
          <td>Cost Price</td>
          <td >:</td>
          <td><table width="100%" >
          <tr>
          <td>
       <select name="exunit" id ="exunitid" >

       <?php
         
         $sql = "select uom_code, uom_desc from prod_uommas";
         $sql .= " order by uom_code";
         
         $tmp = mysql_query($sql) or die ("Cant get UOM type : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['uom_code']."'";
             //if($exunit == $row['uom_code']) { echo " selected"; }
             echo ">".$row['uom_code']." - ".$row['uom_desc']."</option>";
           }
         }
       ?>
		   </select>
          </td>
          <td><input class="inputtxt" name="expri" id ="expriid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 80px"></td>
          <!-- <td><input class="inputtxt" name="exdoz" id ="exdozid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 80px"></td> -->
          </tr>
          </table>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
         </tr>
         <tr>
          <td></td>
          <td></td>
          <td ></td>
          <td>
          <!-- <table width="100%">
          <tr>
          <td><input class="inputtxt" name="agenunit" id ="agenunitid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 80px"></td>
          <td><input class="inputtxt" name="agenpri" id ="agenpriid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 80px"></td>
          <td><input class="inputtxt" name="agendoz" id ="agendozid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 80px"></td>
          </tr>
          </table> -->
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
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
        
	  	   $locatr = "m_prod_mas.php?menucd=".$var_menucode;			
		     echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="window.location.href=\''.$locatr.'\'" >';
         
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
				    echo("<span>Fail! Duplicated Product Code Found</span>");
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
