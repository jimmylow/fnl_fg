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
     
     	$salyr   = $_POST['salyr'];
		$selfct  = $_POST['selfct'];
		$seltct  = $_POST['seltct'];
     	$selfsup = $_POST['selfsup'];
		$seltsup = $_POST['seltsup'];
		$selfmar = $_POST['selfmar'];
		$seltmar = $_POST['seltmar'];
		
		#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmpyrcomm where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
		$sql  = " Delete From tmpyrcomm1 where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
		#-----------------------------------------------------------------------------------
		
		$shardSize = 6000;
	 	$sqliq = "";		   			
	 	$k = 0;
		$sqld  = "select x.invno, x.custcd, substr(x.mthyr, 1, 2), substr(x.mthyr, 4, 7), y.supervisor_code, z.mkt_code,";
		$sqld .= "       k.salesamt, k.comm";
		$sqld .= " from cinvoicemas x, customer_master y, supervisor_master z, marketing_master k";
		$sqld .= " where substr(x.mthyr, 4, 7) = '$salyr'";
		$sqld .= " and x.custcd between '$selfct' and '$seltct'";
		$sqld .= " and x.custcd = y.CustNo";
		$sqld .= " and y.supervisor_code = z.supervisor_code";
		$sqld .= " and y.supervisor_code between '$selfsup' and '$seltsup'";
		$sqld .= " and z.mkt_code between '$selfmar' and '$seltmar'";
		$sqld .= " and z.mkt_code = k.mkt_code";
		$sqld .= " and x.stat = 'A'";
		$sqld .= " order by 2";
		$rs_n1 = mysql_query($sqld);
		while ($rn1 = mysql_fetch_assoc($rs_n1)){ 
		    $invno  = $rn1['invno'];
			$custcd = $rn1['custcd'];
			$smth   = $rn1['substr(x.mthyr, 1, 2)'];
			$syr    = $rn1['substr(x.mthyr, 4, 7)'];
			$ssuper = $rn1['supervisor_code'];
			$smktcd = $rn1['mkt_code'];
			$starsl = $rn1['salesamt'];
			$comm   = $rn1['comm'];

			#-------------------------------------------------
			$gamt = 0;
			$sql2 = " select sum(sprounipri * soldqty) from cinvoicedet1";
			$sql2 .= " where invno = '$invno'";
			$sql_resultc = mysql_query($sql2);
        	$rowc = mysql_fetch_array($sql_resultc);
        	$gamt = $rowc['0'];
			#-------------------------------------------------
			if (empty($gamt)){$gamt = 0;}
			
			if ($k % $shardSize == 0) {
				if ($k != 0) {	
					//echo $sqliq;
					mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
				}
				$sqliq = 'Insert Into tmpyrcomm values ';
			}
			$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$var_loginid', '$custcd', '$syr', '$smth', 
															   '$gamt', '$ssuper', '$smktcd', '$invno', '$starsl',
															    '$comm')";
			$k = $k + 1;

		}	
		if (!empty($sqliq)){
			mysql_query($sqliq) or die ("Cant insert 2 : ".mysql_error());
		}
		#-----------------------------------------------------------------------------------
		
		#-----------------------------------------------------------------------------------
		$shardSize = 6000;
	 	$sqliq = "";		   			
	 	$k = 0;
		$sql2  = "select distinct mktcode, taramt, commper, mthsales, yrsales";
		$sql2 .= " from tmpyrcomm";
		$sql2 .= " where usernm = '$var_loginid'";
		$sql2 .= " order by 5,4";
		$rs_n2 = mysql_query($sql2);
		while ($rn2 = mysql_fetch_assoc($rs_n2)){
			$mktcode  = $rn2['mktcode'];
			$taramt   = $rn2['taramt'];
			$commper  = $rn2['commper'];
			$mthsales = $rn2['mthsales'];
			$yrsales  = $rn2['yrsales'];

			//echo $mktcode.' '.$taramt.' '.$commper.' '.$mthsales.' '.$yrsales."<br>";
			$sumsales = 0;
			$sumbsales = 0;
			$comm = 0;
			$aftamt = 0;
			$sql3  = "select sum(gtotal), ctkcd";
			$sql3 .= " from tmpyrcomm";
			$sql3 .= " where mthsales = '$mthsales'  and yrsales = '$yrsales'";
			$sql3 .= " and   usernm = '$var_loginid' and mktcode = '$mktcode'";
			$sql3 .= " group by 2";
			$rs_n3 = mysql_query($sql3);
			while ($rn3 = mysql_fetch_assoc($rs_n3)){
				$salesamt = $rn3['sum(gtotal)'];
				$ctkcd    = $rn3['ctkcd'];	
				#echo $mktcode.' '.$taramt.' '.$commper.' '.$mthsales.' '.$yrsales.' '.$salesamt.' '.$ctkcd."<br>";
		
				if ($salesamt < $taramt){
					$sumbsales = $sumbsales + $salesamt;
				}
				$sumsales = $sumsales + $salesamt;
			}
			if (empty($sumsales)){$sumsales = 0;}
			if (empty($sumbsales)){$sumbsales = 0;}
			$aftamt = $sumsales - $sumbsales;
			$comm = ($aftamt * ($commper / 100));

			
			if ($k % $shardSize == 0) {
				if ($k != 0) {	
					mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
				}
				$sqliq = 'Insert Into tmpyrcomm1 values ';
			}
			$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$var_loginid', '$mktcode', '$mthsales', '$yrsales', 
															   '$sumsales', '$sumbsales', '$comm')";
			$k = $k + 1;
		}
		if (!empty($sqliq)){
			mysql_query($sqliq) or die ("Cant insert 2 : ".mysql_error());
		}	
		#-----------------------------------------------------------------------------------

		// Redirect browser
		$fname  = "yrcommrpt1.rptdesign&__title=myReport";
		$fname .= "&y=".$salyr;
		$fname .= "&fc=".$selfct."&tc=".$seltct;
		$fname .= "&fs=".$selfsup."&ts=".$seltsup;
		$fname .= "&fm=".$selfmar."&tm=".$seltmar;
		$dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));

        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
       $backloc = "../cons_rpt/yr_commrpt.php?stat=4&menucd=".$var_menucode;
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

	document.InpPurBal.salyr.focus();
							
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
	
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 900px; height:270px;">
	 <legend class="title">YEARLY MARKETING COMMISSION REPORT</legend>
	  <br>
	  <form name="InpPurBal" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		<table style="width: 900px">
		  <tr>
		  	<td style="width: 5px"></td>
		  	<td style="width: 150px">Year</td>
		  	<td style="width: 5px">:</td>
		  	<td style="width: 200px">
		  		<select name="salyr" style="width:60px"  class="month">
				<?php
					$curr_year = date("Y");
					$fryr = date("Y");
					$fryr = $curr_year - 10;
					$toyr = $curr_year + 10;
					
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
			
		  </tr>
		  <tr><td></td></tr>
		    <tr>
		  	<td></td>
		  	<td>From Counter</td>
		  	<td>:</td>
		  	<td>
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
		  	<td></td>
		  	<td>To Counter</td>
		  	<td>:</td>
		  	<td>
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
		  <tr><td></td></tr>
	  	  <tr>
	  	    <td></td>
	  	    <td class="tdlabel">From Supervisor</td>
	  	    <td>:</td> 
	  	    <td>
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
	  	    <td class="tdlabel">To Supervisor</td>
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
		  <tr><td></td></tr>
	  	  <tr>
	  	    <td></td>
	  	    <td class="tdlabel">From Marketing Code</td>
	  	    <td>:</td> 
	  	    <td>
				<select name="selfmar" id="selfmar" style="width: 250px">
			    <?php
                   $sql = "select mkt_code, mkt_name from marketing_master ORDER BY mkt_code";
                   $sql_result = mysql_query($sql) or die("Not Enable To Query".mysql_error());
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['mkt_code'].'">'.$row['mkt_code'].' | '.$row['mkt_name'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
			</td>
			<td></td>
	  	    <td class="tdlabel">To Marketing Code</td>
	  	    <td>:</td> 
	  	    <td>
				<select name="seltmar" id="seltmar" style="width: 250px">
			    <?php
                   $sql = "select mkt_code, mkt_name from marketing_master ORDER BY mkt_code";
                   $sql_result = mysql_query($sql) or die("Not Enable To Query".mysql_error());
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['mkt_code'].'">'.$row['mkt_code'].' | '.$row['mkt_name'].'</option>';
				 	 } 
				   }
	            ?>				   
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
	  	  <tr><td>&nbsp;</td></tr>	
	  	</table>
	   </form>	
	</fieldset>
	 </div>

</body>

</html>
