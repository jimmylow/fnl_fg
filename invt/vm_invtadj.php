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
      $rm_adj_id = $_GET['rm_adj_id'];
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

</script>
</head>

<?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->
<body>
  
  <?php
  	 $sql = "select * from invtadj";
     $sql .= " where adj_id ='".$rm_adj_id."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $adjdate = date('d-m-Y', strtotime($row['adjdate']));
     $refno = $row['refno'];
     $remark = $row['remark'];
     $create_by = $row['create_by'];

  ?>
  <div class="contentc">

	<fieldset name="Group1" style=" width: 920px;" class="style2">
	 <legend class="title">STOCK ADJUSTMENT DETAILS&nbsp; :<?php echo $rm_adj_id;?></legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
	   
		<table style="width: 881px">
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 126px">Adjustment No</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 239px">
			<input name="adjno" id="adjnoid" type="text" maxlength="10" style="width: 191px;" readonly="readonly" value="<?php echo $rm_adj_id; ?>" class="textnoentry">
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
			<input name="refno" id="refnoid" type="text" maxlength="10" style="width: 191px;" readonly="readonly" value="<?php echo $refno; ?>" class="textnoentry">
			</td>
			<td style="width: 29px"></td>
			<td style="width: 136px">Adjustment Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		    <input class="textnoentry" readonly="readonly" name="adjdate" id ="adjdateid" type="text" style="width: 106px;" value="<?php  echo $adjdate; ?>">
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
			<input name="remark" id="refnoid" type="text" maxlength="100" style="width: 688px;" readonly="readonly" value="<?php echo $remark; ?>" class="textnoentry">
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
			<input name="create_by" id="create_byid" type="text" maxlength="10" style="width: 191px;" readonly="readonly" value="<?php echo $create_by; ?>" class="textnoentry">
			</td>
			<td style="width: 29px"></td>
			<td style="width: 136px">Created On</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		    <input class="textnoentry" readonly="readonly" name="create_on" id ="create_onid" type="text" style="width: 106px;" value="<?php  echo $adjdate; ?>">
		    </td>
	  	  </tr>  


	  	  		  	

		  	
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 841px">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px">#</th>
              <th class="tabheader">Product Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Onhand Bal</th>
              <th class="tabheader">Physical Qty</th>
              <th class="tabheader">Adjust Qty</th>
             </tr>
            </thead>
            <tbody>            
             <?php
             
             	$sql = "SELECT * FROM invtadjdet";
             	$sql .= " Where adj_id='".$rm_adj_id ."'"; 
	    		    $sql .= " ORDER BY seqno";  
		          $rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
					echo '<tr class="item-row">';	
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
					echo "<td><input name='promat[]' value='".$rowq["item_code"]."' id='procomatid' style='width: 250px; border-style: none;' readonly='readonly'></td>";
                	echo '<td><input name="procodesc[]" value="'.$rowq['description'].'" id="procomat1" style="width: 300px; border-style: none;" readonly="readonly"></td>';
             		echo '<td><input name="procoum[]" value="'.$rowq['oum'].'" id="procoum" style="width: 75px; border-style: none;" readonly="readonly"></td>';       	
                	echo '<td><input name="procomark[]" id="procomark" readonly="readonly" style="width: 75px; border:0;" value="'.$rowq['onhandbal'].'"></td>';
                	echo '<td><input name="prococost[]" value="'.$rowq['physicalqty'].'" id="prococost1" readonly="readonly" style="width: 75px; border:0;"></td>';
                	echo '<td><input name="prococost[]" value="'.$rowq['adjqty'].'" id="prococost1" readonly="readonly" style="width: 75px; border:0;"></td>';
                	
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
				 $locatr = "m_adj_mas.php?menucd=".$var_menucode;
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
