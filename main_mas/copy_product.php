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
    
    if ($_POST['Submit'] == "Copy") {
    	$vmfprodcd = $_POST['fselprcode'];
		$vmtprodcd = $_POST['prod_code'];
		$vartoday  = date("Y-m-d H:i:s");

		$var_menucode  = $_POST['menudcode'];
    	
    	if ($vmtprodcd <> "") {
    		 $vartoday = date("Y-m-d H:i:s");
    		 $sql = "select * from product";
     		 $sql .= " where productcode ='".$vmfprodcd."'";
     		 $sql_result = mysql_query($sql);
     		 $row = mysql_fetch_array($sql_result);

 
             $GroupCode   = $row['GroupCode'];  
             $OwnCode      = $row['OwnCode'];  
             $Category     = $row['Category'];  
             $Description  = $row['Description'];  
             $Size         = $row['Size'];  
             $Color        = $row['Color'];  
             $created_on   = $vartoday;  
             $cDate        = $vartoday;  
             $OnHand       = 0;  
             $RecQty       = 0; 
             $ShipQty      = 0;
             $AdjQty       = 0;
             $ReturnQty    = 0;
             $OrderBal     = 0;
             $OrderQty     = 0;
             $CancelQty    = 0;
             $BalQty       = 0;
             $Selltype     = $row['Selltype'];  
             $SellName     = $row['SellName'];  
             $ExFacPrice   = $row['ExFacPrice'];  
             $ExUnit       = $row['ExUnit'];  
             $Exdozen      = $row['Exdozen'];  
             $CDozen       = $row['CDozen'];  
             $Begbal       = $row['Begbal'];  
             $Cost         = $row['Cost'];  
             $CUnit        = $row['CUnit'];  
             $TransferOut  = $row['TransferOut'];  
             $TransferIn   = $row['TransferIn'];  
             $FacSP        = $row['FacSP'];  
             $DisCost      = $row['DisCost'];  
             $ReturnTOQty  = $row['ReturnTOQty'];  
             $Location     = $row['Location'];  
             $created_by   = $var_loginid;  
             $modified_by  = $var_loginid;  
             $modified_on  = $vartoday;  
             $Status       = $row['Status'];
                     		
             
			 $sql = "INSERT INTO product(
			  ProductCode,
			  GroupCode,
			  OwnCode, 
			  Category,  
              Description,  
              Size,  
              Color,  
              created_on,  
              cDate,  
              OnHand,  
              RecQty,  
              ShipQty,  
              AdjQty,  
              ReturnQty,  
              OrderBal,  
              OrderQty,  
              CancelQty, 
              BalQty,  
              Selltype,  
              SellName,  
              ExFacPrice,  
              ExUnit,  
              Exdozen,  
              CDozen,  
              Begbal,  
              Cost,  
              CUnit,  
              TransferOut,  
              TransferIn,  
              FacSP,  
              DisCost,  
              ReturnTOQty,  
              Location,  
              created_by,  
              modified_by,  
              modified_on,  
              Status) 
          values 
			('$vmtprodcd',
			 '$GroupCode', 
			 '$OwnCode', 
             '$Category',  
             '$Description',  
             '$Size',  
             '$Color',  
             '$created_on',  
             '$cDate',  
             '$OnHand',  
             '$RecQty',  
             '$ShipQty',  
             '$AdjQty',  
             '$ReturnQty',  
             '$OrderBal',  
             '$OrderQty',  
             '$CancelQty', 
             '$BalQty',  
             '$Selltype',  
             '$SellName',  
             '$ExFacPrice',  
             '$ExUnit',  
             '$Exdozen',  
             '$CDozen',  
             '$Begbal',  
             '$Cost',  
             '$CUnit',  
             '$TransferOut',  
             '$TransferIn',  
             '$FacSP',  
             '$DisCost',  
             '$ReturnTOQty',  
             '$Location',  
             '$created_by',  
             '$modified_by',  
             '$modified_on',  
             '$Status')";
			 mysql_query($sql) or die("Query 1 :".mysql_error());

    		 $sql = "SELECT * FROM prodprice";
             $sql .= " Where productcode='".$vmfprodcd."'"; 
	    	 $sql .= " ORDER BY pricecode";  
			 $rs_result = mysql_query($sql) or die("Query 2 :".mysql_error()); 
			   
			 while ($rowq = mysql_fetch_assoc($rs_result)){
			 		$pricecode = $rowq['pricecode']; 
			 		$uprice    = $rowq['uprice'];
			 		
					$sql = "INSERT INTO prodprice values 
						   ('$vmtprodcd', '$pricecode', '$uprice')";
					mysql_query($sql) or die("Query 3 :".mysql_error());
             }
             
             $backloc = "../main_mas/m_prod_mas.php?menucd=".$var_menucode;
           	 echo "<script>";
           	 echo 'location.replace("'.$backloc.'")';
             echo "</script>"; 

		}else{
			$backloc = "../main_mas/m_prod_mas.php?stat=4&menucd=".$var_menucode;
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
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";

.style2 {
	margin-right: 0px;
}
</style>
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>


<script type="text/javascript" charset="utf-8"> 
$(document).ready(function(){
	var ac_config = {
		source: "../bom_master/autocomscrpro1.php",
		select: function(event, ui){
			$("#prod_code").val(ui.item.prod_code);
		
		},
		minLength:1
		
	};
	$("#prod_code").autocomplete(ac_config);
});

function setup() {

		document.InpColMas.fselprcode.focus();
						
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "prorevdte");
		dateMask1.validationMessage = errorMessage;		
}

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


function validateForm()
{
    var x=document.forms["InpColMas"]["fselprcode"].value;
	if (x==null || x=="")
	{
	alert("From Product Code Cannot Be Blank");
	document.InpColMas.fselprcode.focus();
	return false;
	}

 	var x=document.forms["InpColMas"]["prod_code"].value;
	if (x==null || x=="")
	{
	alert("Copy To Product Code Cannot Be Blank");
	document.InpColMas.prod_code.focus();
	return false;
	}
	
	var x=document.forms["InpColMas"]["prorevdte"].value;
	if (x==null || x=="")
	{
	alert("Copy To Costing Rev No Date Cannot Be Blank");
	document.InpColMas.prorevdte.focus();
	return false;
	}
	
	//Check the product Code Valid--------------------------------------------------------
	var flgchk = 1;
	var x=document.forms["InpColMas"]["prod_code"].value;
	var strURL="../bom_master/aja_chk_procode.php?procode="+x;
	var req = getXMLHTTP();
	
    if (req)
	{
		req.onreadystatechange = function()
		{
			if (req.readyState == 4)
			{
				// only if "OK"
				if (req.status == 200)
				{
				
					if (req.responseText == 0)
					{
					  flgchk = 0;
					  document.InpColMas.prod_code.focus();
					  alert ('This To Product Code Not Found :'+x);
					  return false;
					}
				} else {
					//alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
					return false;
				}
			}
		}	 
	}
	req.open("GET", strURL, false);
	req.send(null);
    if (flgchk == 0){
	   return false;
	}
	//---------------------------------------------------------------------------------------------------

	//Check the from product code and rev no is same as to prod code and rev no--------------------------
	var fprodcode = document.forms["InpColMas"]["fselprcode"].value;
	var tprodcode = document.forms["InpColMas"]["prod_code"].value;
	
	var n = fprodcode.match(tprodcode);
	
	if (fprodcode == tprodcode){
			alert("Copy To Product Code Same As From Product Code.");
			document.InpColMas.prod_code.focus();
			return false;
	}
	//---------------------------------------------------------------------------------------------------

	//Check the duplicate of prod code and rev no--------------------------------------------------------
	var x      = document.forms["InpColMas"]["prod_code"].value;
	var flgchk = 1;
	var strURL="../bom_tran/aja_chk_prodrev.php?procode="+x;
	var req = getXMLHTTP();
	
    if (req)
	{
		req.onreadystatechange = function()
		{
			if (req.readyState == 4)
			{
				// only if "OK"
				if (req.status == 200)
				{
				
					if (req.responseText != 0)
					{
					  flgchk = 0;
					  document.InpColMas.prod_code.focus();
					  alert ('This To Product Code Already Have a Record :'+x);
					  return false;
					}
				} else {
					//alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
					return false;
				}
			}
		}	 
	}
	req.open("GET", strURL, false);
	req.send(null);
    if (flgchk == 0){
	   return false;
	}
	//---------------------------------------------------------------------------------------------------	
	
}		
</script>
</head>
<body onload="setup()">
 	<?php include("../topbarm.php"); ?> 
 	<!--<?php include("../sidebarm.php"); ?>-->

	
	<div class ="contentc">
	<fieldset name="Group1" style=" width: 583px; height: 332px;" class="style2">
	 <legend class="title">COPY PRODUCT MASTER</legend>
	  <br>
	
	   <form name="InpColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 567px;" onsubmit="return validateForm()">
	  	<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 165px" class="tdlabel">From Product Code</td>
	  	    <td>:</td> 
	  	    <td>
				<select name="fselprcode" style="width: 140px">
			 	<?php
                   $sql = "select productcode from product WHERE Status = 'A' ORDER BY productcode ";
                   $sql_result = mysql_query($sql);
                   echo "<option size =140 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['productcode'].'">'.$row['productcode'].'</option>';
				 	 } 
				   } 
	        	 ?>				   
	       	</select>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 165px" class="tdlabel"></td>
	  	    <td></td> 
            <td>&nbsp;</td> 
	   	  <tr>
	  	  	<td></td>
	  	  	<td></td>
	  	  	<td></td>
	  	  </tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>To Product Code</td>
	  	  	<td>:</td>
	  	  	<td>
			<input name="prod_code" id="prod_code" type="text" maxlength="15" style="width: 129px" onchange ="upperCase(this.id)" onBlur="AjaxFunctioncd(this.value);">
	  	  	</td>
	  	  </tr>
		  <tr>
	  	  	<td></td>
	  	  	<td>&nbsp;</td>
	  	  	<td>&nbsp;</td>
	  	  	<td>
	  	  	&nbsp;
			<div id="msgcd"></div>
			</td>
	  	  </tr>
	  	   <tr>
	  	  	<td></td>
	  	  	<td></td>
	  	  	<td>&nbsp;</td>
	  	  </tr>    
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 165px"></td>
	  	   <td></td>
	  	   <td>
	  	   <?php
				 $locatr = "m_prod_mas.php?menucd=".$var_menucode;
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
	  	   ?>
	  	   <input type=submit name = "Submit" value="Copy" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	   <tr><td></td></tr>
	  	   <tr>
	  	     <td></td>
	  	     <td style="width: 165px"></td>
	  	     <td></td>
	  	  </tr>

	  	</table>
	   </form>	
	   <br>
	 </fieldset>
	 </div>
    <div class="spacer"></div>
</body>

</html>
