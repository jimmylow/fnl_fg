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
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
     
     	$fsupp   = $_POST['selfsupp'];
     	$tsupp   = $_POST['seltsupp'];
     	$call    = $_POST['chkall'];
     	$fpodte  = date("Y-m-d", strtotime($_POST['inqfdte']));
     	$tpodte  = date("Y-m-d", strtotime($_POST['inqtdte']));
     	$compflg = $_POST['selout'];
     	$todte = date("Y-m-d");      	
     
		#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmppobal where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        
        $shardSize = 3000;
	 	$sqliq = "";		   			
	 	$k = 0;
        if ($call == '1'){
     		$sqlm  = "select x.po_no, x.supplier, x.po_date, x.del_date, y.itemcode, y.qty, y.uprice, y.itmdesc, y.itmuom, y.supplieritem";
		   	$sqlm .= " from po_master x, po_trans y";
        	$sqlm .= " where x.del_date between '$fpodte' and '$tpodte'";
        	$sqlm .= " and 	 x.po_no = y.po_no";
        	$sqlm .= " and   active_flag = 'ACTIVE'";
     	}else{
     		$sqlm  = "select x.po_no, x.supplier, x.po_date, x.del_date, y.itemcode, y.qty, y.uprice, y.itmdesc, y.itmuom, y.supplieritem";
		   	$sqlm .= " from po_master x, po_trans y";
        	$sqlm .= " where x.del_date between '$fpodte' and '$tpodte'";
        	$sqlm .= " and 	 x.po_no = y.po_no";
			$sqlm .= " and   x.supplier between '$fsupp' and '$tsupp'";
			$sqlm .= " and   active_flag = 'ACTIVE'";
     	}
		$rs_result = mysql_query($sqlm);
		while ($row = mysql_fetch_assoc($rs_result)) { 
		   	$pono   = $row['po_no'];
		    $supp   = $row['supplier'];
		    $podte  = $row['po_date'];
		    $deldte = $row['del_date'];
		    $mitmcd = mysql_real_escape_string($row['itemcode']);
		    $qtyord = $row['qty'];
		    $poprice = $row['uprice'];
		    $itmdesc = mysql_real_escape_string($row['itmdesc']);
		    $uom    = $row['itmuom'];
		    $suppitm = mysql_real_escape_string($row['supplieritem']);
		    if ($qtyord == "" or $qtyord == null){ 
        	  $qtyord  = 0.00;
        	}
        	if (empty($mitmcd)){
        		$mitmcd = $suppitm;
        	}
		    
		    #------------Supplier Name-----------------------------			
			$sqlop = "select Name, currency from supplier_master ";
        	$sqlop .= " where SuppNo ='$supp'";
        	$sql_resultop = mysql_query($sqlop);
        	$rowop = mysql_fetch_array($sql_resultop);        
        	$suppde = $rowop[0];
        	$currcy = trim($rowop['currency']);
        	#------------------------------------------------------
        			
        	#------------GRN QTy From Date To Date-----------------------------	
			$sqlg = " select sum(proqty) FROM invthist X, invtrcvddet_nlg Y";
			$sqlg .= " WHERE reftype = 'RC'";
			$sqlg .= " and  po_number='$pono'";
			$sqlg .= " and  x.refid = y.rcvdno ";
			$sqlg .= " and  prodcode = procd ";
			$sqlg .= " and  prodcode='$mitmcd'";	
        	$sql_resultg = mysql_query($sqlg);
        	$rowg = mysql_fetch_array($sql_resultg);        
        	if ($rowg[0] == "" or $rowg[0] == null){ 
        	  $rowg[0]  = 0.00;
        	}
        	$recbal = $rowg[0];
        	#-------------------------------------------------------------
        	
			if ($recbal > 0){				
				#------------last grn Date-----------------------------		
				$sqld = " select max(X.refdate) FROM invthist X, invtrcvddet_nlg Y";
				$sqld .= " WHERE reftype = 'RC'";
				$sqld .= " and  po_number='$pono'";
				$sqld .= " and  x.refid = y.rcvdno ";
				$sqld .= " and  prodcode = procd ";
				$sqld .= " and  prodcode='$mitmcd'";
          		$sql_resultd = mysql_query($sqld);
        		$rowd = mysql_fetch_array($sql_resultd);      
        		$lstgdte = $rowd[0];
        		if ($lstgdte == ""){
        			$lstgdte = $podte;
        		}
        	}else{
        		$lstgdte = "0000-00-00";
        	}	
        	#-------------------------------------------------------------

        	#----------------local currency---------------------------------------------
        	$trate = 0;
        	if ($currcy == "MYR"){
        		$trate = 1;
        	}else{	
        		$spodte = $podte;

			 	while($trate == 0){
        			$sql4 = "select buyrate from curr_xrate ";
   					$sql4 .= " where xmth = month('$spodte') and xyr = year('$spodte')";
   					$sql4 .= " and curr_code = '$currcy'";
   					$sql_result4 = mysql_query($sql4) or die("Cant Echange Rate Table ".mysql_error());;
   					$row4 = mysql_fetch_array($sql_result4);
   					$trate = $row4[0];
   					$date = date_create($spodte);
					date_sub($date, date_interval_create_from_date_string('1 month'));
					$spodte = date_format($date, 'Y-m-d'); // gives you 2013-05-01
					$yrspodte = date_format($date, 'Y');
					if ($yrspodte <= '2000'){
						break;
					}
   				}
        	}
        	$rmpopri = $trate * $poprice;	 
        	#---------------------------------------------------------------------------
        	
        	if(empty($avgcst)){$avgcst = 0;}
			switch ($compflg){
				case 'A':
					if ($k % $shardSize == 0) {
        				if ($k != 0) {	  
            				mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
        				}
        				$sqliq = 'Insert Into tmppobal Values ';
    				}
   					$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$pono', '$supp', '$suppde', '$mitmcd', '$itmdesc', '$qtyord', '$recbal', '$deldte',
        											           '$podte', '$lstgdte', '$var_loginid','$poprice', '$compflg', '$uom', '$avgcst', '$rmpopri', '$currcy')";
		 			$k = $k + 1;
					break;	
		    	case 'Y':
		    		if ($recbal >= $qtyord){
		    			if ($k % $shardSize == 0) {
        					if ($k != 0) {	  
            					mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
        					}
        					$sqliq = 'Insert Into tmppobal Values ';
    					}
   						$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$pono', '$supp', '$suppde', '$mitmcd', '$itmdesc', '$qtyord', '$recbal', '$deldte',
        												           '$podte', '$lstgdte', '$var_loginid','$poprice', '$compflg', '$uom', '$avgcst', '$rmpopri', '$currcy')";
		 				$k = $k + 1;
		    		}	
					break;
		    	case 'N':
		    		if($recbal < $qtyord){
		    			if ($k % $shardSize == 0) {
        					if ($k != 0) {	  
            					mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
        					}
        					$sqliq = 'Insert Into tmppobal Values ';
    					}
   						$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$pono', '$supp', '$suppde', '$mitmcd', '$itmdesc', '$qtyord', '$recbal', '$deldte',
        												           '$podte', '$lstgdte', '$var_loginid','$poprice', '$compflg', '$uom', '$avgcst', '$rmpopri', '$currcy')";
		 				$k = $k + 1;
					}
					break;
		    }	
		}
		if (!empty($sqliq)){
			mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
		}
		
		$sqlm  = "select count(*)";
		$sqlm .= " from tmppobal ";
        $sqlm .= " where usernm = '$var_loginid'";
     	$sql_resultm = mysql_query($sqlm);
        $rowm = mysql_fetch_array($sql_resultm);
        if ($rowv1[0] == "" or $rowv1[0] == null){ 
					$rowv1[0]  = 0.00;
		}
        $cnt  = $rowm[0];

		if($cnt == 0){
			echo "<script>";   
      		echo "alert('No Data Found On Selected Query');"; 
      		echo "</script>";
		}else{
			// Redirect browser
        	$fname = "pur_bal1.rptdesign&__title=myReport"; 
        	$dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&fd=".$fpodte."&td=".$tpodte."&usernm=".$var_loginid."&s=".$compflg."&dbsel=".$varrpturldb;
        	$dest .= urlencode(realpath($fname));

        	//header("Location: $dest" );
        	echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        }
        
        $backloc = "../pur_inq/pur_bal_rpt.php?stat=4&menucd=".$var_menucode;
        echo "<script>";
        echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 

     }
    } 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

	
<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";

.style2 {
	margin-right: 8px;
}
</style>
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>

<script type="text/javascript" charset="utf-8"> 

function setup() {

	document.InpPurBal.selfsupp.focus();
						
 	//Set up the date parsers
    var dateParser = new DateParser("dd-MM-yyyy");
      
	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "inqfdte");
	dateMask1.validationMessage = errorMessage;	
	
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "inqtdte");
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

function enabDis(idchk)
{
	var txtchk = document.InpPurBal.chkall;
	var x      = document.getElementById("selfsupp");
	var y      = document.getElementById("seltsupp");

	if (txtchk.checked){
		document.getElementById("selfsupp").disabled=true;
		document.getElementById("seltsupp").disabled=true;
	}else{
		document.getElementById("selfsupp").disabled=false;
		document.getElementById("seltsupp").disabled=false;
	}
}

function chkSubmit()
{
	var x=document.forms["InpPurBal"]["chkall"];	
	if (!x.checked){
		var fs = document.forms["InpPurBal"]["selfsupp"].value;
		if (fs==null || fs=="")
		{
			alert("From Supplier Cannot Be Blank");
			document.InpPurBal.selfsupp.focus();
			return false;
		}
		
		var ts = document.forms["InpPurBal"]["seltsupp"].value;
		if (ts==null || ts=="")
		{
			alert("To Supplier Cannot Be Blank");
			document.InpPurBal.seltsupp.focus();
			return false;
		}
	}

	var x=document.forms["InpPurBal"]["inqfdte"].value;
	if (x==null || x=="")
	{
		alert("P/O Delivery From Date Cannot Be Blank");
		document.InpPurBal.inqfdte.focus();
		return false;
	}

	var x=document.forms["InpPurBal"]["inqfdte"].value;
	if (x==null || x=="")
	{
		alert("P/O Delievry From Date Cannot Be Blank");
		document.InpPurBal.inqfdte.focus();
		return false;
	}
	
	var x=document.forms["InpPurBal"]["inqtdte"].value;
	if (x==null || x=="")
	{
		alert("P/O Delivery To Date Cannot Be Blank");
		document.InpPurBal.inqtdte.focus();
		return false;
	}
	
	var x=document.forms["InpPurBal"]["inqfdte"].value;
	var y=document.forms["InpPurBal"]["inqtdte"].value;
	
    var fromdate = x.split('-');
        from_date = new Date();
        from_date.setFullYear(fromdate[2],fromdate[1]-1,fromdate[0]); 
    
    var todate = y.split('-');
        to_date = new Date();
        to_date.setFullYear(todate[2],todate[1]-1,todate[0]);
    if (from_date > to_date ) 
    {
        alert("P/O Delevery To Date Must Larger Then From Date");
		document.InpPurBal.rptofdte.focus();
		return false;
    }
}
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 598px; height: 290px;" class="style2">
	 <legend class="title">PURCHASE BALANCE REPORT</legend>
	  <br>
	  <form name="InpPurBal" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 583px;">
		<table style="width: 573px">
		  <tr>
		  	<td style="width: 15px"></td>
		  	<td style="width: 128px">From Supplier Code</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="selfsupp" id="selfsupp" style="width: 278px">
			    <?php
                   $sql = "select suppno, name from supplier_master where (status <> 'D' or status is null) ORDER BY suppno";
                   $sql_result = mysql_query($sql) or die("Enable To Query Supplier".mysql_error());
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['suppno'].'">'.$row['suppno']." | ".$row['name'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  </tr>
		  <tr><td style="width: 15px"></td></tr>
		  <tr>
		  	<td></td>
		  	<td>To Supplier Code</td>
		  	<td>:</td>
		  	<td>
		  		<select name="seltsupp" id="seltsupp" style="width: 278px">
			    <?php
                   $sql = "select suppno, name from supplier_master where (status <> 'D' or status is null) ORDER BY suppno";
                   $sql_result = mysql_query($sql) or die("Enable To Query Supplier".mysql_error());
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['suppno'].'">'.$row['suppno']." | ".$row['name'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>

		  	</td>
		  </tr>
		  <tr><td style="width: 15px"></td></tr>
		  <tr>
		  	  <td></td>
		  	  <td>All Supplier</td>
		  	  <td>:</td>
		  	  <td><input type="checkbox" name="chkall" id="chkall" onclick="enabDis(this.id)" value="1"></td>
		  </tr>
		  <tr><td style="width: 15px"></td></tr>	
	  	  <tr>
	  	    <td style="width: 15px"></td>
	  	    <td style="width: 128px" class="tdlabel">From Delivery Date</td>
	  	    <td style="width: 2px">:</td> 
	  	    <td style="width: 134px">
				<input class="inputtxt" name="inqfdte" id ="inqfdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('inqfdte','ddMMyyyy')" style="cursor:pointer">
			</td>	
	  	  </tr>
	  	  <tr><td style="width: 15px"></td></tr>
	  	  <tr>
	  	    <td style="width: 15px"></td>
	  	    <td style="width: 128px" class="tdlabel">To Delivery Date</td>
	  	    <td style="width: 2px">:</td> 
	  	    <td style="width: 134px">
				<input class="inputtxt" name="inqtdte" id ="inqtdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('inqtdte','ddMMyyyy')" style="cursor:pointer">
			</td>	
	  	  </tr>
	  	  <tr><td style="width: 15px"></td></tr>
	  	  <tr>
	  	    <td style="width: 15px"></td> 
	  	    <td style="width: 128px" class="tdlabel">Completed</td>
	  	    <td style="width: 2px">:</td> 
            <td style="width: 134px">
            	<select name="selout" id="selout" style="width: 89px">	   
			  		<option value="Y">YES</option>
			  		<option value="N">NO</option>
			  		<option value="A">ALL</option>
			  	</select>
            </td> 
	   	  </tr> 
	   	  <tr>
	   	  	<td>&nbsp;</td>
	   	  </tr>
	  	  <tr>
	  	   <td colspan="8" align="center">
	  	   
	  	   <?php
	  	   		include("../Setting/btnprint.php");
	  	   ?>
	  	   </td>
	  	  </tr>
	  	    <tr><td style="width: 15px">&nbsp;</td></tr>
		    <tr><td style="width: 15px">&nbsp;</td></tr>	
	  	</table>
	   </form>	
	</fieldset>
	 </div>
    <div class="spacer"></div>
</body>

</html>
