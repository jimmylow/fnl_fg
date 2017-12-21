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
    
    if ($_POST['Submit'] == "Save") {
        $countcd   = $_POST['countcd'];
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
		
		if($b_sort == "") { $b_sort = 0; }
		if($b_over == "") { $b_over = 0; }
		if($best_rate == "") { $best_rate = 0; }
		if($offe_rate == "") {$offe_rate = 0; }
		if($norm_rate == "") {$norm_rate = 0; }
		if($spc_rate == "") {$spc_rate = 0; }
		if($amt_rate1 == "") {$amt_rate1 = 0; }
		if($fix_rate == "") {$fix_rate = 0; }
		if($amount1 == "") { $amount1 = 0; }
		if($amount2 == "") {$amount2 = 0; }
     
     if ($countcd <> "") {
 
      $var_sql = " SELECT count(*) as cnt from counter ";
      $var_sql .= " WHERE counter = '$countcd'";

      $query_id = mysql_query($var_sql) or die ("Cant Check Counter Code ".mysql_error());
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
         echo "<script>";
         echo 'alert("This Customer Have Records")';
         echo "</script>";     
        
	     $backloc = "../main_mas/ipd_comm_mas.php?countcd=".$countcd."&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }else {
         $vartoday = date("Y-m-d H:i:s");
         $sql = "INSERT INTO counter "; 
         $sql .= " (counter, sup_id, b_sort, b_over, comtype, prn_inv, prn_in_det, best_rate, ";
         $sql .= "  offe_rate, norm_rate, spc_rate, amount1, amount2, amt_rate1, fix_rate, ";
         $sql .= " sort_auto, over_auto, pro_less, "; // diff_amt, rea_diff, ";
         $sql .= " create_by, create_on, modified_by, modified_on ) values ";
         $sql .= "  ('$countcd', '$sup_id','$b_sort', '$b_over', '$comtype', '$prn_inv', '$prn_in_det', '$best_rate', ";
         $sql .= "  '$offe_rate', '$norm_rate', '$spc_rate', '$amount1', '$amount2', '$amt_rate1', '$fix_rate', ";
         $sql .= " '$sort_auto', '$over_auto', '$pro_less', ";  //'$diff_amt', '$rea_diff', ";
         $sql .= "   '$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
         mysql_query($sql) or die ("cant Insert m : ".mysql_error()); 
              
     	 $backloc = "../main_mas/m_comm_mas.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";      
       } 
     }else{
       $backloc = "../main_mas/comm_mas.php?stat=4&menucd=".$var_menucode;
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

function chk_supervisor(str) 
{
var rand = Math.floor(Math.random() * 101);

if (str=="s")
  {
  alert ("Please choose a Customer to continue");
  return;
  } 
  
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    res = xmlhttp.responseText;
    res = res.replace(/\s+/g, "");
    if (res == "Y") {
      alert("This Customer have existing record");
      
      location.replace("../main_mas/upd_comm_mas.php?countcd="+str+"&menucd=<?php echo $var_menucode; ?>");
    
    } else { 
      get_supervisor(str);
    }
  }
 } 
xmlhttp.open("GET","getexistsupervisor.php?q="+str+"&m="+rand,true);
xmlhttp.send();

}

function get_supervisor(str)
{
var rand = Math.floor(Math.random() * 101);

if (str=="s")
  {
  alert ("Please choose a Customer to continue");
  return;
  } 
  
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    //alert (xmlhttp.responseText);
    document.getElementById("supdiv").innerHTML=xmlhttp.responseText; 
    
    }
  }
xmlhttp.open("GET","getsupervisor.php?q="+str+"&m="+rand,true);
xmlhttp.send();

}	

function AjaxFunction(email)
{
      
	var httpxml;
	try	{
			// Firefox, Opera 8.0+, Safari
		httpxml=new XMLHttpRequest();
	}catch (e){
		  // Internet Explorer
		try{
		  httpxml=new ActiveXObject("Msxml2.XMLHTTP");
		}catch (e){
		try{
		   httpxml=new ActiveXObject("Microsoft.XMLHTTP");
		}catch (e){
		   alert("Your browser does not support AJAX!");
		   return false;
	    }
      }
    }

    function stateck()
    {
	  if(httpxml.readyState==4)
	  {
		document.getElementById("msg").innerHTML=httpxml.responseText;
	  }
    }
	
	var url="../Setting/email-ajax.php";
	
	url=url+"?email="+email;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
	httpxml.send(null);
}	

function validateForm()
{
    var x=document.forms["InpSuppMas"]["countcdid"].value;
	if (x==null || x=="a")
	{
	alert("Counter Cannot Be Blank");
	document.InpSuppMas.countcdid.focus();
	return false;
	}
	

	
}	
</script>
</head>

<body onload="document.InpSuppMas.countcdid.focus();">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->
  <div class="contentc">

	<fieldset name="Group1" style=" width: 1065px;" class="style2">
	 <legend class="title">COUNTER COMMISSION MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 1043px; height: 750px">
	  <form name="InpSuppMas" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 970px;">
	   <table>
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 250px">Customer<span class="style4">*</span></td>
	      <td>:</td>
	  	  <td>
		   <select name="countcd" id ="countcdid" >
       <option value ="a">-SELECT-</option>
       <?php
         
         $sql = "select custno, name from customer_master";
         $sql .= " where status = 'A' or status is null";
         $sql .= " and type = 'C'"; // O - outright, C - consignment
         $sql .= " order by custno";
         
         $tmp = mysql_query($sql) or die ("Cant get customer : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['custno']."'>".$row['custno']." - ".$row['name']."</option>";
           
           }
          
         }
       ?>
		   </select>
		  </td>
		  <td></td>
		  <td class="tdlabel"></td>
	  	  <td></td>
	  	  <td>
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
	  	  <td class="tdlabel">Supervisor</td>
	  	  <td>:</td>
	  	  <td >	
   	   <select name="sup_id" id ="sup_id" >
       <option value ="a">-SELECT-</option>
       <?php
         
         $sql = "select supervisor_code, supervisor_name from supervisor_master";
         //$sql .= " where status = 'A' or status is null";
         //$sql .= " and type = 'C'"; // O - outright, C - consignment
         $sql .= " order by supervisor_code ";
         
         $tmp = mysql_query($sql) or die ("Cant get supervisor : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['supervisor_code']."'>".$row['supervisor_code']." - ".$row['supervisor_name']."</option>";
           
           }
          
         }
       ?>
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
       <!--  <tr>
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
			<input class="inputtxt" name="rea_diff" id ="rea_diff" type="text" maxlength="25" onchange ="upperCase(this.id)" style="width: 200px" value="<?php //echo $rea_diff; ?>">
		  </td>
          <td></td>
          <td></td>
          <td></td>
          <td>
         </tr>  -->
         </table>
	  	 <table>
	  	 <tr><td></td></tr>
	  	 <tr><td colspan="8" align="center">
	  	   <?php
	  	   $locatr = "m_comm_mas.php?menucd=".$var_menucode;			
		   echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
	  	   include("../Setting/btnsave.php");
	  	   ?>
	  	   </td>
	  	  </tr>
		   <tr>
	  	  <td></td>
	  	              <td style="width: 1160px" colspan="7"><span style="color:#FF0000">Message :</span>
            <?php
			  
			  if (isset($var_stat)){
			    switch ($var_stat)
				{
				case 1:
  					echo("<span>Success Process</span>");
  					break;
				case 0:
 					echo("<span>Process Fail</span>");
					break;
				case 3:
				    echo("<span>Fail! Duplicated Supplier Code Found</span>");
  					break;
  				case 4:
				    echo("<span>Please Fill In The Data To Save</span>");
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
	   </fieldset>
	</fieldset>
	</div>
	 <div class="spacer"></div>
</body>

</html>
