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
    
      $var_ordno = $_GET['sorno'];
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");

    }
    
    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
        $pdordno = $_POST['sordno'];
        
        $fname = "sales_mas.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&ponum=".$pdponum."&menuc=".$var_menucode."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));

        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../sales/vm_salesentry.php?sorno=".$pdponum."&menucd=".$var_menucode;
       	echo "<script>";
       	echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 

     }
    } 
      
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">


<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";

.general-table #prococode                        { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #procoucost                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #prococompt                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}

.style2 {
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>


<!-- Our jQuery Script to make everything work -->
<script  type="text/javascript" src="jq-rtn-script.js"></script>


<script type="text/javascript"> 

</script>
</head>

<body >
	  <?php include("../topbarm.php"); ?> 
<!--  <?php include("../sidebarm.php"); ?> -->

  <?php
  	 $sql = "select * from salesreturn";
     $sql .= " where srtnno ='".$var_ordno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $custcd = $row['scustcd'];
     $rtndte = date('d-m-Y', strtotime($row['srtndte']));
     $srefno = htmlentities($row['srefno']);
     $remark = htmlentities($row['remark']);
     $type = $row['srtntype'];
     
     $sql2 = "select name from customer_master";
     $sql2 .= " where custno ='".$custcd ."'";
     $sql_result2 = mysql_query($sql2);
     $row2 = mysql_fetch_array($sql_result2);
     $name = $row2['name'];
     
     $sql3 = "select shiptype_desc from shiptype_master";
     $sql3 .= " where shiptype_code='".$type ."'";
     $sql_result3 = mysql_query($sql3);
     $row3 = mysql_fetch_array($sql_result3);
     $shiptype_desc= $row3['shiptype_desc'];

     
     
  ?> 
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">VIEW CUSTOMER SALES RETURN</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Return No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="srtnno" id="srtnnoid" type="text" readonly style="width: 204px;" value = "<?php echo $var_ordno; ?>">         
		   </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   &nbsp;</td>
	  	  </tr>    
    
	   	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Customer</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<input class="inputtxt" name="srtnno" id="srtnnoid" type="text" readonly style="width: 204px;" value = "<?php echo $custcd, ' - ', $name; ?>">
		   </td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Return Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="sartndte" id ="sartndte" type="text" style="width: 128px;" readonly value="<?php  echo $rtndte; ?>">
		   </td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px"><div id="msgcd"></div></td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Reference No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="srefno" id="srefnoid" type="text" readonly maxlength="45" style="width: 204px;" value="<?php echo $srefno; ?>" onchange ="upperCase(this.id)"></td>		   </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Type</td>
		   <td>:</td>
		   <td style="width: 284px">
		   	<input class="inputtxt" name="srtnno" id="srtnnoid" type="text" readonly style="width: 204px;" value = "<?php echo $type, ' | ', $shiptype_desc; ?>">
		   </td>
		  </tr> 
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Remark</td>
	  	   <td style="width: 13px">:</td>
	  	   <td colspan="5">
			<input class="inputtxt" name="saremark" id="saremarkid" type="text" readonly maxlength="100" style="width: 463px;" value="<?php echo $remark; ?>" onchange ="upperCase(this.id)"></td>
		     </td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px">
		   <td style="width: 10px"></td>
		   <td style="width: 204px"></td>
		   <td></td>
		   <td style="width: 284px">
	  	  </tr>	  	  
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 958px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader" style="width: 178px">Product Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader" style="width: 137px">Unit <br>Price(RM)</th>
              <th class="tabheader" style="width: 100px">Quantity</th>              
              <th class="tabheader" style="width: 242px">Amount</th>
             </tr>
            </thead>
            <tbody>
             <?php
             	$sql = "SELECT x.*, y.description FROM salesreturndet x, product y";
             	$sql .= " Where x.srtnno ='".$var_ordno."'";
              $sql .= " and y.productcode = x.sprocd";
	    		    $sql .= " ORDER BY x.sproseq";  
			  	    $rs_result = mysql_query($sql); 
			  
			    $i = 1;   $tamt = 0; $tqty = 0;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
            	$rowq['sproqty']  = number_format($rowq['sproqty'], 0, '', '');
                $sproamt = $rowq['sproqty'] * $rowq['sprounipri'];
                $tamt += $sproamt;
                $tqty += $rowq['sproqty'];

             		echo '<tr class="item-row">';
                	echo '<td style="width: 30px"><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>';
                	echo '<td style="width: 178px"><input name="procd[]" value="'.$rowq['sprocd'].'" tProCd1='.$i.' id="procd'.$i.'" class="autosearch" style="width: 175px" onchange ="upperCase(this.id)" ></td>';
                	echo '<td><input name="procdname[]" value="'.$rowq['description'].'" id="proconame'.$i.'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;"></td>';
                	echo '<td><input name="prouom[]" id="prouom'.$i.'" value="'.$rowq['sprouom'].'" readonly="readonly" style="width: 75px; border-style: none; border-color: inherit; border-width: 0;"></td>';
                	echo '<td style="width: 137px"><input name="prooupri[]" id="prooupri'.$i.'" value="'.$rowq['sprounipri'].'" style="width: 89px; text-align:right;"></td>';
                  echo '<td style="width: 100px"><input name="proorqty[]" value="'.$rowq['sproqty'].'" id="proordqty'.$i.'" onBlur="calcAmt('.$i.');" style="width: 97px; text-align:center;"></td>';
				        	echo '<td style="width: 242px"><input name="proouamt[]" id="proouamt'.$i.'" value="'.number_format($sproamt, 2, '.', ',').'" readonly="readonly" style="width: 116px; border-style: none; border-color: inherit;  text-align:right;"></td>';
             		echo '</tr>';
                    $i = $i + 1;
                }
               
                if ($i == 1){
                	$rowq['sproqty']  = number_format($rowq['sproqty'], 0, '', '');
                  $sproamt = $rowq['sproqty'] * $rowq['sprounipri'];
                  $tamt += $sproamt;
                  
                	echo '<tr class="item-row">';
                	echo '<td style="width: 30px"><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>';
                	echo '<td style="width: 178px"><input name="procd[]" value="'.$rowq['sprocd'].'" tProCd1='.$i.' id="procd'.$i.'" class="autosearch" style="width: 175px" onchange ="upperCase(this.id)" ></td>';
                	echo '<td><input name="procdname[]" value="'.$rowq['description'].'" id="proconame'.$i.'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;"></td>';
                	echo '<td><input name="prouom[]" id="prouom'.$i.'" value="'.$rowq['sprouom'].'" readonly="readonly" style="width: 75px; border-style: none; border-color: inherit; border-width: 0;"></td>';
                	echo '<td style="width: 137px"><input name="prooupri[]" id="prooupri'.$i.'" value="'.$rowq['sprounipri'].'" style="width: 89px; text-align:right;"></td>';
                  echo '<td style="width: 100px"><input name="proorqty[]" value="'.$rowq['sproqty'].'" id="proordqty'.$i.'" onBlur="calcAmt('.$i.');" style="width: 97px; text-align:center;"></td>';
		        		  echo '<td style="width: 242px"><input name="proouamt[]" id="proouamt'.$i.'" value="'.number_format($sproamt, 2, '.', ',').'" readonly="readonly" style="width: 116px; border-style: none; border-color: inherit;  text-align:right;"></td>';
             		echo '</tr>';
                    $i = $i + 1;

                }
             ?>
             </tbody>
           </table>
		  <table class="general-table" style="width: 950px">
          	 <tr>
              <td style="width: 842px; text-align:right" >Total :<input readonly="readonly" name="totqty" id ="totqtyid" type="text" style="width: 116px;" class="textnoentry1" value="<?php echo $tqty; ?>">
                 </td>              
              <td align="right">
              <input readonly="readonly" name="totamt" id ="totamtid" type="text" style="width: 116px;" class="textnoentry1" value="<?php echo number_format($tamt, 2, '.', ','); ?>">
              </td>
             </tr>
        </table>            
           
     <br /><br />
     
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_return_mas.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
          
           mysql_close ($db_link);  
				?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
	  	</table>
	   </form>	
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
