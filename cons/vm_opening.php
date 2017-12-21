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
      $rm_opening_id = $_GET['rm_opening_id'];
      include("../Setting/ChqAuth.php");
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

.general-table #procomat                        { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
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


function calsalrm(){
	var exftramt  = document.InpJobFMas.totalexft0.value;
	var persaltax = document.InpJobFMas.totaltaxd.value;
	var saltaxamt;
	var saltotamt;
	
	if (exftramt == ""){
	 	exftramt = 0;
	 	document.InpJobFMas.totalexft0.value = parseFloat(exftramt).toFixed(4);
	}
	if (persaltax == ""){
	 	exftramt = 0;
	 	document.InpJobFMas.totaltaxd.value = parseFloat(persaltax);
	}
	saltaxamt = parseFloat(exftramt) * (parseFloat(persaltax)/100);
	saltotamt = parseFloat(exftramt) + parseFloat(saltaxamt);
	document.InpJobFMas.totalsaltid.value = parseFloat(saltaxamt).toFixed(4);
	document.InpJobFMas.totalamtid.value = parseFloat(saltotamt).toFixed(4);
}

</script>
</head>

  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->
<body>

  <?php
  	 $sql = "select * from consopen";
     $sql .= " where opening_id ='".$rm_opening_id."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $openingdate = date('d-m-Y', strtotime($row['openingdate']));
     $refno = $row['refno'];
     $custcode = $row['custcode'];
	 $remark = $row['remark'];
     $create_by = $row['create_by'];
     $create_on= $row['create_on'];
     
     
     $result = mysql_query("select name from customer_master where custno ='$custcode'")or die ("Cant get cust desc : " .mysql_error());
	 $row = mysql_fetch_row($result);
	 $custdesc = $row[0];


  ?>
  <div class="contentc">

	<fieldset name="Group1" style=" width: 950px;" class="style2">
	 <legend class="title">COUNTER STOCK OPENING DETAILS&nbsp; :<?php echo $var_revno;?></legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
	   
		<table style="width: 886px">
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 126px">Opening No</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 239px">
			<input name="openingno" id="openingnoid" type="text" maxlength="10" style="width: 191px;" readonly="readonly" value="<?php echo $rm_opening_id; ?>">
	  	  </tr>  
	  	    <tr>
	   	    <td></td>
	  	    <td style="width: 126px">&nbsp;</td>
	  	    <td style="width: 13px">&nbsp;</td>
	  	    <td style="width: 239px">
			&nbsp;</tr>  
	  	    </tr>
	  	    <tr>
	   	    <td></td>
	  	    <td style="width: 126px">Customer</td>
	  	    <td style="width: 13px">:</td>
	  	    <td colspan="5">
			<input name="customer" id="openingnoid0" type="text" maxlength="10" style="width: 460px;" readonly="readonly" value="<?php echo $custdesc; ?>"></td>
	  	    </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 239px"></td>
	  	  </tr>

	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 126px">Ref No</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 239px">
			<input name="refno" id="refnoid" type="text" maxlength="10" style="width: 191px;" readonly="readonly" value="<?php echo $refno; ?>">
			</td>
			<td style="width: 29px"></td>
			<td style="width: 136px">Opening Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		    <input class="inputtxt" readonly="readonly" name="openingdate" id ="openingdateid" type="text" style="width: 106px;" value="<?php  echo $openingdate; ?>">
		    </td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 239px"></td>
	  	  </tr>
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 126px">Remark</td>
	  	    <td style="width: 13px">:</td>
	  	    <td colspan="5">
			<input name="remark" id="refnoid" type="text" maxlength="100" style="width: 690px;" readonly="readonly" value="<?php echo $remark; ?>">
			</td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 239px"></td>
	  	  </tr>
	   	  <tr>

	   	    <td></td>
	  	    <td style="width: 126px">Created By</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 239px">
			<input name="create_by" id="create_byid" type="text" maxlength="10" style="width: 191px;" readonly="readonly" value="<?php echo $create_by; ?>">
			</td>
			<td style="width: 29px"></td>
			<td style="width: 136px">Created On</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		    <input class="inputtxt" readonly="readonly" name="create_on" id ="create_onid" type="text" style="width: 106px;" value="<?php  echo $openingdate; ?>">
		    </td>
	  	  </tr>  


	  	  		  	

		  	
	  	  </table>
		 
		<table style="width: 886px">
	   	  <tr>

	   	    <td></td>
	  	    <td style="width: 126px">Modify By</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 239px">
			<input name="create_by0" id="create_byid0" type="text" maxlength="10" style="width: 191px;" readonly="readonly" value="<?php echo $create_by; ?>">
			</td>
			<td style="width: 29px"></td>
			<td style="width: 136px">Modify On</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		    <input class="inputtxt" readonly="readonly" name="create_on0" id ="create_onid0" type="text" style="width: 106px;" value="<?php  echo $openingdate; ?>">
		    </td>
	  	  </tr>  


	  	  		  	

		  	
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style = "width: 900px;">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px">#</th>
              <th class="tabheader">Group Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Qty /Pack</th>
              <th class="tabheader">Opening Qty</th>

             </tr>
            </thead>
            <tbody>            
             <?php
             	$sql = "SELECT * FROM consopendet";
             	$sql .= " Where opening_id='".$rm_opening_id ."'"; 
	    		$sql .= " ORDER BY seqno";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
					echo '<tr class="item-row">';	
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
					echo "<td><input name='promat[]' value='".$rowq["item_code"]."' id='procomatid' style='width: 250px; border-style: none;' readonly='readonly'></td>";
                	echo '<td><input name="procodesc[]" value="'.$rowq['description'].'" id="procomat1" style="width: 300px; border-style: none;" readonly="readonly"></td>';
             		echo '<td><input name="procoum[]" value="'.$rowq['oum'].'" id="procoum" style="width: 75px; border-style: none;" readonly="readonly"></td>';       	
                	echo '<td><input name="procomark[]" id="procomark" readonly="readonly" style="width: 75px; border:0;" value="'.$rowq['qtyperpack'].'"></td>';
                	echo '<td><input name="prococost[]" value="'.$rowq['totalqty'].'" id="prococost1" readonly="readonly" style="width: 75px; border:0;"></td>';
                 	
                	echo ' </tr>';
                	$i = $i + 1;
                }
             ?>          
            </tbody>
           </table>

      	
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_cons_opening.php?menucd=".$var_menucode;
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
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
