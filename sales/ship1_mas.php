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
    
      $var_ordno = $_GET['sorno'];
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");

    }
    
    if ($_POST['Submit'] == "Update") {
      
    //phpinfo();
    $vmordno = $_POST['sordno'];
		$vmshipdte = date('Y-m-d', strtotime($_POST['shipdte']));
		$vmcustcd = $_POST['sacustcd'];
		$vmtype  = $_POST['stype'];
		$vmprinted = $_POST['sprinted'];
  
            
		if ($vmordno <> "") {
    			
        $vartoday = date("Y-m-d H:i:s"); 
        
				$sql = "INSERT INTO salesshipmas values 
						('$vmordno', '$vmshipdte','$vmcustcd','$vmtype','$vmprinted','$var_loginid','$vartoday', 
						 '$var_loginid', '$vartoday', 'A', 'N', 'N', 'N')";
				mysql_query($sql) or die ("Cant insert : ".mysql_error());
				
				if(!empty($_POST['prococode']) && is_array($_POST['prococode'])) 
				{	
					foreach($_POST['prococode'] as $row=>$matcd ) {
						$matcode   = $matcd;
						$matseqno  = $_POST['seqno'][$row];
						$matuom    = $_POST['procouom'][$row];
						$matqty    = $_POST['procoqty'][$row];
						$matshipqty = $_POST['procoshipqty'][$row];

					
						if ($matcode <> "")
						{
							if ($matqty == "" or empty($matqty)){$matqty = 0;}
							if ($matshipqty == "" or empty($matshipqty)){$matshipqty = 0;}
							$sql = "INSERT INTO salesshipdet values 
						    		('$vmordno', '$matcode', '$matqty', '$matuom','$matshipqty', '$matseqno')";
                    
							mysql_query($sql) or die ("Cant insert details : ".mysql_error());
           				}	
					}
          
          mysql_query("update `salesentry` set `shipflg` = 'Y'
                       where `sordno` = '$vmordno'", $db_link) 
                      or die("Cant Update Sales Order No ".mysql_error());                

				}
				
				$backloc = "../sales/m_ship_mas.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
					
		}else{
			$backloc = "../sales/ship1_mas.php?stat=4&menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 
		} 
   }

    if ($_POST['Submit'] == "SHIP_ALL") {
      
    //phpinfo();
    $vmordno = $_POST['sordno'];
		$vmshipdte = date('Y-m-d', strtotime($_POST['shipdte']));
		$vmcustcd = $_POST['sacustcd'];
		$vmtype  = $_POST['stype'];
		$vmprinted = $_POST['sprinted'];
  
            
		if ($vmordno <> "") {
    			
        $vartoday = date("Y-m-d H:i:s"); 
        
				$sql = "INSERT INTO salesshipmas values 
						('$vmordno', '$vmshipdte','$vmcustcd','$vmtype','$vmprinted','$var_loginid','$vartoday', 
						 '$var_loginid', '$vartoday', 'A', 'N', 'N', 'N')";
				mysql_query($sql) or die ("Cant insert mas : ".mysql_error());      
				
				if(!empty($_POST['prococode']) && is_array($_POST['prococode'])) 
				{	
					foreach($_POST['prococode'] as $row=>$matcd ) {
						$matcode   = $matcd;
						$matseqno  = $_POST['seqno'][$row];
						$matuom    = $_POST['procouom'][$row];
						$matqty    = $_POST['procoqty'][$row];

					
						if ($matcode <> "")
						{
							if ($matqty == "" or empty($matqty)){$matqty = 0;}
							$sql = "INSERT INTO salesshipdet values 
						    		('$vmordno', '$matcode', '$matqty', '$matuom','$matqty', '$matseqno')";
                    
							mysql_query($sql) or die ("Cant insert details : ".mysql_error());
           				}	
					}
          
          mysql_query("update `salesentry` set `shipflg` = 'Y'
                       where `sordno` = '$vmordno'", $db_link) 
                      or die("Cant Update Sales Order No ".mysql_error());                

				}
				
				$backloc = "../sales/m_ship_mas.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
					
		}else{
			$backloc = "../sales/ship1_mas.php?stat=4&menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 
		} 
   }
       
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">


<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";

.general-table #prococode                        { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #procoucost                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #prococompt                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}

.style2 {
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>


<script type="text/javascript"> 
$(document).ready(function(){
	var ac_config = {
		source: "autocomscrpro1.php",
		select: function(event, ui){
			$("#prod_code").val(ui.item.prod_code);
			//$("#promodesc").val(ui.item.prod_desc);
			$("#totallabcid").val(ui.item.prod_labcst);
	
		},
		minLength:1
		
	};
	$("#prod_code").autocomplete(ac_config);
});

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function setup() {

		document.InpPO.sacustcd.focus();
				
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
        
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "shipdte");
		dateMask1.validationMessage = errorMessage;  
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

  document.body.style.cursor = 'wait';
 
  var x=document.forms["InpPO"]["sacustcd"].value;
	if (x==null || x=="s")
	{
	alert("Customer Must Not Be Blank");
	document.InpPO.sacustcd.focus;
	document.body.style.cursor = 'default';
	return false;
	}

   var x=document.forms["InpPO"]["shipdte"].value;
	if (x==null || x=="")
	{
	alert("Ship Date Must Not Be Blank");
	document.InpPO.shipdte.focus;
	document.body.style.cursor = 'default';
	return false;
	}


  var x=document.forms["InpPO"]["stype"].value;
	if (x==null || x=="s")
	{
	alert("Type Must Not Be Blank");
	document.InpPO.stype.focus;
	document.body.style.cursor = 'default';
	return false;
	}
  
	//Check the list of mat item no is Valid-------------------------------------------------------------
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
         
    for (var j = 1; j < rowCount; j++){
       var idrowItem = "prococode"+j;
       var rowItem = document.getElementById(idrowItem).value;	 
              
       if (rowItem != ""){
       	var strURL="aja_chk_subCodeCount.php?rawmatcdg="+rowItem;
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
						   alert ('Invalid Item Code : '+ rowItem + ' At Row '+j);
						   document.body.style.cursor = 'default';
						   return false;
						}
					} else {
						//alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
						document.body.style.cursor = 'default';
						return false;
					}
				}
			}	 
		  }
		
		  req.open("GET", strURL, false);
		  req.send(null);
	    }	  
    }
     if (flgchk == 0){
	   document.body.style.cursor = 'default';
	   return false;
	}
    //---------------------------------------------------------------------------------------------------

    
	//Check the list of mat item no got duplicate item no------------------------------------------------
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
	var mylist = new Array();	    

	for (var j = 1; j < rowCount; j++){

	    var idrowItem = "prococode"+j;
        var rowItem = document.getElementById(idrowItem).value;	 
        if (rowItem != ""){ 
        	mylist.push(rowItem);   
	    }		
    }		
	
	mylist.sort();
	var last = mylist[0];
	
	for (var i=1; i < mylist.length; i++) {
		if (mylist[i] == last){ 
			alert ("Duplicate Item Found; " + last);
			 document.body.style.cursor = 'default';
			 return false;
		}	
		last = mylist[i];
	}   
	//---------------------------------------------------------------------------------------------------
  
	//Check the list of mat item on hand is sufficient or not ---------------------------------------
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
         
    for (var j = 1; j < rowCount; j++){
       var idrowItem = "prococode"+j;
       var idrowship = "procoshipqty"+j;       
       var rowItem = document.getElementById(idrowItem).value;	 
       var rowship = document.getElementById(idrowship).value;	 
              
       if (rowItem != ""){
       	var strURL="getonhand.php?i="+rowItem;
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
						if (parseInt(req.responseText) < parseInt(rowship))
						{
						   flgchk = 0;
						   alert ('Insufficient Balance : '+ rowItem + ' At Row '+j);
						   document.body.style.cursor = 'default';
						   return false;
						}
					} else {
						//alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
						document.body.style.cursor = 'default';
						return false;
					}
				}
			}	 
		  }
		
		  req.open("GET", strURL, false);
		  req.send(null);
	    }	  
    }
     if (flgchk == 0){
	   document.body.style.cursor = 'default';
	   return false;
	}
    //---------------------------------------------------------------------------------------------------
  
}

function deleteRow(tableID) {
	try {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
         
        if (rowCount > 2){
             table.deleteRow(rowCount - 1);
        }else{
             alert ("No More Row To Remove");
        }
	}catch(e) {
		alert(e);
	}
  
}

function get_totpcs (str) {

 var ordqty = document.getElementById("procoshipqty"+str).value;
 var uomqty = document.getElementById("procouqty"+str).value;
 
 var totpcs = ordqty * uomqty;
 document.getElementById("procototpcs"+str).value = totpcs;
 //document.getElementById("procototpcs"+str).value = ordqty;
 
 onhand_checking(str);

}

function onhand_checking(str)
{
 
 var rand = Math.floor(Math.random() * 101);
 var iteminfo = document.getElementById("prococode"+str).value;
 var ordqty = document.getElementById("procoshipqty"+str).value;

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      //alert (xmlhttp.responseText);
      var onhand = xmlhttp.responseText;
      if(parseInt(onhand) < parseInt(ordqty)) { alert ("Insufficient Balance : "+onhand); }     
    }
  }
xmlhttp.open("GET","getonhand.php?i="+iteminfo+"&m="+rand,true);
xmlhttp.send();
}

</script>
</head>

<body onload="setup()">
	  <?php include("../topbarm.php"); ?> 
<!--  <?php include("../sidebarm.php"); ?> -->

  <?php
  	 $sql = "select * from salesentry";
     $sql .= " where sordno ='".$var_ordno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $custcd = $row['scustcd'];
     $orddte = date('d-m-Y', strtotime($row['sorddte']));
     $order_no = htmlentities($row['sordno']);
     $cust_po = htmlentities($row['scustpo']);
     $zone = $row['szone'];
     
     
  ?> 
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">UPDATE SHIPPING ENTRY</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Order No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sordno" id="sordnoid" type="text" readonly style="width: 204px;" value = "<?php echo $order_no; ?>">         
		   </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   &nbsp;</td>
	  	  </tr>

	   	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Customer</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<select name="sacustcd" id="sacustcd" style="width: 268px" readonly>
			 <?php
              $sql = "select custno, name from customer_master ORDER BY custno ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['custno'].'"';
        if ($custcd == $row['custno']) { echo "selected"; }
        echo '>'.$row['custno']." | ".$row['name'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

		   </td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Shipping Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="shipdte" id ="shipdte" type="text" style="width: 128px;" value="<?php  echo $orddte; ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('shipdte','ddMMyyyy')" style="cursor:pointer"></td>
		   </td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px"><div id="msgcd"></div></td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Type</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<select name="stype" id="stypecd" >
			 <?php
              $sql = "select shiptype_code, shiptype_desc from shiptype_master ORDER BY shiptype_code";
              $sql_result = mysql_query($sql);
              echo "<option size =30 value = 's' selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['shiptype_code'].'"';
        if ($zone == $row['shiptype_code']) { echo "selected"; }
        echo '>'.$row['shiptype_code']." | ".$row['shiptype_desc'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>
       <td style="width: 10px"></td>
		   <td style="width: 204px">Printed</td>
		   <td>:</td>
		   <td style="width: 284px">
		   	<select name="sprinted" id="sprintedcd" >
			 <?php
          echo "<option size =30 value = 'N' >NO</option>";
          echo "<option size =30 value = 'Y' >YES</option>";
         ?>          
	       </select>
		   </td>
		  </tr> 
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">&nbsp;</td>
	  	   <td style="width: 13px">&nbsp;</td>
	  	   <td style="width: 201px">
		   &nbsp;</td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   &nbsp;</td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px">
		   <td style="width: 10px"></td>
		   <td style="width: 204px"></td>
		   <td></td>
		   <td style="width: 284px">
	  	  </tr>	  	  
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 958px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Item Code</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Order Qty</th>
              <th class="tabheader">Ship Qty</th>
              <th class="tabheader">Tot. Ship(pcs)</th>
             </tr>
            </thead>
            <tbody>
             <?php
             	$sql = "SELECT * FROM salesentrydet";
             	$sql .= " Where sordno ='".$var_ordno."'"; 
	    		$sql .= " ORDER BY sproseq";  
			  	$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
       
          $sql2 = " select uom_pack from prod_uommas";
          $sql2 .= " where uom_code = '".$rowq['sprouom']."'";

          $result = mysql_query($sql2) or die ("Error uom : ".mysql_error());
          
          if(mysql_numrows($result) > 0) {
            $data = mysql_fetch_object($result);
            $var_uqty = $data->uom_pack;
            if ($var_uqty == "") { $var_uqty = 1; }         
           }  else { $var_uqty = 1; }  
             
             $var_totqty = 0;
             $var_totqty = $rowq['sproqty'] * $var_uqty;     
                                          
             ?>            
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" readonly="readonly" style="width: 27px; border:0;" value ="<?php echo $i; ?>"></td>
                <td>
				<input name="prococode[]" class="tInput" id="prococode<?php echo $i; ?>" tabindex="0" style="border-style: none; border-color: inherit; width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['sprocd']); ?>" readonly></td>
                <td>
				<input name="procouom[]" id="procouom<?php echo $i; ?>" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 60px" value ="<?php echo $rowq['sprouom']; ?>"></td>
        <input name="procouqty[]" value="<?php echo $var_uqty; ?>" id="procouqty<?php echo $i; ?>" type="hidden"></td>                
                <td>
				<input name="procoqty[]" id="procoqty<?php echo $i; ?>" style="border-style :none; width: 48px; text-align : right" value ="<?php echo $rowq['sproqty']; ?>" readonly></td>
                <td>
				<input name="procoshipqty[]" id="procoshipqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="" onBlur="get_totpcs(<?php echo $i; ?>)"></td>
                <td>
        <input name="procototpcs[]" class="tInput" id="procototpcs<?php echo $i; ?>" style="border-style: none; width: 48px; text-align : right" readonly="readonly" value =""></td>                
             </tr>
             
          <?php 
          
                	$i = $i + 1;          
          
             } // while
          ?>     
          <?php
            if ($i == 1){ 
            
          $sql2 = " select uom_pack from prod_uommas";
          $sql2 .= " where uom_code = '".$rowq['sprouom']."'";

          $result = mysql_query($sql2) or die ("Error uom : ".mysql_error());
          
          if(mysql_numrows($result) > 0) {
            $data = mysql_fetch_object($result);
            $var_uqty = $data->uom_pack;
            if ($var_uqty == "") { $var_uqty = 1; }         
           }  else { $var_uqty = 1; }  
             
             $var_totqty = 0;
             $var_totqty = $rowq['sproqty'] * $var_uqty;             
            
            ?>
            	 <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 27px; border:0;" value ="<?php echo $i; ?>"></td>
                <td>
				<input name="prococode[]" class="tInput" id="prococode<?php echo $i; ?>" tabindex="0" style="width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['sprocd']); ?>" readonly></td>
                <td>
				<input name="procouom[]" id="procouom<?php echo $i; ?>" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 60px" value ="<?php echo $rowq['sprouom']; ?>"></td>
        <input name="procouqty[]" value="<?php echo $var_uqty; ?>" id="procouqty<?php echo $i; ?>" type="hidden"></td>
                <td>
				<input name="procoqty[]" id="procoqty<?php echo $i; ?>" style="border-style :none; width: 48px; text-align : right" value ="<?php echo $rowq['sproqty']; ?>" readonly></td>
                <td>
				<input name="procoshipqty[]" id="procoshipqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="" onBlur="get_totpcs(<?php echo $i; ?>)"></td>
                <td>
        <input name="procototpcs[]" class="tInput" id="procototpcs<?php echo $i; ?>" style="border-style: none; width: 48px; text-align : right" readonly="readonly" value =""></td>                
             </tr>
		  <?php
            }
          ?>         
            </tbody>
           </table>
           

     <br /><br />
     
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_ship_mas.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnupdate.php");
         echo '<input type=submit name = "Submit" value="SHIP_ALL" class="butsub" style="width: 100px; height: 32px" >';          
				?>
				</td>
			</tr>
			<tr>
				<td style="width: 1150px" colspan="5">
				<span style="color:#FF0000">Message :</span>
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
							echo("<span>Duplicated Found Or Code Number Fall In Same Range</span>");
							break;
						case 4:
							echo("<span>Please Fill In The Data To Save</span>");
							break;
						case 5:
							echo("<span>This Product Code And Rev No Has A Record</span>");
							break;
						case 6:
							echo("<span>Duplicate Job File ID Found; Process Fail.</span>");
							break;
						case 7:
							echo("<span>This Product Code Dost Not Exits</span>");
							break;			
						default:
							echo "";
						}
					}	
          
         mysql_close ($db_link); 
				?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
	  	</table>
	   </form>	
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
