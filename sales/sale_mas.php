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
    
    //phpinfo();
		$vmorddte = date('Y-m-d', strtotime($_POST['saorddte']));
		$vmcustcd = $_POST['sacustcd'];
		$vmcustpo  = $_POST['sacustpo'];
		//$vmzone = $_POST['szone'];
		$vmzone = "";
    $vmremark = $_POST['saremark'];
            
		if ($vmcustcd <> "") {
    
            /*----------------------------- Cash Bill details ------------------------------------ */
              $chk_invno_query = mysql_query("select count(*) from `ctrl_sysno` where `descrip` = 'SALESORD' and counter = '$vmcustcd'; ", $db_link);

              $chk_invno_res = mysql_fetch_array($chk_invno_query) or die("cant Get Sales Order No Info".mysql_error());
              
              if ($chk_invno_res[0] > 0 ) {
                  $get_invno_query = mysql_query("select noctrl from `ctrl_sysno` where `descrip` = 'SALESORD' and counter = '$vmcustcd' ", $db_link);
                  
                  $get_invno_res = mysql_fetch_object($get_invno_query) or die("Cant Get Sales Order No 2 ".mysql_error()); 

                  $var_invno = vsprintf("%05d",$get_invno_res->noctrl+1); 
                  $var_invno = $vmcustcd.$var_invno; 
                  
 		  mysql_query("update `ctrl_sysno` set `noctrl` = `noctrl` + 1
                           where `descrip` = 'SALESORD'
                           and counter = '$vmcustcd'", $db_link) 
                           or die("Cant Update Cash Bill Auto No ".mysql_error());              
               
                }  else { 

		   mysql_query("insert into `ctrl_sysno` 
                          (`descrip`, `counter`, `noctrl`)
                   values ('SALESORD', '$vmcustcd', 1);",$db_link) or die("Cant Insert Into Cash Bill Auto No");

                   $var_invno = $vmcustcd."00001";

                }  

            /*--------------------------- end Inv no details ---------------------------------- */
    
			
        $vartoday = date("Y-m-d H:i:s"); 
        
				$sql = "INSERT INTO salesentry values 
						('$var_invno', '$vmorddte','$vmcustcd','$vmzone','$vmcustpo', '$vmremark', '$var_loginid','$vartoday', 
						 '$var_loginid', '$vartoday', 'A', 'N')";
				mysql_query($sql) or die ("Cant insert : ".mysql_error());
				
				if(!empty($_POST['prococode']) && is_array($_POST['prococode'])) 
				{	
					foreach($_POST['prococode'] as $row=>$matcd ) {
						$matcode   = $matcd;
						$matseqno  = $_POST['seqno'][$row];
						$matuom    = $_POST['procouom'][$row];
						$matqty    = $_POST['procoqty'][$row];
						$matuprice = $_POST['procoprice'][$row];
            $mattype   = $_POST['procotype'][$row];

					
						if ($matcode <> "" && $matqty <> "")
						{
							//if ($matqty == "" or empty($matqty)){$matqty = 0;}
							if ($matuprice == "" or empty($matuprice)){$matuprice = 0;}
							$sql = "INSERT INTO salesentrydet values 
						    		('$var_invno', '$matcode', '$mattype', '$matqty','$matuom', '$matuprice', '$matseqno')";
                    
							mysql_query($sql) or die ("Cant insert : ".mysql_error());
           				}	
					}
				}
				
				echo "<script language=\"javascript\">"; 
				echo "if(confirm('Print This Sales Form?'))";
				echo "{";
			
				$fname = "salesform.rptdesign&__title=myReport"; 
        		$dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&ponum=".$var_invno."&menuc=".$var_menucode."&dbsel=".$varrpturldb;
        		$dest .= urlencode(realpath($fname));
        
        		//header("Location: $dest" );
        		echo "window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');";
				echo "}";
				echo "</script>";
				
				
				$backloc = "../sales/m_sales_mas.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
					
		}else{
			$backloc = "../sales/sales_mas.php?stat=4&menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 
		}  
    }  
    
  //----------- get special authority ------------------//
  $sqlauth = " select * from progauth";
  $sqlauth .= " where username = '$var_loginid'";
  $sqlauth .= " and program_name = '99'";
  
  $tmpauth = mysql_query($sqlauth) or die ("Cant get auth : ".mysql_error());
  
  if (mysql_numrows($tmpauth) > 0) {
     $speauth = "Y";
  } else {  $speauth = "N"; }
  
 if ($vmorddte == "") { $vmorddte = date("d-m-Y"); }     
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
  var mylist2 = new Array();    

	for (var j = 1; j < rowCount; j++){

	    var idrowItem = "prococode"+j;
        var rowItem = document.getElementById(idrowItem).value;	 
        if (rowItem != ""){ 
        	mylist.push(rowItem); 
          mylist2.push(rowItem);  
	    }		
    }		
	
	mylist.sort();
	var last = mylist[0];
  var dup = 0;
  
     // for (var l=0; l < mylist2.length; l++) {
		 //   alert ("Duplicate Item Found -> " + mylist2[l] + " At Row : "+ l);
     //}  
	
	for (var i=1; i < mylist.length; i++) {
		if (mylist[i] == last){ 
      for (var l=0; l < mylist2.length; l++) {
       if (mylist2[l] == last) {
         dup += 1;
         if (dup >= 2) {
           var idx = l + 1;
			     alert ("Duplicate Item Found -> " + last + " At Row : "+ idx);
			     return false;
           }
        } 
      } 
		}	
		last = mylist[i];
    //idx = i + 1;
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
    
	//Check the list of mat item uprice / qty <> 0 ------------------------------------------------
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
	var mylist = new Array();	    

	for (var j = 1; j < rowCount; j++){

	    var idrowItem = "procoprice"+j;
        var rowItem = document.getElementById(idrowItem).value;	 
        if (rowItem == "" || rowItem == "0"){ 
        	alert ("Unit Price is 0 for Row " + j);
          return false;  
	    }
      
	    var idrowItem = "procoqty"+j;
        var rowItem = document.getElementById(idrowItem).value;	 
        if (rowItem == "" || rowItem == "0"){ 
        	alert ("Invalid Quantity for row " + j);
          return false;  
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
 var uominfo = document.getElementById("procouom"+str).value;

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
    document.getElementById("procoprice"+str).value=result[1]; 
    document.getElementById("procouqty"+str).value=result[2]; 
    
    if(result[1] == 0) { alert ("Unit Price is 0"); }      
        
    }
  }
xmlhttp.open("GET","getsalesprice.php?s="+custinfo+"&i="+iteminfo+"&u="+uominfo+"&m="+rand,true);
xmlhttp.send();
}

function getzone(str)
{

var rand = Math.floor(Math.random() * 101);

if (str=="s")
  {
  alert ("Please choose a customer to continue");
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
    document.getElementById("szonecd").value=xmlhttp.responseText;  
    }
  }
xmlhttp.open("GET","getzone.php?q="+str+"&m="+rand,true);
xmlhttp.send();
}

function get_totpcs (str) {

 var ordqty = document.getElementById("procoqty"+str).value;
 var uomqty = document.getElementById("procouqty"+str).value;
 //var price = document.getElementById("procoprice"+str).value;
 
 var totpcs = ordqty * uomqty;   
    
 document.getElementById("procototpcs"+str).value = totpcs;
 
}

</script>
</head>
<body onload="setup()">
  <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 900px;" class="style2">
	 <legend class="title">SALES ORDER ENTRY</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 900px; ">
	   	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Customer</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<select name="sacustcd" id="sacustcd" style="width: 268px" onchange="getzone(this.value)">
			 <?php
              $sql = "select custno, name from customer_master ORDER BY custno ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 value = 's' selected>-</option>";
                       
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
			<td style="width: 204px">Order Date</td>
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
	  	   <td style="width: 122px">Customer PO</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sacustpo" id="sacustpoid" type="text" maxlength="45" style="width: 204px;" onchange ="upperCase(this.id)" value="<?php echo $vmcustpo; ?>"></td>		   
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Zone</td>
		   <td>:</td>
		   <td style="width: 284px">
		   <input class="textnoentry" name="szone" id ="szonecd" type="text" style="width: 128px;" value="<?php  echo $vmzone; ?>" readonly>
		   </td>
		  </tr> 
	  <tr>
	  	   <td ></td>
	  	   <td >Remark</td>
	  	   <td >:</td>
	  	   <td colspan="5">
			<input class="inputtxt" name="saremark" id="saremarkid" type="text" maxlength="100" style="width: 463px;" onchange ="upperCase(this.id)"></td>
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
		  <table id="itemsTable" class="general-table" style="width: 900px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Item Code</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Unit Qty</th>
              <th class="tabheader">Type</th>
              <th class="tabheader">Unit Price</th>
              <th class="tabheader">Ord. Qty</th>
              <th class="tabheader">Total(pc)</th>              
             </tr>
            </thead>
            <tbody>
          <tr class="item-row">
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
         <?php if ($speauth == "N") { echo 'readonly="readonly"'; } ?> 
         style="border-style: none; border-color: inherit; border-width: 0; width: 75px"></td>
                <td>
				<input name="procoprice[]" value="" class="tInput" id="procoprice1" 
        <?php if ($speauth == "N") { echo 'readonly="readonly"'; } ?> 
        style="border-style: none; border-color: inherit; border-width: 0; width: 75px; text-align : right"></td>
                <td>
				<input name="procoqty[]" value="" id="procoqty1" style="width: 48px; text-align : right" onBlur="get_totpcs(1)"></td>
                <td>
				<input name="procototpcs[]" id="procototpcs1" style="border-style: none; width: 48px; text-align : right" readonly></td>        
             </tr>
             
            </tbody>
           </table>
           
         <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

     <br /><br />
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_sales_mas.php?menucd=".$var_menucode;
			
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
