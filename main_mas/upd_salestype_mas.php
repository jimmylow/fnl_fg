<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];
    
    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "../login.htm"';
      echo "</script>";
    } else {
      $var_salescd  = $_GET['salescd'];
	  $var_salesde = $_GET['salesde'];
	  $var_menucode = $_GET['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {
    
 		    $var_salescd  = $_POST['salescdu'];
        $salesdescd = $_POST['salesdeu'];
        $var_menucode  = $_POST['menudcode'];
         if ($var_salescd <> "") {
        
         $vartoday = date("Y-m-d H:i:s");
         $sql = "Update salestype_master set salestype_desc ='$salesdescd',";
         $sql .= " modified_by='$var_loginid',";
         $sql .= " modified_on='$vartoday' WHERE salestype_code = '$var_salescd'";
           
         //echo $sql;  
       	 mysql_query($sql); 
         $backloc = "../main_mas/salestype_mas.php?menucd=".$var_menucode;
 		     echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }      
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];

         $backloc = "../main_mas/salestype_mas.php?menucd=".$var_menucode;
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">@import "../css/styles.css";
.style1 {
	margin-left: 9px;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript"> 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

</script>
</head>

 	<!--<?php include("../sidebarm.php"); ?> -->
<body OnLoad="document.InpColMas.salesdeu.focus();">
<?php include("../topbarm.php"); ?> 
   
	<div class="contentc">

	<fieldset name="Group1" style="height: 238px; width: 718px;" class="style2">
	 <legend class="title">EDIT SALES TYPE MASTER</legend>
	  <br>
	
	  <form name="InpColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px">
		<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">

		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Sales Type</td>
	  	    <td>:</td>
	  	    <td>
	  	    <?php
	  	       if (isset($var_salescd)){
	  	       echo '<input readonly="readonly" class="inputtxt" name="salescdu" id ="clrcdid" type="text" maxlength="5" onchange ="upperCase(this.id)" value="'.$var_salescd.'" style="width: 48px">';
	  	       }else{
	  	       echo '<input readonly="readonly" class="inputtxt" name="salescdu" id ="clrcdid" type="text" maxlength="5" onchange ="upperCase(this.id)" style="width: 48px">';
	  	       }
	  	    ?>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td>
	  	    </td> 
	  	    <td style="width: 138px" class="tdlabel"></td>
	   	  </tr> 
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Description</td>
	  	    <td>:</td>
	  	    <td>
	  	     <?php
	  	       if (isset($var_salesde)){
	  	       echo '<input class="inputtxt" name="salesdeu" id ="currdeid" type="text" maxlength="50" style="width: 354px" onchange ="upperCase(this.id)" value="'.$var_salesde.'">';
	  	       }else{
	  	       echo '<input class="inputtxt" name="salesdeu" id ="currdeid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 354px" >';
	  	       }
	  	    ?>

			</td>
	  	  </tr> 
<?php        
            $sql = " select create_by, creation_time, modified_by, modified_on";
            $sql .= " from salestype_master ";
            $sql .= " where salestype_code = '".$var_salescd."'";
            
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
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Create By</td>
	  	    <td>:</td>
	  	    <td>
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$createby.'" size="20">';
	  	    ?>
          <span style="width: 138px">&nbsp;</span>Create On : 
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$createon.'" size="20">';
	  	    ?>          
			</td>
	  	  </tr>  
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Modified By</td>
	  	    <td>:</td>
	  	    <td>
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$modiby.'" size="20">';
	  	    ?>
          <span style="width: 138px">&nbsp;</span>Modified On : 
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$modion.'" size="20">';
	  	    ?>          
			</td>
	  	  </tr>                                  
	  	  <tr>
	  	   <td>
	  	   </td>
	  	   <td>
	  	   </td>
	  	   <td>
	  	   </td>
	  	   <td>
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" ><input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	</table>
	   </form>	
	</fieldset>
    </div>
</body>

</html>

