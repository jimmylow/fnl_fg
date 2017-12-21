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
   
    if ($_POST['Submit'] == "Update") {
      $ptselprog = $_POST['progcode'];
      $ptcompnm  = $_POST['rhea_compnm'];
      $pttitle   = $_POST['rptitle'];
      $ptadd1    = $_POST['rptadd1'];
      $ptadd2    = $_POST['rptadd2'];
      $ptadd3    = $_POST['rptadd3'];
      $pttelno   = $_POST['rpttelno'];
      $ptfaxno   = $_POST['rptfaxno'];
      $ptemail   = $_POST['rptemail'];
      $pthomeurl = $_POST['rpthomeurl'];
      $var_menucode  = $_POST['menudcode'];
      $pgstno    = $_POST['gstno'];
          
      if ($ptselprog <> ""){
        
        	$moby= $var_loginid;
        	$moon= date("Y-m-d H:i:s");
        	$sql  = "Update arpthea_set Set rpttitle = '$pttitle', compname = '$ptcompnm ', ";
        	$sql .= "                   add_line_1 ='$ptadd1', add_line_2 = '$ptadd2', ";
			$sql .= "                   add_line_3 ='$ptadd3', telno = '$pttelno', ";	
			$sql .= "                   faxno ='$ptfaxno', emailadd = '$ptemail', ";	
			$sql .= "                   homeurl ='$pthomeurl', lastlogin = '$moby', ";	
			$sql .= "                   lastupd ='$moon', gstno = '$pgstno'";
			$sql .= " where menucode ='$ptselprog'";	
			mysql_query($sql) or die(mysql_error());
			
			$sql = "delete from arptrmk_set where menucode ='".$ptselprog."'";
			mysql_query($sql);
	
			
			$i = 1;
			foreach($_POST['rptrmk'] as $value ) {
				if ($value <> ""){
					$sql = "INSERT INTO arptrmk_set values 
						('$ptselprog', '$value', '$i')";
					mysql_query($sql) or die(mysql_error());
					$i = $i + 1;
				}			
			}
       
         	$backloc = "../admin_set/m_rpthead_set.php?stat=1&menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 

      }else{
            $backloc = "../admin_set/rpthead_set.php?stat=4&menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 
      }
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

<script type="text/javascript" charset="utf-8"> 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function addRow(tableID) {

	var table = document.getElementById(tableID);
	var rowCount = table.rows.length; 
	var row = table.insertRow(rowCount);             
	var colCount = table.rows[0].cells.length;             
	
	for(var i = 0; i < colCount; i++) {                 
		var newcell = row.insertCell(i);                 
		newcell.innerHTML = table.rows[rowCount-1].cells[i].innerHTML;
		newcell.id = rowCount;
     
		switch(newcell.childNodes[0].type) {
		//case "hidden":                            
			//newcell.childNodes[0].value = "";
           // newcell.childNodes[0].id = rowCount;				
			//break; 
		case "text":  
		     newcell.childNodes[0].value = "";
             newcell.childNodes[0].id = rowCount;	
             break;
		           
		case "checkbox":                            
			newcell.childNodes[0].checked = false; 
            //newcell.childNodes[0].id = rowCount;			
			break;                    
		case "select-one":                            
			newcell.childNodes[0].selectedIndex = 0; 
            newcell.childNodes[0].id = rowCount;			
			break; 
       	}            
	}
}

function deleteRow(tableID) {
	try {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
         
        if (rowCount > 2){
           table.deleteRow(rowCount - 1);
        }else{
           alert ("No More Row To Remove");
        }
 	}catch(e) {
		alert(e);
	}
}

function validateForm()
{
	var x=document.forms["InpRptMas"]["rselprog"].value;
	if (x==null || x=="")
	{
	alert("Programe For The Report Cannot Be Blank");
	document.InpRptMas.rselprog.focus();
	return false;
	}
   
}

</script>
</head>

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

<body onload="document.InpRptMas.hea_compnmid.focus()">
<?php include("../topbarm.php"); ?> 
<div class ="contentc">

	<fieldset name="Group1" class="style2" style="width: 756px; height: 770px;">
	 <legend class="title">REPORT ELEMENT FOR <?php echo $progname; ?></legend>
	  <br>
	  <form name="InpRptMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" enctype="multipart/form-data" style="height: 200px; width: 725px;" onsubmit="return validateForm()">
		<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<input name="progcode" type="hidden" value="<?php echo $var_progcode;?>">
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
   			<input class="inputtxt" name="rhea_compnm" id ="hea_compnmid" type="text" maxlength="80" style="width: 506px;"  onchange ="upperCase(this.id)" value="<?php echo $ptcompnm; ?>">
			</td>
	  	  </tr>
	  	    <tr>
	  	    <td></td>
	  	    <td style="width: 165px">GST ID No</td>
	  	    <td>:</td>
	  	    <td>
   			<input class="inputtxt" name="gstno" id ="gstno" type="text" maxlength="80" style="width: 506px;" value="<?php echo $pgstno; ?>"/>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 165px">Report Title</td>
	  	    <td>:</td> 
            <td>
   			<input class="inputtxt" name="rptitle" id ="rptitle" type="text" maxlength="80" style="width: 506px;"  onchange ="upperCase(this.id)" value="<?php echo $pttitle; ?>"></td> 
	   	  </tr> 
	   	  <tr>
	   	  <td>&nbsp;</td>
	   	  </tr>
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 165px">Address Line 1 </td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="rptadd1" id ="rptadd1id" type="text" maxlength="80" style="width: 409px" onchange ="upperCase(this.id)" value="<?php echo $ptadd1; ?>"></td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 165px">Address Line 2</td>
	  	   <td>:</td>
	  	   <td>
			<input class="inputtxt" name="rptadd2" id ="rptadd2id" type="text" maxlength="80" style="width: 409px" onchange ="upperCase(this.id)" value="<?php echo $ptadd2; ?>"></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 165px">Address Line 3</td>
	  	   <td>:</td>
	  	   <td>
			<input class="inputtxt" name="rptadd3" id ="rptadd3id" type="text" maxlength="80" style="width: 409px" onchange ="upperCase(this.id)" value="<?php echo $ptadd3; ?>"></td> 	
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
			<input class="inputtxt" name="rpttelno" id ="rpttelno" type="text" maxlength="50" style="width: 250px" onchange ="upperCase(this.id)" value="<?php echo $pttelno; ?>"></td>  
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 165px">Fax No</td>
            <td>:</td>
			<td>
			<input class="inputtxt" name="rptfaxno" id ="rptfaxno" type="text" maxlength="50" style="width: 250px" onchange ="upperCase(this.id)" value="<?php echo$ptfaxno; ?>"></td>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 165px">E-Mail</td>
            <td>:</td>
			<td>
			<input class="inputtxt" name="rptemail" id ="rptemail" type="text" maxlength="50" style="width: 293px" value="<?php echo $ptemail; ?>"></td>
	  	  </tr>
		  <tr>
	  	    <td></td>
	  	    <td style="width: 165px">Home Page</td>
            <td>:</td>
			<td>
			<input class="inputtxt" name="rpthomeurl" id ="rpthomeurl" type="text" maxlength="80" style="width: 292px" value="<?php echo $pthomeurl; ?>"></td>
	  	  </tr>
		  <tr>
		  	<td>&nbsp;</td>
		  </tr>
		  </table>
		  
		  <table id="dataTable" align="center" style="width: 588px" border="1px solid black;" class="general-table">
	  	     <thead>
	  	     <tr>
	  	        <th class="tabheader" align="center" style="width: 359px;">Remark</th>
			 </tr>
         	 </thead>
         	 <tbody>
         	 <?php
         	 
         	 	$var_sql = " SELECT count(*) as cnt from arptrmk_set";
	      		$var_sql .= " Where menucode ='".$var_progcode."'";
	      		$query_id = mysql_query($var_sql) or die ("Cant Check Product Job Code");
	      		$res_id = mysql_fetch_object($query_id);
             
	      		if ($res_id->cnt > 0 ){

         	 		$sql = "SELECT rptrmk, seqno ";
		    		$sql .= " FROM arptrmk_set Where menucode ='".$var_progcode."'";
    				$sql .= " ORDER BY seqno";  
					$rs_result = mysql_query($sql); 

         	 		while ($rowq = mysql_fetch_assoc($rs_result)) { 
         	 	 		echo '<tr>';
         	
             	 		echo '<td style="width: 69px">';
             	 		echo '<input class="inputtxt" name="rptrmk[]" id ="rptrmk'.$i.'" type="text" style="width: 600px" value="'.$rowq['rptrmk'].'">';
             	 		echo '</td>';
			
             	 		echo '</tr>';
             		}
             	}else{
             		$i=1;
         	 		do{
         	 	 		echo '<tr>';
         	
             	 		echo '<td style="width: 69px">';
             	 		echo '<input class="inputtxt" name="rptrmk[]" id ="rptrmk'.$i.'" type="text" style="width: 600px">';
             	 		echo '</td>';
			
             	 		echo '</tr>';
              	 		$i++;
             		}while ($i<=6);
             	}	 
             ?>
             </tbody>
           </table>
		    
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
			<a href="javascript:addRow('dataTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Row</span></a>
			<a href="javascript:deleteRow('dataTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>
			

	      <table style="width: 723px">
	      <tr><td>&nbsp;</td></tr>
	  	  <tr>
	  	   	<td align="center">
	  	   	<?php
		  	    $locatr = "m_rpthead_set.php?menucd=".$var_menucode;			
				echo '<input type="button" value="Back" class="butsub" maxlength="200" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';

	  	   		include("../Setting/btnupdate.php");
	  	   	?>
	  	   	</td>
		  </tr>
	      <tr>
	  	   <td align="left">
	  	   	<span style="color:#FF0000">Message :</span>
            <?php
			  
			  if (isset($var_stat)){
			    switch ($var_stat)
				{
				case 1:
  					echo("<span>Success Process</span>");
  					break;
				case 4:
 					echo("<span>Program For This Report Cannot Be Blank</span>");
					break;
				default:
  					echo "";
				}
			  }	
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
