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
      $var_supp_cd = $_GET['suppcd'];
	  $var_menucode = $_GET['menucd'];
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menucd'];
         $backloc = "../main_mas/m_supp_mas.php?menucd=".$var_menucode;
	
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }
    
     if ($_POST['Submit'] == "Update") {
       $var_supp_cd = $_POST['suppcd'];
       if ($var_supp_cd <> "") {

         $suppname = $_POST['suppname'];
         $suppstat = $_POST['selactive'];
         $suppadd1 = $_POST['suppadd1'];
         $suppadd2 = $_POST['suppadd2'];
         $suppadd3 = $_POST['suppadd3'];
         $suppadd4 = $_POST['suppadd4'];
         $supptel1  = $_POST['supptel1'];
         $suppfax1  = $_POST['suppfax1'];
         $suppmob1  = $_POST['suppmob1'];
         $suppweb  = $_POST['suppweb'];
         $suppconppl1 = $_POST['suppconppl1'];
         $suppeml1    = $_POST['suppeml1'];

         $supprmk  = $_POST['supprmk'];
         $suppmoby= $var_loginid;
         $suppmoon= date("Y-m-d H:i:s");
         $suppterms = $_POST['terms'];
         $suppcurr = $_POST['curr'];

		 $var_menucode  = $_POST['menucd'];
               
         $sql = "Update supplier_master set Name ='$suppname', ";
         $sql .= " status = '$suppstat', Add1 = '$suppadd1', ";
         $sql .= " Add2 = '$suppadd2', Add3 = '$suppadd3', Add4 ='$suppadd4', ";
         $sql .= " Contact = '$suppconppl1', Tel = '$supptel1', Fax = '$suppfax1', Mobile = '$suppmob1', ";
         $sql .= " Email = '$suppeml1', Homepage = '$suppweb', remark = '$supprmk', ";
         $sql .= " terms = '$suppterms', currency = '$suppcurr', ";
         $sql .= " modified_by='$suppmoby',";
         $sql .= " modified_on='$suppmoon' WHERE SuppNo = '$var_supp_cd'";
         
         mysql_query($sql);
         $backloc = "../main_mas/m_supp_mas.php?menucd=".$var_menucode;
	
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

<style media="all" type="text/css">@import "../css/styles.css";
.style2 {
	margin-right: 0px;
}
.style3 {
	font-size: x-small;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>

<script type="text/javascript" charset="utf-8"> 
function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function validateForm()
{
    var x=document.forms["InpSuppMas"]["suppcdid"].value;
	if (x==null || x=="")
	{
	alert("Supplier Code Cannot Be Blank");
	document.InpSuppMas.suppcdid.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["suppnmid"].value;
	if (x==null || x=="")
	{
	alert("Supplier Name Cannot Be Blank");
	document.InpSuppMas.suppnmid.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["suppadd1id"].value;
	if (x==null || x=="")
	{
	alert("Supplier Address1 Cannot Be Blank");
	document.InpSuppMas.suppadd1id.focus();
	return false;
	}
		
	var x=document.forms["InpSuppMas"]["supptelid"].value;
	if (x==null || x=="")
	{
	alert("Supplier Telephone Cannot Be Blank");
	document.InpSuppMas.supptelid.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["suppconpplid"].value;
	if (x==null || x=="")
	{
	alert("Supplier Contact Person Cannot Be Blank");
	document.InpSuppMas.suppconpplid.focus();
	return false;
	}	
}	

</script>
</head>
 
<body OnLoad="document.InpSuppMas.suppnmid.focus();">
<?php include("../topbarm.php"); ?> 
    <!--<?php include("../sidebarm.php"); ?>--> 
 <?php
        $sql = "select Name, Add1, Add2, Add3, Add4, Contact, Tel, Fax, ";
        $sql .= " Status, Email, Homepage, remark, mobile, terms, currency, modified_by, modified_on ";
        $sql .= " from supplier_master";
        $sql .= " where SuppNo ='".$var_supp_cd."'";
        
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $suppde = $row[0];
        $suppadd1 = $row[1];
        $suppadd2 = $row[2];
        $suppadd3 = $row[3];
        $suppadd4 = $row[4];
        $suppconppl1  = $row[5];
        $supptel1  = $row[6];
        $suppfax1  = $row[7];
        $suppstat = $row[8];
        $suppeml1 = $row[9];
        $suppweb = $row[10];
        $supprmk = $row[11];
        $suppmob = $row[12];
        $suppterms = $row[13];
        $suppcurr = $row[14];

    ?>		
   
    <div class="contentc">

	  <fieldset name="Group1" style="width: 993px; height: 790px">
	  <legend class="title">EDIT SUPPLIER MASTER - <?php echo $var_supp_cd; php?></legend>

	  <form name="InpSuppMas" onsubmit="return validateForm()" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px; width: 970px;">
	    <input name="menucd" type="hidden" value="<?php echo $var_menucode;?>">
	   <table>
	    <tr>
	      <td></td>
	      <td class="tdlabel">Supplier No</td>
	      <td>:</td>
	  	  <td>
		  <input class="textnoentry" name="suppcd" id ="suppcdid" readonly="readonly" type="text" style="width: 161px" value="<?php echo $var_supp_cd; ?>">
		  </td>
		  <td></td>
		  <td class="tdlabel">Status</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="selactive" style="width: 125px">
		    <option <?php if ($suppstat == "A") { echo "selected"; } ?> value="A">ACTIVE</option>
		    <option <?php if ($suppstat == "D") { echo "selected"; } ?> value="D">DEACTIVE</option>
		   </select>
		  </td>
	  	 </tr>
	  	 <tr>
	  	  <td></td> 
	  	  <td></td>
	  	  <td></td> 
          <td><div id="msgcd"></div></td>
	   	 </tr> 
	   	 <tr>
	   	  <td></td>
	  	  <td class="tdlabel">Supplier Name</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="suppname" id ="suppnmid" type="text" maxlength="50" style="width: 396px" value="<?php echo $suppde; ?>">
		  </td>
		  <td></td>
		  <td class="tdlabel">Terms</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="terms" style="width: 125px">
       <option value="a">-SELECT-</option>
       <?php
         
         $sql = "select term_code, term_desc from term_master";
         $sql .= " order by term_code desc";
         
         $tmp = mysql_query($sql) or die ("Cant get term : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['term_code']."'";
             if ($suppterms == $row['term_code']) { echo "selected"; }
             echo ">".$row['term_code']." - ".$row['term_desc']."</option>";
           
           }
          
         }
       ?>
		   </select>        
		  </td>
	  	 </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td>Homepage</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="suppweb" id ="suppwebid" type="text" maxlength="50" style="width: 345px" value="<?php echo $suppweb; ?>"></td>
		  </td>
          <td></td>
          <td>Currency</td>
          <td>:</td>
          <td>
		   <select name="curr" id = "currid" >
       <option value="a">-SELECT-</option>
       <?php
         
         $sql = "select currcode, currdesc from currency_master";
         $sql .= " order by currcode";
         
         $tmp = mysql_query($sql) or die ("Cant get currency : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['currcode']."'";
             if ($suppcurr == $row['currcode']) { echo "selected"; }
             echo ">".$row['currcode']." - ".$row['currdesc']."</option>";
           
           }
          
         }
       ?>                   
		   </select>                
          </td>
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td>Remark</td>
          <td>:</td>
          <td>
		   <input class="inputtxt" name="supprmk" id ="supprmkid" type="text" maxlength="80" style="width: 396px" value="<?php echo $supprmk; ?>"></td>
          <td></td>
          <td></td>
          <td></td>
         </tr>
        </table>
        <br>
        <fieldset name="Group1" class="style2" style="width: 980px">
	     <legend class="style3"><strong>Contact Information</strong></legend>
	      <table>
	  	  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 1</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="suppadd1" id ="suppadd1id" type="text" maxlength="100" style="width: 396px" value="<?php echo $suppadd1; ?>">
			</td>
			<td></td>
			<td style="width: 81px"></td>
			<td></td>
            <td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 2</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="suppadd2" id ="suppadd2id" type="text" maxlength="50" style="width: 396px" value="<?php echo $suppadd2; ?>">
			</td>
			<td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 3</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="suppadd3" id ="suppadd3id" type="text" maxlength="50" style="width: 396px" value="<?php echo $suppadd3; ?>">
			</td>
			<td></td>
			<td style="width: 81px"></td>
			<td></td>
            <td>
			</td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 4</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
	  	    <input class="inputtxt" name="suppadd4" id ="suppadd4id" type="text" maxlength="50" style="width: 151px" value="<?php echo $suppadd4; ?>"></td>
			<td></td>
           <td style="width: 81px">Mobile Tel </td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="suppmob1" id ="suppmob1id" type="text" maxlength="50" style="width: 294px" value="<?php echo $suppmob; ?>"></td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="supptel1" id ="supptel1id" type="text" maxlength="50" style="width: 161px" value="<?php echo $supptel1;?>">
		   </td>
		   <td></td>
           <td style="width: 81px">Fax </td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="suppfax1" id ="suppfax1id" type="text" maxlength="50" style="width: 294px" value="<?php echo $suppfax1;?>"></td>
		  </tr>
		  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Contact<br>Person</td>
	  	    <td style="width: 8px; height: 30px;">:</td>
	  	    <td style="height: 30px">
			<input class="inputtxt" name="suppconppl1" id ="suppconppl1id" type="text" maxlength="50" style="width: 345px" value="<?php echo $suppconppl1;?>">
			</td>
			<td style="height: 30px"></td>
			<td style="width: 81px; height: 30px;">Email</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
			<input class="inputtxt" name="suppeml1" id ="suppeml1id" type="text" maxlength="50" style="width: 345px" onBlur="AjaxFunction(this.value);" value="<?php echo $suppeml1;?>"></td>
		  </tr>
<?php        
            $sql = " select create_by, creation_time, modified_by, modified_on";
            $sql .= " from supplier_master ";
            $sql .= " where SuppNo = '".$var_supp_cd."'";
            
            $tmp = mysql_query($sql) or die("Cant get info : ".mysql_error());
            
            if (mysql_numrows($tmp) > 0) {
               $rst = mysql_fetch_object($tmp);
               $createby = $rst->create_by;
               $createon = $rst->creation_time;
               $modiby = $rst->modified_by;
               $modion = $rst->modified_on;
            
            }  
	  	    ?>
	  	  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Create By</td>
	  	    <td>:</td>
	  	    <td>
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$createby.'" size="20">';
	  	    ?>
          </td>
			<td style="height: 30px"></td>
			<td style="width: 81px; height: 30px;">Create On</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
      
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$createon.'" size="20">';
	  	    ?>          
			</td>
	  	  </tr>  
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Modified By</td>
	  	    <td>:</td>
	  	    <td>
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$modiby.'" size="20">';
	  	    ?>
          </td>
			<td style="height: 30px"></td>
			<td style="width: 81px; height: 30px;">Modified On</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px"> 
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$modion.'" size="20">';
	  	    ?>          
			</td>
	  	  </tr>                               
		  <tr>
	  	    <td></td>
	  	    <td></td><td></td><td></td><td></td><td></td><td></td><td><div id="msg"></div></td>
		  </tr>

		  </table>
		  </fieldset>
	  	 <table>
	  	 <tr><td style="width: 1198px"></td></tr>
	  	 <tr>
	  	   <td align="center" style="width: 1198px">
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" >
	  	   <input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	 </table>
	   </form>	
	   </fieldset>
	   </div>
</body>
</html>
