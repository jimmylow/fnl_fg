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
    $var_spvcd   = htmlentities($_GET['spvcd']);
	  $var_spvname = htmlentities($_GET['spvname']);
	  //$var_ctrcd   = htmlentities($_GET['ctrcd']);
	  $var_mktcd   = htmlentities($_GET['mktcd']); 
    $comm    = htmlentities($_GET['comm']);   
	  $var_menucode = $_GET['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {
    
 		    $var_spvcd = mysql_real_escape_string($_POST['spvcdu']);
        $spvnamecd  = mysql_real_escape_string($_POST['spvnameu']);
        //$ctrcd    = mysql_real_escape_string($_POST['ctrcd']);
        $mktcd    = mysql_real_escape_string($_POST['mktcd']);
        $comm     = $_POST['comm'];
		    $var_menucode  = $_POST['menudcode'];
		
        if ($var_spvcd <> "") {
        
         	$vartoday = date("Y-m-d H:i:s");
         	$sql = "Update supervisor_master set supervisor_name ='$spvnamecd',";
         	$sql .= " comm='$comm', mkt_code = '$mktcd', ";
         	$sql .= " modified_by='$var_loginid',";
         	$sql .= " modified_on='$vartoday' WHERE supervisor_code = '$var_spvcd'";
       	 	mysql_query($sql); 
        
         	$backloc = "../main_mas/supervisor_mas.php?menucd=".$var_menucode; 
         	echo "<script>";
         	echo 'location.replace("'.$backloc.'")';
         	echo "</script>";
      	}      
    }

	if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];

         $backloc = "../main_mas/supervisor_mas.php?menucd=".$var_menucode;
    
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
<body OnLoad="document.InpColMas.spvnameu.focus();">
  <?php include("../topbarm.php"); ?> 
  <div class="contentc">

	<fieldset name="Group1" style="height: 238px; width: 718px;">
	 <legend class="title">EDIT SUPERVISOR MASTER</legend>
	  <br>
	
	  <form name="InpColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Supervisor Code</td>
	  	    <td>:</td>
	  	    <td>
	  	    <?php
	  	       if (isset($var_spvcd)){
	  	       echo '<input readonly="readonly" class="inputtxt" name="spvcdu" id ="spvcdid" type="text" maxlength="15" size="17" onchange ="upperCase(this.id)" value="'.$var_spvcd.'">';
	  	       }else{
	  	       echo '<input readonly="readonly" class="inputtxt" name="spvcdu" id ="spvcdid" type="text" maxlength="15" size="17" onchange ="upperCase(this.id)">';
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
	  	    <td style="width: 138px" class="tdlabel">Supervisor Name</td>
	  	    <td>:</td>
	  	    <td>
	  	     <?php
	  	       if (isset($var_spvname)){
	  	       echo '<input class="inputtxt" name="spvnameu" id ="spvnameid" type="text" maxlength="50" style="width: 354px" onchange ="upperCase(this.id)" value="'.$var_spvname.'">';
	  	       }else{
	  	       echo '<input class="inputtxt" name="spvnameu" id ="spvnameid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 354px">';
	  	       }

	  	    ?>

			</td>
	  	  </tr>  
	  	  	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Marketing Code</td>
	  	    <td>:</td>
	  	    <td>
		   <select name="mktcd" id ="mktcdid" >

       <?php
         
         $sql = "select mkt_code, mkt_name from marketing_master";
         $sql .= " where 1";
         $sql .= " order by mkt_code";
         
         $tmp = mysql_query($sql) or die ("Cant get marketing : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['mkt_code']."'";
             if ($var_mktcd == $row['mkt_code']) { echo " selected"; }
             echo " >".$row['mkt_code']." - ".$row['mkt_name']."</option>";
           
           }
          
         }
       ?>
		   </select>
	  	     </td>
	  	  </tr> 
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Commission (%)</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="comm" id ="commid" type="text" maxlength="15" size = "17" value="<?php echo $comm; ?>">
			</td>
	  	  </tr>                
        
<?php        
            $sql = " select create_by, creation_time, modified_by, modified_on";
            $sql .= " from supervisor_master ";
            $sql .= " where supervisor_code = '".$var_spvcd."'";
            
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

