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
      $var_progcode = $_GET['menun'];
      include("../Setting/ChqAuth.php");
    }
   
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import "../css/styles.css";

.style2 {
	margin-right: 0px;
}
.style3 {
	color: #FF0000;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
</head>
<?php include("../topbarm.php"); ?> 
<!--<?php include("../sidebarm.php"); ?>--> 
<?php
	 $sql = "select * from arpthea_set";
     $sql .= " where menucode ='".$var_progcode."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $ptselprog = $row['menucode'];
     $ptcompnm  = $row['compname'];
     $pttitle   = $row['rpttitle'];
     $ptadd1    = $row['add_line_1'];
     $ptadd2    = $row['add_line_2'];
     $ptadd3    = $row['add_line_3'];
     $pttelno   = $row['telno'];
     $ptfaxno   = $row['faxno'];
     $ptemail   = $row['emailadd'];
     $pthomeurl = $row['homeurl'];
     $pgstno    = $row['gstno'];
     
     $sql = "select menu_name from menud ";
     $sql .= " where menu_code ='".$ptselprog."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);
     $progname = $row[0];
?>
<body>

	<div class ="contentc">
	<fieldset name="Group1" class="style2" style="width: 756px;  height: 770px;">
	 <legend class="title">REPORT ELEMENT FOR <?php echo $progname; ?></legend>
	  <br>
	  <form name="InpRptMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" enctype="multipart/form-data" style="height: 200px; width: 725px;" onsubmit="return validateForm()">
		<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
		  <tr>
	  	    <td></td>
	  	    <td style="width: 165px">Program Name</td>
	  	    <td>:</td>
	  	    <td>
	  	    	<input class="inputtxt" name="rselprog" id ="rselprog" type="text" readonly="readonly" style="width: 483px;" value="<?php echo $progname; ?>">
   			</td>
	  	  </tr>
          <tr>
	  	    <td></td>
	  	    <td style="width: 165px">Report Company Name</td>
	  	    <td>:</td>
	  	    <td>
   			<input class="inputtxt" name="rhea_compnm" id ="hea_compnmid" type="text" readonly="readonly" style="width: 506px;" value="<?php echo $ptcompnm; ?>">
			</td>
	  	  </tr>
	  	    <tr>
	  	    <td></td>
	  	    <td style="width: 165px">GST ID No</td>
	  	    <td>:</td>
	  	    <td>
   			<input class="inputtxt" name="gstno" id ="gstno" type="text" maxlength="80" style="width: 506px;"  readonly="readonly" value="<?php echo $pgstno; ?>"/>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 165px">Report Title</td>
	  	    <td>:</td> 
            <td>
   			<input class="inputtxt" name="rptitle" id ="rptitle" type="text" readonly="readonly" style="width: 506px;" value="<?php echo $pttitle; ?>"></td> 
	   	  </tr> 
	   	  <tr>
	   	  <td>&nbsp;</td>
	   	  </tr>
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 165px">Address Line 1 </td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="rptadd1" id ="rptadd1id" type="text" readonly="readonly" style="width: 409px" value="<?php echo $ptadd1; ?>"></td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 165px">Address Line 2</td>
	  	   <td>:</td>
	  	   <td>
			<input class="inputtxt" name="rptadd2" id ="rptadd2id" type="text" readonly="readonly" style="width: 409px" value="<?php echo $ptadd2; ?>"></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 165px">Address Line 3</td>
	  	   <td>:</td>
	  	   <td>
			<input class="inputtxt" name="rptadd3" id ="rptadd3id" type="text" readonly="readonly" style="width: 409px" value="<?php echo $ptadd3; ?>"></td> 	
	  	  </tr>
    	  <tr>
	  	    <td></td>
	  	    <td style="width: 165px">&nbsp;</td>
	  	    <td></td>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 165px">Telephone No</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="rpttelno" id ="rpttelno" type="text" readonly="readonly" style="width: 250px" value="<?php echo $pttelno; ?>"></td>  
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 165px">Fax No</td>
            <td>:</td>
			<td>
			<input class="inputtxt" name="rptfaxno" id ="rptfaxno" type="text" readonly="readonly" style="width: 250px" value="<?php echo$ptfaxno; ?>"></td>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 165px">E-Mail</td>
            <td>:</td>
			<td>
			<input class="inputtxt" name="rptemail" id ="rptemail" type="text" readonly="readonly" style="width: 293px" value="<?php echo $ptemail; ?>"></td>
	  	  </tr>
		  <tr>
	  	    <td></td>
	  	    <td style="width: 165px">Home Page</td>
            <td>:</td>
			<td>
			<input class="inputtxt" name="rpthomeurl" id ="rpthomeurl" type="text" readonly="readonly" style="width: 292px" value="<?php echo $pthomeurl; ?>"></td>
	  	  </tr>
		  <tr>
		  	<td>&nbsp;</td>
		  </tr>
		  </table>
		  
		  <table id="dataTable" align="center" style="width: 589px" border="1px solid black;" class="general-table">
	  	     <thead>
	  	     <tr>
	  	        <th class="tabheader" align="center" style="width: 359px;">Remark</th>
			 </tr>
         	 </thead>
         	 <tbody>
         	 <?php
         	 	$sql = "SELECT rptrmk, seqno ";
		    	$sql .= " FROM arptrmk_set Where menucode ='".$var_progcode."'";
    			$sql .= " ORDER BY seqno";  
				$rs_result = mysql_query($sql); 

         	 	while ($rowq = mysql_fetch_assoc($rs_result)) { 
         	 	 echo '<tr>';
         	
             	 echo '<td style="width: 600px">'.$rowq['rptrmk'].'</td>';
			
             	 echo '</tr>';
              	 
             	}
             ?>
             </tbody>
           </table>
		    
		  <table style="width: 723px">
	      <tr><td>&nbsp;</td></tr>
	  	  <tr>
	  	   	<td align="center">
	  	   	<?php
		  	    $locatr = "m_rpthead_set.php?menucd=".$var_menucode;			
				echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
	  	   	?>
	  	   	</td>
		  </tr>
	     
	  	</table>
	   </form>	
	
	     <br/>
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
