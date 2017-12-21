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
		$vminvdte = date('Y-m-d', strtotime($_POST['sainvdte']));
		$vmcustcd = $_POST['sacustcd'];
    $vmdisct = $_POST['sadisct']; 
		$vmcustomno  = $_POST['sacustomno'];
    $vmsecdisct = $_POST['sasecdisct'];
    $vmfreight = $_POST['safreight'];
    $vmdeduct = $_POST['sadeduct'];
    $vmtransport = $_POST['satransport'];
    $vmgst  = $_POST['gst']; 
		$vmremark = $_POST['remark'];
  
            
		if ($vmcustcd <> "") {
    
            /*----------------------------- Cash Bill details ------------------------------------ */
              $chk_invno_query = mysql_query("select count(*) from `ctrl_sysno` where `descrip` = 'INVOICE' and counter = 'HQ'; ", $db_link);

              $chk_invno_res = mysql_fetch_array($chk_invno_query) or die("cant Get Invoice No Info".mysql_error());
              
              if ($chk_invno_res[0] > 0 ) {
                  $get_invno_query = mysql_query("select noctrl from `ctrl_sysno` where `descrip` = 'INVOICE' and counter = 'HQ' ", $db_link);
                  
                  $get_invno_res = mysql_fetch_object($get_invno_query) or die("Cant Get Invoice No 2 ".mysql_error()); 

                  $var_invno = vsprintf("%05d",$get_invno_res->noctrl+1); 
                  //$var_invno = $vmcustcd.$var_invno; 
                  
 		  mysql_query("update `ctrl_sysno` set `noctrl` = `noctrl` + 1
                           where `descrip` = 'INVOICE'
                           and counter = 'HQ'", $db_link) 
                           or die("Cant Update Invoice Auto No ".mysql_error());              
               
                }  else { 

		   mysql_query("insert into `ctrl_sysno` 
                          (`descrip`, `counter`, `noctrl`)
                   values ('INVOICE', 'HQ', 1);",$db_link) or die("Cant Insert Into Invoice Auto No");

                   $var_invno = "00001";

                }  

            /*--------------------------- end Inv no details ---------------------------------- */
    
			
        $vartoday = date("Y-m-d H:i:s"); 
        		if ($vmsecdisct = 0 or empty($vmsecdisct)){$vmsecdisct = 0;}
        		if ($vmdisct = 0 or empty($vmdisct)){$vmdisct = 0;}
        		if ($vmdeduct = 0 or empty($vmdeduct)){$vmdeduct = 0;}
				if ($vmfreight = 0 or empty($vmfreight)){$vmfreight = 0;}
				if ($vmtransport = 0 or empty($vmtransport)){$vmtransport = 0;}
				$sql = "INSERT INTO invmas values 
						('$var_invno', '$vminvdte','$vmcustcd','$vmcustomno','$vmdisct', '$vmsecdisct', '$vmdeduct',
             '$vmfreight', '$vmtransport', 'N', '$vmremark', '$var_loginid','$vartoday', 
						 '$var_loginid', '$vartoday', 'A', '$vmgst')";
				mysql_query($sql) or die ("Cant insert 1 : ".mysql_error());
				
				if(!empty($_POST['prococode']) && is_array($_POST['prococode'])) 
				{	
					foreach($_POST['prococode'] as $row=>$matcd ) {
						$matcode   = $matcd;
						$matseqno  = $_POST['seqno'][$row];


					
						if ($matcode <> "")
						{

							$sql = "INSERT INTO invdet values 
						    		('$var_invno', '$matcode', '$matseqno')";
                    
							mysql_query($sql) or die ("Cant insert : ".mysql_error());
              
 		          mysql_query("update `salesshipmas` set `invflg` = 'Y'
                           where `shipno` = '$matcode'", $db_link) 
                           or die("Cant Update Sales Order No ".mysql_error());                
           				}	
					}
				}
				
				#-------------------------------------------------------------------------------------------------
				
				echo "<script language=\"javascript\">"; 
				echo "if(confirm('Print This Invoice?'))";
				echo "{";
			
				 #----------------------------------------------------------
        		$sql = "Delete from tmpinvform where usernm = '$var_loginid'";
        		mysql_query($sql) or die ("Cant Delete 1 : ".mysql_error());

				$sql = "select sordno from invdet where invno = '$var_invno'";
				$rs_result = mysql_query($sql);
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
				    $sord = htmlentities($rowq['sordno']);
		   
					$sql1 = "select * from salesshipdet where shipno = '$sord'";
					$rs_result1 = mysql_query($sql1);
					while ($rowq1 = mysql_fetch_assoc($rs_result1)){
						$spro = htmlentities($rowq1['sprocd']);
						$sqty = $rowq1['sproqty'];
						$suom = htmlentities($rowq1['sprouom']);
						$sseq = $rowq1['sproseq'];
				
						$sql2 = "select sprounipri, sptype from salesentrydet where sordno = '$sord' and sprocd = '$spro'";
     					$sql_result2 = mysql_query($sql2);
     					$row2 = mysql_fetch_array($sql_result2);
     					$suni = htmlentities($row2['sprounipri']);
     					$ssty = htmlentities($row2['sptype']);

						if(empty($sqty)){$sqty = 0;}
						if(empty($suni)){$suni = 0;}
						$samt = $suni * $sqty;
				 
						$sql2 = "select Description from product where ProductCode = '$spro'";
     					$sql_result2 = mysql_query($sql2);
     					$row2 = mysql_fetch_array($sql_result2);
     					$spde = mysql_real_escape_string($row2['Description']);
     			
     					$sql3 = "select salestype_desc from salestype_master where salestype_code = '$ssty'";
     					$sql_result3 = mysql_query($sql3);
     					$row3 = mysql_fetch_array($sql_result3);
     					$sstde = htmlentities($row3['salestype_desc']);
     			
     					$sql4 = "select disctype, discamt from salesentrydisct where sordno = '$sord' and sptype = '$ssty'";
     					$sql_result4 = mysql_query($sql4);
     					$row4 = mysql_fetch_array($sql_result4);
     					$disctyp = htmlentities($row4['disctype']);
						$discamt = htmlentities($row4['discamt']);
						if(empty($discamt)){$discamt = 0;}
				
						if ($disctyp == ''){
							$disdes = "Discount";
						}else{	
							if ($disctyp == '1'){
								$disdes = "Discount (%)";	
							}else{
								$disdes = "Discount (RM)";
							}
						}

						if (!empty($spro)){
							$sqli  = "insert into tmpinvform values ('$spro', '$spde', '$sqty', '$suom', '$suni', ";
							$sqli .= "        '$samt', '$ssty', '$sstde', '$discamt', '$disctyp', '$var_loginid', '$disdes')";
							mysql_query($sqli) or die ("Cant Insert 1 : ".mysql_error());          
						}
					}
				}
        #----------------------------------------------------------
        
        $fname = "invform.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&invn=".$var_invno."&menuc=".$var_menucode."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $dest .= urlencode(realpath($fname));
        
        		//header("Location: $dest" );
        		echo "window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');";
				echo "}";
				echo "</script>";
				
				
				#-------------------------------------------------------------------------------------------------
				
				
				$backloc = "../sales/m_inv_mas.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
					
		}else{
			$backloc = "../sales/inv_mas.php?stat=4&menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 
		}  
    }    
    
 
 //if ($vmgst == "") {
    $sql = " select gst from gst_master";
    $tmp = mysql_query($sql) or die ("Cant get gst : ".mysql_error());
    
    if(mysql_numrows($tmp) > 0) {
        $rst = mysql_fetch_object($tmp);
        $vmgst = $rst->gst;
    }    
    if ($vmgst == "" || $vmgst == 0) {
       echo "GST AMOUNT IS 0. Please contact administrator";
    }  
  //}
      
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
<script  type="text/javascript" src="jq-in-script.js"></script>


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
		var dateMask1 = new DateMask("dd-MM-yyyy", "sainvdte");
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

   var x=document.forms["InpPO"]["sainvdte"].value;
	if (x==null || x=="")
	{
	alert("Invoice Date Must Not Be Blank");
	document.InpPO.sainvdte.focus;
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
       	var strURL="aja_chk_saordCount.php?rawmatcdg="+rowItem;
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
						   alert ('Invalid Sales Order : '+ rowItem + ' At Row '+j);
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
			alert ("Duplicate Sales Order Found; " + last);
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


function getCust(str)
{

 var rand = Math.floor(Math.random() * 101);
 var custinfo = document.getElementById("sacustcd").value;
 var iteminfo = document.getElementById("prococode"+str).value;
 
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
    var res = xmlhttp.responseText;
    res = res.replace(/\s+/g, "");
    
     if (res == "N") { 
       alert("Invalid Order No for this Customer");
       document.getElementById("prococode"+str).value = "";
       document.getElementById("procouom"+str).value = "";
      }
     
    }
  }
xmlhttp.open("GET","getchkcust.php?s="+custinfo+"&i="+iteminfo+"&m="+rand,true);
xmlhttp.send();
}

</script>
</head>
<body onload="setup()">
  <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">INVOICE ENTRY</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
	   	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Customer</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<select name="sacustcd" id="sacustcd" style="width: 268px">
			 <?php
              $sql = "select custno, name from customer_master ";
              //$sql .= " where type = 'O'";
              $sql .= " ORDER BY custno ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected value='s'></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['custno'].'">'.$row['custno']." | ".$row['name'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

		   </td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Invoice Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="sainvdte" id ="sainvdte" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('sainvdte','ddMMyyyy')" style="cursor:pointer"></td>
		   </td>
	  	  </tr>  
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Discount</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sadisct" id="sadisctid" type="text" maxlength="45" style="width: 204px;" >%</td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Custom No</td>
		   <td>:</td>
		   <td style="width: 284px">
			<input class="inputtxt" name="sacustomno" id="sacustomnoid" type="text" maxlength="45" style="width: 204px;" ></td>
		  </tr> 
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Second Discount</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sasecdisct" id="sasecdisctid" type="text" maxlength="45" style="width: 204px;" >%</td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Freight</td>
		   <td>:</td>
		   <td style="width: 284px">
			<input class="inputtxt" name="safreight" id="safreightid" type="text" maxlength="45" style="width: 204px;" ></td>
		  </tr> 
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Add Deduction</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sadeduct" id="sadeductid" type="text" maxlength="45" style="width: 204px;" >%</td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Transport</td>
		   <td>:</td>
		   <td style="width: 284px">
			<input class="inputtxt" name="satransport" id="satransportid" type="text" maxlength="45" style="width: 204px;" ></td>       
		  </tr> 
		  <tr>
	  	   <td ></td>
	  	   <td >GST %</td>
	  	   <td >:</td>
	  	   <td colspan="6"><input class="inputtxt" name="gst" id="gstid" type="text" maxlength="6" style="width: 60px;" value="<?php echo $vmgst; ?>" ></td>		    
		  </tr>       
		  <tr>
	  	   <td ></td>
	  	   <td >Remark</td>
	  	   <td >:</td>
	  	   <td colspan="6"><textarea class="inputtxt" name="remark" id="remark" COLS=60 ROWS=2></textarea></td>
         
		  </tr> 
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 958px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Order No</th>
              <th class="tabheader">Ship Date</th>
             </tr>
            </thead>
            <tbody>
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="1" readonly="readonly" style="width: 27px; border:0;"></td>
                <td>
				<input name="prococode[]" value="" tProItem1=1 id="prococode1" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id);" ></td>
                <td>
				<input name="procouom[]" id="procouom1" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 160px"></td>
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
				 $locatr = "m_inv_mas.php?menucd=".$var_menucode;
			
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
