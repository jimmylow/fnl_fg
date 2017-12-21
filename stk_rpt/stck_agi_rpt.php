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
     
     	$frdte = date("Y-m-d", strtotime($_POST['rptofdte']));
     	$qyr   = date("Y", strtotime($_POST['rptofdte']));

     	
     	#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmpagitab where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        #----------------Prepare Temp Table For Printing -----------------------------------
                                
        $sql = "SELECT ProductCode, OwnCode, Description, ExFacPrice, ExUnit ";
		$sql .= " FROM product";
    	$sql .= " Order BY OwnCode, ProductCode";  
		$rs_result = mysql_query($sql); 

		while ($row = mysql_fetch_assoc($rs_result)) { 
		    $procd  = mysql_real_escape_string($row['ProductCode']);
		    $ownccd = mysql_real_escape_string($row['OwnCode']);
		    $itmdes = mysql_real_escape_string($row['Description']);
		    $excost = mysql_real_escape_string($row['ExFacPrice']);
			$exuom  = mysql_real_escape_string($row['ExUnit']);
			if ($excost == "") { $excost = 0; }

			$sqlc  = "select category_desc";
		    $sqlc .= " from mdfcategory_master";
        	$sqlc .= " where category_code ='$ownccd'";
        	$sql_resultc = mysql_query($sqlc);
        	$rowc = mysql_fetch_array($sql_resultc);
        	$owndes = $rowc['category_desc'];
		    
        	#------------OnHand QTy-----------------------------			
				//---- for opening qty : OP -----//
  				$sql = " select sum(qtyin) as cnt from invthist";
  				$sql .= " where reftype = 'OP'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate <= '$frdte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 1 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$var_begbal = $rst->cnt;
      				if ($var_begbal == "") { $var_begbal = 0; }
  				}else{ 
  					$var_begbal = 0; 
  				}  
  
   				//---- for rec qty : RC -----//
  				$sql = " select sum(qtyin) as cnt from invthist";
  				$sql .= " where reftype = 'RC'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate <= '$frdte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 1 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$var_rec = $rst->cnt;
      				if ($var_rec == "") { $var_rec = 0; }
  				}else{ 
  					$var_rec = 0; 
  				}
  
  				//---- for transfer in qty : TI -----//
  				$sql = " select sum(qtyin) as cnt from invthist";
  				$sql .= " where reftype = 'TI'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate <= '$frdte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 2 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$var_trfin = $rst->cnt;
      				if ($var_trfin == "") { $var_trfin = 0; }
  				}else{ 
  					$var_trfin = 0; 
  				} 
  
  				//---- for transfer out qty : TO -----//
  				$sql = " select sum(qtyout) as cnt from invthist";
  				$sql .= " where reftype = 'TO'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate <= '$frdte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 3 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$var_trfout = $rst->cnt;
      				if ($var_trfout == "") { $var_trfout = 0; }
  				}else{ 
  					$var_trfout = 0; 
  				} 
  
  				//---- for ret from : RN -----//
  				$sql = " select sum(qtyin) as cnt from invthist";
  				$sql .= " where reftype = 'RN'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate <= '$frdte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 4 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$var_rtnfrm = $rst->cnt;
      				if ($var_rtnfrm == "") { $var_rtnfrm = 0; }
  				}else{ 
  					$var_rtnfrm = 0; 
  				} 
  
   				//---- for ship qty : SA -----//
  				$sql = " select sum(qtyout) as cnt from invthist";
  				$sql .= " where reftype = 'SA'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate <= '$frdte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 5 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$var_ship = $rst->cnt;
      				if ($var_ship == "") { $var_ship = 0; }
  				}else{ 
  					$var_ship = 0; 
  				}
  
   				//---- for adj qty : AD -----//
  				$sql = " select sum(qtyin - qtyout) as cnt from invthist";
  				$sql .= " where reftype = 'AD'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate <= '$frdte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 6 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$var_adj = $rst->cnt;
      				if ($var_adj == "") { $var_adj = 0; }
  				}else{ 
  					$var_adj = 0; 
  				} 
  
  				//---- for return to qty : RS -----//
  				$sql = " select sum(qtyout) as cnt from invthist";
  				$sql .= " where reftype = 'RS'";
  				$sql .= " and prodcode = '".$procd."'";
  				$sql .= " and refdate <= '$frdte'";
  				$tmp = mysql_query ($sql) or die ("Cant get 7 : ".mysql_error());
  				if (mysql_numrows($tmp) > 0) {
      				$rst = mysql_fetch_object($tmp);
      				$var_rtnto = $rst->cnt;
      				if ($var_rtnto == "") { $var_rtnto = 0; }
  				}else{ 
  					$var_rtnto = 0; 
  				}   
    
  			$var_rtnall = $var_rtnfrm - $var_rtnto;
  			//echo $var_begbal.' '.$var_rec.' '.$var_trfin.' '.$var_trfout.' '.$var_rtnfrm.' '.$var_ship.' '.$var_adj.' '.$var_rtnto."<br>";
  			$var_onhand =  $var_begbal + $var_rec + $var_trfin - $var_trfout + $var_rtnfrm - $var_ship + $var_adj - $var_rtnto;
			#------------OnHand QTy-----------------------------
			//echo $var_onhand."<br>";
        	if ($var_onhand != 0){
  		
				#-------------------------Aging Qty & Amount -------------------------------
				$bqty = $var_onhand;
				$qyr   = date("Y", strtotime($_POST['rptofdte']));
    			for ($i=5; $i >= 1; $i--){
    				//echo $i."<br>";
    				
    				$pdte = $qyr."-01-01";
    				$lpdte = $qyr."-12-31";
      				//echo $pdte.' '.$lpdte."<br>";
      		
					if ($i == 5){		
						$sqlo = "select sum(qtyin) from invthist ";
        				$sqlo .= " where prodcode ='$procd' ";
        				$sqlo .= " and reftype in ('OP', 'RC', 'TI', 'RN', 'AD')";
        				$sqlo .= " and qtyin <> 0";
        				$sqlo .= " and refdate between '$pdte' and '$frdte'";
        			}else{
        				if ($i == 1){
        					$sqlo = "select sum(qtyin) from invthist ";
	        				$sqlo .= " where prodcode ='$procd' ";
	        				$sqlo .= " and reftype in ('OP', 'RC', 'TI', 'RN', 'AD')";
	        				$sqlo .= " and qtyin <> 0";
	        				$sqlo .= " and refdate <= '$lpdte'";
						}else{
        					$sqlo = "select sum(qtyin) from invthist ";
        					$sqlo .= " where prodcode ='$procd' ";
        					$sqlo .= " and reftype in ('OP', 'RC', 'TI', 'RN', 'AD')";
        					$sqlo .= " and qtyin <> 0";
        					$sqlo .= " and refdate between '$pdte' and '$lpdte'";
        				}
        			}
        			//echo $sqlo."<br>";
					$sql_resulto = mysql_query($sqlo);
        			$rowo = mysql_fetch_array($sql_resulto);
        			if ($rowo[0] == "" or $rowo[0] == null){ 
        		  		$rowo[0]  = 0.00;
        			}
        			$tqty = $rowo[0];
        			//echo $tqty."<br>";
        			
        			switch($i){
        			case 5:
		   				if (($bqty - $tqty) > 0){
            				$qty5 = $tqty;
           					$bqty = $bqty - $tqty;
          				}else{
            				$qty5 = $bqty;
            				$bqty = 0;
          				}
          				break;
        			case 4:
          				if (($bqty - $tqty) > 0){
            				$qty4 = $tqty;
            				$bqty = $bqty - $tqty;
          				}else{
            				$qty4 = $bqty;
            				$bqty = 0;
          				}
          				break;
        			case 3:
          				if (($bqty - $tqty) > 0){
            				$qty3 = $tqty;
            				$bqty = $bqty - $tqty;
          				}else{
            				$qty3 = $bqty;
            				$bqty = 0;
          				}
          				break;
					case 2:
          				if (($bqty - $tqty) > 0){
            				$qty2 = $tqty;
            				$bqty = $bqty - $tqty;
          				}else{
            				$qty2 = $bqty;
            				$bqty = 0;
          				}
          				break;
        			case 1;
          					$qty1 = $bqty;
          					$bqty = 0;
          					break;
      				}
      				
      				if ($bqty <= 0){ break;}
      				
  					$qyr = $qyr - 1;
  					
  					
				}
				#--------------------------------------------------------------------------- 
				//echo $qty5.' '.$qty4.' '.$qty3.' '.$qty2.' '.$qty1."<br>";
        		$balamt = $excost * $var_onhand;
        		$amt1   = $excost * $qty1;
        		$amt2   = $excost * $qty2; 
				$amt3   = $excost * $qty3; 
				$amt4   = $excost * $qty4; 
				$amt5   = $excost * $qty5; 
				$amt6   = $excost * $qty6; 

        		if ($qty1 == "" or $qty1 == null){$qty1 = 0;}
        		if ($amt1 == "" or $amt1 == null){$amt1 = 0;}
        		if ($qty2 == "" or $qty2 == null){$qty2 = 0;}
        		if ($amt2 == "" or $amt2 == null){$amt2 = 0;}
        		if ($qty3 == "" or $qty3 == null){$qty3 = 0;}
        		if ($amt3 == "" or $amt3 == null){$amt3 = 0;}
        		if ($qty4 == "" or $qty4 == null){$qty4 = 0;}
        		if ($amt4 == "" or $amt4 == null){$amt4 = 0;}
        		if ($qty5 == "" or $qty5 == null){$qty5 = 0;}
        		if ($amt5 == "" or $amt5 == null){$amt5 = 0;}
        		
				$sqli  = " Insert Into tmpagitab (owncd, owncddesc, prodcode, proddesc, avgcst, qtybal, amtbal, ";
        		$sqli .= "  qty1, amt1, qty2, amt2, qty3, amt3, qty4, amt4, qty5, amt5, usernm, pruom)";
        		$sqli .= " Values ('$ownccd', '$owndes', '$procd', '$itmdes', '$excost', '$var_onhand', '$balamt', ";
        		$sqli .= "   '$qty1', '$amt1', '$qty2', '$amt2', '$qty3','$amt3', '$qty4', '$amt4', '$qty5', '$amt5','$var_loginid', '$exuom')";
        		
        		mysql_query($sqli) or die("Unable Save In Temp Table 2 ".mysql_error());
			}
		}
     	#-----------------------------------------------------------------------------------
     
     	
      	$var_sql = " SELECT count(*) as cnt from tmpagitab ";
      	$query_id = mysql_query($var_sql) or die ("Cant Check Temp Table");
      	$res_id = mysql_fetch_object($query_id);

      	if ($res_id->cnt > 0 ) {

			// Redirect browser
        	$fname = "stkagi_rpt.rptdesign&__title=myReport"; 
        	$dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&fd=".$frdte."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        	$dest .= urlencode(realpath($fname));

        	//header("Location: $dest" );
        	echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
         	
         	$backloc = "../stk_rpt/stck_agi_rpt.php?menucd=".$var_menucode;
 			echo "<script>";
        	echo 'location.replace("'.$backloc.'")';
        	echo "</script>";

        }else{
        	echo "<script>";   
      		echo "alert('No Data Found!');"; 
      		echo "</script>";
        }
        	
     }
    } 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

	
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

	document.InpRawOpen.rptofdte.focus();
						
 	//Set up the date parsers
    var dateParser = new DateParser("dd-MM-yyyy");
      
	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "rptofdte");
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
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	if (x==null || x=="")
	{
		alert("Opening From Date Must Not Be Blank");
		document.InpRawOpen.rptofdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	
	var myDate = new Date();
	var then = myDate.getDate()+'-'+(myDate.getMonth()+1)+'-'+myDate.getFullYear(); 
	
    var fromdate = x.split('-');
        from_date = new Date();
        from_date.setFullYear(fromdate[2],fromdate[1]-1,fromdate[0]); 
    
    var todate = then.split('-');
        to_date = new Date();
        to_date.setFullYear(todate[2],todate[1]-1,todate[0]);
    if (from_date > to_date ) 
    {
       alert("As At Date Cannot Larger Than To Date");
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
	<fieldset name="Group1" style=" width: 560px; " class="style2">
	 <legend class="title">STOCK AGING REPORT (DETAIL REPORT)</legend>
	  <br>
	  <form name="InpRawOpen" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 527px;">
		<table style="width: 500px">
	  	  <tr>
	  	    <td style="width: 6px"></td>
	  	    <td style="width: 78px" class="tdlabel">As At Date</td>
	  	    <td style="width: 19px">:</td> 
	  	    <td style="width: 304px">
				<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer">
			</td>
		  </tr>
	  	  <tr>
	  	    <td style="width: 6px"></td> 
	  	    <td style="width: 78px" class="tdlabel">&nbsp;</td>
	  	    <td style="width: 19px"></td> 
            <td style="width: 304px"></td> 
	   	  </tr> 
	   	
	  	  <tr>
	  	   <td style="width: 6px">
	  	   <td style="width: 78px"></td>
	  	   <td></td>
	  	   <td style="width: 304px">
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
