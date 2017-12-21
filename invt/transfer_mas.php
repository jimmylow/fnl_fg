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
		$trfdte= date('Y-m-d', strtotime($_POST['trfdte']));
		$remark   = $_POST['remark'];
	            
            
		//if ($refno <> "") {
		
			$sysno = '';
     		$sqlchk = " select noctrl from ctrl_sysno ";
     		$sqlchk.= " where `descrip` = 'INVTTRF' and counter = 'HQ';";
     
     		$dumsysno= mysql_query($sqlchk) or die(mysql_error());
     		while($row = mysql_fetch_array($dumsysno))
     		{
     			$sysno = $row['noctrl'];        
     		}
     		if ($sysno ==NULL)
     		{
     			$sysno = '0';
     					$sysno_sql = "INSERT INTO ctrl_sysno values ('INVTTRF', 'HQ', 1)";

     			mysql_query($sysno_sql);

     		}
     		$newsysno = $sysno + 1;
     		
     		$trf_sysno  = str_pad($newsysno , 4, '0', STR_PAD_LEFT);
     		$trf_sysno = "TRF".$trf_sysno;


         	$vartoday = date("Y-m-d H:i:s");
				$sql = "INSERT INTO invttrf values 
						('$trf_sysno', '$trfdte', '$remark', 
						 '$var_loginid', '$vartoday','$var_loginid', '$vartoday', 'A', 'N')";
				mysql_query($sql);
				
				if(!empty($_POST['procofrm']) && is_array($_POST['procofrm'])) 
				{	
					foreach($_POST['procofrm'] as $row=>$matcd ) {
						$frmcode    = $matcd;
						$seqno      = $_POST['seqno'][$row];
						$frmdesc    = $_POST['procofdesc'][$row];
            $frmdesc    = addslashes($frmdesc);
						$tocode     = $_POST['procoto'][$row];
						$todesc     = $_POST['procotdesc'][$row];
            $todesc     = addslashes($todesc);
						$trfqty     = $_POST['issueqty'][$row];
											
						if ($frmcode <> "" && $tocode <> "" && $trfqty > 0)
						{
							
							$sql = "INSERT INTO invttrfdet values 
						    		('$trf_sysno', '$seqno', '$frmcode', '$frmdesc', '$tocode','$todesc','$trfqty')";
							mysql_query($sql) or die("Can't Insert Transaction ".mysql_error());
							
							/*
							$sql = "INSERT INTO invthist values 
						    		('TRF', '$trf_sysno', '$refno','$remark', '$prorevdte', '$matcode', '0', '$matdesc', '$onhandbal', '$adjqty ','$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
							mysql_query($sql) or die("Can't Insert History ".mysql_error());
               */
               
           				}	
					}
				}
				
				$updsysno_sql = "UPDATE ctrl_sysno SET noctrl = '$newsysno' where `descrip` = 'INVTTRF' and counter = 'HQ'";

		     	 mysql_query($updsysno_sql);
				
				$backloc = "../invt/m_trf_mas.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
			//}		
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

.general-table #procomat                        { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
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
<script  type="text/javascript" src="jq-trf-script.js"></script>


<script type="text/javascript"> 

function setup() {

		document.InpJobFMas.trfdte.focus();
						
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "trfdte");
		dateMask1.validationMessage = errorMessage;		
}


function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
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

    var x=document.forms["InpJobFMas"]["trfdte"].value;
	if (x==null || x=="")
	{
	alert("Date Must Not Be Blank");
	document.InpJobFMas.trfdte.focus();
	return false;
	}  
  
	//Check the list of transfer from no is Valid-------------------------------------------------------------
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;    
         
    for (var j = 1; j < rowCount; j++){
       var idrowItem = "procofrm"+j;
       var rowItem = document.getElementById(idrowItem).value;	 
              
       if (rowItem != ""){
       	var strURL="aja_chk_procdCount.php?procd="+rowItem;
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
						   alert ('Invalid From Product Code : '+ rowItem + ' At Row '+j);
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
    
	//Check the list of transfer to item no is Valid-------------------------------------------------------------
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
         
    for (var j = 1; j < rowCount; j++){
       var idrowItem = "procoto"+j;
       var rowItem = document.getElementById(idrowItem).value;	 
              
       if (rowItem != ""){
       	var strURL="aja_chk_procdCount.php?procd="+rowItem;
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
						   alert ('Invalid To Product Code : '+ rowItem + ' At Row '+j);
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
    
	//Check the list of transfer qty > 0 -------------------------------------------------------------
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
         
    for (var j = 1; j < rowCount; j++){
       var idfrmItem = "procofrm"+j;
       var frmItem = document.getElementById(idfrmItem).value;    
       var idtoItem = "procoto"+j;
       var toItem = document.getElementById(idtoItem).value;
       var idtrfqty = "issueqtyid"+j;
       var trfqty = document.getElementById(idtrfqty).value;       	
       
       if (frmItem != "" && toItem != "" && (trfqty == 0 || trfqty == "")) {
			    flgchk = 0;
			    alert ('Invalid Transfer Qty : '+ frmItem + ' At Row '+j+"QTY:"+trfqty);
			    return false; 
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

	    var idrowItem = "procofrm"+j;
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

	//Check the list of mat item on hand is sufficient or not ---------------------------------------
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
         
    for (var j = 1; j < rowCount; j++){
       var idrowItem = "procofrm"+j;
       var idrowqty = "issueqtyid"+j;       
       var rowItem = document.getElementById(idrowItem).value;	 
       var rowqty = document.getElementById(idrowqty).value;	 
              
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
						if (parseInt(req.responseText) < parseInt(rowqty))
						{
						   flgchk = 0;
						   alert ('Insufficient Balance : '+ rowItem + ' At Row '+j+" Bal : "+req.responseText);
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

function onhand_checking(str)
{
 
 var rand = Math.floor(Math.random() * 101);
 var iteminfo = document.getElementById("procofrm"+str).value;
 var ordqty = document.getElementById("issueqtyid"+str).value;

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
      if(parseInt(onhand) < parseInt(ordqty)) {  
         alert ("Insufficient Balance : "+onhand);
         document.getElementById("issueqtyid"+str).focus;
       }     
    }
  }
xmlhttp.open("GET","getonhand.php?i="+iteminfo+"&m="+rand,true);
xmlhttp.send();
}


</script>
</head>

<body onload= "setup()">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 

  <div class="contentc">

	<fieldset name="Group1" style=" width: 857px;" class="style2">
	 <legend class="title">STOCK TRANSFER</legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 886px">
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 126px">Date</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 239px">
		   <input class="inputtxt" name="trfdte" id ="trfdte" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>" tabindex="0" >
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('prorevdte','ddMMyyyy')" style="cursor:pointer">
			</td>
			<td style="width: 29px"></td>
			<td style="width: 136px"></td>
			<td style="width: 16px"></td>
			<td style="width: 270px">
		   </td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 239px"></td>
	  	  </tr>
	  	  <tr>
	   	    <td></td>
	  	    <td style="width: 126px">Remark</td>
	  	    <td style="width: 13px">:</td>
	  	    <td colspan="5">
			<input class="inputtxt" name="remark" id="remark" type="text" maxlength="100" style="width: 634px;" onchange ="upperCase(this.id)" tabindex="0">
			</td>
	  	  </tr> 
	  	  		  	
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 841px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">From Prod. Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">To Prod. Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">Transfer Qty(PCS)</th>
             </tr>
            </thead>
            <tbody>
            
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="1" readonly="readonly" style="width: 27px; border:0;"></td>
                <td>
				<input name="procofrm[]" value="" tProItem1=1 id="procofrm1" tabindex="0" class="autosearch" style="width: 100px" onchange ="upperCase(this.id)"></td>
                <td>
				<input name="procofdesc[]" value="" class="tInput" id="procofdesc1" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 200px;"></td>
                <td>
				<input name="procoto[]" value="" id="procoto1" class="autosearch" style=" width: 100px;" onchange ="upperCase(this.id)"></td>
                <td>
				<input name="procotdesc[]" value="" class="tInput" id="procotdesc1" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 200px;"></td>
				<td>
				<input name="issueqty[]" value="" class="tInput" id="issueqtyid1" style="width: 75px" onChange="onhand_checking(1);"></td>  
             </tr>
            </tbody>
           </table>
           
         <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

	
		 <table>
		  	<tr>
				<td style="width: 875px; height: 22px;" align="center">
				<?php
				 $locatr = "m_trf_mas.php?menucd=".$var_menucode;
			
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
