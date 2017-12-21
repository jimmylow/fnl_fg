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
    
      $var_ordno = $_GET['trf_id'];
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");

    }
    
    if ($_POST['Submit'] == "Update") {
    
		$vmfrmcd = $_POST['frmctr'];
		$vmtocd  = $_POST['toctr'];     
    $vmmthyr  = $_POST['samthyr'];    
   	$vmordno   = $_POST['trfno'];
		$vmtrfdte = date('Y-m-d', strtotime($_POST['trfdte']));
    $vmremark = htmlentities($_POST['saremark']);
            
		if ($vmordno <> "") {
    			
        $vartoday = date("Y-m-d H:i:s"); 
        
				$sql = "Update cinvttrf Set frmcustcd = '$vmfrmcd', tocustcd = '$vmtocd', ";
				$sql .= " mthyr = '$vmmthyr', trfdate ='$vmtrfdte', remark = '$vmremark', ";
				$sql .= " upd_by = '$var_loginid', upd_on='$vartoday ' ";
				$sql .= "  Where trf_id ='$vmordno'";
        
				mysql_query($sql) or die ("Cant update : ".mysql_error());
        
				$sql =  "Delete From cinvttrfdet";
				$sql .= "  Where trf_id ='$vmordno'";
				
				mysql_query($sql) or die ("Cant delete details : ".mysql_error());        
				
				if(!empty($_POST['procofrm']) && is_array($_POST['procofrm'])) 
				{	
					foreach($_POST['procofrm'] as $row=>$matcd ) {
						$frmcode    = $matcd;
						$seqno      = $_POST['seqno'][$row];
						$frmdesc    = $_POST['procofdesc'][$row];
            $frmdesc    = addslashes($frmdesc);
						//$tocode     = $_POST['procoto'][$row];
						//$todesc     = $_POST['procotdesc'][$row];
            //$todesc    = addslashes($todesc);
						$trfqty     = $_POST['issueqty'][$row];
											
						if ($frmcode <> "" && $trfqty)
						{
							
							$sql = "INSERT INTO cinvttrfdet values 
						    		('$vmordno', '$seqno', '$frmcode', '$frmdesc', '$trfqty')";
							mysql_query($sql) or die("Can't Insert Transaction ".mysql_error());
							
           				}	
					}
				}
				
				$backloc = "../cons/m_ctrf_mas.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
					
		}else{
			$backloc = "../cons/upd_cinvttrf.php?stat=4&menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 
		}  
    }  

 /*   if ($_POST['Submit'] == "Post") {
      
    //phpinfo();
    $vmordno   = $_POST['trfno'];

		if ($vmordno <> "") {
    			
        $vartoday = date("Y-m-d H:i:s"); 
        
        $sql = "select trfdate from invttrf";
				$sql .= "  Where trf_id ='$vmordno'";
				$tmprst = mysql_query($sql) or die ("Cant query master : ".mysql_error());        

        $row = mysql_fetch_array($tmprst);
        $rcvddte = date('Y-m-d', strtotime($row['trfdate']));
                
        
				$sql =  "select * from invttrfdet";
				$sql .= "  Where trf_id ='$vmordno'";
        $sql .= " order by seqno";
				
				$tmprst = mysql_query($sql) or die ("Cant query details : ".mysql_error());        

        if(mysql_numrows($tmprst) > 0) {

          while ($row = mysql_fetch_array($tmprst)) {
              $frcd = $row['from_code'];
              $tocd = $row['to_code'];
              $proqty = $row['trfqty'];
              
							$sql = "INSERT INTO invthist values 
						    		('TO', '$vmordno', '$rcvddte', '$vartoday', '$frcd', '0', '$proqty' )";
                    
							mysql_query($sql) or die ("Cant insert hist in1 : ".mysql_error());
              
							$sql = "INSERT INTO invthist values 
						    		('TI', '$vmordno', '$rcvddte', '$vartoday', '$tocd', '$proqty', '0' )";
                    
							mysql_query($sql) or die ("Cant insert hist in2 : ".mysql_error());              
            }  
         }
         
        $sql = "update invttrf";
        $sql .= " set posted = 'Y'";
				$sql .= "  Where trf_id ='$vmordno'";
				$tmprst = mysql_query($sql) or die ("Cant update master : ".mysql_error());          	
            
        echo "<script>";
        echo 'alert(\'Posted Successfully\');';
        echo "</script>"; 
        
				$backloc = "../invt/m_trf_mas.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>";                     
			 }               

        echo "<script>";
        echo 'alert(\'NOT Posted\');';
        echo "</script>"; 	
					
		}    
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
<script  type="text/javascript" src="jq-trf-script.js"></script>


<script type="text/javascript"> 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function setup() {

		document.InpPO.trfdte.focus();
				
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "trfdte");
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

  var x=document.forms["InpPO"]["frmctr"].value;
	if (x==null || x=="s")
	{
	alert("From Counter Cannot Be Blank");
	document.InpPO.frmctr.focus;
	return false;
	}
  
  var x=document.forms["InpPO"]["toctr"].value;
	if (x==null || x=="s")
	{
	alert("To Counter Cannot Be Blank");
	document.InpPO.toctr.focus;
	return false;
	} 
  
  var x=document.forms["InpPO"]["samthyr"].value;           
	if (x==null || x=="")
	{
	alert("MM/YYYY Must Not Be Blank");
	document.InpPO.samthyr.focus;
	return false;
	} 

    var x=document.forms["InpPO"]["trfdte"].value;
	if (x==null || x=="")
	{
	alert("Date Must Not Be Blank");
	document.InpPO.trfdte.focus();
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
    

	//Check the list of transfer qty > 0 -------------------------------------------------------------
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
         
    for (var j = 1; j < rowCount; j++){
       var idfrmItem = "procofrm"+j;
       var frmItem = document.getElementById(idfrmItem).value;    
       //var idtoItem = "procoto"+j;
       //var toItem = document.getElementById(idtoItem).value;
       var idtrfqty = "issueqtyid"+j;
       var trfqty = document.getElementById(idtrfqty).value;       	
       
       if (frmItem != "" && (trfqty == 0 || trfqty == "")) {
			    flgchk = 0;
			    alert ('Invalid Transfer Qty : '+ frmItem + ' At Row '+j);
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
xmlhttp.open("GET","getconhand.php?i="+iteminfo+"&m="+rand,true);
xmlhttp.send();
}


</script>
</head>

<body onload="setup()">
	  <?php include("../topbarm.php"); ?> 
<!--  <?php include("../sidebarm.php"); ?> -->

  <?php
  	 $sql = "select * from cinvttrf";
     $sql .= " where trf_id ='".$var_ordno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $vmfrmcd = $row['frmcustcd'];
     $vmtocd = $row['tocustcd'];
     $vmmthyr = $row['mthyr'];
     $trfdte = date('d-m-Y', strtotime($row['trfdate']));
     $remark = htmlentities($row['remark']);
     
     
  ?> 
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 857px;" class="style2">
	 <legend class="title">UPDATE STOCK TRANSFER BETWEEN COUNTER</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Transfer No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="textnoentry" name="trfno" id="trfnoid" type="text" readonly style="width: 204px;" value = "<?php echo $var_ordno; ?>">         
		   </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px"></td>
		   <td></td>
		   <td style="width: 284px"></td>
       </tr>
		  <tr>
	  	   <td ></td>
	  	   <td >From Counter</td>
	  	   <td >:</td>
	  	   <td >
		   	<select name="frmctr" id="frmctr" style="width: 268px">
			 <?php
              $sql = "select x.counter, y.name from counter x, customer_master y";
              $sql .= " where y.custno = x.counter";
              $sql .= " and sort_auto = 'Y'"; //only those counter need to send DN
              $sql .= " ORDER BY x.counter ASC";
              $sql_result = mysql_query($sql);
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['counter'].'"';;
        if ($vmfrmcd == $row['counter']) { echo "selected"; }
        echo '>'.$row['counter']." | ".$row['name'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>
		     </td>
		   <td ></td>
		   <td >To Counter</td>
		   <td>:</td>
		   <td >
		   	<select name="toctr" id="toctr" style="width: 268px" >
			 <?php
              $sql = "select x.counter, y.name from counter x, customer_master y";
              $sql .= " where y.custno = x.counter";
              $sql .= " and sort_auto = 'Y'"; //only those counter need to send DN
              $sql .= " ORDER BY x.counter ASC";
              $sql_result = mysql_query($sql);
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['counter'].'"';;
        if ($vmtocd == $row['counter']) { echo "selected"; }
        echo '>'.$row['counter']." | ".$row['name'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>       
		   </td>
	  	  </tr>         
	   	   <tr>
	   	    <td></td>
	  	    <td >MM/YYYY</td>
	  	    <td >:</td>
	  	    <td >
			<input class="inputtxt" name="samthyr" id="samthyrid" type="text" maxlength="45" style="width: 100px;" value="<?php echo $vmmthyr; ?>">
      </td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Transfer Date</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   <input class="inputtxt" name="trfdte" id ="trfdte" type="text" style="width: 128px;" value="<?php  echo $trfdte; ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('trfdte','ddMMyyyy')" style="cursor:pointer"></td>         
         </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px"></td>
		   <td></td>
		   <td style="width: 284px"></td>
	  	  </tr>	              
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Remark</td>
	  	   <td style="width: 13px">:</td>
	  	   <td colspan="5">
			<input class="inputtxt" name="saremark" id="saremarkid" type="text" maxlength="100" style="width: 463px;" value="<?php echo $remark; ?>" onchange ="upperCase(this.id)"></td>
		     </td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px"></td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px"></td>
		   <td></td>
		   <td style="width: 284px"></td>
	  	  </tr>	  	  
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 857px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Product Code</th>
              <th class="tabheader">Description</th>
              <!-- <th class="tabheader">To Prod. Code</th>
              <th class="tabheader">Description</th>  -->
              <th class="tabheader">Transfer Qty(PCS)</th>
             </tr>
            </thead>
            <tbody>
             <?php
             	$sql = "SELECT * from cinvttrfdet ";
             	$sql .= " Where trf_id ='".$var_ordno."'";
	    		    $sql .= " ORDER BY seqno";  
			  	    $rs_result = mysql_query($sql); 
			   
			    $i = 1;  
				while ($rowq = mysql_fetch_assoc($rs_result)){ 


             		echo '<tr class="item-row">';
                	echo '<td style="width: 30px"><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>';
                	echo '<td ><input name="procofrm[]" value="'.$rowq['from_code'].'" tProItem1='.$i.' id="procofrm'.$i.'" class="autosearch" style="width: 150px" onchange ="upperCase(this.id)" ></td>';
                	echo '<td><input name="procofdesc[]" value="'.$rowq['fdesc'].'" id="procofdesc'.$i.'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 300px;"></td>';
                	//echo '<td><input name="procoto[]" id="procoto'.$i.'" value="'.$rowq['to_code'].'" class="autosearch" style="width: 100px;" onchange ="upperCase(this.id)"></td>';
                	//echo '<td><input name="procotdesc[]" id="procotdesc'.$i.'" value="'.$rowq['tdesc'].'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 200px;"></td>';
                  echo '<td><input name="issueqty[]" value="'.$rowq['trfqty'].'" id="issueqtyid'.$i.'" style="width: 75px;" onChange="onhand_checking('.$i.');"></td>';
             		echo '</tr>';
                    $i = $i + 1;
                }
               
                if ($i == 1){
                  
                	echo '<tr class="item-row">';
                	echo '<td style="width: 30px"><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>';
                	echo '<td ><input name="procofrm[]" value="'.$rowq['from_code'].'" tProItem1='.$i.' id="procofrm'.$i.'" class="autosearch" style="width: 100px" onchange ="upperCase(this.id)" ></td>';
                	echo '<td><input name="procofdesc[]" value="'.$rowq['fdesc'].'" id="procofdesc'.$i.'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 200px;"></td>';
                	//echo '<td><input name="procoto[]" id="procoto'.$i.'" value="'.$rowq['to_code'].'" class="autosearch" style="width: 100px;" onchange ="upperCase(this.id)"></td>';
                	//echo '<td><input name="procotdesc[]" id="procotdesc'.$i.'" value="'.$rowq['tdesc'].'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 200px;"></td>';
                  echo '<td><input name="issueqty[]" value="'.$rowq['trfqty'].'" id="issueqtyid'.$i.'" style="width: 75px;" onChange="onhand_checking('.$i.');"></td>';
              		echo '</tr>';
                    $i = $i + 1;

                }
             ?>
             </tbody>
           </table>       
           
         <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

     <br /><br />
     
		 <table>
		  	<tr>
				<td style="width: 857px; height: 22px;" align="center">
				<?php
				 $locatr = "m_ctrf_mas.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnupdate.php");
          //include("../Setting/btnpost.php");
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
