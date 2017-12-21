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
    
      $var_po = $_GET['po'];
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");

    }
    
    if ($_POST['Submit'] == "Update") {
    
   		$vmpo      = $_POST['po'];
		$vmsuppid  = $_POST['supp'];
		$vmpodte   = date('Y-m-d', strtotime($_POST['podte']));
		$vmterms   = $_POST['terms'];
		$vmdeldte  = date('Y-m-d', strtotime($_POST['deldte']));
		$vmremark  = $_POST['remark'];    
            
		if ($vmpo <> "") {
    			
        $vartoday = date("Y-m-d H:i:s"); 
        
				$sql = "Update po_master Set supplier = '$vmsuppid', po_date ='$vmpodte', terms='$vmterms', ";
				$sql .= "                    del_date='$vmdeldte', ";
				$sql .= "                    remark = '$vmremark', modified_by = '$var_loginid', modified_on='$vartoday ' ";
				$sql .= "  Where po_no ='$vmpo'";
        
				mysql_query($sql) or die ("Cant update : ".mysql_error());
        
				$sql =  "Delete From po_trans";
				$sql .= "  Where po_no ='$vmpo'";
				
				mysql_query($sql) or die ("Cant delete details : ".mysql_error());        
				
				if(!empty($_POST['prococode']) && is_array($_POST['prococode'])) 
				{	
					foreach($_POST['prococode'] as $row=>$matcd ) {
						$matcode   = $matcd;
						$matseqno  = $_POST['seqno'][$row];
						$matsupitm = $_POST['supplieritem'][$row];
						$matdesc   = mysql_real_escape_string($_POST['procodesc'][$row]);
						$matuom    = $_POST['procouom'][$row];
						$matqty    = $_POST['procoqty'][$row];
						$matuprice = $_POST['procoprice'][$row];

					
						//if ($matcode <> "")
						//{

							$sql = "INSERT INTO po_trans values 
						    		('$vmpo', '$matcode', '$matsupitm','$matqty', '$matuprice','$matseqno', '$matdesc', '$matuom')";
							//echo $sql;
							mysql_query($sql) or die ("Cant insert : ".mysql_error());
           				//}	
					}
				}
				
				//--------------------
//block - cedricwan  
	//User need to segregate into different buyer order if have more than 1 costing.  
    //When MDF/FNL/Export dept. placed PO, NO MORE auto insert into buyer sales order module. 
    //NLG user need to key in buyer order once received PO from MDF/FNL/Export dept. 
    //unblock again due to Mr Teh said MDF/FNL need to auto-insert into nl_pro 17 Jun 2014


				
				if ($vmsuppid = 'NLG')
				{
				
				// insert into RAW MAT Buyer Order Form which key in in FNL PO (Supplier = "NLG")
				//---- here to connect to nl_db database -----//
				//$var_server = '127.0.0.1';
				//$var_userid = 'root';
				//$var_password = '';
				//$var_db_name='nl_db';
				 
		 		
		 		$var_server = '192.168.0.142:9909';
		        $var_userid = 'root';
		        $var_password = 'admin9002';
		        $var_db_name='nl_db'; 
	     
		 		$db_link2  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
	  	 		mysql_select_db("$var_db_name")or die("cannot select DB ".$var_db_name.mysql_error());
	
		 		//mysql_query("SET NAMES 'utf8'", $db_link2)or die(mysql_error()); 	//this will take effect both retrieve, update or insert data of utf-8 
		 		//---- END connect to nl_db database -----//
		 		
		 			$sql = "DELETE  FROM salesentry WHERE sordno = '$vmpo'"; 
					mysql_query($sql) or die ("Cant DELETE Salesentry Master  : ".mysql_error());

				
					$vmtotqty = 0;
					$vmtotamt = 0;
					//$sql = "INSERT INTO salesentry values 
					//		('$vmpo', '$vmpodte', '$vmdeldte','FNL', '$vmremark','$var_loginid','$vartoday', 
					//		 '$var_loginid','$vartoday','ACTIVE','$vmtotqty', '$vmtotamt')"; 
					//mysql_query($sql) or die ("Cant insert Salesentry Master : ".mysql_error());
					
					
					$sql = "INSERT INTO salesentry values 
							('$vmpo', '$vmpo', '$vmpodte', '$vmdeldte','FNL', 'FNL', '$vmremark', '', '', '', '$var_loginid','$vartoday', 
							 '$var_loginid','$vartoday','ACTIVE','$vmtotqty', '$vmtotamt')"; 
					mysql_query($sql) or die ("Cant insert Salesentry Master : ".mysql_error());

					
					
					$sql = "DELETE  FROM salesentrydet WHERE sordno = '$vmpo'"; 
					mysql_query($sql) or die ("Cant DELETE Salesentry Detail : ".mysql_error());

					
					if(!empty($_POST['prococode']) && is_array($_POST['prococode'])) 
					{	
						foreach($_POST['prococode'] as $row=>$matcd ) {				
							$matcode   = $matcd;
							$matseqno  = $_POST['seqno'][$row];
							$matsupitm = $_POST['supplieritem'][$row];
							$matdesc   = $_POST['procodesc'][$row];
							$matuom    = $_POST['procouom'][$row];
							$matqty    = $_POST['procoqty'][$row];
							$matuprice = $_POST['procoprice'][$row];		
																			
							if ($matcode == "")
							{
								$matcode = $matsupitm;
							}												
							if ($matcode <> "")
							{
								if ($matqty == ""){ $matqty= 0;}
								if ($matuprice == ""){ $matuprice = 0;}
								
								$protamt = $matqty * $matuprice;
								$vmtotqty = $vmtotqty + $matqty ;
								$vmtotamt = $vmtotamt + $protamt;
								if ($protamt == ""){ $protamt = 0;}
								
								$sql = "INSERT INTO salesentrydet values 
										('$vmpo', 'FNL',  '$matsupitm', '$matcode', '$matdesc','$matqty','$matuom','$matuprice','$protamt', '$matseqno')";

								mysql_query($sql) or die ("Cant insert Salesentrydet : ".mysql_error());
							}	
						}
						$sql = "UPDATE salesentry SET toqty = '$vmtotqty ', toamt ='$vmtotamt' ";
				        $sql .= "  WHERE sordno ='$var_invno'";
		
				        mysql_query($sql) or die ("Cant Update Salesentry Amt and Qty : ".mysql_error());

					}
				}	
				// --------------------- end of insert --------------------------------------//
 //end of block.				
				
				
				
				$backloc = "../pur_ord/m_po.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
					
		}else{
			$backloc = "../pur_ord/po.php?stat=4&menucd=".$var_menucode;
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


<!-- Our jQuery Script to make everything work -->
<script  type="text/javascript" src="jq-ac-script.js"></script>


<script type="text/javascript"> 
$(document).ready(function(){
	var ac_config = {
		source: "autocomscrpro1.php",
		select: function(event, ui){
			$("#prod_code").val(ui.item.prod_code);
			$("#promodesc").val(ui.item.prod_desc);
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

		document.InpPO.supp.focus();
				
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
        
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "podte");
		dateMask1.validationMessage = errorMessage;
		
		
		var dateMask2 = new DateMask("dd-MM-yyyy", "deldte");
		dateMask2.validationMessage = errorMessage;  
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

    var x=document.forms["InpPO"]["supp"].value;
	if (x==null || x=="s")
	{
	alert("Supplier Must Not Be Blank");
	document.InpPO.supp.focus;
	return false;
	}

   var x=document.forms["InpPO"]["podte"].value;
	if (x==null || x=="")
	{
	alert("Date Must Not Be Blank");
	document.InpPO.podte.focus;
	return false;
	}

	var x=document.forms["InpPO"]["deldte"].value;
	if (x==null || x=="")
	{
	alert("Delivery Date Must Not Be Blank");
	document.InpPO.deldte.focus;  
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
						   alert ('Invalid Raw Mat Item Sub Code : '+ rowItem + ' At Row '+j);
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
	    }	  
    }
     if (flgchk == 0){
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
			 return false;
		}	
		last = mylist[i];
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


function showsupplier(str)
{

var rand = Math.floor(Math.random() * 101);

if (str=="s")
  {
  alert ("Please choose a supplier to continue");
  return;
  } 
  
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
    var txtrst = xmlhttp.responseText;
    
    var result = txtrst.split("+");
    //alert (result[0]+" : "+result[1]+" : "+result[2]);
    document.getElementById("suppadd").value=result[0];   
    document.getElementById("terms").value=result[1];  
    
    }
  }
xmlhttp.open("GET","getsupplier.php?q="+str+"&m="+rand,true);
xmlhttp.send();
}

function calcCost (str) {

 var ordqty = document.getElementById("procoqty"+str).value;
 var price = document.getElementById("procoprice"+str).value;
 
 var totamt = ordqty * price; 
    
 document.getElementById("procoamount"+str).value = totamt.toFixed(2);
 
}

</script>
</head>

<body onload="setup()">
	  <?php include("../topbarm.php"); ?> 
<!--  <?php include("../sidebarm.php"); ?> -->

  <?php
  	 $sql = "select * from po_master";
     $sql .= " where po_no ='".$var_po."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $supplier = $row['supplier'];
     $po_date = date('d-m-Y', strtotime($row['po_date']));
     $terms = $row['terms'];
     $del_date = date('d-m-Y', strtotime($row['del_date']));
     $remark   = htmlentities($row['remark']);
     
     $sql="SELECT add1, add2, add3, add4, contact, tel, fax, mobile, terms  FROM supplier_master ";
     $sql .= " where suppno = '".$supplier."'";

     $result = mysql_query($sql) or die ("Error : ".mysql_error());

     $data = mysql_fetch_object($result);

     $var_add = "";
     
     $var_add .= $data->add1." \n"; 
     $var_add .= $data->add2." \n";
     $var_add .= $data->add3." \n";
     $var_add .= $data->add4." \n";
     
     $var_add = strtoupper($var_add);
     $var_add .= "\nTel : "; 
     if (!empty($data->tel)) { $var_add .= $data->tel.","; }  
     if (!empty($data->mobile)) { $var_add .= $data->mobile; }   
     $var_add .= "\nFax : "; 
     if (!empty($data->fax1)) { $var_add .= $data->fax; }     
     
  ?> 
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 849px;" class="style2">
	 <legend class="title">PURCHASE ORDER</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 838px"; border="0">
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 110px">Supplier</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 300px">
           		<select class= "inputtxt" name="supp"  id = "supp" onchange="showsupplier(this.value)" >
          		 <option value ="s">-SELECT-</option>

          		 <?php 
                  $sql="SELECT suppno, name FROM supplier_master ";
                  $sql .= " where (status <> 'D' or status is null)"; 
                  $sql .= " order by suppno";

                  $result = mysql_query($sql);

                  while($row = mysql_fetch_array($result))
                     {
                       echo "<option value= '".$row['suppno']."'";
                       if ($supplier == $row['suppno']) { echo "selected"; }
                       echo ">".$row['suppno']." - ".$row['name'];
                       echo "</option>";
                     }                  
          		 ?> 
          		 </select>
			</td>    
			<td style="width: 29px"></td>
			<td style="width: 136px">P.O. #</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		   <input class="textnoentry" readonly="readonly" type="text" name="po"  id = "po" style="width: 128px;" value="<?php  echo $var_po; ?>"  >
		   </td>        
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td ></td>
	  	   <td ></td>
	  	   <td ></td>
	  	  </tr>
	  	  <tr>
	  	   <td valign="top">Address</td>
	  	   <td rowspan="6" valign="top">:</td>
         <td colspan="2" rowspan="6">
          <textarea class="texta" name="suppadd" id="suppadd" COLS=35 ROWS=8><?php echo $var_add; ?></textarea>
         </td>
			<td style="width: 29px"></td>
			<td style="width: 136px">P.O Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		   <input class="inputtxt" name="podte" id ="podte" type="text" style="width: 128px;" value="<?php  echo $po_date; ?>"  >
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('podte','ddMMyyyy')" style="cursor:pointer">
		   </td>       
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td ></td>
	  	   <td ></td>
	  	   <td ></td>
	  	  </tr>
		  <tr>
		   <td></td>
       <td></td>
		   <td >Terms</td>
		   <td>:</td>
		   <td>
		   <input name="terms" id ="terms" type="text" style="width: 156px;" readonly value = "<?php echo $terms; ?>" ></td>
		  </tr> 
		  <tr>
	  	   <td></td>
		   <td></td>
		   <td ></td>
		   <td></td>
		   <td></td>
	  	  </tr>
		  <tr>
	  	   <td></td>
		   <td></td>
		   <td ></td>
		   <td></td>
		   <td></td>
        </tr>
	  	   <tr>
	  	   <td></td>
		   <td></td>
			<td >Delivery Date</td>
			<td >:</td>
			<td >
		   <input class="inputtxt" name="deldte" id ="deldte" type="text" style="width: 128px;" value="<?php  echo $del_date; ?>" >
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('deldte','ddMMyyyy')" style="cursor:pointer">
		   </td>       
	  	  </tr>
	
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Item Code</th>
              <th class="tabheader">Supplier Item</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">Quantity</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Unit Price</th>
              <th class="tabheader">Amount</th>
             </tr>
            </thead>
            <tbody>
             <?php
             	$sql = "SELECT * FROM po_trans";
             	$sql .= " Where po_no='".$var_po."'"; 
	    		$sql .= " ORDER BY seqno";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
        
             $var_amt = $rowq['qty'] * $rowq['uprice']; 
                   
                                          
             ?>            
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 27px; border:0;" value ="<?php echo $i; ?>"></td>
                <td>
				<input name="prococode[]" tProItem1=1 id="prococode<?php echo $i; ?>" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['itemcode']); ?>"></td>
                <td>
				<input name="supplieritem[]" tProItem1=1 id="prococode<?php echo $i; ?>0" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['supplieritem']); ?>"></td>
                <td>
				<input name="procodesc[]" class="tInput" id="procodesc" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;" value ="<?php echo $rowq['itmdesc']; ?>"></td>
                <td>
				<input name="procoqty[]" id="procoqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['qty']; ?>" onBlur="calcCost('<?php echo $i; ?>');"></td>
                <td>
				<input name="procouom[]" id="procouom" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 75px" value ="<?php echo $rowq['itmuom']; ?>"></td>
                <td>
				<input name="procoprice[]" class="tInput" id="procoprice<?php echo $i; ?>" style=" width: 75px; text-align : right" value ="<?php echo $rowq['uprice']; ?>" onBlur="calcCost('<?php echo $i; ?>');"></td>
                <td>
				<input name="procoamount[]" class="tInput" id="procoamount<?php echo $i; ?>" readonly="readonly" style="width: 75px; border:0; text-align : right" value ="<?php echo number_format($var_amt,2,'.',' '); ?>"></td>
             </tr>
             
          <?php 
          
                	$i = $i + 1;          
          
             } // while
          ?>     
          <?php
            if ($i == 1){ ?>
            	 <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 27px; border:0;" value ="<?php echo $i; ?>"></td>
                <td>
				<input name="prococode[]" tProItem1=1 id="prococode<?php echo $i; ?>" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['itemcode']); ?>"></td>
                <td>
				<input name="supplieritem[]" tProItem1=1 id="prococode<?php echo $i; ?>1" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['supplieritem']); ?>"></td>
                <td>
				<input name="procodesc[]" class="tInput" id="procodesc" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;" value ="<?php echo $row1['description']; ?>"></td>
                <td>
				<input name="procoqty[]" id="procoqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['qty']; ?>" onBlur="calcCost('<?php echo $i; ?>');"></td>
                <td>
				<input name="procouom[]" id="procouom" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 75px" value ="<?php echo $rowq['uom']; ?>"></td>
                <td>
				<input name="procoprice[]" class="tInput" id="procoprice<?php echo $i; ?>" style="width: 75px; text-align : right" value ="<?php echo $rowq['uprice']; ?>" onBlur="calcCost('<?php echo $i; ?>');"></td>
                <td>
				<input name="procoamount[]" class="tInput" id="procoamount<?php echo $i; ?>" readonly="readonly" style="width: 75px; border:0; text-align : right" value ="<?php echo number_format($var_amt,2,'.',' '); ?>"></td>
             </tr>
		  <?php
            }
          ?>         
            </tbody>
           </table>
           
         <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

     <br /><br />
		 <table>
			<tr>
				<td valign="top">Remarks :</td>
        <td><textarea class="inputtxt" name="remark" id="remark1" COLS=60 ROWS=5><?php echo $remark; ?></textarea></td>
			</tr>
    </table>  

     <br /><br />
     
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_po.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnupdate.php");
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
