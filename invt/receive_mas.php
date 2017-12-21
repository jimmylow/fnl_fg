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
      $var_po = $_GET['po'];
      include("../Setting/ChqAuth.php");
      
      $sql = " select supplier from po_master";
      $sql .= " where po_no = '".$var_po."'";
      
      $tmp = mysql_query($sql) or die ("cant get PO : ".mysql_error());
      $rst = mysql_fetch_object($tmp);
      
      $var_sacustcd = $rst->supplier;

    }
    
    if ($_POST['Submit'] == "Save") {
    
    //phpinfo();
		$vmrcvddte = date('Y-m-d', strtotime($_POST['sarcvddte']));
		$vmcustcd = $_POST['sacustcd'];
		$vmrefno  = mysql_real_escape_string($_POST['srefno']);
    $vmpono   = $_POST['po'];
		$vmrefdte = date('Y-m-d', strtotime($_POST['sarefdte']));
		$vmremark = $_POST['saremark'];
      
    //phpinfo();
            
		if ($vmcustcd <> "") {
    
            /*----------------------------- Cash Bill details ------------------------------------ */
              $chk_invno_query = mysql_query("select count(*) from `ctrl_sysno` where `descrip` = 'INVTRCVDOTH' and counter = 'HQ'; ", $db_link);

              $chk_invno_res = mysql_fetch_array($chk_invno_query) or die("cant Get Receive Info".mysql_error());
              
              if ($chk_invno_res[0] > 0 ) {
                  $get_invno_query = mysql_query("select noctrl from `ctrl_sysno` where `descrip` = 'INVTRCVDOTH' and counter = 'HQ' ", $db_link);
                  
                  $get_invno_res = mysql_fetch_object($get_invno_query) or die("Cant Get Receive 2 ".mysql_error()); 

                  $var_invno = vsprintf("%04d",$get_invno_res->noctrl+1); 
                  //$var_invno = $vmcustcd.$var_invno; 
                  
 		  mysql_query("update `ctrl_sysno` set `noctrl` = `noctrl` + 1
                           where `descrip` = 'INVTRCVDOTH' and counter = 'HQ'", $db_link) 
                           or die("Cant Update Receive Auto No ".mysql_error());              
               
                }  else { 

		   mysql_query("insert into `ctrl_sysno` 
                          (`descrip`, `counter`, `noctrl`)
                   values ('INVTRCVDOTH', 'HQ', 1);",$db_link) or die("Cant Insert Into Receive Auto No ".mysql_error());

                   $var_invno = "0001";

                }  

            /*--------------------------- end Inv no details ---------------------------------- */
    
			
        $vartoday = date("Y-m-d H:i:s"); 
               
				$sql = "INSERT INTO invtrcvd values 
						('$var_invno', '$vmrcvddte', '$vmrefdte', '$vmcustcd', '$vmpono', '$vmrefno', '$vmremark','$var_loginid','$vartoday', 
						 '$var_loginid', '$vartoday', 'A', 'N')";
				mysql_query($sql) or die ("Cant insert : ".mysql_error());
				
				if(!empty($_POST['procd']) && is_array($_POST['procd'])) 
				{	
					foreach($_POST['procd'] as $row=>$matcd ) {
						$matcode   = $matcd;
						$matseqno  = $_POST['seqno'][$row];
						$matuom    = mysql_real_escape_string($_POST['prouom'][$row]);
						$matqty    = $_POST['proorqty'][$row];
						$matuprice = $_POST['prooupri'][$row];
					
						if ($matcode <> "")
						{
							if ($matqty == "" or empty($matqty)){$matqty = 0;}
							if ($matuprice == "" or empty($matuprice)){$matuprice = 0;}
							$sql = "INSERT INTO invtrcvddet values 
						    		('$var_invno', '$matcode', '$matqty','$matuom', '$matuprice', '$matseqno')";
                    
							mysql_query($sql) or die ("Cant insert : ".mysql_error());
           				}	
					}
				}
				
				$backloc = "../invt/m_rcvd_mas.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
					
		}else{
			$backloc = "../invt/receive_mas.php?stat=4&menucd=".$var_menucode;
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

.general-table #procomat                        { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}

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
<!-- <scriptyy  type="text/javascript" src="jq-rcvd-script.js"></scriptyy> -->


<script type="text/javascript"> 

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
		var dateMask1 = new DateMask("dd-MM-yyyy", "sarcvddte");
		dateMask1.validationMessage = errorMessage;
    
		var dateMask2 = new DateMask("dd-MM-yyyy", "sarefdte");
		dateMask2.validationMessage = errorMessage;    
 
}

function calcAmt(vid)
{
    var vproqty = "proordqty"+vid;
    var vrecqty = "prorecqty"+vid;
    var vproupri = "prooupri"+vid;
    var vproperc = "proouperc"+vid;
    var vproamt = "proouamt"+vid;
	
    var col1 = document.getElementById(vproqty).value;
    var col3 = document.getElementById(vrecqty).value;
	var col2 = document.getElementById(vproupri).value;		
	var totsumamt = 0;
	
	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Quantity :' + col1);
    	   col1 = 0;
    	}
    	document.getElementById(vproqty).value = parseFloat(col1).toFixed(0);
    }
    if (col2 != ""){	
		if(isNaN(col2)) {
    	   alert('Please Enter a valid number for Unit Price :' + col2);
    	   col2 = 0;
    	}
    	document.getElementById(vproupri).value = parseFloat(col2).toFixed(4);
    }	
	
	if ((!isNaN(col1) && (col1 != "")) && (!isNaN(col2) && (col2 != ""))){
		totsumamt = parseFloat(col1) * parseFloat(col2);
    totsumperc =  (parseFloat(col1) / parseFloat(col3)) * 100;
    if(parseFloat(totsumperc) >= 110) {
       document.getElementById(vproperc).value = parseFloat(totsumperc).toFixed(0);
     } else {
       document.getElementById(vproperc).value = "";
     } 
		document.getElementById(vproamt).value = parseFloat(totsumamt).toFixed(2);		
     }	
	 caltotamt();
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
	if (x==null || x=="")
	{
		alert("Customer No Cannot Be Blank");
		document.InpPO.sacustcd.focus();
		return false;
	}

	var x=document.forms["InpPO"]["sarcvddte"].value;
	if (x==null || x=="")
	{
		alert("Received Date Must Not Be Blank");
		document.InpPO.sarcvddte.focus();
		return false;
	}
  
	var x=document.forms["InpPO"]["sarefdte"].value;
	if (x==null || x=="")
	{
		alert("Date Must Not Be Blank");
		document.InpPO.sarefdte.focus();
		return false;
	}  
	
	//Check the list of mat item no is Valid-------------------------------------------------------------
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
         
    for (var j = 1; j < rowCount; j++){
       var idrowItem = "procd"+j;
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

	    var idrowItem = "procd"+j;
        var rowItem = document.getElementById(idrowItem).value;	 
        if (rowItem != ""){ 
        	mylist.push(rowItem);   
	    }		
    }		
	
	mylist.sort();
	var last = mylist[0];
	
	for (var i=1; i < mylist.length; i++) {
		if (mylist[i] == last){ 
			alert ("Duplicate Product Code Found; " + last);
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
	caltotamt();

}

function caltotamt(){
    var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length; 
		
	var totmat = 0;
		
	for(var i = 1; i < rowCount; i++) { 
	  var vprouamt = "proouamt"+i;
	  var colamt = document.getElementById(vprouamt).value;					
		
	  if (!isNaN(colamt) && (colamt != "")){
				totmat = parseFloat(totmat) + parseFloat(colamt);		
	  }
	}
	document.InpPO.totamtid.value = parseFloat(totmat).toFixed(2);	     
}


</script>
</head>
<body onload="setup()">
  <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">GOODS RECEIVED ENTRY (Other Supplier)</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
	   	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Supplier</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			 <input class="textnoentry" name="sacustcd" id="sacustcd" type="text" value="<?php echo $var_sacustcd; ?>" style="width: 204px;" readonly></td>
		   </td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Received Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="sarcvddte" id ="sarcvddte" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('sarcvddte','ddMMyyyy')" style="cursor:pointer"></td>
		   </td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px"><div id="msgcd"></div></td>
	  	  </tr>
	  <tr>
	  	   <td ></td>
	  	   <td >PO No</td>
	  	   <td >:</td>
	  	   <td colspan="5">
			<input class="textnoentry" name="po" id="poid" type="text" style="width: 204px;" value ="<?php echo $var_po; ?>" readonly></td>
		     </td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">DO No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="srefno" id="srefnoid" type="text" maxlength="45" style="width: 204px;" onchange ="upperCase(this.id)"></td>
		   <td style="width: 10px"></td>
			<td style="width: 204px">Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="sarefdte" id ="sarefdte" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('sarefdte','ddMMyyyy')" style="cursor:pointer"></td>
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
      </table>
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 958px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader" style="width: 178px">Product Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Unit Price(RM)</th>
              <th class="tabheader">Order Qty</th>
              <th class="tabheader">Rcvd Qty</th>
              <th class="tabheader">%</th>                            
              <th class="tabheader">Amount</th>
             </tr>
            </thead>
            <tbody>
             <?php
             	$sql = "SELECT * FROM po_trans";
             	$sql .= " Where po_no ='".$var_po."'"; 
	    		    $sql .= " ORDER BY seqno"; 
              
              //echo $sql; 
			  	$rs_result = mysql_query($sql) or die ("cant get podet : ".mysql_error()); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
       
             ?>                        
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 27px; border:0;"></td>
                <td >
				<input name="procd[]" class="tInput" id="prococode<?php echo $i; ?>" tabindex="0" style="border-style: none; border-color: inherit; width: 140px"  value ="<?php echo htmlentities($rowq['itemcode']); ?>" readonly></td>
                <td>
				<input name="procdname[]" id="proconame<?php echo $i; ?>" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 250px;" value="<?php echo $rowq['itmdesc']; ?>"></td>
                <td>
				<input name="prouom[]" id="prouom<?php echo $i; ?>" readonly="readonly" style="width: 50px; border-style: none; border-color: inherit; border-width: 0;" value="<?php echo $rowq['itmuom']; ?>">
				</td>
                <td >
				<input name="prooupri[]" id="prooupri<?php echo $i; ?>" style="border-style: none; width: 80px; text-align:right;" readonly="readonly" value="<?php echo $rowq['uprice']; ?>">
				</td>
                <td >
				<input name="prorecqty[]" id="prorecqty<?php echo $i; ?>" style="border-style: none; width: 80px; text-align:right;" readonly="readonly" value="<?php echo $rowq['qty']; ?>">
				</td>         
                <td >
				<input name="proorqty[]" id="proordqty<?php echo $i; ?>" onBlur="calcAmt('<?php echo $i; ?>');" style="width: 80px; text-align:center;">
				</td> 
				<td >
				<input name="proouperc[]" id="proouperc<?php echo $i; ?>" readonly="readonly" style="width: 50px; border-style: none; border-color: inherit; border-width: 0; text-align:right;">
				</td>                      
				<td >
				<input name="proouamt[]" id="proouamt<?php echo $i; ?>" readonly="readonly" style="width: 116px; border-style: none; border-color: inherit; border-width: 0; text-align:right;">
				</td>
             </tr>
          <?php 
          
                	$i = $i + 1;          
          
             } // while
          ?>     
            </tbody>           
           </table>
		  <table class="general-table" style="width: 958px">
          	 <tr>
              <td style="width: 842px; text-align:right" >Total : </td>              
              <td align="right">
              <input readonly="readonly" name="totamt" id ="totamtid" type="text" style="width: 116px;" class="textnoentry1">
              </td>
             </tr>
        </table>               
           
        <!-- <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>
        -->

     <br /><br />
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_rcvd_mas.php?menucd=".$var_menucode;
			
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
