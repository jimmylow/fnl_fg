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
    
      $var_ordno = $_GET['ino'];
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");

    }
    
    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
        $pinvno = $_POST['sinvno'];
        
        #----------------------------------------------------------
        $sql = "Delete from tmpinvform where usernm = '$var_loginid'";
        mysql_query($sql) or die ("Cant Delete 1 : ".mysql_error());
		$sql = "Delete from tmpinvformtot where username = '$var_loginid'";
        mysql_query($sql) or die ("Cant Delete 1 : ".mysql_error());

		$sql = "select sordno from invdet where invno = '$pinvno'";
		$rs_result = mysql_query($sql);
		while ($rowq = mysql_fetch_assoc($rs_result)){ 
		    $sord = htmlentities($rowq['sordno']);
		   
			$sql1 = "select * from salesshipdet where shipno = '$sord'";
			$rs_result1 = mysql_query($sql1);
			while ($rowq1 = mysql_fetch_assoc($rs_result1)){
				$spro = htmlentities($rowq1['sprocd']);
				$sqty = $rowq1['sproqty'];
				$suom = htmlentities($rowq1['sprouom']);
				$sseq = $rowq1['sproseq'];
				
				$sql2 = "select sprounipri, sptype from salesentrydet where sordno = '$sord' and sprocd = '$spro'";
     			$sql_result2 = mysql_query($sql2);
     			$row2 = mysql_fetch_array($sql_result2);
     			$suni = htmlentities($row2['sprounipri']);
     			$ssty = htmlentities($row2['sptype']);

				if(empty($sqty)){$sqty = 0;}
				if(empty($suni)){$suni = 0;}
				$samt = $suni * $sqty;
				 
				$sql2 = "select Description from product where ProductCode = '$spro'";
     			$sql_result2 = mysql_query($sql2);
     			$row2 = mysql_fetch_array($sql_result2);
     			$spde = mysql_real_escape_string($row2['Description']);
     			
     			$sql3 = "select salestype_desc from salestype_master where salestype_code = '$ssty'";
     			$sql_result3 = mysql_query($sql3);
     			$row3 = mysql_fetch_array($sql_result3);
     			$sstde = htmlentities($row3['salestype_desc']);
     			
     			$sql4 = "select disctype, discamt from salesentrydisct where sordno = '$sord' and sptype = '$ssty'";
     			$sql_result4 = mysql_query($sql4);
     			$row4 = mysql_fetch_array($sql_result4);
     			$disctyp = htmlentities($row4['disctype']);
				$discamt = htmlentities($row4['discamt']);
				if(empty($discamt)){$discamt = 0;}
				
				if ($disctyp == ''){
					$disdes = "Discount";
				}else{	
					if ($disctyp == '1'){
						$disdes = "Discount (%)";	
					}else{
						$disdes = "Discount (RM)";
					}
				}
				
				
				$sql = "select distinct salestyp, stypdisv, stypcat from tmpinvform where usernm = '$var_loginid'";
				$rs_result = mysql_query($sql);
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
					$styp    = htmlentities($rowq['salestyp']);
					$stypdis = htmlentities($rowq['stypdisv']);
					$scat    = htmlentities($rowq['stypcat']);
					
					if (empty($scat)){
						$netstypdisv = '0'; 
						
					}else{
						if ($scat == '2'){
						$netstypdisv = $stypdis	;
						}else{
							$sqldd  = "select sum(amt) from tmpinvform where salestyp = '$styp' and usernm = '$var_loginid'";
							$sql_resultdd = mysql_query($sqldd);
							$rowdd = mysql_fetch_array($sql_resultdd);
							$dsalestypc = htmlentities($rowdd['sum(amt)']);
							
							$discamt = $dsalestypc * ($stypdis / 100);
							$netstypdisv = $discamt;	
		
						}
					}  
				}
				
				if ($netstypdisv='' or $netstypdisv=' ' or $netstypdisv==NULL){
					$netstypdisv = 0;
				}

				if (!empty($spro)){
					$sqli  = "insert into tmpinvform values ('$spro', '$spde', '$sqty', '$suom', '$suni', ";
					$sqli .= "        '$samt', '$ssty', '$sstde', '$discamt', '$disctyp', '$var_loginid', '$disdes', '$netstypdisv')";
					//mysql_query($sqli) or die ("Cant Insert 1 : ".mysql_error());  
					mysql_query($sqli) or die("Error Insert into tmpinvform  Table : ".mysql_error(). ' Failed SQL is --> '. $sqli);	 	        
				}
			}
		}
        #----------------------------------------------------------
		$sql = "select distinct salestyp, stypdisv, stypcat from tmpinvform where usernm = '$var_loginid'";
		$rs_result = mysql_query($sql);
		while ($rowq = mysql_fetch_assoc($rs_result)){ 
		    $styp    = htmlentities($rowq['salestyp']);
		    $stypdis = htmlentities($rowq['stypdisv']);
		    $scat    = htmlentities($rowq['stypcat']);
		    
		    if (empty($scat)){
		    	$sqli  = "update tmpinvform set netstypdisv = '0' ";
				$sqli .= " where salestyp = '$styp' and usernm = '$var_loginid'";
				mysql_query($sqli) or die ("Cant Update 1 : ".mysql_error()); 	 
			}else{
				if ($scat == '2'){
					$sqli  = "update tmpinvform set netstypdisv = '$stypdis' ";
					$sqli .= " where salestyp = '$styp' and usernm = '$var_loginid'";
					mysql_query($sqli) or die ("Cant Update 2 : ".mysql_error()); 	
				}else{
					$sqldd  = "select sum(amt) from tmpinvform where salestyp = '$styp' and usernm = '$var_loginid'";
					$sql_resultdd = mysql_query($sqldd);
     				$rowdd = mysql_fetch_array($sql_resultdd);
     				$dsalestypc = htmlentities($rowdd['sum(amt)']);
					
					$discamt = $dsalestypc * ($stypdis / 100);
					$sqli  = "update tmpinvform set netstypdisv = '$discamt' ";
					$sqli .= " where salestyp = '$styp' and usernm = '$var_loginid'";
					mysql_query($sqli) or die ("Cant Update 3 : ".mysql_error());				
				}
			}  
		}
		$sqldd  = "select discount, sec_disct, add_deduction, freight, transport, gst from invmas where invno = '$pinvno'";
		$sql_resultdd = mysql_query($sqldd);
     	$rowdd = mysql_fetch_array($sql_resultdd);
     	$fdis    = $rowdd['discount'];
		$secdis  = $rowdd['sec_disct'];
		$deduper = $rowdd['add_deduction'];
		$frie    = $rowdd['freight'];
		$trans   = $rowdd['transport'];
		$gstper  = $rowdd['gst'];
		if (empty($fdis)){$fdis = 0;}
		if (empty($secdis)){$secdis = 0;}
		if (empty($deduper)){$deduper = 0;}
		if (empty($frie)){$frie = 0;}
		if (empty($trans)){$trans = 0;}
		if (empty($gstper)){$gstper = 0;}
		
		$sqldd  = "select sum(amt) from tmpinvform where usernm = '$var_loginid'";
		$sql_resultdd = mysql_query($sqldd);
     	$rowdd = mysql_fetch_array($sql_resultdd);
     	$sumamt = htmlentities($rowdd['sum(amt)']);
		if (empty($sumamt)){$sumamt = 0;}
		
		$sqldd  = "select sum(distinct netstypdisv) from tmpinvform where usernm = '$var_loginid' and netstypdisv <> 0";
		$sql_resultdd = mysql_query($sqldd);
     	$rowdd = mysql_fetch_array($sql_resultdd);
     	$disamt = htmlentities($rowdd['sum(distinct netstypdisv)']);
		if (empty($disamt)){$disamt = 0;}
		
		$netamt = $sumamt - $disamt;
		$gamt = $netamt;
		if ($fdis <> 0){
			$fdisamt = ($fdis /100) * $netamt;
			$netamt = $netamt - $fdisamt;
		}
		if (empty($fdisamt)){$fdisamt = 0;}
		if($secdis <> 0){ 		
			$sdisamt = ($secdis /100) * $netamt;
			$netamt = $netamt - $sdisamt;
		}
		if (empty($sdisamt)){$sdisamt = 0;}
		if ($deduper <> 0){		
			$deduamt = ($deduper /100) * $netamt;
			$netamt = $netamt - $deduamt;
		}
		if (empty($deduamt )){$deduamt  = 0;}
		if ($frie <> 0){
			$netamt = $netamt + $frie;
		}
		if ($trans <> 0){	
			$netamt = $netamt + $trans;
		}
		
		$gstamt = $gstper * $netamt / 100;
		$netamt = $netamt + $gstamt;
		$sqli  = "insert into tmpinvformtot values ('$gamt', '$var_loginid', '$fdis', '$fdisamt', '$secdis',";
		$sqli .= " '$sdisamt', '$deduper', '$deduamt', '$frie', '$trans', '$gstper', '$gstamt', '$netamt')";
		mysql_query($sqli) or die ("Cant Update 3 : ".mysql_error());
		
        $sqldd  = "select apphea_txt from apphea_set";
		$sql_resultdd = mysql_query($sqldd);
     	$rowdd = mysql_fetch_array($sql_resultdd);
     	$comphea = htmlentities($rowdd['apphea_txt']);

		if ($comphea == 'SPRINGICE'){
			$fname = "invform.rptdesign&__title=myReport";
		}else{ 
        	$fname = "invformnofnl.rptdesign&__title=myReport";
        } 
        $dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&invn=".$pinvno."&menuc=".$var_menucode."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $dest .= urlencode(realpath($fname));

        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../sales/vm_inv.php?ino=".$pinvno."&menucd=".$var_menucode;
       	echo "<script>";
       	echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 

     }
    }
    
     if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print Custom") {
        $pinvno = $_POST['sinvno'];
        
        #----------------------------------------------------------
        $sql = "Delete from tmpinvform where usernm = '$var_loginid'";
        mysql_query($sql) or die ("Cant Delete 1 : ".mysql_error());

		$sql = "select sordno from invdet where invno = '$pinvno'";
		$rs_result = mysql_query($sql);
		while ($rowq = mysql_fetch_assoc($rs_result)){ 
		    $sord = htmlentities($rowq['sordno']);
		   
			$sql1 = "select * from salesshipdet where shipno = '$sord'";
			$rs_result1 = mysql_query($sql1);
			while ($rowq1 = mysql_fetch_assoc($rs_result1)){
				$spro = htmlentities($rowq1['sprocd']);
				$sqty = $rowq1['shipqty'];
				$suom = htmlentities($rowq1['sprouom']);
				$sseq = $rowq1['sproseq'];
				
				$sql2 = "select sprounipri from salesentrydet where sordno = '$sord' and sprocd = '$spro'";
     			$sql_result2 = mysql_query($sql2);
     			$row2 = mysql_fetch_array($sql_result2);
     			$suni = htmlentities($row2['sprounipri']);
				
				if(empty($sqty)){$sqty = 0;}
				if(empty($suni)){$suni = 0;}
				$samt = $suni * $sqty;
				 
				$sql2 = "select Description from product where ProductCode = '$spro'";
     			$sql_result2 = mysql_query($sql2);
     			$row2 = mysql_fetch_array($sql_result2);
     			$spde = mysql_real_escape_string($row2['Description']);
     			
     			$sql3 = "select salestype_desc from salestype_master where salestype_code = '$ssty'";
     			$sql_result3 = mysql_query($sql3);
     			$row3 = mysql_fetch_array($sql_result3);
     			$sstde = htmlentities($row3['salestype_desc']);
     			
     			$sql4 = "select disctype, discamt from salesentrydisct where sordno = '$sord' and sptype = '$ssty'";
     			$sql_result4 = mysql_query($sql4);
     			$row4 = mysql_fetch_array($sql_result4);
     			$disctyp = htmlentities($row4['disctype']);
				$discamt = htmlentities($row4['discamt']);
				if(empty($discamt)){$discamt = 0;}
				
				if ($disctyp == ''){
					$disdes = "Discount";
				}else{	
					if ($disctyp == '1'){
						$disdes = "Discount (%)";	
					}else{
						$disdes = "Discount (RM)";
					}
				}
				
				$sql = "select distinct salestyp, stypdisv, stypcat from tmpinvform where usernm = '$var_loginid'";
				$rs_result = mysql_query($sql);
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
					$styp    = htmlentities($rowq['salestyp']);
					$stypdis = htmlentities($rowq['stypdisv']);
					$scat    = htmlentities($rowq['stypcat']);
					
					if (empty($scat)){
						$netstypdisv = '0'; 
						
					}else{
						if ($scat == '2'){
						$netstypdisv = $stypdis	;
						}else{
							$sqldd  = "select sum(amt) from tmpinvform where salestyp = '$styp' and usernm = '$var_loginid'";
							$sql_resultdd = mysql_query($sqldd);
							$rowdd = mysql_fetch_array($sql_resultdd);
							$dsalestypc = htmlentities($rowdd['sum(amt)']);
							
							$discamt = $dsalestypc * ($stypdis / 100);
							$netstypdisv = $discamt;	
		
						}
					}  
				}
				
				if ($netstypdisv='' or $netstypdisv=' ' or $netstypdisv==NULL){
					$netstypdisv = 0;
				}

				if (!empty($spro)){
					$sqli  = "insert into tmpinvform values ('$spro', '$spde', '$sqty', '$suom', '$suni', ";
					$sqli .= "        '$samt', '$ssty', '$sstde', '$discamt', '$disctyp', '$var_loginid', '$disdes', '$netstypdisv')";
					//mysql_query($sqli) or die ("Cant Insert Custom : ".mysql_error());
					mysql_query($sqli) or die("Error Insert into tmpinvform  custom Table : ".mysql_error(). ' Failed SQL is --> '. $sqli);	 	        					
				}
			}
		}
        #----------------------------------------------------------
        
        $fname = "invcustomform.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&invn=".$pinvno."&menuc=".$var_menucode."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $dest .= urlencode(realpath($fname));

        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../sales/vm_inv.php?ino=".$pinvno."&menucd=".$var_menucode;
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
  	 $sql = "select * from invmas";
     $sql .= " where invno ='".$var_ordno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $custcd = $row['custcd'];
     $invdte = date('d-m-Y', strtotime($row['invdte']));
     $customno = htmlentities($row['customno']);
     $disct = $row['discount'];     
     $sec_disct = $row['sec_disct'];
     $deduct = $row['add_deduction'];
     $freight = $row['freight'];
     $transport = $row['transport'];
     $remark = $row['remark'];
     $vmgst =  $row['gst'];
     
  ?> 
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">INVOICE</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Invoice No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sinvno" id="sinvnoid" type="text" readonly style="width: 204px;" value = "<?php echo $var_ordno; ?>">         
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
              echo "<option size =30 selected value='s'></option>";
                       
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
			<td style="width: 204px">Invoice Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="sainvdte" id ="sainvdte" type="text" style="width: 128px;" value="<?php  echo $invdte; ?>">
 		   </td>
	  	  </tr>  
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Discount</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sadisct" id="sadisctid" type="text" maxlength="45" style="width: 204px;" value ="<?php echo $disct; ?>" readonly>%</td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Custom No</td>
		   <td>:</td>
		   <td style="width: 284px">
			<input class="inputtxt" name="sacustomno" id="sacustomnoid" type="text" maxlength="45" style="width: 204px;" value ="<?php echo $customno; ?>" readonly></td>
		  </tr> 
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Second Discount</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sasecdisct" id="sasecdisctid" type="text" maxlength="45" style="width: 204px;" value ="<?php echo $sec_disct; ?>" readonly>%</td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Freight</td>
		   <td>:</td>
		   <td style="width: 284px">
			<input class="inputtxt" name="safreight" id="safreightid" type="text" maxlength="45" style="width: 204px;" value ="<?php echo $freight; ?>" readonly></td>
		  </tr> 
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Add Deduction</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sadeduct" id="sadeductid" type="text" maxlength="45" style="width: 204px;" value ="<?php echo $deduct; ?>" readonly>%</td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Transport</td>
		   <td>:</td>
		   <td style="width: 284px">
			<input class="inputtxt" name="satransport" id="satransportid" type="text" maxlength="45" style="width: 204px;"  value ="<?php echo $transport; ?>" readonly></td>       
		  </tr> 
		  <tr>
	  	   <td ></td>
	  	   <td >GST %</td>
	  	   <td >:</td>
	  	   <td colspan="6"><input class="inputtxt" name="gst" id="gstid" type="text" maxlength="6" style="width: 60px;" value="<?php echo $vmgst; ?>" ></td>		    
		  </tr>       
		  <tr>
	  	   <td ></td>
	  	   <td >Remark</td>
	  	   <td >:</td>
	  	   <td colspan="6"><textarea class="inputtxt" name="remark" id="remark" COLS=60 ROWS=2 readonly><?php echo $remark; ?></textarea></td>
         
		  </tr> 
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 958px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Order No</th>
              <th class="tabheader">Ship Date</th>             
             </tr>
            </thead>
            <tbody>
             <?php
             	$sql = "SELECT * FROM invdet";
             	$sql .= " Where invno ='".$var_ordno."'"; 
	    		$sql .= " ORDER BY seqno";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
        
                            
             ?>            
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 27px; border:0;" value ="<?php echo $i; ?>"></td>
                <td>
				<input name="prococode[]" tProItem1=1 id="prococode<?php echo $i; ?>" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['sordno']); ?>" readonly></td>
                <td>
				<input name="procouom[]" id="procouom" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 160px" value ="<?php echo ""; ?>" ></td>
             </tr>
             
          <?php 
          
                	$i = $i + 1;          
          
             } // while
          ?>     
        
            </tbody>
           </table>
     
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_inv_mas.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 include("../Setting/btnprint.php");
				 
				 if ($var_accvie == 0){
     				echo '<input type=submit name = "Submit" value="Print Custom" disabled="disabled" class="butsub" style="width: 100; height: 32px">';
  				 }else{
    				echo '<input type=submit name = "Submit" value="Print Custom" class="butsub" style="width: 100px; height: 32px">';
  				 } 
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
