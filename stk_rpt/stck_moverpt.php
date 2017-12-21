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
     
     	$frcat  = $_POST['selfcat'];
     	$tocat  = $_POST['seltcat'];
     	$catall = $_POST['chkallcat'];
     	$frdte  = date("Y-m-d", strtotime($_POST['rptofdte']));
     	$todte  = date("Y-m-d", strtotime($_POST['rptotdte']));
     	$rpttyp = $_POST['rptype'];
     	
     	if ($frdte <> "" and $todte <> ""){
     	
     		#----------------Prepare Temp Table For Printing -----------------------------------
     		$sql  = " Delete From tmpitmmove_rpt where usernm = '$var_loginid'";
        	mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        	
        	if ($catall == '1'){
        		$sqlm = "SELECT ProductCode, Description, OwnCode, ExFacPrice, ExUnit ";
				$sqlm .= " FROM product";			
    			$sqlm .= " Order BY OwnCode, ProductCode";
    		}else{
    			$sqlm = "SELECT ProductCode, Description, OwnCode, ExFacPrice, ExUnit ";
				$sqlm .= " FROM product";
    			$sqlm .= " where OwnCode between '$frcat' and '$tocat'";
    			$sqlm .= " Order BY OwnCode, ProductCode";
    		}	  
			$rs_resultm = mysql_query($sqlm);

			while ($rowm = mysql_fetch_assoc($rs_resultm)) { 
		    	$procd   = mysql_real_escape_string($rowm['ProductCode']);
		    	$procdde = mysql_real_escape_string($rowm['Description']);
		    	$owncat  = mysql_real_escape_string($rowm['OwnCode']);
		    	$excost  = mysql_real_escape_string($rowm['ExFacPrice']);
		    	$exuom   = mysql_real_escape_string($rowm['ExUnit']);
        	
        		$sqlc  = "select category_desc";
		    	$sqlc .= " from mdfcategory_master";
        		$sqlc .= " where category_code ='$owncat'";   
        		$sql_resultc = mysql_query($sqlc);
        		$rowc = mysql_fetch_array($sql_resultc);
        		$owndes = $rowc['category_desc'];
        		    		
        		#------------OnHand QTy From Date-----------------------------			
					//---- for opening qty : OP -----//
  					$sql = " select sum(qtyin) as cnt from invthist";
  					$sql .= " where reftype = 'OP'";
  					$sql .= " and prodcode = '".$procd."'";
  					$sql .= " and refdate < '$frdte'";
  					$tmp = mysql_query ($sql) or die ("Cant get 1 : ".mysql_error());
  					if (mysql_numrows($tmp) > 0) {
      					$rst = mysql_fetch_object($tmp);
      					$var_begbal = $rst->cnt;
      					if ($var_begbal == "") { $var_begbal = 0; }
  					}else{$var_begbal = 0;}  
  
   					//---- for rec qty : RC -----//
  					$sql = " select sum(qtyin) as cnt from invthist";
  					$sql .= " where reftype = 'RC'";
  					$sql .= " and prodcode = '".$procd."'";
  					$sql .= " and refdate < '$frdte'";
  					$tmp = mysql_query ($sql) or die ("Cant get 1 : ".mysql_error());
  					if (mysql_numrows($tmp) > 0) {
      					$rst = mysql_fetch_object($tmp);
      					$var_rec = $rst->cnt;
      					if ($var_rec == "") { $var_rec = 0; }
  					}else{$var_rec = 0;}
  
  					//---- for transfer in qty : TI -----//
  					$sql = " select sum(qtyin) as cnt from invthist";
  					$sql .= " where reftype = 'TI'";
  					$sql .= " and prodcode = '".$procd."'";
  					$sql .= " and refdate < '$frdte'";
  					$tmp = mysql_query ($sql) or die ("Cant get 2 : ".mysql_error());
  					if (mysql_numrows($tmp) > 0) {
      					$rst = mysql_fetch_object($tmp);
      					$var_trfin = $rst->cnt;
      					if ($var_trfin == "") { $var_trfin = 0; }
  					}else{$var_trfin = 0;} 
  
  					//---- for transfer out qty : TO -----//
  					$sql = " select sum(qtyout) as cnt from invthist";
  					$sql .= " where reftype = 'TO'";
  					$sql .= " and prodcode = '".$procd."'";
  					$sql .= " and refdate < '$frdte'";
  					$tmp = mysql_query ($sql) or die ("Cant get 3 : ".mysql_error());
  					if (mysql_numrows($tmp) > 0) {
      					$rst = mysql_fetch_object($tmp);
      					$var_trfout = $rst->cnt;
      					if ($var_trfout == "") { $var_trfout = 0; }
  					}else{$var_trfout = 0;} 
  
  					//---- for ret from : RN -----//
  					$sql = " select sum(qtyin) as cnt from invthist";
  					$sql .= " where reftype = 'RN'";
  					$sql .= " and prodcode = '".$procd."'";
  					$sql .= " and refdate < '$frdte'";
  					$tmp = mysql_query ($sql) or die ("Cant get 4 : ".mysql_error());
  					if (mysql_numrows($tmp) > 0) {
      					$rst = mysql_fetch_object($tmp);
      					$var_rtnfrm = $rst->cnt;
      					if ($var_rtnfrm == "") { $var_rtnfrm = 0; }
  					}else{$var_rtnfrm = 0;} 
  
   					//---- for ship qty : SA -----//
  					$sql = " select sum(qtyout) as cnt from invthist";
  					$sql .= " where reftype = 'SA'";
  					$sql .= " and prodcode = '".$procd."'";
  					$sql .= " and refdate < '$frdte'";
  					$tmp = mysql_query ($sql) or die ("Cant get 5 : ".mysql_error());
  					if (mysql_numrows($tmp) > 0) {
      					$rst = mysql_fetch_object($tmp);
      					$var_ship = $rst->cnt;
      					if ($var_ship == "") { $var_ship = 0; }
  					}else{$var_ship = 0;}
  
   					//---- for adj qty : AD -----//
  					$sql = " select sum(qtyin - qtyout) as cnt from invthist";
  					$sql .= " where reftype = 'AD'";
  					$sql .= " and prodcode = '".$procd."'";
  					$sql .= " and refdate < '$frdte'";
  					$tmp = mysql_query ($sql) or die ("Cant get 6 : ".mysql_error());
  					if (mysql_numrows($tmp) > 0) {
      					$rst = mysql_fetch_object($tmp);
      					$var_adj = $rst->cnt;
      					if ($var_adj == "") { $var_adj = 0; }
  					}else{$var_adj = 0;} 
  
  					//---- for return to qty : RS -----//
  					$sql = " select sum(qtyout) as cnt from invthist";
  					$sql .= " where reftype = 'RS'";
  					$sql .= " and prodcode = '".$procd."'";
  					$sql .= " and refdate < '$frdte'";
  					$tmp = mysql_query ($sql) or die ("Cant get 7 : ".mysql_error());
  					if (mysql_numrows($tmp) > 0) {
      					$rst = mysql_fetch_object($tmp);
      					$var_rtnto = $rst->cnt;
      					if ($var_rtnto == "") { $var_rtnto = 0; }
  					}else{$var_rtnto = 0;}   
  				$var_rtnall = $var_rtnfrm - $var_rtnto;
  				$var_bfqty =  $var_begbal + $var_rec + $var_trfin - $var_trfout + $var_rtnfrm - $var_ship + $var_adj - $var_rtnto;
        		#-------------------------------------------------------------
        		
        		#------------GRN QTy From Date To Date-----------------------------			
  				$sql = " select sum(qtyin) as cnt from invthist";
  				$sql .= " where reftype = 'OP'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate between '$frdte' and '$todte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 1 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$uvar_begbal = $rst->cnt;
      				if ($uvar_begbal == "") { $uvar_begbal = 0; }
  				}else{$uvar_begbal = 0;}  
  
  				$sql = " select sum(qtyin) as cnt from invthist";
  				$sql .= " where reftype = 'RC'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate between '$frdte' and '$todte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 1 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$uvar_rec = $rst->cnt;
      				if ($uvar_rec == "") { $uvar_rec = 0; }
  				}else{$uvar_rec = 0;}
  				$utotrec = $uvar_begbal + $uvar_rec;
        		#-------------------------------------------------------------

				#------------Tran In Qty From Date To Date-----------------------------			
				$sql = " select sum(qtyin) as cnt from invthist";
  				$sql .= " where reftype = 'TI'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate between '$frdte' and '$todte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 2 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$uvar_trfin = $rst->cnt;
      				if ($uvar_trfin == "") { $uvar_trfin = 0; }
  				}else{ 
  					$uvar_trfin = 0; 
  				} 
        		#-------------------------------------------------------------

				#------------Tran Out Qty From Date To Date-----------------------------
				$sql = " select sum(qtyout) as cnt from invthist";
  				$sql .= " where reftype = 'TO'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate between '$frdte' and '$todte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 3 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$uvar_trfout = $rst->cnt;
      				if ($uvar_trfout == "") { $uvar_trfout = 0; }
  				}else{ 
  					$uvar_trfout = 0; 
  				}        		
  				#-------------------------------------------------------------

				#------------Return QTy From Date To Date-----------------------------			
				$sql = " select sum(qtyin) as cnt from invthist";
  				$sql .= " where reftype = 'RN'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate between '$frdte' and '$todte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 4 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$uvar_rtnfrm = $rst->cnt;
      				if ($uvar_rtnfrm == "") { $uvar_rtnfrm = 0; }
  				}else{ 
  					$uvar_rtnfrm = 0; 
  				} 
  				$sql = " select sum(qtyout) as cnt from invthist";
  				$sql .= " where reftype = 'RS'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate between '$frdte' and '$todte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 7 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$uvar_rtnto = $rst->cnt;
      				if ($uvar_rtnto == "") { $uvar_rtnto = 0; }
  				}else{ 
  					$uvar_rtnto = 0; 
  				}   
				$uvar_rtnall = $uvar_rtnfrm - $uvar_rtnto;
        		#-------------------------------------------------------------
        		
        		#------------Ship QTy From Date To Date-----------------------------			
				$sql = " select sum(qtyout) as cnt from invthist";
  				$sql .= " where reftype = 'SA'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate between '$frdte' and '$todte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 5 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$uvar_ship = $rst->cnt;
      				if ($uvar_ship == "") { $uvar_ship = 0; }
  				}else{ 
  					$uvar_ship = 0; 
  				}
        		#-------------------------------------------------------------
				
				
				#------------Adj QTy From Date To Date-----------------------------			
				$sql = " select sum(qtyin - qtyout) as cnt from invthist";
  				$sql .= " where reftype = 'AD'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate between '$frdte' and '$todte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 6 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$uvar_adj = $rst->cnt;
      				if ($uvar_adj == "") { $uvar_adj = 0; }
  				}else{ 
  					$uvar_adj = 0; 
  				} 
        		#-------------------------------------------------------------
        		
        		$cloqty = $var_bfqty + $utotrec + $uvar_trfin - $uvar_trfout + $uvar_rtnall - $uvar_ship + $uvar_adj;

				if ($var_bfqty <> 0 or $utotrec <> 0 or $uvar_trfin <> 0  or
				    $uvar_trfout <> 0 or $uvar_rtnall <> 0 or $uvar_ship <> 0 or $uvar_adj <> 0){
				
					$vartoday = date("Y-m-d");
        			
        			$obalamt = $excost * $var_bfqty;
        			$cbalamt = $excost * $cloqty;
        			if (empty($excost)){$excost = 0;}
					$sqliq  = " Insert Into tmpitmmove_rpt (subcode, description, cat, cat_desc, ";
        			$sqliq .= "  uom, openqty, openavgcst, openamt, recqty, traninqty, tranoutqty, rtnqty, ";
        			$sqliq .= "  shipqty, adjqty, cloqty, cloamt, usernm)";
        			$sqliq .= " Values ('$procd', '$procdde', '$owncat', '$owndes', '$exuom',";
        			$sqliq .= "   '$var_bfqty', '$excost', '$obalamt', '$utotrec', '$uvar_trfin', '$uvar_trfout',";
        			$sqliq .= "   '$uvar_rtnall', '$uvar_ship', '$uvar_adj', '$cloqty', '$cbalamt','$var_loginid')";
        			
        			mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
				}
     		}
     		#-----------------------------------------------------------------------------------
			// Redirect browser
			if ($rpttyp == "D"){
        		$fname = "stk_movement_rpt.rptdesign&__title=myReport"; 
        		$dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&fd=".$frdte."&td=".$todte."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        		$dest .= urlencode(realpath($fname));
        	}else{
        		$fname = "stk_moveigrp_surpt.rptdesign&__title=myReport"; 
        		$dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&fd=".$frdte."&td=".$todte."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        		$dest .= urlencode(realpath($fname));
        	}	

        	//header("Location: $dest" );
        	echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        	$backloc = "../stk_rpt/stck_moverpt.php?menucd=".$var_menucode;
       		echo "<script>";
       		echo 'location.replace("'.$backloc.'")';
        	echo "</script>";
        }
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

	document.InpRawOpen.selfcat.focus();
						
 	//Set up the date parsers
    var dateParser = new DateParser("dd-MM-yyyy");
      
	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "rptofdte");
	dateMask1.validationMessage = errorMessage;		
		
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "rptotdte");
	dateMask1.validationMessage = errorMessage;	
}

function enabDis(idchk)
{
	var txtchk = document.InpRawOpen.chkallcat;
	var x      = document.getElementById("selfcat");
	var y      = document.getElementById("seltcat");

	if (txtchk.checked){
		document.getElementById("selfcat").disabled=true;
		document.getElementById("seltcat").disabled=true;
	}else{
		document.getElementById("selfcat").disabled=false;
		document.getElementById("seltcat").disabled=false;
	}
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

function chkSubmit()
{
	var txtchk = document.InpRawOpen.chkallcat;

	if (!txtchk.checked){
		var x=document.forms["InpRawOpen"]["selfcat"].value;
		if (x==null || x=="")
		{
			alert("From Category Code Cannot Be Blank");
			document.InpRawOpen.selfcat.focus();
			return false;
		}
		
		var x=document.forms["InpRawOpen"]["seltcat"].value;
		if (x==null || x=="")
		{
			alert("To Category Code Cannot Be Blank");
			document.InpRawOpen.seltcat.focus();
			return false;
		}
	}

	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	if (x==null || x=="")
	{
		alert("Opening From Date Must Not Be Blank");
		document.InpRawOpen.rptofdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptotdte"].value;
	if (x==null || x=="")
	{
		alert("Opening To Date Must Not Be Blank");
		document.InpRawOpen.rptotdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	var y=document.forms["InpRawOpen"]["rptotdte"].value;
	
    var fromdate = x.split('-');
        from_date = new Date();
        from_date.setFullYear(fromdate[2],fromdate[1]-1,fromdate[0]); 
    
    var todate = y.split('-');
        to_date = new Date();
        to_date.setFullYear(todate[2],todate[1]-1,todate[0]);
    if (from_date > to_date ) 
    {
        alert("Opening To Date Must Larger Then From Date");
		document.InpRawOpen.rptofdte.focus();
		return false;
    }
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 627px; height: 260px;" class="style2">
	 <legend class="title">STOCK MOVEMENT REPORT BY OWN CODE</legend>
	  <br>
	  <form name="InpRawOpen" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 619px;">
		<table>
		  <tr>
		  	<td></td>
		  	<td style="width: 140px">From <span>Own Code</span></td>
		  	<td>:</td>
		  	<td colspan="5">
		  		<select name="selfcat" id ="selfcat" style="width: 278px">
			    <?php
                   $sql = "select category_code, category_desc from mdfcategory_master ORDER BY category_code";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['category_code'].'">'.$row['category_code']." | ".$row['category_desc'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  </tr>
		  <tr><td></td></tr>
		  <tr> 
		    <td></td>
		  	<td style="width: 140px">To Own Code</td>
		  	<td>:</td>
		  	<td colspan="5">
		  		<select name="seltcat" id ="seltcat" style="width: 278px">
			    <?php
                   $sql = "select category_code, category_desc from mdfcategory_master ORDER BY category_code";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['category_code'].'">'.$row['category_code']." | ".$row['category_desc'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  </tr> 	
		  <tr><td></td></tr>
		  <tr>
		    <td style="width: 10px"></td>
		  	<td style="width: 132px">All Own Code</td>
		  	<td>:</td>
		  	<td colspan="5"><input type="checkbox" name="chkallcat" id="chkallcat" onclick="enabDis(this.id)" value="1"></td>
		  </tr>	
		  <tr><td></td></tr>	
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 140px" class="tdlabel">From Date</td>
	  	    <td>:</td> 
	  	    <td style="width: 134px">
				<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer">
			</td>
			<td style="width: 27px"></td>
			<td style="width: 109px">To Date</td>
			<td>:</td>
			<td>
				<input class="inputtxt" name="rptotdte" id ="rptotdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptotdte','ddMMyyyy')" style="cursor:pointer">
			</td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>Report Type</td>
	  	  	<td>:</td>
	  	  	<td colspan="5">
	  	  		<select name="rptype" id="rpttype">
	  	  			<option value="D">Detail</option>
	  	  			<option value="S">Summary</option>
	  	  		</select>
	  	  	</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 140px" class="tdlabel">&nbsp;</td>
	  	    <td></td> 
            <td style="width: 134px"></td> 
	   	  </tr> 
	   	
	  	  <tr>
	  	   <td style="width: 181px" colspan="8" align="center">
	  	   
	  	   <?php
	  	   		include("../Setting/btnprint.php");
	  	   ?>
	  	   </td>
	  	  </tr>
	  	   <tr><td></td></tr>

	  	</table>
	   </form>	
	</fieldset>
	 </div>
    <div class="spacer"></div>
</body>

</html>
