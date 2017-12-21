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
      $var_countcd = $_GET['countcd'];
	  $var_menucode = $_GET['menucd'];
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menucd'];
         $backloc = "../main_mas/m_comm_mas.php?menucd=".$var_menucode;
	
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }
    
     if ($_POST['Submit'] == "Update") {
       $var_countcd = $_POST['countcd'];
       if ($var_countcd <> "") {

        //$name = $_POST['name'];
        $sup_id = $_POST['sup_id'];
        $b_sort = $_POST['b_sort'];
        $b_over = $_POST['b_over'];
        $comtype = $_POST['comtype'];
        $prn_inv  = $_POST['prn_inv'];
        $prn_in_det  = $_POST['prn_in_det'];
        $best_rate  = $_POST['best_rate'];
        $offe_rate = $_POST['offe_rate'];
        $norm_rate = $_POST['norm_rate'];
        $spc_rate = $_POST['spc_rate'];
        $amount1 = $_POST['amount1'];
        $amount2 = $_POST['amount2'];
        $amt_rate1 = $_POST['amt_rate1'];
        $fix_rate = $_POST['fix_rate'];
        $sort_auto  = $_POST['sort_auto'];
        $over_auto  = $_POST['over_auto'];
        $pro_less  = $_POST['pro_less'];
        //$diff_amt = $_POST['diff_amt'];
        //$rea_diff = $_POST['rea_diff'];

         $suppmoby= $var_loginid;
         $suppmoon= date("Y-m-d H:i:s");

		     $var_menucode  = $_POST['menucd'];
               
         $sql = "Update counter set ";
         $sql .= " sup_id = '$sup_id', b_sort = '$b_sort', b_over = '$b_over', ";
         $sql .= " comtype = '$comtype', prn_inv = '$prn_inv', prn_in_det ='$prn_in_det', ";
         $sql .= " best_rate = '$best_rate', offe_rate = '$offe_rate', norm_rate = '$norm_rate', ";
         $sql .= " spc_rate = '$spc_rate', amount1 = '$amount1', amount2 = '$amount2', ";
         $sql .= " amt_rate1 = '$amt_rate1', fix_rate = '$fix_rate', sort_auto = '$sort_auto', over_auto = '$over_auto', "; 
         $sql .= " pro_less = '$pro_less', ";  //diff_amt = '$diff_amt', rea_diff = '$rea_diff', ";                 
         $sql .= " modified_by='$suppmoby',";
         $sql .= " modified_on='$suppmoon' WHERE Counter = '$var_countcd'";
         
         mysql_query($sql);
         $backloc = "../main_mas/m_comm_mas.php?menucd=".$var_menucode;
	
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
    var x=document.forms["InpSuppMas"]["countcdid"].value;
	if (x==null || x=="")
	{
	alert("Counter Cannot Be Blank");
	document.InpSuppMas.countcdid.focus();
	return false;
	}
	
}	

</script>
</head>
 
<body OnLoad="document.InpSuppMas.b_overid.focus();">
<?php include("../topbarm.php"); ?> 
    <!--<?php include("../sidebarm.php"); ?>--> 
 <?php
        $sql = "select name, sup_id, b_sort, b_over, comtype, prn_inv, prn_in_det, ";
        $sql .= " best_rate, offe_rate, norm_rate, spc_rate, amount1, amount2, amt_rate1, fix_rate,  ";
        $sql .= " sort_auto, over_auto, pro_less ";  // diff_amt, rea_diff ";
        $sql .= " from counter";
        $sql .= " where counter ='".$var_countcd."'";
        
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $name = $row[0];
        $sup_id = $row[1];
        $b_sort = $row[2];
        $b_over = $row[3];
        $comtype = $row[4];
        $prn_inv  = $row[5];
        $prn_in_det  = $row[6];
        $best_rate  = $row[7];
        $offe_rate = $row[8];
        $norm_rate = $row[9];
        $spc_rate = $row[10];
        $amount1 = $row[11];
        $amount2 = $row[12];
        $amt_rate1 = $row[13];
        $fix_rate = $row[14];
        $sort_auto  = $row[15];
        $over_auto  = $row[16];
        $pro_less  = $row[17];
        //$diff_amt = $row[18];
        //$rea_diff = $row[19];
        
        $sql = " select name from  customer_master ";
        $sql .= " where custno = '$var_countcd'";
        
        $tmp = mysql_query($sql) or die ("Cant get cust name : ".mysql_error());
        
        if (mysql_numrows($tmp) > 0) {
           $res = mysql_fetch_object($tmp);
           $name = $res->name;
        }


    ?>		
   
    <div class="contentc">

	  <fieldset name="Group1" style="width: 993px; height: 790px">
	  <legend class="title">EDIT COUNTER COMMISSION MASTER - <?php echo $var_countcd; php?></legend>

	  <form name="InpSuppMas" onsubmit="return validateForm()" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px; width: 970px;">
	    <input name="menucd" type="hidden" value="<?php echo $var_menucode;?>">
	   <table>
	    <tr>
	      <td></td>
	      <td class="tdlabel">Counter</td>
	      <td>:</td>
	  	  <td>
		  <input class="textnoentry" name="countcd" id ="countcdid" readonly="readonly" type="text" style="width: 161px" value="<?php echo $var_countcd; ?>">
		  </td>
		  <td></td>
		  <td class="tdlabel">Name</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="textnoentry" name="name" id ="nameid" readonly="readonly" type="text" style="width: 350px" value="<?php echo $name; ?>">
		  </td>      
      </tr>
	   	 <tr>
	   	  <td></td>
	  	  <td class="tdlabel">Supervisor</td>
	  	  <td>:</td>
	  	  <td>        
		   <select name="sup_id">
       <?php
         
         $sql = "select supervisor_code, supervisor_name from supervisor_master";
         //$sql .= " where counter_code = '".$var_countcd."'";
         $sql .= " order by supervisor_code ";
         
         $tmp = mysql_query($sql) or die ("Cant get supervisor : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option ";
             if($sup_id == $row['supervisor_code']) { echo "selected"; }
             echo " value = '".$row['supervisor_code']."'>";
             echo $row['supervisor_code']." - ".$row['supervisor_name']."</option>";
           
           }
          
         }
       ?>
		   </select>
		  </td>
	  	 </tr>
	   	 <tr>
	   	  <td></td>
	  	  <td class="tdlabel">Bear Over Rate</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="b_over" id ="b_overid" type="text" maxlength="50" style="width: 80px" value="<?php echo $b_over; ?>">
		  %</td>
		  <td></td>
		  <td class="tdlabel"></td>
	  	  <td></td>
	  	  <td>
		  </td>
	  	 </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td>Bear Short Rate</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="b_sort" id ="b_sortid" type="text" maxlength="50" style="width: 80px" value="<?php echo $b_sort; ?>">
		  %</td>
          <td></td>
          <td></td>
          <td></td>
          <td>
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td>Commission Type</td>
          <td>:</td>
          <td>
		   <select name="comtype" id ="comtypeid">
		    <option <?php if ($comtype == "T") { echo "selected"; } ?> value="T">SELL TYPE</option>
		    <option <?php if ($comtype == "I") { echo "selected"; } ?> value="I">ITEM BY ITEM</option>
		    <option <?php if ($comtype == "A") { echo "selected"; } ?> value="A">SALES AMOUNT</option>
		    <option <?php if ($comtype == "F") { echo "selected"; } ?> value="F">FIXED</option>        
		   </select>
          </td>
          <td></td>
          <td></td>
          <td></td>
         </tr>
         <tr>
          <td></td>
          <td>Print Invoice</td>
          <td>:</td>
          <td>
		   <select name="prn_inv">
		    <option <?php if ($prn_inv == "Y") { echo "selected"; } ?> value="Y">YES</option>
		    <option <?php if ($prn_inv == "N") { echo "selected"; } ?> value="N">NO</option>
		   </select>
          <td></td>
          <td></td>
          <td></td>
         </tr>
         <tr>
          <td></td>
          <td>Print Invoice in Details</td>
          <td>:</td>
          <td>
		   <select name="prn_in_det" >
		    <option <?php if ($prn_in_det == "Y") { echo "selected"; } ?> value="Y">YES</option>
		    <option <?php if ($prn_in_det == "N") { echo "selected"; } ?> value="N">NO</option>
		   </select>
          <td></td>
          <td></td>
          <td></td>
         </tr>
         <tr>
          <td></td>
          <td>Commission rate for best buy items</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="best_rate" id ="best_rate" type="text" maxlength="50" style="width: 80px" value="<?php echo $best_rate; ?>">
		  %</td>
          <td></td>
          <td></td>
          <td></td>
          <td>
         </tr>
         <tr>
          <td></td>
          <td>Commission rate for offer items</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="offe_rate" id ="offe_rate" type="text" maxlength="50" style="width: 80px" value="<?php echo $offe_rate; ?>">
		  %</td>
          <td></td>
          <td></td>
          <td></td>
          <td>
         </tr>
         <tr>
          <td></td>
          <td>Commission rate for normal items</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="norm_rate" id ="norm_rate" type="text" maxlength="50" style="width: 80px" value="<?php echo $norm_rate; ?>">
		  %</td>
          <td></td>
          <td></td>
          <td></td>
          <td>
         </tr>
         <tr>
          <td></td>
          <td>Commission rate for stockin</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="spc_rate" id ="spc_rate" type="text" maxlength="50" style="width: 80px" value="<?php echo $spc_rate; ?>">
		  %</td>
          <td></td>
          <td></td>
          <td></td>
          <td>
         </tr>  
         <tr>
          <td></td>
          <td>If sales amount less than</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="amount1" id ="amount1" type="text" maxlength="50" style="width: 80px" value="<?php echo $amount1; ?>">
		   the rate is : <input class="inputtxt" name="fix_rate" id ="fix_rate" type="text" maxlength="50" style="width: 80px" value="<?php echo $fix_rate; ?>">
       </td>
          <td></td>
          <td></td>
          <td></td>
          <td>
         </tr>         
         <tr>
          <td></td>
          <td>If sales amount more than</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="amount2" id ="amount2" type="text" maxlength="50" style="width: 80px" value="<?php echo $amount2; ?>">
		   the rate is : <input class="inputtxt" name="amt_rate1" id ="amt_rate1" type="text" maxlength="50" style="width: 80px" value="<?php echo $amt_rate1; ?>">
       </td>
          <td></td>
          <td></td>
          <td></td>
          <td>
         </tr>                                                             
         <tr>
          <td></td>
          <td>If shortage, send D/N</td>
          <td>:</td>
          <td>
		   <select name="sort_auto" >
		    <option <?php if ($sort_auto == "Y") { echo "selected"; } ?> value="Y">YES</option>
		    <option <?php if ($sort_auto == "N") { echo "selected"; } ?> value="N">NO</option>
		   </select>
          <td></td>
          <td></td>
          <td></td>
         </tr>
         <tr>
          <td></td>
          <td>If overage, send C/N</td>
          <td>:</td>
          <td>
		   <select name="over_auto" >
		    <option <?php if ($over_auto == "Y") { echo "selected"; } ?> value="Y">YES</option>
		    <option <?php if ($over_auto == "N") { echo "selected"; } ?> value="N">NO</option>
		   </select>
          <td></td>
          <td></td>
          <td></td>
         </tr> 
         <tr>
          <td></td>
          <td>Promotion Less</td>
          <td>:</td>
          <td>
		   <select name="pro_less" >
		    <option <?php if ($pro_less == "Y") { echo "selected"; } ?> value="Y">YES</option>
		    <option <?php if ($pro_less == "N") { echo "selected"; } ?> value="N">NO</option>
		   </select>
          <td></td>
          <td></td>
          <td></td>
         </tr> 
         <!-- <tr>
          <td></td>
          <td>Differents of sales amount</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="diff_amt" id ="diff_amt" type="text" maxlength="50" style="width: 80px" value="<?php //echo $diff_amt; ?>">
		  </td>
          <td></td>
          <td></td>
          <td></td>
          <td>
         </tr>
         <tr>
          <td></td>
          <td>Reason of differents</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="rea_diff" id ="rea_diff" type="text" maxlength="50" style="width: 200px" value="<?php //echo $rea_diff; ?>">
		  </td>
          <td></td>
          <td></td>
          <td></td>
          <td>
         </tr> --> 
                         

<?php        
            $sql = " select create_by, create_on, modified_by, modified_on";
            $sql .= " from counter ";
            $sql .= " where counter = '".$var_countcd."'";
            
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
	  	    <td ></td>
	  	    <td >Create By</td>
	  	    <td>:</td>
	  	    <td>
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$createby.'" size="20">';
	  	    ?>
          </td>
			<td ></td>
			<td >Create On</td>
			<td >:</td>
			<td >
      
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$createon.'" size="20">';
	  	    ?>          
			</td>
	  	  </tr>  
	  	    <td ></td>
	  	    <td >Modified By</td>
	  	    <td>:</td>
	  	    <td>
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$modiby.'" size="20">';
	  	    ?>
          </td>
			<td ></td>
			<td >Modified On</td>
			<td >:</td>
			<td > 
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
