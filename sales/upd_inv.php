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
    
      $var_ordno = $_GET['ino'];
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");

    }
    
    if ($_POST['Submit'] == "Update") {
    
   	$vminvno   = $_POST['sinvno'];
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
            
		if ($vminvno <> "") {
    			
        $vartoday = date("Y-m-d H:i:s"); 
        
				$sql = "Update invmas Set custcd = '$vmcustcd', invdte ='$vminvdte', customno = '$vmcustomno', ";
				$sql .= "                    discount = '$vmdisct', sec_disct = '$vmsecdisct', add_deduction = '$vmdeduct', ";
        $sql .= "                    freight = '$vmfreight', transport = '$vmtransport', remark = '$vmremark', gst = '$vmgst', ";
				$sql .= "                    modified_by = '$var_loginid', modified_on='$vartoday ' ";
				$sql .= "  Where invno ='$vminvno'";
        
				mysql_query($sql) or die ("Cant update : ".mysql_error());
        
        $sql = " select sordno from invdet";
        $sql .= " where invno ='$vminvno'";
        
        $tmp = mysql_query($sql) or die ("Cant get order no : ".mysql_error());
        
        /*
        if (mysql_numrows($tmp) > 0) {
           while($row = mysql_fetch_array($tmp)) {
           
              $salesorderno = $row['sordno'];
 		          mysql_query("update `salesentry` set `invflg` = 'N'
                           where `sordno` = '$salesorderno'", $db_link) 
                           or die("Cant Update Sales Order No ".mysql_error());             
               
           }
        
        }
        */
        
				$sql =  "Delete From invdet";
				$sql .= "  Where invno ='$vminvno'";
				
				mysql_query($sql) or die ("Cant delete details : ".mysql_error());        
				
				if(!empty($_POST['prococode']) && is_array($_POST['prococode'])) 
				{	
					foreach($_POST['prococode'] as $row=>$matcd ) {
						$matcode   = $matcd;
						$matseqno  = $_POST['seqno'][$row];


					
						if ($matcode <> "")
						{

							$sql = "INSERT INTO invdet values 
						    		('$vminvno', '$matcode', '$matseqno')";
                    
							mysql_query($sql) or die ("Cant insert : ".mysql_error());
              
 		          //mysql_query("update `salesentry` set `invflg` = 'Y'
                  //         where `sordno` = '$matcode'", $db_link) 
                  //         or die("Cant Update Sales Order No ".mysql_error());                
           				}	
					}
				}
				
				$backloc = "../sales/m_inv_mas.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
					
		}else{
			$backloc = "../sales/upd_inv.php?stat=4&menucd=".$var_menucode;
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
<script  type="text/javascript" src="jq-in-script.js"></script>


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
<!--  <?php include("../sidebarm.php"); ?> -->

  <?php
  	 $sql = "select * from invmas";
     $sql .= " where invno ='".$var_ordno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $custcd = $row['custcd'];
     $invdte = date('d-m-Y', strtotime($row['invdte']));
     $customno = htmlentities($row['customno']);
     $disct = $row['discount'];     
     $sec_disct = $row['sec_disct'];
     $deduct = $row['add_deduction'];
     $freight = $row['freight'];
     $transport = $row['transport'];
     $remark = $row['remark'];
     $vmgst =  $row['gst'];
     
  ?> 
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">UPDATE INVOICE</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Invoice No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sinvno" id="sinvnoid" type="text" readonly style="width: 204px;" value = "<?php echo $var_ordno; ?>">         
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
		   	<select name="sacustcd" id="sacustcd" style="width: 268px">
			 <?php
              $sql = "select custno, name from customer_master ORDER BY custno ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected value='s'></option>";
                       
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
			<td style="width: 204px">Invoice Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="sainvdte" id ="sainvdte" type="text" style="width: 128px;" value="<?php  echo $invdte; ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('sainvdte','ddMMyyyy')" style="cursor:pointer"></td>
		   </td>
	  	  </tr>  
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Discount</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sadisct" id="sadisctid" type="text" maxlength="45" style="width: 204px;" value ="<?php echo $disct; ?>" >%</td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Custom No</td>
		   <td>:</td>
		   <td style="width: 284px">
			<input class="inputtxt" name="sacustomno" id="sacustomnoid" type="text" maxlength="45" style="width: 204px;" value ="<?php echo $customno; ?>"></td>
		  </tr> 
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Second Discount</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sasecdisct" id="sasecdisctid" type="text" maxlength="45" style="width: 204px;" value ="<?php echo $sec_disct; ?>">%</td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Freight</td>
		   <td>:</td>
		   <td style="width: 284px">
			<input class="inputtxt" name="safreight" id="safreightid" type="text" maxlength="45" style="width: 204px;" value ="<?php echo $freight; ?>" ></td>
		  </tr> 
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Add Deduction</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sadeduct" id="sadeductid" type="text" maxlength="45" style="width: 204px;" value ="<?php echo $deduct; ?>">%</td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Transport</td>
		   <td>:</td>
		   <td style="width: 284px">
			<input class="inputtxt" name="satransport" id="satransportid" type="text" maxlength="45" style="width: 204px;"  value ="<?php echo $transport; ?>"></td>       
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
	  	   <td colspan="6"><textarea class="inputtxt" name="remark" id="remark" COLS=60 ROWS=2><?php echo $remark; ?></textarea></td>
         
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
             <?php
             	$sql = "SELECT * FROM invdet";
             	$sql .= " Where invno ='".$var_ordno."'"; 
	    		$sql .= " ORDER BY seqno";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
        
                            
             ?>            
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 27px; border:0;" value ="<?php echo $i; ?>"></td>
                <td>
				<input name="prococode[]" tProItem1=1 id="prococode<?php echo $i; ?>" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['sordno']); ?>"></td>
                <td>
				<input name="procouom[]" id="procouom" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 160px" value ="<?php echo ""; ?>"></td>
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
				<input name="prococode[]" tProItem1=1 id="prococode<?php echo $i; ?>" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['sordno']); ?>"></td>
                <td>
				<input name="procouom[]" id="procouom" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 160px" value ="<?php echo ""; ?>"></td>
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
				 $locatr = "m_inv_mas.php?menucd=".$var_menucode;
			
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
