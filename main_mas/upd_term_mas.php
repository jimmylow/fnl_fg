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
      $var_termcd  = $_GET['termcd'];
	  $var_termde = $_GET['termde'];
	  $var_menucode = $_GET['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {
    
 		$var_termcd  = $_POST['termcdu'];
        $termdescd = $_POST['termdeu'];
		$var_menucode  = $_POST['menudcode'];
        
		if ($var_termcd <> "") {
        
         $vartoday = date("Y-m-d H:i:s");
         $sql = "Update term_master set term_desc ='$termdescd',";
         $sql .= " modified_by='$var_loginid',";
         $sql .= " modified_on='$vartoday' WHERE term_code = '$var_termcd'";
           
       	 mysql_query($sql); 
        
        
        $backloc = "../main_mas/term_mas.php?menucd=".$var_menucode;
        
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }      
    }
    
    if ($_POST['Submit'] == "Back") {
		$var_menucode  = $_POST['menudcode'];
        $backloc = "../main_mas/term_mas.php?menucd=".$var_menucode;
        
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
<body OnLoad="document.InpTermMas.termdeu.focus();">
 <?php include("../topbarm.php"); ?> 
  <div class="contentc">

	<fieldset name="Group1" style="height: 238px; width: 718px;" class="style2">
	 <legend class="title">EDIT TERM MASTER</legend>
	  <br>
	
	  <form name="InpTermMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Term Code</td>
	  	    <td>:</td>
	  	    <td>
	  	      <input readonly="readonly" class="inputtxt" name="termcdu" id ="termcdid" type="text" maxlength="5" onchange ="upperCase(this.id)" value="<?php echo $var_termcd; ?>" style="width: 44px">
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
	  	     <input class="inputtxt" name="termdeu" id ="countdeid" type="text" maxlength="60" style="width: 486px" onchange ="upperCase(this.id)" value="<?php echo $var_termde; ?>">
        	</td>
	  	  </tr>  
<?php        
            $sql = " select create_by, create_on, modified_by, modified_on";
            $sql .= " from term_master ";
            $sql .= " where term_code = '".$var_termcd."'";
            
            $tmp = mysql_query($sql) or die("Cant get info : ".mysql_error());
            
            if (mysql_numrows($tmp) > 0) {
               $rst = mysql_fetch_object($tmp);
               $createby = $rst->create_by;
               $createon = $rst->create_on;
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

