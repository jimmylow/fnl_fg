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
    
   	$vmordno   = $_POST['srtnno'];
		$vmrtndte = date('Y-m-d', strtotime($_POST['sartndte']));
		$vmcustcd = $_POST['sacustcd'];
		$vmrefno  = $_POST['srefno'];
    $vmremark = $_POST['saremark'];
            
		if ($vmordno <> "") {
    			
        $vartoday = date("Y-m-d H:i:s"); 
        
				$sql = "Update invtreturn Set custcd = '$vmcustcd', rtndte ='$vmrtndte', ";
				$sql .= "                    refno = '$vmrefno', remark = '$vmremark', ";
				$sql .= "                    modified_by = '$var_loginid', modified_on='$vartoday ' ";
				$sql .= "  Where rtnno ='$vmordno'";
        
				mysql_query($sql) or die ("Cant update : ".mysql_error());
        
				$sql =  "Delete From invtreturndet";
				$sql .= "  Where rtnno ='$vmordno'";
				
				mysql_query($sql) or die ("Cant delete details : ".mysql_error());        
				
				if(!empty($_POST['procd']) && is_array($_POST['procd'])) 
				{	
					foreach($_POST['procd'] as $row=>$matcd ) {
						$matcode   = $matcd;
						$matseqno  = $_POST['seqno'][$row];
						$matuom    = $_POST['prouom'][$row];
						$matqty    = $_POST['proorqty'][$row];
						$matuprice = $_POST['prooupri'][$row];
					
						if ($matcode <> "")
						{
							if ($matqty == "" or empty($matqty)){$matqty = 0;}
							if ($matuprice == "" or empty($matuprice)){$matuprice = 0;}
							$sql = "INSERT INTO invtreturndet values 
						    		('$vmordno', '$matcode', '$matqty','$matuom', '$matuprice', '$matseqno')";
                    
							mysql_query($sql) or die ("Cant insert : ".mysql_error());
           				}	
					}
				}
				
				$backloc = "../invt/m_rtn_mas.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
					
		}else{
			$backloc = "../invt/upd_invtreturn.php?stat=4&menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 
		}  
    }  
    
    if ($_POST['Submit'] == "Post") {
      
    //phpinfo();
    $vmordno  = $_POST['srtnno'];

		if ($vmordno <> "") {
    			
        $vartoday = date("Y-m-d H:i:s"); 
        
        $sql = "select rtndte from invtreturn";
				$sql .= "  Where rtnno ='$vmordno'";
				$tmprst = mysql_query($sql) or die ("Cant query master : ".mysql_error());        

        $row = mysql_fetch_array($tmprst);
        $rcvddte = date('Y-m-d', strtotime($row['rtndte']));
                
        
				$sql =  "select * from invtreturndet";
				$sql .= "  Where rtnno ='$vmordno'";
        $sql .= " order by proseq";
				
				$tmprst = mysql_query($sql) or die ("Cant query details : ".mysql_error());        

        if(mysql_numrows($tmprst) > 0) {

          while ($row = mysql_fetch_array($tmprst)) {
              $procd = $row['procd'];
              $proqty = $row['proqty'];
              
							$sql = "INSERT INTO invthist values 
						    		('RS', '$vmordno', '$rcvddte', '$vartoday', '$procd', '0', '$proqty')";
                    
							mysql_query($sql) or die ("Cant insert hist : ".mysql_error());
            }  
         }
         
        $sql = "update invtreturn";
        $sql .= " set posted = 'Y'";
				$sql .= "  Where rtnno ='$vmordno'";
				$tmprst = mysql_query($sql) or die ("Cant update master : ".mysql_error());          	
            
        echo "<script>";
        echo 'alert(\'Posted Successfully\');';
        echo "</script>"; 
        
				$backloc = "../invt/m_rtn_mas.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>";                     
			 }               

        echo "<script>";
        echo 'alert(\'NOT Posted\');';
        echo "</script>"; 	
					
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
<script  type="text/javascript" src="jq-rtn-script.js"></script>


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
		var dateMask1 = new DateMask("dd-MM-yyyy", "sartndte");
		dateMask1.validationMessage = errorMessage;
 
}

function calcAmt(vid)
{
    var vproqty = "proordqty"+vid;
    var vproupri = "prooupri"+vid;
    var vproamt = "proouamt"+vid;
	
    var col1 = document.getElementById(vproqty).value;
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

	var x=document.forms["InpPO"]["sartndte"].value;
	if (x==null || x=="")
	{
		alert("Return Date Must Not Be Blank");
		document.InpPO.sartndte.focus();
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
<!--  <?php include("../sidebarm.php"); ?> -->

  <?php
  	 $sql = "select * from invtreturn";
     $sql .= " where rtnno ='".$var_ordno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $custcd = $row['custcd'];
     $rtndte = date('d-m-Y', strtotime($row['rtndte']));
     $srefno = htmlentities($row['refno']);
     $remark = htmlentities($row['remark']);
     
     
  ?> 
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">UPDATE PURCHASE RETURN</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Return No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="textnoentry" name="srtnno" id="srtnnoid" type="text" readonly style="width: 204px;" value = "<?php echo $var_ordno; ?>">         
		   </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   &nbsp;</td>
	  	  </tr>    
    
	   	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Supplier</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<select name="sacustcd" id="sacustcd" style="width: 268px">
			 <?php
              $sql = "select suppno, name from supplier_master ORDER BY suppno ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['suppno'].'"';
        if ($custcd == $row['suppno'] ) { echo "selected"; }
        echo ' >'.$row['suppno']." | ".$row['name'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

		   </td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Return Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="sartndte" id ="sartndte" type="text" style="width: 128px;" value="<?php  echo $rtndte; ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('sartndte','ddMMyyyy')" style="cursor:pointer"></td>
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
	  	   <td style="width: 122px">Reference No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="srefno" id="srefnoid" type="text" maxlength="45" style="width: 204px;" value="<?php echo $srefno; ?>" onchange ="upperCase(this.id)"></td>		   </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px"></td>
		   <td></td>
		   <td style="width: 284px">
		   </td>
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
              <th class="tabheader" style="width: 178px">Product Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader" style="width: 137px">Unit <br>Price(RM)</th>
              <th class="tabheader" style="width: 100px">Quantity</th>              
              <th class="tabheader" style="width: 242px">Amount</th>
             </tr>
            </thead>
            <tbody>
             <?php
             	$sql = "SELECT x.*, y.description FROM invtreturndet x, product y";
             	$sql .= " Where x.rtnno ='".$var_ordno."'";
              $sql .= " and y.productcode = x.procd";
	    		    $sql .= " ORDER BY x.proseq";  
			  	    $rs_result = mysql_query($sql); 
			   
			    $i = 1;   $tamt = 0;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
            		$rowq['proqty']  = number_format($rowq['proqty'], 0, '', '');
                $sproamt = $rowq['proqty'] * $rowq['prounipri'];
                $tamt += $sproamt;

             		echo '<tr class="item-row">';
                	echo '<td style="width: 30px"><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>';
                	echo '<td style="width: 178px"><input name="procd[]" value="'.$rowq['procd'].'" tProCd1='.$i.' id="procd'.$i.'" class="autosearch" style="width: 175px" onchange ="upperCase(this.id)" ></td>';
                	echo '<td><input name="procdname[]" value="'.$rowq['description'].'" id="proconame'.$i.'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;"></td>';
                	echo '<td><input name="prouom[]" id="prouom'.$i.'" value="'.$rowq['prouom'].'" readonly="readonly" style="width: 75px; border-style: none; border-color: inherit; border-width: 0;"></td>';
                	echo '<td style="width: 137px"><input name="prooupri[]" id="prooupri'.$i.'" value="'.$rowq['prounipri'].'" style="width: 89px; text-align:right;"></td>';
                  echo '<td style="width: 100px"><input name="proorqty[]" value="'.$rowq['proqty'].'" id="proordqty'.$i.'" onBlur="calcAmt('.$i.');" style="width: 97px; text-align:center;"></td>';
				        	echo '<td style="width: 242px"><input name="proouamt[]" id="proouamt'.$i.'" value="'.number_format($sproamt, 2, '.', ',').'" readonly="readonly" style="width: 116px; border-style: none; border-color: inherit; border-width: 0; text-align:right;"></td>';
             		echo '</tr>';
                    $i = $i + 1;
                }
               
                if ($i == 1){
                	$rowq['proqty']  = number_format($rowq['proqty'], 0, '', '');
                  $sproamt = $rowq['proqty'] * $rowq['prounipri'];
                  $tamt += $sproamt;
                  
                	echo '<tr class="item-row">';
                	echo '<td style="width: 30px"><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>';
                	echo '<td style="width: 178px"><input name="procd[]" value="'.$rowq['procd'].'" tProCd1='.$i.' id="procd'.$i.'" class="autosearch" style="width: 175px" onchange ="upperCase(this.id)" ></td>';
                	echo '<td><input name="procdname[]" value="'.$rowq['description'].'" id="proconame'.$i.'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;"></td>';
                	echo '<td><input name="prouom[]" id="prouom'.$i.'" value="'.$rowq['prouom'].'" readonly="readonly" style="width: 75px; border-style: none; border-color: inherit; border-width: 0;"></td>';
                	echo '<td style="width: 137px"><input name="prooupri[]" id="prooupri'.$i.'" value="'.$rowq['prounipri'].'" style="width: 89px; text-align:right;"></td>';
                  echo '<td style="width: 100px"><input name="proorqty[]" value="'.$rowq['proqty'].'" id="proordqty'.$i.'" onBlur="calcAmt('.$i.');" style="width: 97px; text-align:center;"></td>';
		        		  echo '<td style="width: 242px"><input name="proouamt[]" id="proouamt'.$i.'" value="'.number_format($sproamt, 2, '.', ',').'" readonly="readonly" style="width: 116px; border-style: none; border-color: inherit; border-width: 0; text-align:right;"></td>';
             		echo '</tr>';
                    $i = $i + 1;

                }
             ?>
             </tbody>
           </table>
		  <table class="general-table" style="width: 958px">
          	 <tr>
              <td style="width: 842px; text-align:right" >Total : </td>              
              <td align="right">
              <input readonly="readonly" name="totamt" id ="totamtid" type="text" style="width: 116px;" class="textnoentry1" value="<?php echo number_format($tamt, 2, '.', ','); ?>">
              </td>
             </tr>
        </table>            
           
         <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

     <br /><br />
     
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_rtn_mas.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnupdate.php");
          include("../Setting/btnpost.php");
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
