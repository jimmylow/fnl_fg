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
        $cinvnum = $_POST['sordno'];
        
        #----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmpcinvtot where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        
        $lessrm = 0;   $netrm = 0;
        $sql  = "SELECT distinct sptype";
		$sql .= " FROM cinvoicedet1";
  		$sql .= " where invno = '$cinvnum'";	 	
		$rs_result = mysql_query($sql); 
		while ($row = mysql_fetch_assoc($rs_result)) { 
		    $sptype = mysql_real_escape_string($row['sptype']);
		    
		    $sumamttyp = 0;
		    $sql1  = "select sum(sprounipri * soldqty) from cinvoicedet1";
		    $sql1 .= " where invno = '$cinvnum' and sptype = '$sptype'";
		    $sql_resultc = mysql_query($sql1);
        	$rowc = mysql_fetch_array($sql_resultc);
        	$sumamttyp = $rowc['0'];
        	if (empty($sumamttyp)){$sumamttyp = 0;}
        	
        	$rate = 0;
        	$sql1  = "select rate from cinvoicedet2";
		    $sql1 .= " where invno = '$cinvnum' and sptype = '$sptype'";
		    $sql_resultc = mysql_query($sql1);
        	$rowc = mysql_fetch_array($sql_resultc);
        	$rate = $rowc['rate'];
        	if (empty($rate)){$rate = 0;}
			if ($rate == 0){
				$slessamt = 0;
			}else{
				$slessamt = $sumamttyp * ($rate/100);
			}
			$snetamt = $sumamttyp - $slessamt;
			$lessrm = $lessrm + $slessamt;
			$netrm  = $netrm + $snetamt;
		}
		
		$sqli  = " Insert Into tmpcinvtot ";
   		$sqli .= " Values ('$var_loginid', '$lessrm', '$netrm')";
   		mysql_query($sqli) or die("Unable Save In Temp Table ".mysql_error());

        
        $fname = "cinv_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&cinv=".$cinvnum."&menuc=".$var_menucode."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../cons/vm_invoice.php?sorno=".$cinvnum."&menucd=".$var_menucode;
       	echo "<script>";
       	echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 

     }
    }
  
	
    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Summary") {
        $cinvnum = $_POST['sordno'];
        
        
        #----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmpcinvtot where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        
        $lessrm = 0;   $netrm = 0;
        $sql  = "SELECT distinct sptype";
		$sql .= " FROM cinvoicedet1";
  		$sql .= " where invno = '$cinvnum'";	 	
		$rs_result = mysql_query($sql); 
		while ($row = mysql_fetch_assoc($rs_result)) { 
		    $sptype = mysql_real_escape_string($row['sptype']);
		    
		    $sumamttyp = 0;
		    $sql1  = "select sum(sprounipri * soldqty) from cinvoicedet1";
		    $sql1 .= " where invno = '$cinvnum' and sptype = '$sptype'";
		    $sql_resultc = mysql_query($sql1);
        	$rowc = mysql_fetch_array($sql_resultc);
        	$sumamttyp = $rowc['0'];
        	if (empty($sumamttyp)){$sumamttyp = 0;}
        	
        	$rate = 0;
        	$sql1  = "select rate from cinvoicedet2";
		    $sql1 .= " where invno = '$cinvnum' and sptype = '$sptype'";
		    $sql_resultc = mysql_query($sql1);
        	$rowc = mysql_fetch_array($sql_resultc);
        	$rate = $rowc['rate'];
        	if (empty($rate)){$rate = 0;}
			if ($rate == 0){
				$slessamt = 0;
			}else{
				$slessamt = $sumamttyp * ($rate/100);
			}
			$snetamt = $sumamttyp - $slessamt;
			$lessrm = $lessrm + $slessamt;
			$netrm  = $netrm + $snetamt;
		}
		
		$sqli  = " Insert Into tmpcinvtot ";
   		$sqli .= " Values ('$var_loginid', '$lessrm', '$netrm')";
   		mysql_query($sqli) or die("Unable Save In Temp Table ".mysql_error());

        
        $fname = "cinvsum_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&cinv=".$cinvnum."&menuc=".$var_menucode."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../cons/vm_invoice.php?sorno=".$cinvnum."&menucd=".$var_menucode;
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


.style2 {
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>


<script type="text/javascript"> 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

</script>
</head>
<body >
  <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->
 
  <?php
  	 $sql = "select * from cinvoicemas";
     $sql .= " where invno ='".$var_ordno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $custcd = $row['custcd'];
     $invoicedte = date('d-m-Y', strtotime($row['invdte']));
     $mthyr = $row['mthyr'];
     $period = $row['period'];
     $var_lesstype = $row['less_type'];
     $var_less_amt = $row['less_amt'];
	 
	 $sql  = "select name";
   	 $sql .= " from counter";
   	 $sql .= " where counter ='$custcd'";   
   	 $sql_result = mysql_query($sql) or die(mysql_error());
   	 $row = mysql_fetch_array($sql_result);
	 $var_counter = mysql_real_escape_string($row[0]);
	 
	 //echo 'kkk-'.$var_counter;

          
          //echo "t : ".$var_lesstype." ant : ".$var_lessamt;
  ?>  

  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">VIEW INVOICE</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
	   	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Invoice No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sordno" id="sordnoid" type="text" readonly style="width: 204px;" value = "<?php echo $var_ordno; ?>">                  
         </td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Invoice Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="invoicedte" id ="invoicedte" type="text" style="width: 128px;" value="<?php echo $invoicedte; ?>" readonly>
		   </td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px"></td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">MM/YYYY</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="samthyr" id="samthyrid" type="text" maxlength="45" style="width: 100px;" value="<?php echo $mthyr; ?>"></td>		   
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
	  	   <td style="width: 122px">Counter</td>
	  	   <td style="width: 13px">:</td>
	  	   <td colspan="4">
		   	<input class="inputtxt" name="counter" id="sordnoid0" type="text" readonly style="width: 473px;" value = "<?php echo $var_counter; ?>"></td>
		   <td style="width: 284px">
		   </td>
	  	  </tr>       
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">&nbsp;</td>
	  	   <td style="width: 13px">&nbsp;</td>
	  	   <td style="width: 201px">
		   &nbsp;</td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   </td>
	  	  </tr>	  	  
	  	  </table>
         <br />
<?php

		echo '<table width="100%">';
    
       echo '<tr>';
       echo '<th class="tabheader">Product</th>';
       echo '<th class="tabheader">Description</th>';       
       echo '<th class="tabheader" align="right">Qty</th>';
       echo '<th class="tabheader" align="right">UOM</th>';
       echo '<th class="tabheader" align="right">Qty(PC)</th>';                     
       echo '<th class="tabheader" align="right">U.Price</th>';
       echo '<th class="tabheader" align="right">Amount</th>';                    
       echo '</tr>';
                 
         $var_currtype = "";   $var_prevtype = "";  $var_amt = 0;  $var_subamt = 0; 
         $var_lessamt = 0;  $var_namt = 0;   $var_tamt = 0;   $var_tlessamt = 0;   $var_tnamt = 0;
         
         $sql3 = " select sptype, rate from cinvoicedet2";
         $sql3 .= " where invno = '".$var_ordno."'";
         
         //echo $sql3;
         $tmp3 = mysql_query ($sql3) or die ("cant get rate : ".mysql_error());
         
         if(mysql_numrows($tmp3) > 0) {
            while ($row = mysql_fetch_array($tmp3)) {
         
         $sql2 = " select * from cinvoicedet1";
         $sql2 .= " where invno = '$var_ordno'";
         $sql2 .= " and sptype = '".$row['sptype']."'";
         $sql2 .= " order by sprocd ";

         //echo $sql2;
         $tmp2 = mysql_query($sql2) or die ("cant get prod : ".mysql_error());
         
         if(mysql_numrows($tmp2) > 0) {
             while ($row2 = mysql_fetch_array($tmp2)) {
             
             $var_uqty = 1;
             
             $sql4 = " select uom_pack from prod_uommas";
             $sql4 .= " where uom_code = '".$row2['uom']."'";
               
             $tmp4 = mysql_query($sql4) or die ("cant get uom pack : ".mysql_error());
             if(mysql_numrows($tmp4) > 0) {
               $rst4 = mysql_fetch_object($tmp4);
               $var_uqty = $rst4->uom_pack;
             } 
             
             $var_totqty = $var_uqty * $row2['soldqty'];             

             //$var_amt =  $row2['sprounipri'] *  $var_totqty; // change to Qty. Not in pieces (UOM pack)
             $var_amt =  $row2['sprounipri'] * $row2['soldqty'];
             $var_subamt += $var_amt;
             $var_tamt += $var_amt;                             
                        
             echo '<tr>';
             echo '<td>'.$row2['sprocd'].'</td>';
             echo '<td>'.$var_desc.'</td>'; 
             echo '<td align="right">'.$row2['soldqty'].'</td>';             
             echo '<td align="center">'.$row2['uom'].'</td>';
             echo '<td align="right">'.$var_totqty.'</td>';                           
             echo '<td align="right">'.$row2['sprounipri'].'</td>';
             echo '<td align="right">'.number_format($var_amt,2, '.',',').'</td>';             
             echo '</tr>';           
             
             
            } //while ($row2 
           } 
                       
               $sql2 = "select salestype_desc from salestype_master ";
               $sql2 .= " where salestype_code = '".$row['sptype']."'";
               
               //echo $sql2;
               $tmptype = mysql_query($sql2) or die ("Cant get type : ".mysql_error());
               $rsttype = mysql_fetch_object($tmptype);

               $var_rate = 0;  
               /* //block - to get discount from counter master            
               switch ($var_lesstype) {
                case "1" : $var_rate = 0; $var_type = ""; $var_lessamt = 0; break;
                case "2" : $var_rate = $var_less_amt; $var_type = "%";
                           $var_lessamt =  $var_subamt * $var_rate / 100;
                           break;
                case "3" : $var_rate = $var_less_amt; $var_type = "RM";
                           $var_lessamt =  $var_subamt - $var_rate;
                           break;
                default : $var_rate = 0; $var_type = "";  $var_lessamt = 0;
                }               
             */
             
              //add here - cedric 23/12/2013
              $var_type = $row['sptype'];
              $var_rate = $row['rate'];
              $var_lessamt =  $var_subamt * $var_rate / 100;
             	
               


              
               $var_tlessamt += $var_lessamt;
               $var_namt = $var_subamt - $var_lessamt;
               $var_tnamt += $var_namt;
               
               echo '<tr bgcolor="#efefef">';
               echo '<td colspan="3">Type : '.$row['sptype'].' - '.$rsttype->salestype_desc.'</td>';
               echo '<td align="right"></td>';
               echo '<td align="right"></td>';               
               echo '<td align="right"></td>';
               echo '<td align="right">'.number_format($var_subamt,2, '.',',').'</td>';
               echo '</tr>';
               echo '<tr bgcolor="#efefef">';
               echo '<td colspan="3" ></td>';
               echo '<td align="right"></td>';
               echo '<td align="right"></td>';               
               echo '<td align="right">Discount('.$var_type.' -> '.$var_rate.'%)</td>';
               echo '<td align="right">'.number_format($var_lessamt,2, '.',',').'</td>';
               echo '</tr>';
               echo '<tr bgcolor="#efefef">';
               echo '<td colspan="3" ></td>';
               echo '<td align="right"></td>';
               echo '<td align="right"></td>';               
               echo '<td align="right">NETT : </td>';
               echo '<td align="right">'.number_format($var_namt,2, '.',',').'</td>';
               echo '</tr>';
               echo '<tr><td>&nbsp;</td></tr>';
               
               $var_gamt = 0;  $var_subamt = 0;   $var_lessamt = 0;  $var_namt = 0; 
              
            } 
                
          echo '<tr ><td colspan="3"></td>';
          echo '<td align="right"></td>';
          echo '<td align="right"></td>';               
          echo '<td align="right">Gross Total :</td>';
          echo '<td align="right">'.number_format($var_tamt,2, '.',',').'</td>';
          echo '</tr>';  
          echo '<tr ><td colspan="3"></td>';
          echo '<td align="right"></td>';
          echo '<td align="right"></td>';               
          echo '<td align="right">NETT Total :</td>';
          echo '<td align="right">'.number_format($var_tnamt,2, '.',',').'</td>';
          echo '</tr>';           
          echo '<tr><td>&nbsp;</td></tr>';

      }   
	  	
   echo '</table>';
   
?>        
        
     <br /><br />
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_invoice.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 include("../Setting/btnprint.php");				 
    			 echo '<input type=submit name = "Submit" value="Summary" class="butsub" style="width: 80px; height: 32px">';

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
