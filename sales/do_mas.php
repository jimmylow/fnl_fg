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
    
    if ($_POST['Submit'] == "Search") {  
    
       $ordno = $_POST['saordno'];
       
		   $backloc = "../sales/do1_mas.php?menucd=".$var_menucode."&sorno=".$ordno;
       echo "<script>";
       echo 'location.replace("'.$backloc.'")';
       echo "</script>";   
           
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

function validateForm()
{

  var x=document.forms["InpPO"]["saordno"].value;
	if (x==null || x=="s")
	{
	alert("Sales Order No Must Not Be Blank");
	document.InpPO.saordno.focus;
	return false;
	}
}

</script>
</head>
<body >
  <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">DELIVERY ORDER ENTRY</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
	   	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Sales Order No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<select name="saordno" id="saordno" style="width: 268px">
			 <?php
              $sql = "select shipno from salesshipmas ";
              $sql .= " where doflg = 'N' ";
              $sql .= " and stype = 'C' ";  // do is for consignment CUSTOMER ONLY
              $sql .= " and stat= 'A'";
			  $sql .= " ORDER BY shipno ASC";
              
              $sql_result = mysql_query($sql);
              echo "<option size =30 value='s' selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['shipno'].'">'.$row['shipno'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

		   </td>
       <td style="width: 13px"></td>
			<td>
				<?php
				 echo '<input type="Submit" name = "Submit" value="Search" class="butsub" style="width: 60px; height: 32px" >';

				 $locatr = "m_do_mas.php?menucd=".$var_menucode;	
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';

				?>
      </td>
	  	  </tr>  
	  	  </table>
	   </form>	
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
