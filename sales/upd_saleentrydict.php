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
  	 $sql = "select * from salesentry";
     $sql .= " where sordno ='".$var_ordno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $custcd = $row['scustcd'];
     $orddte = date('d-m-Y', strtotime($row['sorddte']));
     $order_no = htmlentities($row['sordno']);
     $cust_po = htmlentities($row['scustpo']);
     $zone = $row['szone'];
          
  ?> 
   
  <div class="contentc">
     <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">SALES ORDER</legend>
	  <br>	 
	  	   
		<table style="width: 993px; " border = "0">
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Order No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sordno" id="sordnoid" type="text" readonly style="width: 204px;" value = "<?php echo $order_no; ?>">         
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
              $sql = "select custno, name from customer_master ORDER BY custno ASC";
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
	  	   <td style="width: 122px">Customer PO</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sacustpo" type="text" style="width: 204px;" value = "<?php echo $cust_po; ?>" readonly></td>		   </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Zone</td>
		   <td>:</td>
		   <td style="width: 284px">
		   	<select name="szone" id="szonecd" >
			 <?php
              $sql = "select zone_code, zone_desc from zone_master ORDER BY zone_code";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['zone_code'].'"';
        if ($zone == $row['zone_code']) { echo "selected"; }
        echo '>'.$row['zone_code']." | ".$row['zone_desc'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

		   </td>
		  </tr> 
	
	  	  </table>
		 
		  <br><br>
			  <table id="itemsTable" class="general-table" style="width: 958px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Item Code</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Unit Qty</th>
              <th class="tabheader">Type</th>
              <th class="tabheader">Unit Price</th>
              <th class="tabheader">Ord. Qty</th>
              <th class="tabheader">Ord(Pcs)</th>
              <th class="tabheader">Price/pc</th>
              <th class="tabheader">Total(pc)</th>              
             </tr>
            </thead>
            <tbody>
             <?php
             	$sql = "SELECT * FROM salesentrydet";
             	$sql .= " Where sordno ='".$var_ordno."'"; 
	    		$sql .= " ORDER BY sproseq";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
        
          $sql2 = " select uom_pack from prod_uommas";
          $sql2 .= " where uom_code = '".$rowq['sprouom']."'";

          $result = mysql_query($sql2) or die ("Error uom : ".mysql_error());
          
          if(mysql_numrows($result) > 0) {
            $data = mysql_fetch_object($result);
            $var_uqty = $data->uom_pack;
            if ($var_uqty == "") { $var_uqty = 1; }         
           }  else { $var_uqty = 1; }  
           
           $var_totpcs = $rowq['sproqty'] * $var_uqty;  
           $var_pripcs = $rowq['sprounipri'] / $var_uqty;  
                                    
             ?>            
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 27px; border:0;" value ="<?php echo $i; ?>"></td>
                <td>
				<input name="prococode[]" tProItem1=1 id="prococode<?php echo $i; ?>" tabindex="0" class="autosearch" style="width: 161px" value ="<?php echo htmlentities($rowq['sprocd']); ?>" readonly></td>
                <td>
				<input name="procouom[]" id="procouom" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 60px" value ="<?php echo $rowq['sprouom']; ?>"></td>
                <td>
        <input name="procouqty[]" value="<?php echo $var_uqty; ?>" id="procouqty1" style="width: 48px; text-align : right" ></td>                
                <td>
				<input name="procotype[]" id="procotype<?php echo $i; ?>" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 75px" value ="<?php echo $rowq['sptype']; ?>"></td>
                <td>
				<input name="procoprice[]" class="tInput" id="procoprice<?php echo $i; ?>" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 75px; text-align : right" value ="<?php echo $rowq['sprounipri']; ?>"></td>
                <td>
				<input name="procoqty[]" id="procoqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['sproqty']; ?>"></td>
                <td>
        <input name="procoordpcs[]" id="procoordpcs1" style="width: 48px; text-align : right" value ="<?php echo $var_totpcs; ?>"></td>                
                <td>
        <input name="procopripcs[]" id="procopripcs1" style="width: 48px; text-align : right" value ="<?php echo $var_pripcs; ?>"></td>                
                <td>
        <input name="procototpcs[]" id="procototpcs1" style="width: 48px; text-align : right" value ="<?php echo $var_totpcs; ?>" ></td>                
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
				 $locatr = "m_sales_mas.php?menucd=".$var_menucode;
			
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
