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
     
     	$salyr  = $_POST['salyr'];
		$selfct = $_POST['selfct'];
		$seltct = $_POST['seltct'];
     	$selfty = $_POST['selfty'];
		$seltty = $_POST['seltty'];
		$fsu    = $_POST['selfsup'];
     	$tsu    = $_POST['seltsup'];
		$selopt = $_POST['selopt'];
		
		#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmpsalesper where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
		#-----------------------------------------------------------------------------------
		
		$shardSize = 6000;
	 	$sqliq = "";		   			
	 	$k = 0;
		$sqld  = "select distinct x.custcd";
		$sqld .= " from cinvoicemas x, customer_master y";
		$sqld .= " where substr(x.mthyr, 4, 7) = '$salyr'";
		$sqld .= " and   x.custcd between '$selfct' and '$seltct'";
		$sqld .= " and   x.custcd = y.CustNo";
		$sqld .= " and   x.stat = 'A'";
		$sqld .= " and   y.supervisor_code between '$fsu' and '$tsu'";
		$rs_n1 = mysql_query($sqld);
		while ($rn1 = mysql_fetch_assoc($rs_n1)){ 
			$custcd = $rn1['custcd'];
	
			$sql2  = "select distinct salestype_code";
			$sql2 .= " from salestype_master";
			$sql2 .= " where salestype_code between '$selfty' and '$seltty'";
			$rs_n2 = mysql_query($sql2);
			while ($rn2 = mysql_fetch_assoc($rs_n2)){ 
				$salestyp = $rn2['salestype_code'];
				
				$gtotal = 0;
				$mthvar = 1;
				for ($i = 1; $i < 13; $i++){
					$mthvar = str_pad($mthvar, 2, '0', STR_PAD_LEFT);
					if 	($selopt == 'G'){
						
						$sql3  = "select sum(x.sprounipri * x.soldqty) from cinvoicedet1 x, cinvoicemas y";
						$sql3 .= " where  x.invno  = y.invno     and substr(y.mthyr, 4, 7) = '$salyr'";
						$sql3 .= " and    y.custcd = '$custcd'   and substr(y.mthyr, 1, 2) = '$mthvar'"; 
						$sql3 .= " and    x.sptype = '$salestyp' and y.stat = 'A'";
						$sql_r3 = mysql_query($sql3);
						$ro3 = mysql_fetch_array($sql_r3);
						$gamt[$i] = $ro3['0'];

					}else{

						$grossamt = 0;
						$lesstype = "";
						$lessrate = 0;
						$lessamt  = 0;
						$sql3  = "select sum(x.sprounipri * x.soldqty), y.less_type, z.rate";
						$sql3 .= " from cinvoicedet1 x, cinvoicemas y, cinvoicedet2 z";
						$sql3 .= " where  x.invno  = y.invno     and substr(y.mthyr, 4, 7) = '$salyr'";
						$sql3 .= " and    y.custcd = '$custcd'   and substr(y.mthyr, 1, 2) = '$mthvar'"; 
						$sql3 .= " and    x.sptype = '$salestyp' and y.stat = 'A'";
						$sql3 .= " and    y.invno  = z.invno     and x.invno = z.invno";
						$sql3 .= " and    x.sptype = z.sptype";
						$sql3 .= " group by 2, 3";
						$sql_r3 = mysql_query($sql3);
						$ro3 = mysql_fetch_array($sql_r3);
						$grossamt = $ro3['0'];
						$lesstype = $ro3['1'];
						$lessrate = $ro3['2'];
						
						if ($lesstype == '3'){
							$lessamt = $grossamt * $lessrate / 100;
							
							$mlamt = 0;
							$mlcnt = 0;
							$proamt = 0;
							$sql4  = "select y.less_amt, count(distinct x.sptype)";
							$sql4 .= " from cinvoicedet1 x, cinvoicemas y";
							$sql4 .= " where  x.invno  = y.invno     and substr(y.mthyr, 4, 7) = '$salyr'";
							$sql4 .= " and    y.custcd = '$custcd'   and substr(y.mthyr, 1, 2) = '$mthvar'";
							$sql4 .= " and    y.stat = 'A'";
							$sql_r4 = mysql_query($sql4);
							$ro4 = mysql_fetch_array($sql_r4);
							$mlamt = $ro4['0'];
							$mlcnt = $ro4['1'];
							if (empty($mlamt)){$mlamt = 0;}
							if (empty($mlcnt)){$mlcnt = 0;}
							if ($mlcnt == 0){
								$proamt = $mlamt;
							}else{	
								$proamt = $mlamt / $mlcnt;
							}
							if (empty($proamt)){$proamt = 0;}
							$gamt[$i] = $grossamt - $lessamt - $proamt;

						}else{
							$lessamt = $grossamt * $lessrate / 100;
							$gamt[$i] = $grossamt - $lessamt;
						}
					}
					if (empty($gamt[$i])){$gamt[$i] = 0;}
					$gtotal = $gtotal + $gamt[$i];
					$mthvar = $mthvar + 1;
				}
				for ($i = 1; $i < 13; $i++){
					if (empty($gamt[$i])){$gamt[$i] = 0;}
				}

				if ($k % $shardSize == 0) {
					if ($k != 0) {	
						
						mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
					}
					$sqliq = 'Insert Into tmpsalesper values ';
				}
				$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$var_loginid', '$salestyp', '$custcd', '$salyr', 
																   '$gamt[1]', '$gamt[2]', '$gamt[3]',
																   '$gamt[4]', '$gamt[5]', '$gamt[6]',
																   '$gamt[7]', '$gamt[8]', '$gamt[9]',
																   '$gamt[10]', '$gamt[11]', '$gamt[12]', 
																   '$gtotal')";
				$k = $k + 1;
			}	
		}	
		if (!empty($sqliq)){
			mysql_query($sqliq) or die ("Cant insert 2 : ".mysql_error());
		}
		#-----------------------------------------------------------------------------------
		
		// Redirect browser
		$fname  = "salesper_rpt.rptdesign&__title=myReport";
		$fname .= "&y=".$salyr;
		$fname .= "&fc=".$selfct."&tc=".$seltct;
		$fname .= "&fst=".$selfty."&tst=".$seltty;
		$fname .= "&so=".$selopt;
		$fname .= "&fsu=".$fsu."&tsu=".$tsu;
		$dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));

        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../cons_rpt/mth_salerpt.php?menucd=".$var_menucode;
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
	  	    <td class="tdlabel">From Sales Type</td>
	  	    <td>:</td> 
	  	    <td>
				<select name="selfty" id="selfty" style="width: 250px">
			    <?php
                   $sql = "select salestype_code, salestype_desc from salestype_master ORDER BY salestype_code";
                   $sql_result = mysql_query($sql) or die("Not Enable To Query".mysql_error());
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['salestype_code'].'">'.$row['salestype_code'].' | '.$row['salestype_desc'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
			</td>
			<td></td>
	  	    <td class="tdlabel">To Sales Type</td>
	  	    <td>:</td> 
	  	    <td>
				<select name="seltty" id="seltty" style="width: 250px">
			    <?php
                   $sql = "select salestype_code, salestype_desc from salestype_master ORDER BY salestype_code";
                   $sql_result = mysql_query($sql) or die("Enable To Query".mysql_error());
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					   echo '<option value="'.$row['salestype_code'].'">'.$row['salestype_code'].' | '.$row['salestype_desc'].'</option>';
					 } 
				   }
	            ?>				   
			  </select>
			</td>		
	  	  </tr>
		  <tr><td></td></tr>
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
			 <tr><td></td></tr>
	  	  <tr>
	  	    <td></td>
	  	    <td class="tdlabel">Sales Option</td>
	  	    <td>:</td> 
	  	    <td>
				<select name="selopt" id="selopt" style="width: 250px">
					<option value = 'G'>Gross Sales</option>
					<option value = 'N'>Net Sales</option>
			  </select>
			</td>
			<td></td>
	  	    <td></td>
	  	    <td></td> 
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
