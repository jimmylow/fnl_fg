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
      set_time_limit(180);
      include("../Setting/ChqAuth.php");
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
		$frcus  = $_POST['selfcus'];
		$tocus  = $_POST['seltcus'];
		$sfrdte = date("Y-m-d", strtotime($_POST['rptfsdte']));
		$stodte = date("Y-m-d", strtotime($_POST['rpttsdte']));
     	$frprd  = $_POST['selfprod'];
     	$toprd  = $_POST['seltprod'];
     	$selqt  = $_POST['selqty'];
     	
     	#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmpshipreqde where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        
        if ($selqt == 0){
        	$sql  = "SELECT y.sprocd, y.shipqty, x.shipdte, x.scustcd, x.stype, y.sproqty, y.sprouom ";
			$sql .= " FROM salesshipmas x, salesshipdet y";
  			$sql .= " where x.shipno = y.shipno";
  			$sql .= " and   x.shipdte between '$sfrdte' and '$stodte'";	
  			$sql .= " and   y.sprocd between '$frprd' and '$toprd'";
  			$sql .= " and   x.scustcd between '$frcus' and '$tocus'";
  			$sql .= " and   y.shipqty = 0 and x.stat = 'A'";
    		$sql .= " Order by y.sprocd";
    	}else{
    		$sql  = "SELECT y.sprocd, y.shipqty, x.shipdte, x.scustcd, x.stype, y.sproqty, y.sprouom ";
			$sql .= " FROM salesshipmas x, salesshipdet y";
  			$sql .= " where x.shipno = y.shipno";
  			$sql .= " and   x.shipdte between '$sfrdte' and '$stodte'";	
  			$sql .= " and   y.sprocd between '$frprd' and '$toprd'";
  			$sql .= " and   x.scustcd between '$frcus' and '$tocus'";
  			$sql .= " and   y.shipqty > 0 and x.stat = 'A'";
    		$sql .= " Order by y.sprocd";
    	}
    	//echo $sql;
  
		$rs_result = mysql_query($sql); 
		while ($row = mysql_fetch_assoc($rs_result)) { 
		    $procd   = mysql_real_escape_string($row['sprocd']);
		    $shqty   = $row['shipqty'];
		    $shipdte = $row['shipdte'];
		    $scustcd = $row['scustcd'];
		    $stype   = $row['stype'];
		    $sproqty = $row['sproqty'];
		    $sprouom = $row['sprouom'];
		    if(empty($shqty)){$shqty = 0;}
		    if(empty($sproqty)){$sproqty = 0;}
		    
		    $sql2  = "select uom_pack from prod_uommas";
          	$sql2 .= " where uom_code = '$sprouom'";
          	$result = mysql_query($sql2) or die ("Error uom : ".mysql_error());
          	if(mysql_numrows($result) > 0) {
            	$data = mysql_fetch_object($result);
            	$var_uqty = $data->uom_pack;
            	if ($var_uqty == ""){$var_uqty = 1;}
            }else{ 
            	$var_uqty = 1; 
            }  
            $var_totpcs = $shqty * $var_uqty;   
        	
        	$sqlc  = "select Location, Cost";
		    $sqlc .= " from product";
        	$sqlc .= " where ProductCode ='$procd'";
        	$sql_resultc = mysql_query($sqlc);
        	$rowc = mysql_fetch_array($sql_resultc);
        	$prolo = mysql_real_escape_string($rowc['Location']);
        	$procs = mysql_real_escape_string($rowc['Cost']);
        	
        	
        	$sqlc  = "select name from customer_master where custno = '$scustcd'";
        	$sql_resultc = mysql_query($sqlc);
        	$rowc = mysql_fetch_array($sql_resultc);
        	$custnm = mysql_real_escape_string($rowc['name']);
        	
        	$sqlc  = "select shiptype_desc from shiptype_master where shiptype_code = '$stype'";
        	$sql_resultc = mysql_query($sqlc);
        	$rowc = mysql_fetch_array($sql_resultc);
        	$shiptype_desc = mysql_real_escape_string($rowc['shiptype_desc']);

			$samt = $shqty * $procs;
			if(empty($procs)){$procs = 0;}
			if(empty($samt)){$samt = 0;}
			
        	$sqli  = " Insert Into tmpshipreqde values ";
        	$sqli .= " ('$var_loginid', '$procd', '$scustcd', '$custnm', '$prolo',";
        	$sqli .= "  '$stype', '$shiptype_desc', '$shipdte', '$sprouom', '$var_totpcs', ";
        	$sqli .= "  '$procs', '$samt', '$shqty', '$sproqty')";
        	mysql_query($sqli) or die("Unable Save In Temp Table ".mysql_error());
		}
     	#-----------------------------------------------------------------------------------
     
		// Redirect browser
        $fname = "shipreqdet.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&fd=".$sfrdte."&td=".$stodte."&fcu=".$frcus."&tcu=".$tocus."&fpr=".$frprd."&tpr=".$toprd."&sqt=".$selqt."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));

        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../salesrpt/shipreqdet.php?menucd=".$var_menucode;
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

	document.InpRawOpen.selfcus.focus();
						
 	//Set up the date parsers
    var dateParser = new DateParser("dd-MM-yyyy");
      
	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "rptfsdte");
	dateMask1.validationMessage = errorMessage;
	
	var dateMask1 = new DateMask("dd-MM-yyyy", "rpttsdte");
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
	var x=document.forms["InpRawOpen"]["selfcus"].value;
	if (x==null || x=="")
	{
		alert("From Customer Must Not Be Blank");
		document.InpRawOpen.selfcus.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["seltcus"].value;
	if (x==null || x=="")
	{
		alert("To Customer Must Not Be Blank");
		document.InpRawOpen.seltcus.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["selfprod"].value;
	if (x==null || x=="")
	{
		alert("From Product Code Must Not Be Blank");
		document.InpRawOpen.selfprod.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["seltprod"].value;
	if (x==null || x=="")
	{
		alert("To Product Code Must Not Be Blank");
		document.InpRawOpen.seltprod.focus();
		return false;
	}


	var x=document.forms["InpRawOpen"]["rptfsdte"].value;
	if (x==null || x=="")
	{
		alert("Ship From Date Must Not Be Blank");
		document.InpRawOpen.rptfsdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rpttsdte"].value;
	if (x==null || x=="")
	{
		alert("Ship To Date Must Not Be Blank");
		document.InpRawOpen.rpttsdte.focus();
		return false;
	}

	
	var x=document.forms["InpRawOpen"]["rptfsdte"].value;
	var x=document.forms["InpRawOpen"]["rpttsdte"].value;
	
    var fromdate = x.split('-');
        from_date = new Date();
        from_date.setFullYear(fromdate[2],fromdate[1]-1,fromdate[0]); 
    
    var todate = y.split('-');
        to_date = new Date();
        to_date.setFullYear(todate[2],todate[1]-1,todate[0]);
    if (from_date > to_date ) 
    {
       alert("To Date Cannot Larger Than To Date");
	   document.InpRawOpen.rptfsdte.focus();
	   return false;
    }
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 894px; height: 198px;" class="style2">
	 <legend class="title">SHIPPING REQUEST DETAIL REPORT</legend>
	  <br />
	  <form name="InpRawOpen" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 527px;">
		<table style="width: 877px">
		   <tr>
		  	<td></td>
		  	<td style="width: 130px">From Customer</td>
		  	<td>:</td>
		  	<td style="width: 278px">
		  		<select name="selfcus" id ="selfcus" style="width: 183px">
			    <?php
                   $sql = "select CustNo, Name from customer_master ORDER BY CustNo";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
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
		  	<td></td>
		  	<td style="width: 140px">To Customer</td>
		  	<td>:</td>
		  	<td>
		  		<select name="seltcus" id ="seltcus" style="width: 183px">
			    <?php
                   $sql = "select CustNo, Name from customer_master ORDER BY CustNo";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
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
		  <tr><td></td></tr>
		  <tr>
		  	<td></td>
		  	<td style="width: 130px">From Product</td>
		  	<td>:</td>
		  	<td>
		  		<select name="selfprod" id ="selfprod" style="width: 251px">
			    <?php
                   $sql = "select ProductCode, Description from product ORDER BY ProductCode";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['ProductCode'].'">'.$row['ProductCode']." | ".$row['Description'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  	<td></td>
		  	<td style="width: 130px">To Product</td>
		  	<td>:</td>
		  	<td>
		  		<select name="seltprod" id ="seltprod" style="width: 251px">
			    <?php
                   $sql = "select ProductCode, Description from product ORDER BY ProductCode";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['ProductCode'].'">'.$row['ProductCode']." | ".$row['Description'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>

		  </tr>
		  <tr><td></td></tr>	 	
	  	  <tr>
	  	    <td></td>
	  	    <td>From ShipDate</td>
	  	    <td>:</td> 
	  	    <td>
				<input class="inputtxt" name="rptfsdte" id ="rptfsdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px" />
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptfsdte','ddMMyyyy')" style="cursor:pointer" />
			</td>
			<td></td>
			 <td>To ShipDate</td>
	  	    <td>:</td> 
	  	    <td>
				<input class="inputtxt" name="rpttsdte" id ="rpttsdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px" />
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rpttsdte','ddMMyyyy')" style="cursor:pointer" />
			</td>
		  </tr>
		  <tr><td></td></tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td>Quantity</td>
	  	    <td>:</td> 
            <td>
            	<select name="selqty" id ="selqty" style="width: 50px">
                   <option value="0">=0</option>
                   <option value="1">>0</option> 			   
			  	</select>
            </td> 
	   	  </tr> 
	   	  <tr><td></td></tr>	
	  	  <tr>
	  	  	 <td colspan="8" align="center">
	  	  		 <?php
	  	  	 		include("../Setting/btnprint.php");
	  	  		 ?>
	  	  	 </td>
	  	  </tr>
	  	   <tr><td style="width: 6px"></td></tr>
	  	</table>
	   </form>	
	</fieldset>
	 </div>
    <div class="spacer"></div>
</body>

</html>
