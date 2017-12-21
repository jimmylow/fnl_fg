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
    
      $var_ordno = $_GET['trf_id'];
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
<script  type="text/javascript" src="jq-trf-script.js"></script>


<script type="text/javascript"> 

</script>
</head>

<body>
	  <?php include("../topbarm.php"); ?> 
<!--  <?php include("../sidebarm.php"); ?> -->

  <?php
  	 $sql = "select * from cinvttrf";
     $sql .= " where trf_id ='".$var_ordno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $vmfrmcd = $row['frmcustcd'];
     $vmtocd = $row['tocustcd'];
     $vmmthyr = $row['mthyr'];
     $trfdte = date('d-m-Y', strtotime($row['trfdate']));
     $remark = htmlentities($row['remark']);
     
     
  ?> 
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 857px;" class="style2">
	 <legend class="title">VIEW STOCK TRANSFER BETWEEN COUNTER</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Transfer No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="textnoentry" name="trfno" id="trfnoid" type="text" readonly style="width: 204px;" value = "<?php echo $var_ordno; ?>">         
		   </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px"></td>
		   <td></td>
		   <td style="width: 284px"></td>
       </tr>
		  <tr>
	  	   <td ></td>
	  	   <td >From Counter</td>
	  	   <td >:</td>
	  	   <td >
		   	<select name="frmctr" id="frmctr" style="width: 268px">
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
        if ($vmfrmcd == $row['counter']) { echo "selected"; }
        echo '>'.$row['counter']." | ".$row['name'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>
		     </td>
		   <td ></td>
		   <td >To Counter</td>
		   <td>:</td>
		   <td >
		   	<select name="toctr" id="toctr" style="width: 268px" >
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
        if ($vmtocd == $row['counter']) { echo "selected"; }
        echo '>'.$row['counter']." | ".$row['name'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>       
		   </td>
	  	  </tr>         
	   	   <tr>
	   	    <td></td>
	  	    <td >MM/YYYY</td>
	  	    <td >:</td>
	  	    <td >
			<input class="inputtxt" name="samthyr" id="samthyrid" type="text" maxlength="45" style="width: 100px;" value="<?php echo $vmmthyr; ?>">
      </td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Transfer Date</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   <input class="inputtxt" name="trfdte" id ="trfdte" type="text" style="width: 128px;" value="<?php  echo $trfdte; ?>" readonly>
         </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px"></td>
		   <td></td>
		   <td style="width: 284px"></td>
	  	  </tr>	              
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Remark</td>
	  	   <td style="width: 13px">:</td>
	  	   <td colspan="5">
			<input class="inputtxt" name="saremark" id="saremarkid" type="text" maxlength="100" style="width: 463px;" value="<?php echo $remark; ?>" readonly></td>
		     </td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px"></td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px"></td>
		   <td></td>
		   <td style="width: 284px"></td>
	  	  </tr>	  	  
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 857px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Product Code</th>
              <th class="tabheader">Description</th>
              <!-- <th class="tabheader">To Prod. Code</th>
              <th class="tabheader">Description</th>  -->
              <th class="tabheader">Transfer Qty(PCS)</th>
             </tr>
            </thead>
            <tbody>
             <?php
             	$sql = "SELECT * from cinvttrfdet ";
             	$sql .= " Where trf_id ='".$var_ordno."'";
	    		    $sql .= " ORDER BY seqno";  
			  	    $rs_result = mysql_query($sql); 
			   
			    $i = 1;  
				while ($rowq = mysql_fetch_assoc($rs_result)){ 


             		echo '<tr class="item-row">';
                	echo '<td style="width: 30px"><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>';
                	echo '<td ><input name="procofrm[]" value="'.$rowq['from_code'].'" tProItem1='.$i.' id="procofrm'.$i.'" class="inputtxt" style="width: 100px" readonly="readonly" ></td>';
                	echo '<td><input name="procofdesc[]" value="'.$rowq['fdesc'].'" id="procofdesc'.$i.'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 200px;"></td>';
                	//echo '<td><input name="procoto[]" id="procoto'.$i.'" value="'.$rowq['to_code'].'" class="inputtxt" style="width: 100px;" readonly="readonly"></td>';
                	//echo '<td><input name="procotdesc[]" id="procotdesc'.$i.'" value="'.$rowq['tdesc'].'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 200px;"></td>';
                  echo '<td><input name="issueqty[]" value="'.$rowq['trfqty'].'" id="issueqty'.$i.'" style="width: 75px;" readonly="readonly"></td>';
             		echo '</tr>';
                    $i = $i + 1;
                }
               
                if ($i == 1){
                  
                	echo '<tr class="item-row">';
                	echo '<td style="width: 30px"><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>';
                	echo '<td ><input name="procofrm[]" value="'.$rowq['from_code'].'" tProItem1='.$i.' id="procofrm'.$i.'" class="autosearch" style="width: 100px" readonly="readonly" ></td>';
                	echo '<td><input name="procofdesc[]" value="'.$rowq['fdesc'].'" id="procofdesc'.$i.'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 200px;"></td>';
                	//echo '<td><input name="procoto[]" id="procoto'.$i.'" value="'.$rowq['to_code'].'" class="autosearch" style="width: 100px;" readonly="readonly"></td>';
                	//echo '<td><input name="procotdesc[]" id="procotdesc'.$i.'" value="'.$rowq['tdesc'].'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 200px;"></td>';
                  echo '<td><input name="issueqty[]" value="'.$rowq['trfqty'].'" id="issueqty'.$i.'" style="width: 75px;" readonly="readonly"></td>';
              		echo '</tr>';
                    $i = $i + 1;

                }
             ?>
             </tbody>
           </table>       
           
     <br /><br />
     
		 <table>
		  	<tr>
				<td style="width: 857px; height: 22px;" align="center">
				<?php
				 $locatr = "m_ctrf_mas.php?menucd=".$var_menucode;
			
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
