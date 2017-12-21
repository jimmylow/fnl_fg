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
    
      $var_stat = $_GET['stat'];
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }
   
 
	 if ($_POST['Submit'] == "Cancel") {
     	if(!empty($_POST['salorno']) && is_array($_POST['salorno'])) 
     	{
           foreach($_POST['salorno'] as $key) {
             $defarr = explode(",", $key);
             $var_sale = $defarr[0];
             //$var_cust = $defarr[3];
                        
		     $vartoday = date("Y-m-d H:i:s");
			 $sql  = "Update invmas Set stat = 'C', modified_by = '$var_loginid', modified_on = '$vartoday' ";
             $sql .=	" Where invno ='".$var_sale."'";
             
             mysql_query($sql) or die(mysql_error()." 1");		 	 
		   }
		   $backloc = "../sales/m_inv_mas.php?stat=1&menucd=".$var_menucode;
           //echo "<script>";
           //echo 'location.replace("'.$backloc.'")';
           //echo "</script>";   
       }      
    }
    
   if ($_POST['Submit'] == "Active") {
     	if(!empty($_POST['salorno']) && is_array($_POST['salorno'])) 
     	{
           foreach($_POST['salorno'] as $key) {
             $defarr = explode(",", $key);
             $var_sale = $defarr[0];
             //$var_cust = $defarr[3];
                        
		     $vartoday = date("Y-m-d H:i:s");
			 $sql  = "Update invmas Set stat = 'A', modified_by = '$var_loginid', modified_on = '$vartoday' ";
             $sql .=	" Where invno ='".$var_sale."'";
             
             mysql_query($sql) or die(mysql_error()." 1");		 	 
		   }
		   $backloc = "../sales/m_inv_mas.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }

	if ($_GET['p'] == "Print") {
        $pinvno = $_GET['sinvno'];
        
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

				if (!empty($spro)){
					$sqli  = "insert into tmpinvform values ('$spro', '$spde', '$sqty', '$suom', '$suni', ";
					$sqli .= "        '$samt', '$ssty', '$sstde', '$discamt', '$disctyp', '$var_loginid', '$disdes', '0')";
					//echo $sqli."<br>";
					mysql_query($sqli) or die ("Cant Insert 1 : ".mysql_error());
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
        $backloc = "../sales/m_inv_mas.php?ino=".$pinvno."&menucd=".$var_menucode;
       	echo "<script>";
     	//echo 'location.replace("'.$backloc.'")';
        echo "</script>";    
	   } 

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";
@import "../css/demo_table.css";
thead th input { width: 90% }

.style2 {
	margin-right: 0px;
}
</style>

<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript"> 
$(document).ready(function() {
	$('#example').dataTable( {
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
		"bStateSave": true,
		"bFilter": true,
		"sPaginationType": "full_numbers",
		"bAutoWidth":false,
		"aoColumns": [
    					null,
    					null,
    					{ "sType": "uk_date" },
    					{ "sType": "uk_date" },
    					null,
    					null,
    					null,
    					null,
    					null,
    					null,
    					null
    				]
	})
	
	.columnFilter({sPlaceHolder: "head:after",

		aoColumns: [ 
					 null,	
					 { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     null,
				     null,
				     null,
				     null
				   ]
		});	
} );
			
jQuery(function($) {
  
    $("tr :checkbox").live("click", function() {
        $(this).closest("tr").css("background-color", this.checked ? "#FFCC33" : "");
    });
  
});
			
</script>
</head>
    <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 

<body>
  <div class="contentc">


	<fieldset name="Group1" style=" width: 900px;" class="style2">
	 <legend class="title">INVOICE LISTING</legend>
	  <br>
	 
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 1131px; height: 38px;" align="left">
           <?php
                $locatr = "inv_mas.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
  				
    	  	   $msgdel = "Are You Sure Delete Selected Invoice?";
    	  	   if ($var_accdel == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Cancel" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type=submit name = "Submit" value="Cancel" class="butsub" style="width: 60px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
  				}
  				
			   $msgdel = "Are You Sure Active Selected Invoice?";
    	  	   if ($var_accdel == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Cancel" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type=submit name = "Submit" value="Active" class="butsub" style="width: 60px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
  				}

    	      ?></td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
           <tr>
          <th></th>
          <th style="width: 234px">Invoice No</th>
          <th style="width: 129px">Invoice Date</th>
          <th style="width: 128px">Customer</th>
          <th style="width: 124px">Custom No</th>
          <th>Status</th>
          <th></th>
          <th></th>
           <th></th>
		  <th></th>
		  <th></th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 234px">Invoice No</th>
          <th class="tabheader" style="width: 129px">Invoice Date</th>
          <th class="tabheader" style="width: 128px">Customer</th>
          <th class="tabheader" style="width: 124px">Custom No</th>
          <th class="tabheader" style="width: 124px">Status</th>
          <th class="tabheader" style="width: 12px">Detail</th>
           <th class="tabheader" style="width: 12px">Print</th>
          <th class="tabheader" style="width: 12px">Update</th>
		  <th class="tabheader" style="width: 12px">Cancel</th>
		  <th class="tabheader" style="width: 12px">Active</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT invno, invdte, custcd, customno, stat ";
		    $sql .= " FROM invmas";
    		$sql .= " ORDER BY invno desc";  
			$rs_result = mysql_query($sql); 
	
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
				$invno = htmlentities($rowq['invno']);
				$invdte = date('d-m-Y', strtotime($rowq['invdte']));
				
			/*	$sql1 = "select app_stat from salesappr";
        		$sql1 .= " where sordno ='".$salorno."' ";
        		$sql1 .= " and sbuycd ='".$rowq['sbuycd']."' ";
        		$sql_result1 = mysql_query($sql1) or die("error query sales order status :".mysql_error());
        		$row2 = mysql_fetch_array($sql_result1);
				$sstat = $row2[0];   */
				
				$urlpop = 'upd_inv.php';
				$urlvie = 'vm_inv.php';
        
				echo '<tr>';
            	echo '<td>'.$numi.'</td>';
           		echo '<td>'.$invno.'</td>';
            	echo '<td>'.$invdte.'</td>';
            	echo '<td>'.$rowq['custcd'].'</td>';
            	echo '<td>'.$rowq['customno'].'</td>';
            	echo '<td>'.$rowq['stat'].'</td>';
            
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?ino='.$invno.'&custcd='.$rowq['custcd'].'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	}
            	
            	echo '<td align="center"><a href="m_inv_mas.php?p=Print&sinvno='.$invno.'&menucd='.$var_menucode.'" title="Print Invoice"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate Invoice" /></a></td>'; 

	            if ($var_accupd == 0){
		            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
	            }else{
	            	if ($sstat == "APPROVE"){
	            		echo '<td align="center"><a href="#" title="This Sales Order Is Approved; Edit Is Not Allow">[EDIT]</a>';'</td>';
	            	}else{ 
		            	echo '<td align="center"><a href="'.$urlpop.'?ino='.$invno.'&custcd='.$rowq['custcd'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
	            	}
	            }
	            if ($var_accdel == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
	            }else{
	              if ($sstat == "APPROVE"){
					echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
				  }else{	
	              	$values = implode(',', $rowq);	
	              	echo '<td align="center"><input type="checkbox" name="salorno[]" value="'.$values.'" />'.'</td>';
    	          }	
    	        }
           		
           		 if ($var_accdel == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
	            }else{
	              if ($sstat == "APPROVE"){
					echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
				  }else{	
	              	$values = implode(',', $rowq);	
	              	echo '<td align="center"><input type="checkbox" name="salorno[]" value="'.$values.'" />'.'</td>';
    	          }	
    	        }
           		
           		echo '</tr>';
            $numi = $numi + 1;
			}
		 ?>
		 </tbody>
		 </table>
		</form>
	   </fieldset>
	  </div>	
	  <div class="spacer"></div>
	
</body>

</html>
