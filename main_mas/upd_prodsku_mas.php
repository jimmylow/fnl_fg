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
      $var_prodcd = $_GET['prodcd'];
	  $var_menucode = $_GET['menucd'];
        
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_prodcd = $_POST['prodcd'];
         $var_menucode  = $_POST['menucd'];
         $backloc = "../main_mas/upd_prod_mas.php?prodcd=".$var_prodcd."&menucd=".$var_menucode;
	
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }    
    
    if ($_POST['Submit'] == "Submit") {
     $var_prodcd = $_POST['prodcd'];
     $var_menucode  = $_POST['menucd'];
     
     if($var_prodcd  <> "") {
     
         $var_sku = $_POST['sku'];
         $vartoday = date("Y-m-d H:i:s");
         
         $sql = "INSERT INTO prodsku_master "; 
         $sql .= " (ProductCode, sku_code, create_by, create_on ) values ";
         $sql .= " ('$var_prodcd', '$var_sku', '$var_loginid', '$vartoday')";
         
         mysql_query($sql) or die ("Insert failed : ".mysql_error());
			$backloc = "../main_mas/upd_prodsku_mas.php?prodcd=".$var_prodcd."&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";

    }    
   } 
   
 if(isset($_GET['id']) && isset($_GET['action']) && isset($_GET['prodcd'])) { 
    $var_id   = $_GET['id'];
    $var_action = $_GET['action'];
    $var_prodcd     = $_GET['prodcd'];

        $sql = "delete from `prodsku_master`"; 
        $sql .= " where `ProductCode` = '$var_prodcd'";
        $sql .= " and `sku_code` = '$var_id'"; 
        
         //echo $sql;
         mysql_query($sql) or die ("delete failed : ".mysql_error());                             
                    
         echo "<script>";   
         echo "alert('Data is deleted');"; 
         echo "</script>";
			$backloc = "../main_mas/upd_prodsku_mas.php?prodcd=".$var_prodcd."&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";

    
  }     
        
   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";

.style2 {
	margin-right: 0px;
}
.style3 {
	font-size: x-small;
}
.style4 {
	color: #FF0000;
	font-weight:bold;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>


<script type="text/javascript" charset="utf-8"> 
function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function validateForm()
{
    var x=document.forms["InpSuppMas"]["prodcdid"].value;
	if (x==null || x=="")
	{
	alert("Product Code Cannot Be Blank");
	document.InpSuppMas.prodcdid.focus();
	return false;
	}
	
}	
</script>
</head>

<body >
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->
  
  <div class="contentc">

	<fieldset name="Group1"  >
	 <legend class="title">EDIT PRODUCT SKU MASTER - <?php echo $var_prodcd; php?></legend>
	  <br>
	  <fieldset name="Group1">
	  <form name="InpSuppMas" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px; width: 970px;">
	    <input name="menucd" type="hidden" value="<?php echo $var_menucode;?>">	   
     <table width = "100%" border=0>
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 200px" colspan="4">Product Code :
		  <input class="inputtxt" name="prodcd" id ="prodcdid" readonly="readonly" type="text" maxlength="50" style="width: 396px" value="<?php echo $var_prodcd; ?>">
		  </td>
      </tr>     
         <tr>
          <td></td>
          <td>SKU ID - Customer ID</td>
          <td >:</td>
          <td>
		   <select name="sku" >

       <?php
         
         $sql = "select sku_code, customer_code from sku_master";
         $sql .= " order by sku_code";
         
         $tmp = mysql_query($sql) or die ("Cant get sku : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['sku_code']."'";
             if ($sku == $row['sku_code']) { echo " selected"; }
             echo ">".$row['sku_code']." - ".$row['customer_code']."</option>";
           
           }
          
         }
       ?>
		   </select> 
          <span style = "width:50px">&nbsp;</span>          
          <input type=submit name = "Submit" value="Submit" class="butsub" style="width: 60px; height: 32px" >
          </td>
          <td><input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" ></td>
         </tr>         
                           
		  </table>
      
        <table width ="100%" >  
        <tr >  
        <th class="tabheader">No</th>         
        <th class="tabheader">SKU ID</th>
        <th class="tabheader">Customer ID</th>         
        <th class="tabheader">Created By</th> 
        <th class="tabheader">Created On</th>                      
        <th class="tabheader" align="center" colspan="2">Action</th>               
        </tr>      
      
<?php

   
      $sql = " SELECT x.*, y.customer_code FROM prodsku_master x, sku_master y where x.ProductCode = '$var_prodcd'";
      $sql .= " and y.sku_code = x.sku_code";
      $sql .= " order by x.sku_code";
      $tmp = mysql_query ($sql) or die ("Cant get SKU details ".mysql_error());
      
      //echo $sql; 
       
      if(mysql_numrows($tmp) > 0) {
                 
          $var_cntrec = 1;    $defaultcolor = "#E5E5E5"; 
          
          while ($row = mysql_fetch_array($tmp)) {              
                           
            if($defaultcolor == "#E5E5E5") 
               { $defaultcolor = "#EfEfEf"; }
            else
               { $defaultcolor = "#E5E5E5"; }
                      
             echo "<tr class='cell' bgcolor='$defaultcolor' onMouseOver='this.style.backgroundColor=\"#fffff0\";'  onMouseOut='this.style.backgroundColor=\"".$defaultcolor."\";'>";
             echo "<td>".$var_cntrec."</td>";
             echo "<td align='center'>".$row['sku_code']."</td>";
             echo "<td align='center'>".$row['customer_code']."</td>";
             echo "<td >".$row['create_by']."</td>";
             echo "<td>".$row['create_on']."</td>";
                                     
             echo '<td align = "center" >';
             echo '<a onClick="javascript:return confirm(\'Are you sure u want to ';
             echo 'delete this SKU :`'.$row['sku_code'].'` ?\')"'; 
             echo ' href="'.$_SERVER['PHP_SELF'].'?action=del&menucd='.$var_menucode.'&prodcd='.$var_prodcd.'&id='.$row['sku_code'].'">';                         
             echo '<img src="../images/deleterow.png" border="0" width="16" height="16" hspace="2" alt="Active Record" /></a></td>';
 
             $var_cntrec += 1;
           }
         
         } 

?>     
		  </fieldset>
      </form>	 
	   </fieldset>
	</div>

</body>

</html>
