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
       $varmenucd = $_GET['menudcd'];
	   $var_menucode = $_GET['menucd'];
    }
	
	if ($_POST['Submit'] == "Back") {
       
         $var_menucode  = $_POST['menudcd'];
         $backloc = "../admin_set/menu_set.php?menucd=".$var_menucode;
	
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
.style2 {
	margin-right: 0px;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>

<script type="text/javascript" charset="utf-8"> 
</script>
</head>

<body>
  <?php include("../topbarm.php"); ?> 
<!-- <?php include("../sidebarm.php"); ?>--> 
<div class ="contentc">

     <?php
        $sql = "select menu_stat, menu_name, menu_desc, menu_type,";
        $sql .= " menu_path, menu_seq, menu_parent";
        $sql .= " from menud";
        $sql .= " where menu_code ='".$varmenucd."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $menuact = $row[0];
        $menuname = $row[1];
        $menudesc = $row[2];
        $menutype = $row[3];
        $menupath = $row[4];
        $menuseq = $row[5];
        $menuparent  = $row[6];
      
        $sql = "select menu_name from menud  ";
        $sql .= " where menu_code ='".$menuparent."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $menuparnm = $row[0];

    ?>		

	<fieldset name="Group1" style=" width: 911px; height: 330px;" class="style2">
	 <legend class="title">MENU SETTING</legend>
	  <br>
	    <form name="VmUserMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		<input name="menudcd" type="hidden" value="<?php echo $var_menucode;?>">
		<table style="width: 884px">
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 283px" class="tdlabel">Menu Code</td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" readonly="readonly" name="menucd" id ="menucdid" type="text" style="width: 95px" value="<?php echo $varmenucd; ?>">
			</td>
			<td>
			</td>
		    <td style="width: 224px" class="tdlabel">Status</td>
	  	    <td>:</td>
	  	    <td style="width: 97px">
			<input class="inputtxt" readonly="readonly" name="menuact" id ="menuact" type="text" style="width: 125px" value="<?php echo $menuact; ?>">
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td>
	  	    </td> 
	  	    <td style="width: 283px" class="tdlabel"></td>
	  	    <td>
	  	    </td>
	  	    <td><div id="msgcd"></div></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 283px" class="tdlabel">Menu Name</td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="menudname" id ="menunmid" type="text" readonly="readonly" style="width: 417px" value="<?php echo $menuname; ?>">
			</td>
			<td>
			</td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	    <td style="height: 28px">
	  	    </td>
	  	    <td style="width: 283px; height: 28px;" class="tdlabel">Description</td>
	  	    <td style="height: 28px">:</td>
	  	    <td style="width: 431px; height: 28px;">
			<input class="inputtxt" name="menude" id ="menudeid" type="text" readonly="readonly" style="width: 417px" value="<?php echo $menudesc; ?>">
			</td>
			<td style="height: 28px">
			</td>
		  </tr>
		  <tr><td></td></tr>
		  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 283px" class="tdlabel">Menu Type</td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
	  	    <input class="inputtxt" name="menutyp" id ="menutypid" type="text" readonly="readonly" style="width: 125px" value="<?php echo $menutype; ?>">
            </td>
			<td>
			</td>
		  </tr>
		  <tr><td></td></tr>
		   <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 283px" class="tdlabel">Menu Path</td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="menupath" id ="menupathid" readonly="readonly" type="text" style="width: 417px" value="<?php echo $menupath; ?>"></td>
			<td>
			</td>
		    <td></td>
	  	    <td></td>
	  	    <td>
			</td>
		   </tr>
		  <tr><td></td></tr>
		  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 283px" class="tdlabel">Menu #</td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="menuseq" id ="menuseqid" readonly="readonly" type="text" style="width: 79px" value="<?php echo $menuseq; ?>">
			</td>
			<td>
			</td>
		  </tr>
		  <tr>
	  	    <td>
	  	    </td>
	  	    <td></td>
	  	    <td></td>
	  	    <td><div style="color:red" id="msgint"></div></td>
			<td></td>
		  </tr>
		   <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 283px" class="tdlabel">Parent </td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
	  	    <input class="inputtxt" name="menupar" id ="menuparid" readonly="readonly" type="text" style="width: 417px" value="<?php echo $menuparnm; ?>">
			</td>
			<td>
			</td>
		  </tr>
		   <tr>
	  	    <td></td>
	  	    <td></td>
	  	    <td></td>
	  	    <td>
	  	      <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px">
			</td>
			<td></td>
		  </tr>
	  	</table>
	   </form>
	 </fieldset>
</div>
</body>

</html>
