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
        $pcredit = $_POST['sordno'];
        
         #----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmpccretot where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        
        $lessrm = 0;   $netrm = 0;
        $sql  = "SELECT distinct sptype";
		$sql .= " FROM ccreditdet2";
  		$sql .= " where creditno = '$pcredit'";	 	
		$rs_result = mysql_query($sql); 
		while ($row = mysql_fetch_assoc($rs_result)) { 
		    $sptype = mysql_real_escape_string($row['sptype']);
		    
		    $sumamttyp = 0;
		    $sql1  = "select sum(sprounipri * overqty) from ccreditdet1";
		    $sql1 .= " where creditno = '$pcredit' and sptype = '$sptype'";
		    $sql_resultc = mysql_query($sql1);
        	$rowc = mysql_fetch_array($sql_resultc);
        	$sumamttyp = $rowc['0'];
        	if (empty($sumamttyp)){$sumamttyp = 0;}
        	
        	$rate = 0;
        	$sql1  = "select rate from ccreditdet2";
		    $sql1 .= " where creditno = '$pcredit' and sptype = '$sptype'";
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
		
	    $sql1  = "select bearrate from ccreditmas";
		$sql1 .= " where creditno = '$pcredit'";
		$sql_resultc = mysql_query($sql1);
        $rowc = mysql_fetch_array($sql_resultc);
        $bearrate = $rowc['0'];
        if (empty($bearrate)){$bearrate = 0;}        
		$bearrm = $netrm * ($bearrate/100);
		if (empty($bearrm)){$bearrm = 0;}
		$sumnet = $netrm - $bearrm; 
		
		$sqli  = " Insert Into tmpccretot";
   		$sqli .= " Values ('$var_loginid', '$lessrm', '$netrm', '$bearrm', '$sumnet')";
   		mysql_query($sqli) or die("Unable Save In Temp Table ".mysql_error());

        $fname = "ccredit_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&ccre=".$pcredit."&menuc=".$var_menucode."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $dest .= urlencode(realpath($fname));

        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../cons/vm_creditnote.php?sorno=".$pcredit."&menucd=".$var_menucode;
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
  	 $sql = "select * from ccreditmas";
     $sql .= " where creditno ='".$var_ordno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $custcd = $row['custcd'];
     $creditdte = date('d-m-Y', strtotime($row['creditdte']));
     $mthyr = $row['mthyr'];
     $period = $row['period'];
     $var_bear = $row['bearrate'];
          
  ?>  

  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">VIEW CREDIT NOTE</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
	   	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Credit Note No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sordno" id="sordnoid" type="text" readonly style="width: 204px;" value = "<?php echo $var_ordno; ?>">                  
         </td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Credit Note Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="creditdte" id ="creditdte" type="text" style="width: 128px;" value="<?php echo $creditdte; ?>" readonly>
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
	  	   <td style="width: 201px">
		   	<select name="frmctr" id="frmctr" style="width: 268px" >
			 <?php
              $sql = "select x.counter, y.name from counter x, customer_master y";
              $sql .= " where y.custno = x.counter";
              $sql .= " and sort_auto = 'Y'"; //only those counter need to send DN
              $sql .= " ORDER BY x.counter ASC";
              $sql_result = mysql_query($sql);
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['counter'].'"';;
        if ($scustcd == $row['counter']) { echo "selected"; }
        echo '>'.$row['counter']." | ".$row['name'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>
		     </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px"></td>
		   <td></td>
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
       echo '<th class="tabheader" align="right">U.Price</th>';
       echo '<th class="tabheader" align="right">Qty</th>';
       echo '<th class="tabheader" >UOM</th>';
       echo '<th class="tabheader" align="right">Qty(PCS)</th>';         
       echo '<th class="tabheader" align="right">Gross Amt</th>';
       echo '<th class="tabheader" align="right">Less %</th>';
       echo '<th class="tabheader" align="right">Less RM</th>';
       echo '<th class="tabheader" align="right">Nett Amt</th>';                     
       echo '</tr>';
                 
         $var_currtype = "";   $var_prevtype = "";  $var_amt = 0;  $var_subamt = 0;    $var_bearamt = 0;
         $var_lessamt = 0;  $var_namt = 0;   $var_tamt = 0;   $var_tlessamt = 0;   $var_tnamt = 0;
         
         $sql3 = " select sptype, rate from ccreditdet2";
         $sql3 .= " where creditno = '".$var_ordno."'";
         
         //echo $sql3;
         $tmp3 = mysql_query ($sql3) or die ("cant get rate : ".mysql_error());
         
         if(mysql_numrows($tmp3) > 0) {
            while ($row = mysql_fetch_array($tmp3)) {
         
         $sql2 = " select * from ccreditdet1";
         $sql2 .= " where creditno = '$var_ordno'";
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
             
             $var_totqty = $var_uqty * $row2['shortqty'];              

             $var_amt =  $row2['sprounipri'] *  $var_totqty; 
             $var_subamt += $var_amt;
             $var_tamt += $var_amt;                
                        
             echo '<tr>';
             echo '<td>'.$row2['sprocd'].'</td>';
             echo '<td align="right">'.$row2['sprounipri'].'</td>';
             echo '<td align="right">'.$row2['shortqty'].'</td>';
             echo '<td>'.$row2['uom'].'</td>';
             echo '<td align="right">'.$var_totqty.'</td>';              
             echo '<td align="right">'.number_format($var_amt,2, '.',',').'</td>';
             echo '<td colspan="3"></td>';
             echo '</tr>';           
             
             
            } //while ($row2 
           } 
                        
               $sql2 = "select salestype_desc from salestype_master ";
               $sql2 .= " where salestype_code = '".$row['sptype']."'";
               
               //echo $sql2;
               $tmptype = mysql_query($sql2) or die ("Cant get type : ".mysql_error());
               $rsttype = mysql_fetch_object($tmptype);

               $var_rate = $row['rate'];
               $var_lessamt = $var_subamt * $var_rate /100; $var_namt = $var_subamt - $var_lessamt;
               $var_tlessamt += $var_lessamt;
               $var_tnamt += $var_namt;
               
               echo '<tr bgcolor="#efefef">';
               echo '<td colspan="5">Type : '.$row['sptype'].' - '.$rsttype->salestype_desc.'</td>';
               echo '<td align="right">'.number_format($var_subamt,2, '.',',').'</td>';
               echo '<td align="right">'.number_format($var_rate,2, '.',',').'</td>';               
               echo '<td align="right">'.number_format($var_lessamt,2, '.',',').'</td>';
               echo '<td align="right">'.number_format($var_namt,2, '.',',').'</td>';
               echo '</tr>';
               echo '<tr><td>&nbsp;</td></tr>';
               
               $var_gamt = 0;  $var_subamt = 0;   $var_lessamt = 0;  $var_namt = 0; 
              
            } 
                
          $var_bearamt = $var_bear /100 * $var_tnamt;
          $var_nbearamt = $var_tnamt - $var_bearamt;
                
          echo '<tr ><td colspan="5">Total :</td>';
          echo '<td align="right">'.number_format($var_tamt,2, '.',',').'</td>';
          echo '<td align="right"></td>';               
          echo '<td align="right">'.number_format($var_tlessamt,2, '.',',').'</td>';
          echo '<td align="right">'.number_format($var_tnamt,2, '.',',').'</td>';
          echo '</tr>';
          echo '<tr bgcolor="#efefef"><td colspan="5"></td>';
          echo '<td align="right">Bear :</td>';
          echo '<td align="right">'.number_format($var_bear,2, '.',',').'</td>';               
          echo '<td align="right">'.number_format($var_nbearamt,2, '.',',').'</td>';
          echo '<td align="right">'.number_format($var_bearamt,2, '.',',').'</td>';
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
				 $locatr = "m_creditnote.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 include("../Setting/btnprint.php");
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
