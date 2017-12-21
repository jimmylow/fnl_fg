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
    
   	$vmordno   = $_POST['sordno'];
		$vmorddte = date('Y-m-d', strtotime($_POST['saorddte']));
		$vmcustcd = $_POST['sacustcd'];
		$vmmthyr  = $_POST['samthyr'];
		$vmperiod = $_POST['speriod'];
    $vmlessamt = $_POST['lessamt'];  
    $vmlesstype = $_POST['lesstype'];           
            
		if ($vmordno <> "") {
    			
        $vartoday = date("Y-m-d H:i:s"); 
        
				//$sql = "Update csalesmas Set scustcd = '$vmcustcd', sorddte ='$vmorddte', ";
				$sql = "Update csalesmas Set sorddte ='$vmorddte', ";
        $sql .= " less_type = '$vmlesstype', less_amt = '$vmlessamt', ";
				$sql .= " smthyr = '$vmmthyr', speriod = '$vmperiod', ";
				$sql .= " modified_by = '$var_loginid', modified_on='$vartoday ' ";
				$sql .= " Where sordno ='$vmordno'";
        
				mysql_query($sql) or die ("Cant update : ".mysql_error());
        
				$sql =  "Delete From csalesdet";
				$sql .= "  Where sordno ='$vmordno'";
				
				mysql_query($sql) or die ("Cant delete details : ".mysql_error());        
				
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
					
						if ($matcode <> "" && $matsoldqty <> "")
						{
							if ($matdoqty == "" or empty($matdoqty)){$matdoqty = 0;}
              if ($matsoldqty == "" or empty($matsoldqty)){$matsoldqty = 0;}
              if ($matrtnqty == "" or empty($matrtnqty)){$matrtnqty = 0;}
              if ($matshortqty == "" or empty($matshortqty)){$matshortqty = 0;}
              if ($matoverqty == "" or empty($matoverqty)){$matoverqty = 0;}
              if ($matadjqty == "" or empty($matadjqty)){$matadjqty = 0;}
              if ($matendbal == "" or empty($matendbal)){$matendbal = 0;}

							$sql = "INSERT INTO csalesdet values 
						    		('$vmordno', '$matcode', '$matupri', '$mattype', '$matdoqty','$matsoldqty', '$matrtnqty',
                     '$matshortqty', '$matoverqty', '$matadjqty', '$matendbal', '$matseqno')";
                    
							mysql_query($sql) or die ("Cant insert 2 : ".mysql_error());
           				}	
					}
				}
				
				$backloc = "../cons/m_csales_mas.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
					
		}else{
			$backloc = "../cons/upd_csales.php?stat=4&menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 
		}  
    }  
 
 /*   
  //----------- get special authority ------------------//
  $sqlauth = " select * from progauth";
  $sqlauth .= " where username = '$var_loginid'";
  $sqlauth .= " and program_name = '99'";
  
  $tmpauth = mysql_query($sqlauth) or die ("Cant get auth : ".mysql_error());
  
  if (mysql_numrows($tmpauth) > 0) {
     $speauth = "Y";
  } else {  $speauth = "N"; }
 */
        
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
    
    var result = priamt.split("k");
    //alert (result[0]+" : "+result[1]+" : "+result[2]);
    document.getElementById("procotype"+str).value=result[0];   
    document.getElementById("procoupri"+str).value=result[1]; 
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
      document.getElementById("lessamtid").value=0;   
      document.getElementById("lessamtid").readonly=true; 
      } else {
      document.getElementById("lessamtid").value="";   
      //document.getElementById("lessamt").readonly=true;       
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

  var begbal = parseInt(document.getElementById("begbal"+str).value);
  var doqty = parseInt(document.getElementById("procodoqty"+str).value);
  var soldqty = parseInt(document.getElementById("procosoldqty"+str).value);
  var rtnqty = parseInt(document.getElementById("procortnqty"+str).value);
  var shortqty = parseInt(document.getElementById("procoshortqty"+str).value);
  var overqty = parseInt(document.getElementById("procooverqty"+str).value);
  var adjqty = parseInt(document.getElementById("procoadjqty"+str).value);
  
  if (isNaN(begbal)) { begbal = 0; }
  if (isNaN(doqty)) { doqty = 0; }
  if (isNaN(soldqty)) { soldqty = 0; }
  if (isNaN(rtnqty)) { rtnqty = 0; }
  if (isNaN(shortqty)) { shortqty = 0; }
  if (isNaN(overqty)) { overqty = 0; }
  if (isNaN(adjqty)) { adjqty = 0; }
  
  //alert ("Beg: "+begbal+"DO :"+doqty+"Sold : "+soldqty+"Ret : "+rtnqty+"short : "+shortqty+"Over : "+overqty+"Adj :"+adjqty)
  var endbal = begbal + doqty - soldqty - rtnqty - shortqty + overqty + adjqty;
 document.getElementById("procobalqty"+str).value = endbal;
 
}


</script>
</head>

<body onload="setup()">
	  <?php include("../topbarm.php"); ?> 
<!--  <?php include("../sidebarm.php"); ?> -->

  <?php
  	 $sql = "select * from csalesmas";
     $sql .= " where sordno ='".$var_ordno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $custcd = $row['scustcd'];
     $orddte = date('d-m-Y', strtotime($row['sorddte']));
     $mthyr = $row['smthyr'];
     $period = $row['speriod'];
     $lessamt = $row['less_amt'];
     $lesstype = $row['less_type'];
     
     $sql = " select pro_less from counter ";
     $sql .= " where counter = '$custcd'";
     
     $result = mysql_query($sql) or die ("Error proless : ".mysql_error());
     
     if(mysql_numrows($result) >0) {
       $data = mysql_fetch_object($result); 
     
       $var_less = trim($data->pro_less);     
       if($var_less == "") { $var_less = "N"; }     
      } else { $var_less = "N"; }
  ?> 
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">UPDATE COUNTER SALES ENTRY</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Order No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="textnoentry" name="sordno" id="sordnoid" type="text" readonly style="width: 204px;" value = "<?php echo $var_ordno; ?>">         
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
		   	<select name="sacustcd" id="sacustcd" style="width: 268px" class="textnoentry">
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
				echo '<option value="'.$row['custno'].'"';
        if ($custcd == $row['custno']) { echo "selected"; }
        echo '>'.$row['custno']." | ".$row['name'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

		   </td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Order Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="saorddte" id ="saorddte" type="text" style="width: 128px;" value="<?php  echo $orddte; ?>">
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
			<input class="textnoentry" readonly name="samthyr" id="samthyrid" type="text" maxlength="45" style="width: 100px;" value="<?php echo $mthyr; ?>"></td>		   
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Period</td>
		   <td>:</td>
		   <td style="width: 284px">
		   	<select name="speriod" id="speriodcd" >
			 <?php
				echo '<option value="1"';
        if ($period == '1') { echo "selected"; }
        echo '>1 | FIRST HALF MONTH</option>';
        
				echo '<option value="2"';
        if ($period == '2') { echo "selected"; }
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
        if ($lesstype == '1') { echo "selected"; }
        echo '>NO</option>';
        
				echo '<option value="2"';
        if ($lesstype == '2') { echo "selected"; }
        echo '>%</option>'; 
        
				echo '<option value="3"';
        if ($lesstype == '3') { echo "selected"; }
        echo '>AMT</option>';                
                    
	         ?>				   
	       </select>         
         
			<input class="inputtxt" name="lessamt" id="lessamtid" type="text" maxlength="10" style="width: 50px;text-align : right" value="<?php echo $lessamt; ?>"
      <?php if($var_less == "N") { echo "readonly"; } ?>>		   
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
              <th class="tabheader">Product Code</th>
              <th class="tabheader">Unit Price</th>
              <th class="tabheader">Type</th>
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
             
          		$domthyr  = $mthyr;
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
             
             	$sql = "SELECT * FROM csalesdet";
             	$sql .= " Where sordno ='".$var_ordno."'"; 
	    		    $sql .= " ORDER BY sproseq";  
			  	    $rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
              
              $var_salesamt = 0;
              $var_salesamt = number_format($rowq['soldqty'] * $rowq['sprounipri'], 2, '.', ',');  
              
             if($domthyr <> "") {

                $sql2 = " SELECT SUM(x.sproqty) as tot FROM salesentrydet x, salesentry y";
                $sql2 .= " WHERE y.scustcd = '".$custcd."'";
                $sql2 .= " AND x.sordno = y.sordno";
                $sql2 .= " AND y.sordno IN (SELECT sordno FROM salesdo WHERE ";
                $sql2 .= " MONTH(delorddte) = ".$domth;
                $sql2 .= " AND YEAR (delorddte) = ".$doyear.")";
                $sql2 .= " AND x.sprocd = '".$rowq['sprocd']."'";
                
                //echo $sql2;
                $tmp2 = mysql_query($sql2) or die ("cant get do qty : ".mysql_error());
                
                if(mysql_numrows($tmp2) >0) {
                   $rst2 = mysql_fetch_object($tmp2);
                   $doqty = $rst2->tot; 
                   if($doqty =="") { $doqty = 0; }
                 }
                 
                $sql2 = " SELECT endbal FROM csalesdet x, csalesmas y ";
                $sql2 .= " WHERE y.scustcd = '".$custcd."'";
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
				<input name="seqno[]" id="seqno" readonly="readonly" style="width: 27px; border:0;" value ="<?php echo $i; ?>"></td>
                <td>
				<input name="prococode[]" tProItem1=1 id="prococode<?php echo $i; ?>" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['sprocd']); ?>"></td>
                <td>
				<input name="procoupri[]" id="procoupri<?php echo $i; ?>" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 60px" value ="<?php echo $rowq['sprounipri']; ?>"></td>
                <td>
        <input name="procotype[]" class="tInput" id="procotype<?php echo $i; ?>" style="border-style: none; width: 48px;" value ="<?php echo $rowq['sptype']; ?>" readonly="readonly"></td>                
                <td>
				<input name="procodoqty[]" id="procodoqty<?php echo $i; ?>" style="border-style: none; width: 48px; text-align : right" value ="<?php echo $rowq['doqty']; ?>" readonly></td>
				<input id="begbal<?php echo $i; ?>" type="hidden" value ="<?php echo $begbal; ?>" ></td>
                <td>
        <input name="procosoldqty[]" class="tInput" id="procosoldqty<?php echo $i; ?>" style="width: 48px; text-align : right"  value ="<?php echo $rowq['soldqty']; ?>" onBlur="getamt(<?php echo $i; ?>)"></td>                
                <td>
        <input name="procosamt[]" class="tInput" id="procosamt<?php echo $i; ?>" style="border-style: none; width: 48px; text-align : right" readonly="readonly" value ="<?php echo $var_salesamt; ?>"></td>                
                <td>
        <input name="procortnqty[]" class="tInput" id="procortnqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['rtnqty']; ?>"></td>                
                <td>
        <input name="procoshortqty[]" class="tInput" id="procoshortqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['shortqty']; ?>"></td>                
                <td>
        <input name="procooverqty[]" class="tInput" id="procooverqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['overqty']; ?>"></td>                
                <td>
        <input name="procoadjqty[]" class="tInput" id="procoadjqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['adjqty']; ?>" onBlur="getbal(<?php echo $i; ?>)"></td>                
                <td>
        <input name="procobalqty[]" class="tInput" id="procobalqty<?php echo $i; ?>" style="border-style: none; width: 48px; text-align : right" value ="<?php echo $rowq['endbal']; ?>"></td>              
             </tr>
             
          <?php 
          
                	$i = $i + 1;          
          
             } // while
          ?>     
          <?php
            if ($i == 1){ ?>
            	 <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" readonly="readonly" style="width: 27px; border:0;" value ="<?php echo $i; ?>"></td>
                <td>
				<input name="prococode[]" tProItem1=1 id="prococode<?php echo $i; ?>" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['sprocd']); ?>"></td>
                <td>
				<input name="procoupri[]" id="procoupri<?php echo $i; ?>" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 60px" value ="<?php echo $rowq['sprounipri']; ?>"></td>
                <td>
        <input name="procotype[]" class="tInput" id="procotype<?php echo $i; ?>" style="border-style: none; width: 48px;" value ="<?php echo $rowq['sptype']; ?>" readonly="readonly"></td>                
                <td>
				<input name="procodoqty[]" id="procodoqty<?php echo $i; ?>" style="border-style: none; width: 48px; text-align : right" value ="<?php echo $rowq['doqty']; ?>" readonly></td>
				<input id="begbal<?php echo $i; ?>" type="hidden" value ="<?php echo $begbal; ?>" ></td>
                <td>
        <input name="procosoldqty[]" class="tInput" id="procosoldqty<?php echo $i; ?>" style="width: 48px; text-align : right"  value ="<?php echo $rowq['soldqty']; ?>" onBlur="getamt(<?php echo $i; ?>)"></td>                
                <td>
        <input name="procosamt[]" class="tInput" id="procosamt<?php echo $i; ?>" style="border-style: none; width: 48px; text-align : right" readonly="readonly" value ="<?php echo $var_salesamt; ?>"></td>                
                <td>
        <input name="procortnqty[]" class="tInput" id="procortnqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['rtnqty']; ?>"></td>                
                <td>
        <input name="procoshortqty[]" class="tInput" id="procoshortqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['shortqty']; ?>"></td>                
                <td>
        <input name="procooverqty[]" class="tInput" id="procooverqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['overqty']; ?>"></td>                
                <td>
        <input name="procoadjqty[]" class="tInput" id="procoadjqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['adjqty']; ?>" onBlur="getbal(<?php echo $i; ?>)"></td>                
                <td>
        <input name="procobalqty[]" class="tInput" id="procobalqty<?php echo $i; ?>" style="border-style: none; width: 48px; text-align : right" value ="<?php echo $rowq['endbal']; ?>"></td>              
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
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_csales_mas.php?menucd=".$var_menucode;
			
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
