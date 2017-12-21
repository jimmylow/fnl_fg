<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];

    if($var_loginid == "") { 
         echo "<script>";   
         echo "alert('Not Log In to the system');"; 
         echo "</script>"; 

         echo "<script>";
         echo 'top.location.href = "./index.html"';
         echo "</script>";
    }else{
 		 $frpcd  = $_GET['fp'];
 		 $topcd = $_GET['tp'];
 		 $menucd = $_GET['menuc'];
    }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Product Price List</title>
<style type="text/css">
<!--
@page { 
		size: A4;
		margin-left:0.25in;
		margin-right:0.25in;
		margin-top:0.25in;
  		margin-bottom:0.25in;
  		font-size:10px;
  		font-family:"Times New Roman", Times, serif;
}
-->

table { margin: 1em; }
th    { padding: .1em; border: 1px #ccc solid; }

td.h    { 
	border-bottom: 1px #ccc solid;
	margin:0;
 	padding: 5px;
}

@media print 
{
    .noPrint 
    {
        display:none;
    }
}

</style>    
<script type="text/javascript">
function confirmPrint()
{
   i = confirm("Do you want to print this Report?");		
   if(i)
	{
	  window.print();		  
	  setTimeout("window.close()",5000);
	}
}	  
</script>
</head>
<?php
	$sqlcd  = "select apphea_txt from apphea_set";
    $sql_resultcd = mysql_query($sqlcd) or die("Can't query Temp Table 1:".mysql_error());
    $rowcd = mysql_fetch_array($sql_resultcd);
    $compname = $rowcd['apphea_txt'];
?>
<body >
	<div style="float: left;" id="print">
<input id="print-bnt" class ="noPrint" type="button" value="Print" onclick="confirmPrint()" style="width: 60px; height: 32px; background-color: #FFCC33; font-weight: bold"> </div>
<!-- ########################### Start Body ############################### -->
	<br />
	<table width="900" cellspacing="0%" cellpadding="0%">
		<tr>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
			<td></td>
			<td colspan="4" align="center"><h3><?php echo $compname; ?></h3></td>
			<td></td>
			<td style="width: 100px"></td>
			<td style="width: 100px"></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td colspan="4" align="center"><h3>PRODUCT PRICE LIST</h3></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table>
	<table width="900" cellspacing="0%" cellpadding="1%">
		<thead>
			<tr style="border:2px">
			<th class="tab1" style="width: 20px"></th>
			<th class="tab1" style="width: 200px"></th>		
			<?php
				$sql1  = "SELECT distinct price_code, price_desc from price_master ";
    	    	$sql1 .= " order by price_code";
				$rs_result1 = mysql_query($sql1) or die("Can't query Temp Table 3: ".mysql_error());		
				while ($row1 = mysql_fetch_assoc($rs_result1)){
					echo '<th class="tab1" style="width: 5%;">'.$row1['price_code']."</th>";
				}
			?>
			</tr>

			<tr style="border:2px">
			<th class="tab1">No</th>
			<th class="tab1">Product Code</th>		
			<?php
				unset($sizarr);
				$sql1  = "SELECT distinct price_code, price_desc from price_master ";
    	    	$sql1 .= " order by price_code";
				$rs_result1 = mysql_query($sql1) or die("Can't query Temp Table 3: ".mysql_error());		
				$i = 0;
				while ($row1 = mysql_fetch_assoc($rs_result1)){
					$sizpcd[$i] = $row1['price_code'];
					$sizpde[$i] = $row1['price_desc'];
					echo '<th class="tab1" style="width: 7%;">'.$row1['price_desc']."</th>";
					$i = $i + 1;
				}
			?>
			</tr>
		</thead>	
		<tbody>
			<?php
				$i = 1;
				$lgarrsize = sizeof($sizpcd) - 1;
				$sqlq  = "SELECT distinct ProductCode from product ";
       			$sqlq .= " where ProductCode between '$frpcd' and '$topcd'";
       			$sqlq .= " order by ProductCode";
	   			$rs_resultq = mysql_query($sqlq) or die("Can't query Temp Table color: ".mysql_error()); 
	    		while ($rowq = mysql_fetch_assoc($rs_resultq)){
					$procd = $rowq['ProductCode'];			 	      	
	
					echo "<tr>";
					echo "<td class='h'>".$i."</td>";
					echo "<td class='h'>".$procd."</td>";
					
					for ($j = 0; $j <= $lgarrsize; $j++) {
						$sqlcd  = "select uprice from prodprice where productcode = '$procd' and pricecode = '$sizpcd[$j]'";
					    $sql_resultcd = mysql_query($sqlcd) or die("Can't query Temp Table 1:".mysql_error());
					    $rowcd = mysql_fetch_array($sql_resultcd);
						$upri = $rowcd['uprice'];
						if (empty($upri)){$upri = 0;}
	
						echo "<td class='h' align='right'>".$upri."</td>";
					}
					echo "</tr>";
					$i = $i + 1;
				}
			?>
		</tbody>
		<tfoot>
		</tfoot>
		</table>
		<p style="page-break-before: always">
<!-- ############################# End Body ######################## -->
</body>
<script type="text/javascript" language="JavaScript1.2">confirmPrint()</script>
</html>
