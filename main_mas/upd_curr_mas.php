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
      $var_code  = $_GET['c'];
	    $var_desc = $_GET['d'];
	    $var_menucode = $_GET['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {
    
 		$var_curru  = $_POST['curru'];
    $var_descu = $_POST['descu'];
		$var_menucode  = $_POST['menudcode'];
        
		if ($var_curru <> "") {
        
         $vartoday = date("Y-m-d H:i:s");
         $sql = "Update currency_master set currdesc ='$var_descu',";
         $sql .= " modified_by='$var_loginid',";
         $sql .= " modified_on='$vartoday' WHERE currcode = '$var_curru'";
           
       	 mysql_query($sql) or die("Cant update : ".mysql_error()); 
        
        
        $backloc = "../main_mas/curr_mas.php?menucd=".$var_menucode;
        
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }      
    }
    
    if ($_POST['Submit'] == "Back") {
		$var_menucode  = $_POST['menudcode'];
        $backloc = "../main_mas/curr_mas.php?menucd=".$var_menucode;
        
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
<body OnLoad="document.InpTermMas.curru.focus();">
 <?php include("../topbarm.php"); ?> 
  <div class="contentc">

	<fieldset name="Group1" style="height: 238px; width: 718px;" class="style2">
	 <legend class="title">EDIT CURRENCY MASTER</legend>
	  <br>
	
	  <form name="InpTermMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Currency Code</td>
	  	    <td>:</td>
	  	    <td>
	  	      <input readonly="readonly" class="inputtxt" name="curru" id ="currid" type="text" maxlength="30" onchange ="upperCase(this.id)" value="<?php echo $var_code; ?>" >
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
	  	    <td style="width: 138px" class="tdlabel">Currency Description</td>
	  	    <td>:</td>
	  	    <td>
	  	     <input class="inputtxt" name="descu" id ="descid" type="text" maxlength="60" style="width: 486px" onchange ="upperCase(this.id)" value="<?php echo $var_desc; ?>">
        	</td>
	  	  </tr>
<?php        
            $sql = " select create_by, create_on, modified_by, modified_on";
            $sql .= " from currency_master ";
            $sql .= " where currcode = '".$var_code."'";
            
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

