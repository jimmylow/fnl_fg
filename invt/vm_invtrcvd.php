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


<script type="text/javascript"> 

</script>
</head>

<body >
	  <?php include("../topbarm.php"); ?> 
<!--  <?php include("../sidebarm.php"); ?> -->

  <?php
  	 $sql = "select * from invtrcvd";
     $sql .= " where rcvdno ='".$var_ordno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $custcd = $row['custcd'];
     $rcvddte = date('d-m-Y', strtotime($row['rcvddte']));
     $refdte = date('d-m-Y', strtotime($row['refdte'])); 
     $var_po = $row['pono'];    
     $srefno = htmlentities($row['refno']);
     $remark = $row['remark'];
     
  ?> 
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">VIEW GOODS RECEIVED (Other Supplier)</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Received No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="textnoentry" name="srcvdno" id="srcvdnoid" type="text" readonly style="width: 204px;" value = "<?php echo $var_ordno; ?>">         
		   </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   &nbsp;</td>
	  	  </tr>    
    
	   	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Supplier</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			 <input class="textnoentry" name="sacustcd" id="sacustcd" type="text" value="<?php echo $custcd; ?>" style="width: 204px;" readonly></td>
		   </td>        
			<td style="width: 10px"></td>
			<td style="width: 204px">Received Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="sarcvddte" id ="sarcvddte" type="text" style="width: 128px;" value="<?php  echo $rcvddte; ?>">
		   </td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px"><div id="msgcd"></div></td>
	  	  </tr>
	  <tr>
	  	   <td ></td>
	  	   <td >PO No</td>
	  	   <td >:</td>
	  	   <td colspan="5">
			<input class="textnoentry" name="po" id="poid" type="text" style="width: 204px;" value ="<?php echo $var_po; ?>" readonly></td>
		     </td>
	  	  </tr>        
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">DO No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="srefno" id="srefnoid" type="text" maxlength="45" style="width: 204px;" value="<?php echo $srefno; ?>" readonly></td>
			<td style="width: 10px"></td>
			<td style="width: 204px"> Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="sarefdte" id ="sarefdte" type="text" style="width: 128px;" value="<?php  echo $refdte; ?>" readonly>
		   </td>
		  </tr>
		  <tr>
	  	   <td ></td>
	  	   <td >Remark</td>
	  	   <td >:</td>
	  	   <td colspan="5">
			<input class="inputtxt" name="saremark" id="saremarkid" type="text" maxlength="100" style="width: 463px;" value="<?php echo $remark; ?>" readonly></td>
		     </td>
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
              <th class="tabheader">Unit Price(RM)</th>
              <th class="tabheader">Order Qty</th>
              <th class="tabheader">Rcvd Qty</th>
              <th class="tabheader">Prev. Rcvd Qty</th>
              <th class="tabheader">%</th>                            
              <th class="tabheader">Amount</th>
             </tr>
            </thead>
            <tbody>
             <?php
             	$sql = "SELECT * FROM po_trans";
             	$sql .= " Where po_no ='".$var_po."'"; 
	    		    $sql .= " ORDER BY seqno"; 
              
              //echo $sql; 
			  	$rs_result = mysql_query($sql) or die ("cant get podet : ".mysql_error()); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
        
            $sql2 = "select proqty from invtrcvddet";
            $sql2 .= " where rcvdno = '".$var_ordno."'";
            $sql2 .= " and procd = '".$rowq['itemcode']."'";
            
            //echo $sql2;
            $tmp2 = mysql_query($sql2) or die ("cant get item : ".mysql_error());
            
            if(mysql_numrows($tmp2) >0) {
              $rst2 = mysql_fetch_object($tmp2);
              
              $rcvdqty = $rst2->proqty;
            }
            if($rcvdqty == "") { $rcvdqty = 0; }
            
            $sql2 = "select sum(proqty) as tqty from invtrcvddet";
            $sql2 .= " where rcvdno in ";
            $sql2 .= " (select rcvdno from invtrcvd where pono = '".$var_po."')";
            $sql2 .= " and procd = '".$rowq['itemcode']."'";
            
            //echo $sql2;
            $tmp2 = mysql_query($sql2) or die ("cant get item : ".mysql_error());
            
            if(mysql_numrows($tmp2) >0) {
              $rst2 = mysql_fetch_object($tmp2);
              
              $prcvdqty = $rst2->tqty;
            }
            if($prcvdqty == "") { $prcvdqty = 0; }
            
            $percent = ($prcvdqty / $rowq['qty']) * 100;
             
            $prevrvcdqty = intval($prcvdqty) - intval($rcvdqty);  //only get d previous receive, bt not include this transaction
            //echo "P : ".$prcvdqty." C : ".$rcvdqty." N : ".$prevrvcdqty;             
            
            $amt = $rcvdqty * $rowq['uprice'];   
            
            $tamt += $amt;          
       
             ?>                        
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 27px; border:0;"></td>
                <td >
				<input name="procd[]" class="tInput" id="prococode<?php echo $i; ?>" tabindex="0" style="border-style: none; border-color: inherit; width: 140px"  value ="<?php echo htmlentities($rowq['itemcode']); ?>" readonly></td>
                <td>
				<input name="procdname[]" id="proconame<?php echo $i; ?>" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 250px;" value="<?php echo $rowq['itmdesc']; ?>"></td>
                <td>
				<input name="prouom[]" id="prouom<?php echo $i; ?>" readonly="readonly" style="width: 45px; border-style: none; border-color: inherit; border-width: 0;" value="<?php echo $rowq['itmuom']; ?>">
				</td>
                <td >
				<input name="prooupri[]" id="prooupri<?php echo $i; ?>" style="border-style: none; width: 70px; text-align:right;" readonly="readonly" value="<?php echo $rowq['uprice']; ?>">
				</td>
                <td >
				<input name="prorecqty[]" id="prorecqty<?php echo $i; ?>" style="border-style: none; width: 70px; text-align:right;" readonly="readonly" value="<?php echo intval($rowq['qty']); ?>">
				</td>         
                <td >
				<input name="proorqty[]" id="proordqty<?php echo $i; ?>" onBlur="calcAmt('<?php echo $i; ?>');" style="width: 70px; text-align:center;" value="<?php echo intval($rcvdqty); ?>">
				</td>
        <td >
				<input name="proprecqty[]" id="proprecqty<?php echo $i; ?>" style="border-style: none; width: 70px; text-align:right;" readonly="readonly" value="<?php echo $prevrvcdqty; ?>">
				</td>         
				<td >
				<input name="proouperc[]" id="proouperc<?php echo $i; ?>" readonly="readonly" style="width: 40px; border-style: none; border-color: inherit; border-width: 0; text-align:right;" value="<?php echo $percent; ?>">
				</td>                      
				<td >
				<input name="proouamt[]" id="proouamt<?php echo $i; ?>" readonly="readonly" style="width: 100px; border-style: none; border-color: inherit; border-width: 0; text-align:right;" value="<?php echo number_format($amt, 2, '.', ','); ?>">
				</td>
             </tr>
          <?php 
          
                	$i = $i + 1;          
          
             } // while
          ?>     
             </tbody>
           </table>
		  <table class="general-table" style="width: 958px">
          	 <tr>
              <td style="width: 842px; text-align:right" >Total : </td>              
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
				 $locatr = "m_rcvd_mas.php?menucd=".$var_menucode;
			
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
