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
    
    if ($_POST['Submit'] == "GetItem") {
    
		$vmorddte = date('Y-m-d', strtotime($_POST['saorddte']));
		$vmcustcd = $_POST['sacustcd'];
		$vmmthyr  = $_POST['samthyr'];
		$vmperiod = $_POST['speriod'];
    $vmlessamt = $_POST['lessamt'];
    $vmlesstype = $_POST['lesstype'];
        
    }    
    
    if ($_POST['Submit'] == "Save") {
    
    //phpinfo();
		$vmorddte = date('Y-m-d', strtotime($_POST['saorddte']));
		$vmcustcd = $_POST['sacustcd'];
		$vmmthyr  = $_POST['samthyr'];
		$vmperiod = $_POST['speriod'];
    $vmlessamt = $_POST['lessamt'];  
    $vmlesstype = $_POST['lesstype'];
                
		if ($vmcustcd <> "") {
    
            /*----------------------------- Cash Bill details ------------------------------------ */
              $chk_invno_query = mysql_query("select count(*) from `ctrl_sysno` where `descrip` = 'CTRSALES' and counter = '$vmcustcd'; ", $db_link);

              $chk_invno_res = mysql_fetch_array($chk_invno_query) or die("cant Get Sales Order No Info".mysql_error());
              
              if ($chk_invno_res[0] > 0 ) {
                  $get_invno_query = mysql_query("select noctrl from `ctrl_sysno` where `descrip` = 'CTRSALES' and counter = '$vmcustcd' ", $db_link);
                  
                  $get_invno_res = mysql_fetch_object($get_invno_query) or die("Cant Get Sales Order No 2 ".mysql_error()); 

                  $var_invno = vsprintf("%05d",$get_invno_res->noctrl+1); 
                  $var_invno = $vmcustcd.$var_invno; 
                  
 		  mysql_query("update `ctrl_sysno` set `noctrl` = `noctrl` + 1
                           where `descrip` = 'CTRSALES'
                           and counter = '$vmcustcd'", $db_link) 
                           or die("Cant Update Cash Bill Auto No ".mysql_error());              
               
                }  else { 

		   mysql_query("insert into `ctrl_sysno` 
                          (`descrip`, `counter`, `noctrl`)
                   values ('CTRSALES', '$vmcustcd', 1);",$db_link) or die("Cant Insert Into Cash Bill Auto No");

                   $var_invno = $vmcustcd."00001";

                }  

            /*--------------------------- end Inv no details ---------------------------------- */
    
			
        $vartoday = date("Y-m-d H:i:s"); 
        
				$sql = "INSERT INTO csalesmas values 
						('$var_invno', '$vmorddte','$vmcustcd','$vmmthyr','$vmperiod', '$vmlesstype', '$vmlessamt', 
						 '$var_loginid','$vartoday','$var_loginid', '$vartoday', 'A')";
				mysql_query($sql) or die ("Cant insert : ".mysql_error());
				
				if(!empty($_POST['prococode']) && is_array($_POST['prococode'])) 
				{	
					foreach($_POST['prococode'] as $row=>$matcd ) {
						$matcode     = $matcd;
						$matseqno    = $_POST['seqno'][$row];
						$matupri     = $_POST['procoupri'][$row];
            			$mattype     = $_POST['procotype'][$row];
						$matdoqty    = $_POST['procodoqty'][$row];
						$matsoldqty  = $_POST['procosoldqty'][$row];
						$matrtnqty   = $_POST['procortnqty'][$row];
						$matshortqty = $_POST['procoshortqty'][$row];
						$matoverqty  = $_POST['procooverqty'][$row];
						$matadjqty   = $_POST['procoadjqty'][$row];
						$matendbal   = $_POST['procobalqty'][$row];
						$openingqty  = $_POST['opening'][$row];
						//echo "</br>";
					//echo 'MAT - '. $matcode . "</br>";
					//echo 'S Qty - '. $matsoldqty  . "</br>";
					//echo ' Row - '. $row . "</br>";
							
							if ($matrtnqty ==NULL) { $matrtnqty =0; }
							if ($matdoqty==NULL) { $matdoqty=0; }
							if ($matsoldqty==NULL) { $matsoldqty =0; }
							if ($matshortqty==NULL) { $matshortqty=0; }
							if ($matoverqty==NULL) { $matoverqty=0; }
							if ($matadjqty==NULL) { $matadjqty=0; }
							if ($matadjqty==NULL) { $matadjqty=0; }
							if ($openingqty==NULL) { $openingqty=0; }

					//echo 'MAT - '. $matcode . "</br>";
					//echo 'S Qty - '. $matsoldqty  . "</br>";
					//echo ' Row - '. $row . "</br>";
						//if ($matcode <> "" && $matsoldqty <> "")
						if ($matcode <> "")

						{
							//if ($matqty == "" or empty($matqty)){$matqty = 0;}
							

							$sql = "INSERT INTO csalesdet values 
						    		('$var_invno', '$matcode', '$matupri', '$mattype', '$matdoqty','$openingqty','$matsoldqty', '$matrtnqty',
                     '$matshortqty', '$matoverqty', '$matadjqty', '$matendbal', '$matseqno')";
                    
							mysql_query($sql) or die ("Cant insert 2 : ".mysql_error());
							//echo $sql; 
           				}	
					}
				}
				//break;
				$backloc = "../cons/m_csales_mas.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
					
		}else{
			$backloc = "../cons/csales_mas.php?stat=4&menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 
		}  
    }  
  
 if ($vmorddte == "") { $vmorddte = date("d-m-Y"); }  
 if ($vmmthyr == "") { $vmmthyr = date("m/Y"); }  
    
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

		document.InpPO.sacustcd.focus();
				
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "saorddte");
		dateMask1.validationMessage = errorMessage;
 
}

function setup2() {

 		//Set up the date parsers
        var dateParser = new DateParser("MM/yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("MM/yyyy", "samthyr");
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

  var x=document.forms["InpPO"]["sacustcd"].value;
	if (x==null || x=="s")
	{
	alert("Customer Must Not Be Blank");
	document.InpPO.sacustcd.focus;
	return false;
	}

   var x=document.forms["InpPO"]["saorddte"].value;
	if (x==null || x=="")
	{
	alert("Order Date Must Not Be Blank");
	document.InpPO.saorddte.focus;
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
						   alert ('Invalid Product Code : '+ rowItem + ' At Row '+j);
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
			//alert ("Duplicate Item Found; " + last);
			 //return false;
		}	
		last = mylist[i];
	}   
	//---------------------------------------------------------------------------------------------------
  
	//Check the list of item type is Valid-------------------------------------------------------------
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
         
    for (var j = 1; j < rowCount; j++){
       var idrowItem = "procotype"+j;
       var rowItem = document.getElementById(idrowItem).value;	 
              
       if (rowItem != ""){
       	var strURL="aja_chk_prodtypeCount.php?rawmatcdg="+rowItem;
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
						   alert ('Invalid Product Type : '+ rowItem + ' At Row '+j);
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
    
    //Check input price is Valid-------------------------------------------------------
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
	
	  for (var j = 1; j < rowCount; j++){

	    var idrowbook = "procoupri"+j;
        var rowItemc = document.getElementById(idrowbook).value;	 
        
        if (rowItemc != ""){ 
        	if(isNaN(rowItemc)) {
    	   		alert('Please Enter a valid number for Unit Price :' + rowItemc + " Line No :"+j);
    	   		document.itemsTable.idrowbook.focus();
    	   		return false;
    	    }    
    	}
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


function showoveDecimal(vterms){
 	if (vterms != ""){
		if(isNaN(vterms)) {
    	   alert('Please Enter a number for Terms :' + vterms);
    	   document.InpPO.terms.focus();
    	   return false;
    	}
    }
}

function getUprice(str)
{

 var rand = Math.floor(Math.random() * 101);
 var custinfo = document.getElementById("sacustcd").value;
 var iteminfo = document.getElementById("prococode"+str).value;
 var domthyr  = document.getElementById("samthyrid").value;

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
    var priamt = xmlhttp.responseText;
    
    var result = priamt.split("~");
   // alert (str+" Type: " +result[0]+" $: "+result[1]+" DO Qty: "+result[2]+" Beg Bal: "+result[3]+" Open: "+result[4]);
    document.getElementById("procotype"+str).value=result[0];   
    document.getElementById("procoupri"+str).value=result[1]; 
    document.getElementById("opening"+str).value=result[4];
    document.getElementById("procodoqty"+str).value=result[2];
    document.getElementById("begbal"+str).value=result[3];   

    }
  }
xmlhttp.open("GET","getsalesprice.php?s="+custinfo+"&i="+iteminfo+"&d="+domthyr+"&m="+rand,true);
xmlhttp.send();
}

function getLess()
{

 var rand = Math.floor(Math.random() * 101);
 var custinfo = document.getElementById("sacustcd").value;

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
    var lessflg = xmlhttp.responseText;
    lessflg = lessflg.replace(/\s+/g, "");

    //alert("K"+lessflg+"K");
    
    if (lessflg == 'N') {
      document.getElementById("lesstypecd").value="1";     
      document.getElementById("lessamtid").value=0;   
      document.getElementById("lessamtid").readOnly=true; 
      } else {
      document.getElementById("lessamtid").value="";   
      document.getElementById("lessamtid").readOnly=false;       
      } 
    }
  }
xmlhttp.open("GET","getless.php?s="+custinfo+"&m="+rand,true);
xmlhttp.send();
}

function getamt (str) {

 var soldqty = document.getElementById("procosoldqty"+str).value;
 var price = document.getElementById("procoupri"+str).value;
 
 var totamt = soldqty * price; 
    
 document.getElementById("procosamt"+str).value = totamt.toFixed(2);
 
}

function getbal (str) {

  var begbal = parseInt(document.getElementById("begbal"+str).value); //alert ('X');
  var opening = parseInt(document.getElementById("opening"+str).value);
  var doqty = parseInt(document.getElementById("procodoqty"+str).value);
  var soldqty = parseInt(document.getElementById("procosoldqty"+str).value);
  var rtnqty = parseInt(document.getElementById("procortnqty"+str).value);
  var shortqty = parseInt(document.getElementById("procoshortqty"+str).value);
  var overqty = parseInt(document.getElementById("procooverqty"+str).value);
  var adjqty = parseInt(document.getElementById("procoadjqty"+str).value);
  

  //alert(begbal);
  if (isNaN(begbal)) { begbal = 0; }
  if (isNaN(opening)) { opening = 0; }
  if (isNaN(doqty)) { doqty = 0; }
  if (isNaN(soldqty)) { soldqty = 0; }
  if (isNaN(rtnqty)) { rtnqty = 0; }
  if (isNaN(shortqty)) { shortqty = 0; }
  if (isNaN(overqty)) { overqty = 0; }
  if (isNaN(adjqty)) { adjqty = 0; }
  
  var endbal = opening + begbal + doqty - soldqty - rtnqty - shortqty + overqty + adjqty;
 document.getElementById("procobalqty"+str).value = endbal;
 
}

</script>
</head>
<body onload="setup(); setup2();">
  <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">COUNTER SALES ENTRY</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<!--		<input id="begbal<?php echo $i; ?>" type="hidden" value ="<?php echo $begbal; ?>" readonly> -->
				<input id="begbal<?php echo $i; ?>" type="hidden" value ="<?php echo $begbal; ?>" >

			
	   
		<table style="width: 993px; ">
	   	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Customer</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<select name="sacustcd" id="sacustcd" style="width: 268px" onChange="getLess();">
			 <?php
              $sql = "select custno, name from customer_master";
              $sql .= " where type = 'C'";
              $sql .= " ORDER BY custno ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['custno'].'"';;
        if ($vmcustcd == $row['custno']) { echo "selected"; }
        echo '>'.$row['custno']." | ".$row['name'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

		   </td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Key In Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="saorddte" id ="saorddte" type="text" style="width: 128px;" value="<?php  echo $vmorddte; ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('saorddte','ddMMyyyy')" style="cursor:pointer"></td>
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
	  	   <td style="width: 122px">MM/YYYY</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="samthyr" id="samthyrid" type="text" maxlength="45" style="width: 100px;" value="<?php echo $vmmthyr; ?>"></td>		   
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Period</td>
		   <td>:</td>
		   <td style="width: 284px">
		   	<select name="speriod" id="speriodcd" >
			 <?php
				echo '<option value="1"';
        if ($vmperiod == '1') { echo "selected"; }
        echo '>1 | FIRST HALF MONTH</option>';
        
				echo '<option value="2"';
        if ($vmperiod == '2') { echo "selected"; }
        echo '>2 | SECOND HALF MONTH</option>';        
                    
	         ?>				   
	       </select>

		   </td>
		  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Less</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<select name="lesstype" id="lesstypecd" >
			 <?php
				echo '<option value="1"';
        if ($vmlesstype == '1') { echo "selected"; }
        echo '>NO</option>';
        
				echo '<option value="2"';
        if ($vmlesstype == '2') { echo "selected"; }
        echo '>%</option>'; 
        
				echo '<option value="3"';
        if ($vmlesstype == '3') { echo "selected"; }
        echo '>AMT</option>';                
                    
	         ?>				   
	       </select>         
			<input class="inputtxt" name="lessamt" id="lessamtid" type="text" maxlength="10" style="width: 50px;text-align : right" value="<?php echo $vmlessamt; ?>"></td>		   
		     </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
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
       <input type=submit name = "Submit" value="GetItem" class="butsub" style="width: 90px; height: 32px" >
		   </td>
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
           
         <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

     	  <table id="itemsTable" class="general-table" style="width: 958px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Group Code</th>
              <th class="tabheader">Unit Price</th>
              <th class="tabheader">Type</th>
              <th class="tabheader">Opening</th>
              <th class="tabheader">D/O Qty</th>              
              <th class="tabheader">Sold Qty</th>
              <th class="tabheader">Sales Amt</th>
              <th class="tabheader">Rtn Qty</th>
              <th class="tabheader">Short Qty</th>
              <th class="tabheader">Over Qty</th> 
              <th class="tabheader">Adj Qty</th>
              <th class="tabheader">End Balance</th>                                           
             </tr>
            </thead>
            <tbody>
             <?php
              $sql = " select sortby from customer_master";
              $sql .= " where custno = '$vmcustcd'";
              $rs_result = mysql_query($sql);
              
              //echo $sql;
              if (mysql_numrows($rs_result) > 0) {
                 $rst = mysql_fetch_object($rs_result);
                 $var_tmp = $rst->sortby; 
                 
                 switch ($var_tmp) {
                   case "1" : $var_sortby = "sprocd"; break;
                   case "2" : $var_sortby = "sprounipri"; break;
                   case "3" : $var_sortby = "sptype"; break;                   
                   default : $var_sortby = "sprocd"; break;
                 }
                //echo $var_sortby; 
                
              } else { $var_sortby = "sprocd"; }
             
              $sql = " select max(sordno) as sordno from csalesmas";
              $sql .= " where scustcd = '".$vmcustcd."'";
              
              $result = mysql_query ($sql) or die ("error ordno : ".mysql_error());
              if(mysql_numrows($result) > 0) {
                $rst = mysql_fetch_object($result);
                $var_ordno = $rst->sordno;
              }  else { $var_ordno = ""; }   

          		$domthyr  = $_POST['samthyr'];
              if($domthyr <> "") {
                $tmpmth = explode("/", $domthyr);
                $domth = intval($tmpmth[0]);
                $doyear = $tmpmth[1]; 
                
                if($domth == 12) {
                  $prevmth = 1;
                  $prevyr = intval($doyear) - 1;
                }  else {
                  $prevmth = $domth - 1;
                  $prevyr = $doyear;
                  $prevmthyr =  vsprintf("%02d",$prevmth)."/".$prevyr; 
                }

              }
          
             	$sql = "SELECT sprocd, sprounipri, sptype, endbal FROM csalesdet";
             	$sql .= " Where sordno = '".$var_ordno."'"; 
	    		    $sql .= " ORDER BY ".$var_sortby;  
			  	    $rs_result = mysql_query($sql); 

              //echo $sql; break;
 
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
        
              if($domthyr <> "") {

                $sql2 = " SELECT SUM(x.sproqty) as tot FROM salesentrydet x, salesentry y";
                $sql2 .= " WHERE y.scustcd = '".$vmcustcd."'";
                $sql2 .= " AND x.sordno = y.sordno";
                $sql2 .= " AND y.sordno IN (SELECT sordno FROM salesdo WHERE ";
                $sql2 .= " MONTH(delorddte) = ".$domth;
                $sql2 .= " AND YEAR (delorddte) = ".$doyear.")";
                $sql2 .= " AND x.sprocd = '".$rowq['sprocd']."'";
                 $sql2 .= " AND x.sordno = y.sordno";

                GCHT00003
                
                //echo $sql2;
                $tmp2 = mysql_query($sql2) or die ("cant get do qty : ".mysql_error());
                
                if(mysql_numrows($tmp2) >0) {
                   $rst2 = mysql_fetch_object($tmp2);
                   $doqty = $rst2->tot; 
                   if($doqty =="") { $doqty = 0; }
                 }
                 
                $sql2 = " SELECT endbal FROM csalesdet x, csalesmas y ";
                $sql2 .= " WHERE y.scustcd = '".$vmcustcd."'";
                $sql2 .= " AND y.smthyr = '".$prevmthyr."'";
                $sql2 .= " AND x.sordno = y.sordno";
                $sql2 .= " AND x.sprocd = '".$rowq['sprocd']."'";
                
                //echo $sql2;
                $tmp2 = mysql_query($sql2) or die ("cant get do qty : ".mysql_error());
                
                if(mysql_numrows($tmp2) >0) {
                   $rst2 = mysql_fetch_object($tmp2);
                   $begbal = $rst2->endbal; 
                   if($begbal =="") { $begbal = 0; }
                 } else { $begbal = 0; }
                 
                 
               } else {
                   $doqty = 0; 
                   $begbal = 0;
               }
               

                                          
             ?>            
             <tr class="item-row">
                <td style="width: 30px">
                <input id="begbal<?php echo $i; ?>" type="hidden" value ="<?php echo $begbal; ?>" >
				<input name="seqno[]1" id="seqno" readonly="readonly" style="width: 27px; border:0;" value ="<?php echo $i; ?>" size="20"></td>
                <td>
				<input name="prococode[]1" tProItem1=1 id="prococode<?php echo $i; ?>" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['sprocd']); ?>" size="20"></td>
                <td>
				<input name="procoupri[]1" id="procoupri<?php echo $i; ?>" class="tInput" style="width: 60px" value ="<?php echo $rowq['sprounipri']; ?>" size="20"></td>
                <td>
        <input name="procotype[]1" class="tInput" id="procotype<?php echo $i; ?>" style="width: 48px;" value ="<?php echo $rowq['sptype']; ?>" size="20" onchange ="upperCase(this.id)"></td>                
                <td>
       			<input name="opening[]1" id="opening<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['endbal']; ?>" size="20"> </td>
                <td>
				<input name="procodoqty[]1" id="procodoqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $doqty; ?>" size="20"> </td>
                <td>
        <input name="procosoldqty[]1" class="tInput" id="procosoldqty<?php echo $i; ?>" style="width: 48px; text-align : right"  value ="<?php echo $var_soldqty; ?>" onBlur="getamt(<?php echo $i; ?>)" size="20"></td>                
                <td>
        <input name="procosamt[]1" class="tInput" id="procosamt<?php echo $i; ?>" style="border-style: none; width: 48px; text-align : right" readonly="readonly" value ="<?php echo $var_salesamt; ?>" size="20"></td>                
                <td>
        <input name="procortnqty[]1" class="tInput" id="procortnqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $var_rtnqty; ?>" size="20"></td>                
                <td>
        <input name="procoshortqty[]1" class="tInput" id="procoshortqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $var_shortqty; ?>" size="20"></td>                
                <td>
        <input name="procooverqty[]1" class="tInput" id="procooverqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $var_overqty; ?>" size="20"></td>                
                <td>
        <input name="procoadjqty[]1" class="tInput" id="procoadjqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $var_adjqty; ?>" onBlur="getbal(<?php echo $i; ?>)" size="20"></td>                
                <td>
        <input name="procobalqty[]1" class="tInput" id="procobalqty<?php echo $i; ?>" style="border-style: none; width: 48px; text-align : right" value ="<?php echo $var_endbal; ?>" size="20"></td>              
             </tr>
             
          <?php 
          
                	$i = $i + 1;          
          
             } // while
          ?>     

          <?php
            if ($i == 1){ ?>
            	 <tr class="item-row">
                <td style="width: 30px">
                <input id="begbal<?php echo $i; ?>" type="hidden" value ="<?php echo $begbal; ?>" >
				<input name="seqno[]" id="seqno" readonly="readonly" style="width: 27px; border:0;" value ="<?php echo $i; ?>"></td>
                <td>
				<input name="prococode[]" tProItem1=1 id="prococode<?php echo $i; ?>" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['sprocd']); ?>"></td>
                <td>
				<input name="procoupri[]" id="procoupri<?php echo $i; ?>" class="tInput" style="width: 60px" value ="<?php echo $rowq['sprounipri']; ?>"></td>
                <td>
        <input name="procotype[]" class="tInput" id="procotype<?php echo $i; ?>" style=" width: 48px;" value ="<?php echo $rowq['sptype']; ?>" onchange ="upperCase(this.id)"></td>
                <td>
				<input name="opening[]" id="opening<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $doqty; ?>" ></td>
                <td>
				<input name="procodoqty[]" id="procodoqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $doqty; ?>" ></td>
					 </td>
                <td>
        <input name="procosoldqty[]" class="tInput" id="procosoldqty<?php echo $i; ?>" style="width: 48px; text-align : right"  value ="<?php echo $var_soldqty; ?>" onBlur="getamt(<?php echo $i; ?>)"></td>                
                <td>
        <input name="procosamt[]" class="tInput" id="procosamt<?php echo $i; ?>" style="border-style: none; width: 48px; text-align : right" readonly="readonly" value ="<?php echo $var_salesamt; ?>"></td>                
                <td>
        <input name="procortnqty[]" class="tInput" id="procortnqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $var_rtnqty; ?>"></td>                
                <td>
        <input name="procoshortqty[]" class="tInput" id="procoshortqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $var_shortqty; ?>"></td>                
                <td>
        <input name="procooverqty[]" class="tInput" id="procooverqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $var_overqty; ?>"></td>                
                <td>
        <input name="procoadjqty[]" class="tInput" id="procoadjqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $var_adjqty; ?>" onBlur="getbal(<?php echo $i; ?>)"></td>                
                <td>
        <input name="procobalqty[]" class="tInput" id="procobalqty<?php echo $i; ?>" style="border-style: none; width: 48px; text-align : right" value ="<?php echo $var_endbal; ?>"></td>              
             </tr>
		  <?php
            }
          ?>       
            
    <!--      <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="1" readonly="readonly" style="width: 27px; border:0;"></td>
                <td>
				<input name="prococode[]" value="" tProItem1=1 id="prococode1" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id);" ></td>
                <td>
				<input name="procouom[]" id="procouom1" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 60px" ></td>
                <td>
        <input name="procouqty[]" value="" class="tInput" id="procouqty1" style="border-style: none; width: 48px; text-align : right" readonly="readonly"></td>                
                <td>
				<input name="procotype[]" id="procotype1" class="tInput" 
         <?php //if ($speauth == "N") { echo 'readonly="readonly"'; } ?> 
         style="border-style: none; border-color: inherit; border-width: 0; width: 75px"></td>
                <td>
				<input name="procoprice[]" value="" class="tInput" id="procoprice1" 
        <?php //if ($speauth == "N") { echo 'readonly="readonly"'; } ?> 
        style="border-style: none; border-color: inherit; border-width: 0; width: 75px; text-align : right"></td>
                <td>
				<input name="procoqty[]" value="" id="procoqty1" style="width: 48px; text-align : right" onBlur="get_totpcs(1)"></td>
                <td>
				<input name="procoordpcs[]" value="" id="procoordpcs1" style="border-style: none; width: 48px; text-align : right" readonly></td>
                <td>
				<input name="procopripcs[]" value="" id="procopripcs1" style="border-style: none; width: 48px; text-align : right" ></td>
                <td>
				<input name="procototpcs[]" id="procototpcs1" style="border-style: none; width: 48px; text-align : right" readonly></td>        
             </tr>
             -->
             
            </tbody>
           </table>
           
     <br /><br />
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 23px;" align="center">
				<?php
				 $locatr = "m_csales_mas.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnsave.php");
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
						case 4:
							echo("<span>Please Fill In The Sales Order No; Process Fail</span>");
							break;
						case 5:
							echo("<span>This Sales Order No Is Use For This Buyer Code; Process Fail</span>");
							break;
						default:
							echo "";
						}
					}	
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
