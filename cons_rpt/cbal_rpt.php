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
		$syr = $_POST['salyr'];
		$tpo = $_POST['spopt'];
		
		#----------------------------------------------------
		$sql  = "CREATE TEMPORARY TABLE tmp2salesmas(";
	    $sql .= "sordno varchar(30), sorddte datetime, scustcd varchar(10), smthyr varchar(7), speriod varchar(1), ";
		$sql .= "less_type varchar(1), less_amt decimal(10,2), stat varchar(1), salesdte date)";
		mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
		$sql  = " Delete From tmp2salesmas";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error);	
        $sql  = " Delete From tmp_cbal where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Delete Table tmp_cbal ".mysql_error);	
		
		$shardSize = 7000;
	 	$sqliq = "";		   			
	 	$k = 0;
		$sqld  = "select *";
        $sqld .= " from csalesmas where stat != 'C'";
        $sqld .= " and scustcd between '$fco' and '$tco'";
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
	
		#-------------------------------Current Date Transaction----------------------------------------
		$shardSize = 8000;
	 	$sqliq = "";		   			
	 	$k = 0; 
	 		 	$t = 1;   
		$sql2  = "select y.scustcd, x.sprocd, sum(x.openingqty), sum(x.doqty), sum(x.soldqty), sum(x.rtnqty), sum(x.shortqty),";
		$sql2 .= "       sum(x.overqty), sum(x.adjqty), x.sprounipri, y.salesdte";
		$sql2 .= " from csalesdet x, tmp2salesmas y";
		$sql2 .= " where year(y.salesdte) = '$syr' and x.sordno = y.sordno";
		$sql2 .= " group by 1, 2, 10, 11";
		$sql2 .= " order by  1, 2";
		$rs_n = mysql_query($sql2);
		while ($rn = mysql_fetch_assoc($rs_n)) {
			$custcd   = $rn['scustcd'];
			$prcd     = $rn['sprocd'];
			$opqty    = $rn['sum(x.openingqty)'];
			$doqty    = $rn['sum(x.doqty)'];
			$soldqty  = $rn['sum(x.soldqty)'];
			$rtnqty   = $rn['sum(x.rtnqty)'];
			$shortqty = $rn['sum(x.shortqty)'];
			$overqty  = $rn['sum(x.overqty)'];
			$adjqty   = $rn['sum(x.adjqty)'];
			$selpri   = $rn['sprounipri'];
			$salesdte = $rn['salesdte'];
			if (empty($opqty)){$opqty = 0;}
			if (empty($doqty)){$doqty = 0;}
			if (empty($soldqty)){$soldqty = 0;}
			if (empty($rtnqty)){$rtnqty = 0;}
			if (empty($shortqty)){$shortqty = 0;}
			if (empty($overqty)){$overqty = 0;}
			if (empty($adjqty)){$adjqty = 0;}
			if (empty($selpri)){$selpri = 0;}
			$year = '';
			$month = '';
			list($year, $month, $date) = explode("-", $salesdte);

			if ($tpo == 'B'){
				$sqlf = "select MAX(ExFacPrice) from product";
				$sqlf .= " where GroupCode = '$prcd' and Status != 'D'";
				$s_rf = mysql_query($sqlf);
				$rf = mysql_fetch_array($s_rf);
				$costpri  = $rf['MAX(ExFacPrice)'];
			}			
			if (empty($costpri)){$costpri = 0;}
			
			if ($tpo == 'A'){
				$priins = $selpri; 
			}else{
				$priins = $costpri;
			}   			
	
       		if ($k % $shardSize == 0) {
       			if ($k != 0) {	 
           			mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
       			}
       			$t = 1;
       			$sqliq = 'Insert Into tmp_cbal (usernm, smth, syr, custno, prodcd, openqty, doqty, soldqty, rtnqty,   
       											shortqty, overqty, adjqty, selpri, cnt) values ';
    			}
   				$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$var_loginid', '$month', '$year', '$custcd', '$prcd', '$opqty', '$doqty', '$soldqty', '$rtnqty', 
   																   '$shortqty', '$overqty', '$adjqty', '$priins', '$t')";
		 		$k = $k + 1;
		 		$t = $t + 1;
			}	
			if (!empty($sqliq)){
				mysql_query($sqliq) or die ("Cant insert 2 : ".mysql_error());
			}
			#-------------------------------Current Date Transaction----------------------------------------
				
		// Redirect browser
		$fname  = "consbalrpt.rptdesign&__title=myReport";
       	$fname .= "&usernm=".$var_loginid;
       	$fname .= "&fc=".$fco."&tc=".$tco;
       	$fname .= "&y=".$syr;
       	$fname .= "&price=".$tpo;
       	$fname .= "&dbsel=".$varrpturldb; 
       	$dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname;
       	$dest .= urlencode(realpath($fname));
       	//header("Location: $dest" );
       	echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        
        $backloc = "../cons_rpt/cbal_rpt.php?stat=4&menucd=".$var_menucode;
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
	<fieldset name="Group1" style=" width: 798px; height:200px;">
	 <legend class="title">BALANCING REPORT</legend>
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
		  	<td style="width: 128px">Year</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="salyr" style="width:60px"  class="month">
				<?php
					$curr_year = date("Y");
					$fryr = date("Y");
					$fryr = $curr_year - 10;
					$toyr = $curr_year + 3;
					
					for ($i = $fryr; $i <= $toyr; $i++ ){
						if ($i == $curr_year){
							echo '<option selected value='.$i.'>'.$i.'</option>';
						}else{
							echo '<option value='.$i.'>'.$i.'</option>';
						}
					}
					?>
				</select>
		  	</td>
		  	<td></td>
		  	<td></td>
		  	<td></td>
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
			  	</select>
		  	</td>
		  	<td></td>
		  </tr>

		  <tr><td style="width: 15px"></td></tr>
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
