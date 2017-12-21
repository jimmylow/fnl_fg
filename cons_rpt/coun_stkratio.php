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
     
     	$salmth = $_POST['salmth'];
     	$salyr  = $_POST['salyr'];
		$selfpr = $_POST['selfpr'];
		$seltpr = $_POST['seltpr'];
     	$priopt = $_POST['priopt'];
		$rptopt = $_POST['rptopt'];
		$ratval = $_POST['ratval'];
		
		$salmth = str_pad($salmth, 2, '0', STR_PAD_LEFT);
		#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmp_ratiotab where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
		#-----------------------------------------------------------------------------------
		
		$shardSize = 6000;
	 	$sqliq = "";		   			
	 	$k = 0;
		$sqld  = "select y.sprocd, sum(y.soldqty), sum(y.endbal), sum(y.soldqty * y.sprounipri), ";
		$sqld .= "       sum(y.endbal * y.sprounipri)";	
		$sqld .= " from csalesmas x, csalesdet y";
		$sqld .= " where substr(smthyr, 1, 2) = '$salmth' and substr(smthyr, 4, 7) = '$salyr'";
		$sqld .= " and x.sordno = y.sordno";
		$sqld .= " and y.sprocd between '$selfpr' and '$seltpr'";
		$sqld .= " group by 1";
		$sqld .= " order by 1";
		$rs_n1 = mysql_query($sqld);
		while ($rn1 = mysql_fetch_assoc($rs_n1)) {
		    $sprocd  = $rn1['sprocd'];
			$soldqty = $rn1['sum(y.soldqty)'];
			$endbal  = $rn1['sum(y.endbal)'];
			$soldamt = $rn1['sum(y.soldqty * y.sprounipri)'];
			$endamt  = $rn1['sum(y.endbal * y.sprounipri)'];
			if (empty($soldamt)){$soldamt = 0;}
			if (empty($endamt)){$endamt = 0;}
			if (empty($soldqty)){$soldqty = 0;}
			if (empty($endbal)){$endbal = 0;}
			
			$rval = 0;
			if ($soldqty == 0){
				$rval = 0;
			}else{
				$rval = $endbal / $soldqty;
			}
			
			if ($rval >= $ratval){
				if ($k % $shardSize == 0) {
					if ($k != 0) {	  
						mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
					}
					$sqliq = 'Insert Into tmp_ratiotab values';
				}
				$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$sprocd', '$soldqty', '$endbal', '$rval', 
																	'$var_loginid', '$soldamt', '$endamt')";
				$k = $k + 1;
			}
		}	
		if (!empty($sqliq)){
			mysql_query($sqliq) or die ("Cant insert 2 : ".mysql_error());
		}
		#-----------------------------------------------------------------------------------

		$sqlm  = "select count(*)";
		$sqlm .= " from tmp_ratiotab";
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
			if ($rptopt == 'D'){
				$fname  = "conration_rpt.rptdesign&__title=myReport"; 
        	}else{
				$fname  = "conration_rpts.rptdesign&__title=myReport";
			}
			$fname .= "&m=".$salmth;
			$fname .= "&y=".$salyr;
			$fname .= "&fp=".$selfpr."&tp=".$seltpr;
			$fname .= "&pri=".$priopt;
			$fname .= "&r=".$ratval;
			$dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        	$dest .= urlencode(realpath($fname));

        	//header("Location: $dest" );
        	echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
       }
        
        $backloc = "../cons_rpt/coun_stkratio.php?stat=4&menucd=".$var_menucode;
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

	document.InpPurBal.salmth.focus();
							
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
	var fs = document.forms["InpPurBal"]["ratval"].value;
	if (fs==null || fs=="")
	{
		alert("Ratio Cannot Be Blank");
		document.InpPurBal.ratval.focus();
		return false;
	}

	if (fs != ""){
		if(isNaN(fs)) {
    	   alert('Please Enter a valid number for ratio :' + fs);
    	   fs = 1;
    	}
    	document.getElementById(ratval).value = parseFloat(fs).toFixed(1);
    }
}

function chk_deci(vid)
{
    var col1 = document.getElementById(vid).value;

	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Quantity :' + col1);
    	   col1 = 1;
    	}
    	document.getElementById(vid).value = parseFloat(col1).toFixed(1);
    }
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 700px; height:300px;">
	 <legend class="title">RATIO OF BALANCE STOCK OVER SALES</legend>
	  <br>
	  <form name="InpPurBal" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		<table style="width: 690px">
		  <tr>
		  	<td style="width: 5px"></td>
		  	<td style="width: 200px">Month</td>
		  	<td style="width: 5px">:</td>
		  	<td style="width: 200px">
		  		<?php 
					$curr_month = date("m"); 
					$month = array (1=>"1 | Jan", 2=>"2 | Feb", 3=>"3 | Mar",  4=>"4 | Apr",  5=>"5 | May",  6=>"6 | Jun", 
							        7=>"7 | Jul", 8=>"8 | Aug", 9=>"9 | Sep", 10=>"10 | Oct", 11=>"11 | Nov", 12=>"12 | Dec"); 
					$select = "<select name=\"salmth\" id=\"salmth\">\n"; 
					foreach ($month as $key => $val) { 
	    				$select .= "\t<option value=\"".$key."\""; 
						if ($key == $curr_month) { 
						    $select .= " selected>".$val."\n"; 
						} else { 
						    $select .= ">".$val."\n"; 
						} 
					} 
					$select .= "</select>"; 
					echo $select; 
				?>
		  	</td>
			<td></td>
			<td></td>
			<td></td>
			
		  </tr>
		  <tr><td></td></tr>
		  <tr>
		  	<td></td>
		  	<td>Year</td>
		  	<td>:</td>
		  	<td>
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
			</tr>
			<tr><td></td></tr>
			<tr>
				<td></td>
				<td class="tdlabel">Balance Ration More Than</td>
				<td>:</td> 
				<td>
				<input class="inputtxt" name="ratval" id ="ratval" type="text" style="width: 40px; text-align:right;" value='1.0' onblur='chk_deci(this.id)'>
				times
				</td>	
			</tr>
			<tr><td></td></tr>
		    <tr>
		  	<td></td>
		  	<td>From Group Code</td>
		  	<td>:</td>
		  	<td>
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
		  <tr><td></td></tr>
	  	  <tr>
	  	    <td></td>
	  	    <td class="tdlabel">Price Option</td>
	  	    <td>:</td> 
	  	    <td>
				<select id='priopt' name='priopt'>
					<option value='S'>Selling Price</option>
					<option value='C'>Cost Price</option>
				</select>
			</td>	
	  	  </tr>
		  <tr><td></td></tr>
	  	  <tr>
	  	    <td></td>
	  	    <td class="tdlabel">Report Option</td>
	  	    <td>:</td> 
	  	    <td>
				<select id='rptopt' name='rptopt'>
					<option value='D'>Detail</option>
					<option value='S'>Summary</option>
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
