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
      $var_ordno = $_GET['sorno'];
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


<script type="text/javascript"> 

</script>
</head>
 <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->
<body>
 
  <?php
  	 $sql = "select * from csalesmas";
     $sql .= " where sordno ='".$var_ordno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $custcd = $row['scustcd'];
     $orddte = date('d-m-Y', strtotime($row['sorddte']));
     $mthyr = $row['smthyr'];
     $period = $row['speriod'];
     $lessamt = $row['less_amt'];
     $lesstype = $row['less_type'];
          
  ?> 
   
  <div class="contentc">
     <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">VIEW COUNTER SALES</legend>
	  <br>	 
	  	   
		<table style="width: 993px; " border = "0">
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Order No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sordno" id="sordnoid" type="text" readonly style="width: 204px;" value = "<?php echo $var_ordno; ?>">         
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
		   	<select name="sacustcd" id="sacustcd" style="width: 268px">
			 <?php
              $sql = "select custno, name from customer_master";
              $sql .= " where type = 'C'";
              $sql .= " ORDER BY custno ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['custno'].'"';
        if ($custcd == $row['custno']) { echo "selected"; }
        echo '>'.$row['custno']." | ".$row['name'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

		   </td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Order Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="saorddte" type="text" style="width: 128px;" value="<?php  echo $orddte; ?>" readonly>
		   </td>
	  	  </tr>  
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">MM/YYYY</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="samthyr" id="samthyrid" type="text" maxlength="45" style="width: 100px;" value="<?php echo $mthyr; ?>" readonly></td>		   
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Period</td>
		   <td>:</td>
		   <td style="width: 284px">
		   	<select name="speriod" id="speriodcd" >
			 <?php
				echo '<option value="1"';
        if ($period == '1') { echo "selected"; }
        echo '>1 | FIRST HALF MONTH</option>';
        
				echo '<option value="2"';
        if ($period == '2') { echo "selected"; }
        echo '>2 | SECOND HALF MONTH</option>';        
 
	         ?>				   
	       </select>

		   </td>
		  </tr> 
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Less</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<select name="lesstype" id="lesstypecd" >
			 <?php
				echo '<option value="1"';
        if ($lesstype == '1') { echo "selected"; }
        echo '>NO</option>';
        
				echo '<option value="2"';
        if ($lesstype == '2') { echo "selected"; }
        echo '>%</option>'; 
        
				echo '<option value="3"';
        if ($lesstype == '3') { echo "selected"; }
        echo '>AMT</option>';                
                    
	         ?>				   
	       </select>          
			<input class="inputtxt" name="lessamt" id="lessamtid" type="text" maxlength="45" style="width: 50px;text-align : right" value="<?php echo $lessamt; ?>" readonly></td>		   
		     </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   </td>
	  	  </tr> 
	
	  	  </table>
		 
		  <br><br>
			  <table id="itemsTable" class="general-table" style="width: 958px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Product Code</th>
              <th class="tabheader">Unit Price</th>
              <th class="tabheader">Type</th>
              <th class="tabheader">D/O Qty</th>              
              <th class="tabheader">Sold Qty</th>
              <th class="tabheader">Sales Amt</th>
              <th class="tabheader">Rtn Qty</th>
              <th class="tabheader">Short Qty</th>
              <th class="tabheader">Over Qty</th> 
              <th class="tabheader">Adj Qty</th>
              <th class="tabheader">End Balance</th>                            
             </tr>
            </thead>
            <tbody>
             <?php
             	$sql = "SELECT * FROM csalesdet";
             	$sql .= " Where sordno ='".$var_ordno."'"; 
	    		    $sql .= " ORDER BY sproseq";  
			  	    $rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
              
              $var_salesamt = 0;
              $var_salesamt = number_format($rowq['soldqty'] * $rowq['sprounipri'], 2, '.', ',');  
                                          
             ?>            
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" readonly="readonly" style="width: 27px; border:0;" value ="<?php echo $i; ?>"></td>
                <td>
				<input name="prococode[]" tProItem1=1 id="prococode<?php echo $i; ?>" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['sprocd']); ?>"></td>
                <td>
				<input name="procoupri[]" id="procoupri<?php echo $i; ?>" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 60px" value ="<?php echo $rowq['sprounipri']; ?>"></td>
                <td>
        <input name="procotype[]" class="tInput" id="procotype<?php echo $i; ?>" style="border-style: none; width: 48px;" value ="<?php echo $rowq['sptype']; ?>" readonly="readonly"></td>                
                <td>
				<input name="procodoqty[]" id="procodoqty<?php echo $i; ?>" style="border-style: none; width: 48px; text-align : right" value ="<?php echo $rowq['doqty']; ?>" readonly></td>
                <td>
        <input name="procosoldqty[]" class="tInput" id="procosoldqty<?php echo $i; ?>" style="width: 48px; text-align : right"  value ="<?php echo $rowq['soldqty']; ?>" readonly></td>                
                <td>
        <input name="procosamt[]" class="tInput" id="procosamt<?php echo $i; ?>" style="border-style: none; width: 48px; text-align : right" readonly="readonly" value ="<?php echo $var_salesamt; ?>"></td>                
                <td>
        <input name="procortnqty[]" class="tInput" id="procortnqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['rtnqty']; ?>" readonly></td>                
                <td>
        <input name="procoshortqty[]" class="tInput" id="procoshortqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['shortqty']; ?>" readonly></td>                
                <td>
        <input name="procooverqty[]" class="tInput" id="procooverqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['overqty']; ?>" readonly></td>                
                <td>
        <input name="procoadjqty[]" class="tInput" id="procoadjqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['adjqty']; ?>" readonly></td>                
                <td>
        <input name="procobalqty[]" class="tInput" id="procobalqty<?php echo $i; ?>" style="border-style: none; width: 48px; text-align : right" value ="<?php echo $rowq['endbal']; ?>" readonly></td>              
             </tr>
             
          <?php 
          
                	$i = $i + 1;          
          
             } // while
          ?>     
          </tbody>
           </table>

     <br /><br />
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_csales_mas.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 include("../Setting/btnprint.php");

				?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
	  	</table>	
	</fieldset>
	</form>
	</div>
	<div class="spacer"></div>
</body>

</html>
