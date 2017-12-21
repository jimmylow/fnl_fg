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
	  set_time_limit(3600);
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
     	$fco = $_POST['selfct'];
     	$tco = $_POST['seltct'];
     	$fpr = $_POST['selfpr'];
     	$tpr = $_POST['seltpr'];
     	$fca = $_POST['selfcat'];
     	$tca = $_POST['seltcat'];
     	$fsu = $_POST['selfsup'];
     	$tsu = $_POST['seltsup'];
     	$tgb = $_POST['selgrby'];
     	$tpo = $_POST['spopt'];
     	$fsd = date("Y-m-d", strtotime($_POST['inqfd']));
     	$tsd = date("Y-m-d", strtotime($_POST['inqtd']));
		$rop = $_POST['rptopt'];
		
		$fsd = date("Y-m-01", strtotime($fsd));
		$tsd = date("Y-m-t", strtotime($tsd));

		#----------------------------------------------------
		$sql  = "CREATE TEMPORARY TABLE tmp2salesmas(";
	    $sql .= "sordno varchar(30), sorddte datetime, scustcd varchar(10), smthyr varchar(7), speriod varchar(1), ";
		$sql .= "less_type varchar(1), less_amt decimal(10,2), stat varchar(1), salesdte date)";
		mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
		$sql  = " Delete From tmp2salesmas";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error);
		
		$shardSize = 7000;
	 	$sqliq = "";		   			
	 	$k = 0;
		$sqld  = "select *";
        $sqld .= " from csalesmas where stat != 'C'";
		$rs_n1 = mysql_query($sqld);
		while ($rn1 = mysql_fetch_assoc($rs_n1)) {
			$sordno    = $rn1['sordno'];
		    $sorddte   = $rn1['sorddte'];
		    $scustcd   = $rn1['scustcd'];
			$smthyr    = $rn1['smthyr'];
		    $speriod   = $rn1['speriod'];
		    $less_type = $rn1['less_type'];
			$less_amt  = $rn1['less_amt'];
		    $stat      = $rn1['stat'];
			if (empty($less_amt)){$less_amt = 0;}
			
			$mthsdte   = substr($smthyr, 0, 2);
			$yrsdte    = substr($smthyr, 3, 6);
			$bsdte     = '01-'.$mthsdte.'-'.$yrsdte;
			$salesdte  = date("Y-m-t", strtotime($bsdte));

			if ($k % $shardSize == 0) {
       			if ($k != 0) {	  
        	   		mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
       			}
       			$sqliq = 'Insert Into tmp2salesmas values ';
    		}
   			$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$sordno', '$sorddte', '$scustcd', '$smthyr', '$speriod','$less_type', '$less_amt', 
															   '$stat', '$salesdte')";
			$k = $k + 1;
		}
		if (!empty($sqliq)){
			mysql_query($sqliq) or die ("Cant insert 2 : ".mysql_error());
		}
		#----------------------------------------------------
		
		#----------------Prepare Temp Table For Printing -----------------------------------
        $sql  = " Delete From tmpctstmv1 where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        
        #-------------------------------B/F Opening ------------------------------------
        $shardSize = 7000;
	 	$sqliq = "";		   			
	 	$k = 0;
	 	$sqld  = "select distinct min(x.salesdte), x.scustcd, y.sprocd";
        $sqld .= " from tmp2salesmas x, csalesdet y";
		$sqld .= " where x.salesdte between '$fsd' and '$tsd'";
		$sqld .= " and x.sordno = y.sordno";
		$sqld .= " and y.sprocd between '$fpr' and '$tpr'"; 
		$sqld .= " and x.scustcd between '$fco' and '$tco'";
		$sqld .= " group by 2, 3";
		$rs_n1 = mysql_query($sqld);
		while ($rn1 = mysql_fetch_assoc($rs_n1)) {
			$mdte   = $rn1['min(x.salesdte)'];
		    $custcd = $rn1['scustcd'];
		    $prcd   = $rn1['sprocd'];

			$sqlf = "select MAX(ExFacPrice) from product";
			$sqlf .= " where GroupCode = '$prcd' and Status != 'D'";
			$s_rf = mysql_query($sqlf);
			$rf = mysql_fetch_array($s_rf);
			$cstpri  = $rf['MAX(ExFacPrice)'];
			if (empty($cstpri)){$cstpri = 0;}
			
			$sqlf = "select sordno from tmp2salesmas";
			$sqlf .= " where scustcd = '$custcd' and salesdte = '$mdte'";
			$s_rf = mysql_query($sqlf);
			$rf = mysql_fetch_array($s_rf);
			$refno  = $rf['sordno'];
			
	 		$openqty = 0;
	 		$selpri = 0;
			$sqlf = "select openingqty, sprounipri  from csalesdet";
			$sqlf .= " where sprocd = '$prcd' and sordno = '$refno'";
			$s_rf = mysql_query($sqlf);
			$rf = mysql_fetch_array($s_rf);
			$openqty  = $rf['openingqty'];
			$selpri  = $rf['sprounipri'];
		    if (empty($openqty)){$openqty = 0;}
		    if (empty($selpri)){$selpri = 0;}

       		if ($k % $shardSize == 0) {
       			if ($k != 0) {	  
        	   		mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
       			}
       			$sqliq = 'Insert Into tmpctstmv1 (countercd, counterde, procode, prodesc, openqty, usernm, category, supercd, costpri, sellpri) values ';
    		}
   			$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$custcd', '$custde', '$prcd', '$prde', '$openqty','$var_loginid', '$category', '$supcd',
   																  '$cstpri', '$selpri')";
			 $k = $k + 1;
		}	
		if (!empty($sqliq)){
			mysql_query($sqliq) or die ("Cant insert 2 : ".mysql_error());
		}
		#-------------------------------B/F Opening ------------------------------------			
	
			#-------------------------------Current Date Transaction----------------------------------------
			$shardSize = 7000;
	 		$sqliq = "";		   			
	 		$k = 0;    
			$sql2  = "select y.scustcd, x.sprocd, sum(x.doqty), sum(x.soldqty), sum(x.rtnqty), sum(x.shortqty), sum(x.overqty), sum(x.adjqty), x.sprounipri";
			$sql2 .= " from csalesdet x, tmp2salesmas y";
			$sql2 .= " where y.salesdte between '$fsd' and '$tsd' and x.sordno = y.sordno";
			$sql2 .= " and   x.sprocd between '$fpr' and '$tpr'";
			$sql2 .= " and   y.scustcd between '$fco' and '$tco'";
			$sql2 .= " group by 1, 2, 9";
			$sql2 .= " order by 1, 2";
			$rs_n = mysql_query($sql2);
			while ($rn = mysql_fetch_assoc($rs_n)) {
			    $custcd   = $rn['scustcd'];
				$prcd     = $rn['sprocd'];
				$doqty    = $rn['sum(x.doqty)'];
				$soldqty  = $rn['sum(x.soldqty)'];
				$rtnqty   = $rn['sum(x.rtnqty)'];
				$shortqty = $rn['sum(x.shortqty)'];
				$overqty  = $rn['sum(x.overqty)'];
				$adjqty   = $rn['sum(x.adjqty)'];
				$selpri   = $rn['sprounipri'];
				
				$sqlf = "select MAX(ExFacPrice) from product";
				$sqlf .= " where GroupCode = '$prcd' and Status != 'D'";
				$s_rf = mysql_query($sqlf);
				$rf = mysql_fetch_array($s_rf);
				$costpri  = $rf['MAX(ExFacPrice)'];
				if (empty($costpri)){$costpri = 0;}
				
       			if ($k % $shardSize == 0) {
       				if ($k != 0) {	  
           				mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
       				}
       				$sqliq = 'Insert Into tmpctstmv1 (countercd, counterde, procode, prodesc, doqty, soldqty, rtnqty, shortqty, overqty, adjqty, 
       												  usernm, category, supercd, costpri, sellpri) values ';
    			}
   				$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$custcd', '$custde', '$prcd', '$prde', '$doqty', '$soldqty', '$rtnqty', '$shortqty' , '$overqty', 
   																   '$adjqty', '$var_loginid', '$category', '$supcd', '$costpri', '$selpri')";
		 		$k = $k + 1;
			}	
			if (!empty($sqliq)){
				mysql_query($sqliq) or die ("Cant insert 2 : ".mysql_error());
			}
			#-------------------------------Current Date Transaction----------------------------------------
		#-------------------------------Current Date----------------------------------------
				
		// Redirect browser
		if ($rop == 'D'){
			switch($tgb){
			case 'Category':
				$fname  = "ctstk_movepe.rptdesign&__title=myReport";
				break;
			case 'Counter':
				$fname  = "ctctstk_movepe.rptdesign&__title=myReport";
				break;
			case 'Group Code':
				$fname  = "gcctstk_movepe.rptdesign&__title=myReport";
				break;
			case 'Supervisor':
				$fname  = "suctstk_movepe.rptdesign&__title=myReport";
				break;
			}
		}else{
			switch($tgb){
			case 'Category':
				$fname  = "ctstk_movepes.rptdesign&__title=myReport";
				break;
			case 'Counter':
				$fname  = "ctctstk_movepes.rptdesign&__title=myReport";
				break;
			case 'Group Code':
				$fname  = "gcctstk_movepes.rptdesign&__title=myReport";
				break;
			case 'Supervisor':
				$fname  = "suctstk_movepes.rptdesign&__title=myReport";
				break;
			}
		} 		
       	$fname .= "&fp=".$fpr."&tp=".$tpr;
       	$fname .= "&usernm=".$var_loginid;
       	$fname .= "&s=".$fsd."&e=".$tsd;
       	$fname .= "&fc=".$fco."&tc=".$tco;
       	$fname .= "&fcat=".$fca."&tcat=".$tca;
       	$fname .= "&fsu=".$fsu."&tsu=".$tsu;
       	$fname .= "&price=".$tpo;
       	$fname .= "&dbsel=".$varrpturldb; 
       	$dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname;
       	$dest .= urlencode(realpath($fname));
       	//header("Location: $dest" );
       	echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        
        $backloc = "../cons_rpt/coun_stkmovepr.php?stat=4&menucd=".$var_menucode;
        echo "<script>";
       // echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 

     }
    } 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

	
<style media="all" type="text/css">
@import url('../css/styles.css');
@import url('../css/demo_table.css');

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

	document.InpPurBal.selfct.focus();
						
 	//Set up the date parsers
    var dateParser = new DateParser("dd-MM-yyyy");
      
	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "inqfd");
	dateMask1.validationMessage = errorMessage;
	
	var dateMask1 = new DateMask("dd-MM-yyyy", "inqtd");
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

function chkSubmit()
{
	var x=document.forms["InpPurBal"]["inqfd"].value;
	if (x==null || x=="")
	{
		alert("From Date Cannot Be Blank");
		document.InpPurBal.inqfd.focus();
		return false;
	}
	
	var x=document.forms["InpPurBal"]["inqtd"].value;
	if (x==null || x=="")
	{
		alert("To Date Cannot Be Blank");
		document.InpPurBal.inqtd.focus();
		return false;
	}
}
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 798px; height:370px;">
	 <legend class="title">COUNTER STOCK MOVEMENT REPORT (PERIOD)</legend>
	  <br>
	  <form name="InpPurBal" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		<table>
		   <tr>
		  	<td style="width: 15px"></td>
		  	<td style="width: 200px">From Counter</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="selfct" id="selfct" style="width: 278px">
			    <?php
                   $sql = "select CustNo, Name from customer_master ORDER BY CustNo";
                   $sql_result = mysql_query($sql) or die("Not Enable To Query Product Code".mysql_error());
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['CustNo'].'">'.$row['CustNo']." | ".$row['Name'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  	<td style="width: 15px"></td>
		  	<td style="width: 200px">To Counter</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="seltct" id="seltct" style="width: 278px">
			    <?php
                   $sql = "select CustNo, Name from customer_master ORDER BY CustNo";
                   $sql_result = mysql_query($sql) or die("Not Enable To Query Product Code".mysql_error());
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['CustNo'].'">'.$row['CustNo']." | ".$row['Name'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  </tr>
		  <tr><td style="width: 15px"></td></tr>		
		  <tr>
		  	<td style="width: 15px"></td>
		  	<td style="width: 128px">From Group Code</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="selfpr" id="selfpr" style="width: 100px">
			    <?php
                   $sql = "select distinct GroupCode from product where (Status <> 'D' or Status is null) ORDER BY GroupCode";
                   $sql_result = mysql_query($sql) or die("Not Enable To Query".mysql_error());
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['GroupCode'].'">'.$row['GroupCode'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  	<td></td>
		  	<td>To Group Code</td>
		  	<td>:</td>
		  	<td>
		  		<select name="seltpr" id="seltpr" style="width: 100px">
			    <?php
                   $sql = "select distinct GroupCode from product where (Status <> 'D' or Status is null) ORDER BY GroupCode";
                   $sql_result = mysql_query($sql) or die("Enable To Query".mysql_error());
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['GroupCode'].'">'.$row['GroupCode'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>

		  	</td>
		  </tr>
		  <tr><td style="width: 15px"></td></tr>
		  <tr>
		  	<td style="width: 15px"></td>
		  	<td style="width: 128px">From Category</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="selfcat" id="selfcat" style="width: 200px">
			    <?php
                   $sql = "select category_code, category_desc from category_master ORDER BY category_code";
                   $sql_result = mysql_query($sql) or die("Not Enable To Query".mysql_error());
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['category_code'].'">'.$row['category_code'].' | '.$row['category_desc'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  	<td></td>
		  	<td>To Category</td>
		  	<td>:</td>
		  	<td>
		  		<select name="seltcat" id="seltcat" style="width: 200px">
			    <?php
                   $sql = "select category_code, category_desc from category_master ORDER BY category_code";
                   $sql_result = mysql_query($sql) or die("Enable To Query".mysql_error());
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['category_code'].'">'.$row['category_code'].' | '.$row['category_desc'].'</option>';				 	 } 
				   }
	            ?>				   
			  </select>

		  	</td>
		  </tr>
		  <tr><td style="width: 15px"></td></tr>
		  <tr>
		  	<td style="width: 15px"></td>
		  	<td style="width: 128px">From Supervisor</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="selfsup" id="selfsup" style="width: 250px">
			    <?php
                   $sql = "select supervisor_code, supervisor_name from supervisor_master ORDER BY supervisor_code";
                   $sql_result = mysql_query($sql) or die("Not Enable To Query".mysql_error());
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['supervisor_code'].'">'.$row['supervisor_code'].' | '.$row['supervisor_name'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  	<td></td>
		  	<td>To Supervisor</td>
		  	<td>:</td>
		  	<td>
		  		<select name="seltsup" id="seltsup" style="width: 250px">
			    <?php
                   $sql = "select supervisor_code, supervisor_name from supervisor_master ORDER BY supervisor_code";
                   $sql_result = mysql_query($sql) or die("Enable To Query".mysql_error());
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					   echo '<option value="'.$row['supervisor_code'].'">'.$row['supervisor_code'].' | '.$row['supervisor_name'].'</option>';
					 } 
				   }
	            ?>				   
			  </select>

		  	</td>
		  </tr>
		  <tr><td style="width: 15px"></td></tr>
		  <tr>
		  	<td style="width: 15px"></td>
		  	<td style="width: 128px">Group By</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="selgrby" id="selgrby" style="width: 100px">
		   			<option value="Category">Category</option>
		   			<option value="Counter">Counter</option>
		   			<option value="Group Code">Group Code</option>
		   			<option value="Supervisor">Supervisor</option>
			  	</select>
		  	</td>
		  	<td></td>
		  </tr>
		  <tr><td style="width: 15px"></td></tr>
		   <tr>
		  	<td style="width: 15px"></td>
		  	<td style="width: 128px">Price</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="spopt" id="spopt" style="width: 100px">
		   			<option value="A">Selling Price</option>
		   			<option value="B">Cost Price</option>
		   			<option value="C">No Price</option>
			  	</select>
		  	</td>
		  	<td></td>
		  </tr>
		  <tr><td style="width: 15px"></td></tr>
	  	  <tr>
	  	    <td style="width: 15px"></td>
	  	    <td style="width: 128px" class="tdlabel">From Date</td>
	  	    <td style="width: 2px">:</td> 
	  	    <td style="width: 134px">
				<input class="inputtxt" name="inqfd" id ="inqfd" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('inqfd','ddMMyyyy')" style="cursor:pointer">
			</td>	
	  	    <td style="width: 15px"></td>
	  	    <td style="width: 128px" class="tdlabel">To Date</td>
	  	    <td style="width: 2px">:</td> 
	  	    <td style="width: 134px">
				<input class="inputtxt" name="inqtd" id ="inqtd" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('inqtd','ddMMyyyy')" style="cursor:pointer">
			</td>	
	  	  </tr>
		  <tr><td style="width: 15px"></td></tr>
		   <tr>
		  	<td style="width: 15px"></td>
		  	<td style="width: 128px">Report Option</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="rptopt" id="rptopt" style="width: 100px">
		   			<option value="D">Detail</option>
		   			<option value="S">Summary</option>
			  	</select>
		  	</td>
		  	<td></td>
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
	  	  <tr><td>&nbsp;</td></tr>	
	  	</table>
	   </form>	
	</fieldset>
	 </div>

</body>

</html>
