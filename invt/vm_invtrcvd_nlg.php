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
  	 $sql = "select * from invtrcvd_nlg";
     $sql .= " where rcvdno ='".$var_ordno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $custcd = $row['custcd'];
     $rcvddte = date('d-m-Y', strtotime($row['rcvddte']));
     $refdte = date('d-m-Y', strtotime($row['refdte']));     
     $srefno = htmlentities($row['refno']);
     $remark = $row['remark'];
     $posted = $row['posted'];
     
     if ($posted =='N')
     {
     	$post_desc = 'Not Posted';
     }else{
     	$post_desc = 'Posted';
     }
     
     $sql = "select name from customer_master";
     $sql .= " where custno ='".$custcd."'";
     $sql_result = mysql_query($sql);
     $rowq = mysql_fetch_array($sql_result);
     $name = $rowq['name'];

     
  ?> 
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">VIEW NLG GOODS RECEIVED</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Received No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="textnoentry1" name="srcvdno" id="srcvdnoid" type="text" readonly style="width: 204px;" value = "<?php echo $var_ordno; ?>">         
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
		   	<input class="textnoentry1" name="sacustcd" id="sacustcd" type="text" readonly style="width: 300px;" value = "<?php echo $name; ?>"></td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Received Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="textnoentry1" name="sarcvddte" id ="sarcvddte" type="text" style="width: 128px;" value="<?php  echo $rcvddte; ?>" readonly>
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
	  	   <td style="width: 122px">DO No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="textnoentry1" name="srefno" id="srefnoid" type="text" maxlength="45" style="width: 204px;" value="<?php echo $srefno; ?>" readonly></td>
			<td style="width: 10px"></td>
			<td style="width: 204px"> Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="textnoentry1" name="sarefdte" id ="sarefdte" type="text" style="width: 128px;" value="<?php  echo $refdte; ?>" readonly>
		   </td>
		  </tr>
		  <tr>
	  	   <td ></td>
	  	   <td >Remark</td>
	  	   <td >:</td>
	  	   <td colspan="5">
			<input class="textnoentry1" name="saremark" id="saremarkid" type="text" maxlength="100" style="width: 463px;" value="<?php echo $remark; ?>" readonly></td>
		     </td>
	  	  </tr>
			<tr>
	  	   <td ></td>
	  	   <td >Posted</td>
	  	   <td >:</td>
	  	   <td colspan="5">
			<input class="textnoentry1" name="posted" id="saremarkid0" type="text" maxlength="5" style="width: 30px;" value="<?php echo $posted; ?>" readonly></td>
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
             	$sql = "SELECT x.* FROM invtrcvddet_nlg x";
             	$sql .= " Where x.rcvdno ='".$var_ordno."'";
	    		$sql .= " ORDER BY x.proseq";  
			  	$rs_result = mysql_query($sql); 
			  	
			   
			    $i = 1;   $tamt = 0;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
            		$rowq['proqty']  = number_format($rowq['proqty'], 0, '', '');
                $sproamt = $rowq['proqty'] * $rowq['prounipri'];
                $tamt += $sproamt;
                
                $sql = "select description from product ";
				$sql .= " where productcode = '". $rowq['procd']."'";
				$sql_result = mysql_query($sql);
				
				if ($sql_result <> FALSE)
				{
					$row = mysql_fetch_array($sql_result);
					$description= $row[0];
				}


             		echo '<tr class="item-row">';
                	echo '<td style="width: 30px"><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>';
                	echo '<td style="width: 178px"><input name="procd[]" value="'.$rowq['procd'].'" tProCd1='.$i.' id="procd'.$i.'" style="width: 175px" readonly></td>';
                	echo '<td><input name="procdname[]" value="'.$description.'" id="proconame'.$i.'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;"></td>';
                	echo '<td><input name="prouom[]" id="prouom'.$i.'" value="'.$rowq['prouom'].'" readonly="readonly" style="width: 75px; border-style: none; border-color: inherit; border-width: 0;"></td>';
                	echo '<td style="width: 137px"><input name="prooupri[]" id="prooupri'.$i.'" value="'.$rowq['prounipri'].'" style="width: 89px; text-align:right;" readonly></td>';
                  echo '<td style="width: 100px"><input name="proorqty[]" value="'.$rowq['proqty'].'" id="proordqty'.$i.'" style="width: 97px; text-align:center;" readonly></td>';
				        	echo '<td style="width: 242px"><input name="proouamt[]" id="proouamt'.$i.'" value="'.number_format($sproamt, 2, '.', ',').'" readonly="readonly" style="width: 116px; border-style: none; border-color: inherit;  text-align:right;"></td>';
             		echo '</tr>';
                    $i = $i + 1;
                }
               
                if ($i == 1){
                	$rowq['proqty']  = number_format($rowq['proqty'], 0, '', '');
                  $sproamt = $rowq['proqty'] * $rowq['prounipri'];
                  $tamt += $sproamt;
                  
                	echo '<tr class="item-row">';
                	echo '<td style="width: 30px"><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>';
                	echo '<td style="width: 178px"><input name="procd[]" value="'.$rowq['procd'].'" tProCd1='.$i.' id="procd'.$i.'" style="width: 175px" readonly></td>';
                	echo '<td><input name="procdname[]" value="'.$rowq['description'].'" id="proconame'.$i.'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;"></td>';
                	echo '<td><input name="prouom[]" id="prouom'.$i.'" value="'.$rowq['prouom'].'" readonly="readonly" style="width: 75px; border-style: none; border-color: inherit; border-width: 0;"></td>';
                	echo '<td style="width: 137px"><input name="prooupri[]" id="prooupri'.$i.'" value="'.$rowq['prounipri'].'" style="width: 89px; text-align:right;" readonly></td>';
                  echo '<td style="width: 100px"><input name="proorqty[]" value="'.$rowq['proqty'].'" id="proordqty'.$i.'"  style="width: 97px; text-align:center;" readonly></td>';
		        		  echo '<td style="width: 242px"><input name="proouamt[]" id="proouamt'.$i.'" value="'.number_format($sproamt, 2, '.', ',').'" readonly="readonly" style="width: 116px; border-style: none; border-color: inherit;  text-align:right;"></td>';
             		echo '</tr>';
                    $i = $i + 1;

                }
             ?>
             </tbody>
           </table>
		  <table class="general-table" style="width: 958px">
          	 <tr>
              <td style="width: 835px; text-align:right" >Total : </td>              
              <td align="right">
              <input readonly="readonly" name="totamt" id ="totamtid" type="text" style="width: 116px;" class="textnoentry2" value="<?php echo number_format($tamt, 2, '.', ','); ?>">
              </td>
             </tr>
        </table>            
           
     <br /><br />
     
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_nlgrcvd_mas.php?menucd=".$var_menucode;
			
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
