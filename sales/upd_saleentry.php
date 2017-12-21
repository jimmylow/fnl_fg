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
    
      $var_ordno = $_GET['sorno'];
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");

    }
    
 if(isset($_POST['UpdHeader'])) {
  if($_POST['UpdHeader'] == "Upd_Header") {
    
      //phpinfo();
      
   	  $vmordno   = $_POST['sordno'];
		  $vmorddte  = date('Y-m-d', strtotime($_POST['saorddte']));
		  //$vmcustcd  = $_POST['sacustcd'];
		  $vmcustpo  = $_POST['sacustpo'];
      //$vmzone    = $_POST['szone']; 
      $vmzone = "";
      $vmremark = $_POST['saremark']; 
      
      $vartoday = date("Y-m-d H:i:s");
      $var_ordno = $_POST['sordno'];
      
			$sql = "Update salesentry Set sorddte ='$vmorddte', ";        
			$sql .= "                    scustpo = '$vmcustpo', ";
      $sql .= "                    remark = '$vmremark',";
			$sql .= "                    modified_by = '$var_loginid', modified_on='$vartoday' ";
			$sql .= "  Where sordno ='$vmordno'";
        
			mysql_query($sql) or die ("Cant update 1a : ".mysql_error());  
       
      $var_upddata = false;
      
    }
  }    

 if(isset($_POST['Submit'])) {  
    if ($_POST['Submit'] == "Submit") {
    
      //phpinfo();
      
   	  $vmordno   = $_POST['sordno'];
			
      $matcode   = $_POST['prococode'];
			$matuom    = $_POST['procouom'];
			$matqty    = $_POST['procoqty'];
			$matuprice = $_POST['procoprice'];
      $mattype   = $_POST['procotype']; 
      
      $upd = "Y";
 
      //------------------------- chk duplicate prod --------------    
      $sql = " select * from salesentrydet ";
      $sql .= " where sordno = '$vmordno'";
      $sql .= " and sprocd = '$matcode'";
     
      //echo $sql;
      $result = mysql_query($sql) or die ("Error chk dup : ".mysql_error());
      
      if(mysql_numrows($result) > 0) {
	  
        $sql2 = "SELECT sprocd FROM salesentrydet";
        $sql2 .= " Where sordno ='".$vmordno."'"; 
	    	$sql2 .= " ORDER BY sprocd";  
			  $rs_result = mysql_query($sql2);
        
        $var_cnt = 0;
        while ($row = mysql_fetch_array($rs_result)) {
           $var_cnt += 1;
           if ($row['sprocd'] == $matcode) {
              break;
           } 
            
        }

     	  $upd = "N";
        echo "<script>";
        echo 'alert("Duplicate Item Found : '.$matcode.' At Row : '.$var_cnt.'");';
        echo "</script>";
        
      }      
      //----------------------------------------------------------

      //------------------------- chk prod type --------------    
      $sql = " select * from salestype_master ";
      $sql .= " where salestype_code = '$mattype'";
     
      $result = mysql_query($sql) or die ("Error chk dup : ".mysql_error());

      if(mysql_numrows($result) == 0) {
     	 // $upd = "N";
        //echo "<script>";
        //echo 'alert("Invalid Product Type : '.$mattype.'");';
        //echo "</script>";
        
      }      
      //----------------------------------------------------------
              
      if($upd == "Y") {    
      
	            $vartoday = date("Y-m-d H:i:s");
				
				//$sql = "Update salesentry Set sorddte ='$vmorddte', ";        
				//$sql .= "                    scustpo = '$vmcustpo', szone = '$vmzone', ";
        //$sql .= "                    remark = '$vmremark',";
				//$sql .= "                    modified_by = '$var_loginid', modified_on='$vartoday' ";
				//$sql .= "  Where sordno ='$vmordno'";
        
				//mysql_query($sql) or die ("Cant update 1a : ".mysql_error());  
        
 				if ($matcode <> "") {

				   $sql = "INSERT INTO salesentrydet values 
					 		('$vmordno', '$matcode', '$mattype', '$matqty', '$matuom', '$matuprice', '1')";
                    
						mysql_query($sql) or die ("Cant insert : ".mysql_error());
         }	          

         $var_upddata = false; 
         
        echo "<script>";
        echo 'alert("Insert Successfully");';
        echo "</script>";                

       } else {
       
         $var_upddata = false;

         $prodcd = $matcode;
         $produom = $matuom;
         $produqty = $_POST['procouqty'];
         $prodtype = $mattype;
         $produpri = $matuprice;
         $prodqty = $matqty;
         $prodtotqty = $_POST['procototpcs'];  
                
       }  // if($upd == "Y)
       
      $var_ordno = $_POST['sordno'];
    }
   }   

 if(isset($_POST['Upd'])) {
  if($_POST['Upd'] == "UpdRec") {
    
      //phpinfo();
      
   	  $vmordno   = $_POST['sordno'];
		  //$vmorddte  = date('Y-m-d', strtotime($_POST['saorddte']));
		  //$vmcustcd  = $_POST['sacustcd'];
		  //$vmcustpo  = $_POST['sacustpo'];
      //$vmzone    = $_POST['szone']; 
      //$vmzone = "";
			//$vmremark = $_POST['saremark'];
      
      $matcode   = $_POST['prococode'];
		$matuom    = $_POST['procouom'];
		$matqty    = $_POST['procoqty'];
		$matuprice = $_POST['procoprice'];
      $mattype   = $_POST['procotype']; 
      
      $upd = "Y";
 
      //------------------------- chk duplicate prod --------------    
     /* $sql = " select * from salesentrydet ";
      $sql .= " where sordno = '$vmordno'";
      $sql .= " and sprocd = '$matcode'";
     
      //echo $sql;
      $result = mysql_query($sql) or die ("Error chk dup : ".mysql_error());
      
      if(mysql_numrows($result) > 0) {
     	  $upd = "N";
        echo "<script>";
        echo 'alert("Duplicate Item Found : '.$matcode.'");';
        echo "</script>";
        
      }   */   
      //----------------------------------------------------------

      //------------------------- chk prod type --------------    
      $sql = " select * from salestype_master ";
      $sql .= " where salestype_code = '$mattype'";
     
      $result = mysql_query($sql) or die ("Error chk dup : ".mysql_error());

      if(mysql_numrows($result) == 0) {
     	//  $upd = "N";
        //echo "<script>";
        //echo 'alert("Invalid Product Type : '.$mattype.'");';
        //echo "</script>";
        
      }      
      //----------------------------------------------------------
              
      if($upd == "Y") {    
      
	            $vartoday = date("Y-m-d H:i:s");
				
				//$sql = "Update salesentry Set sorddte ='$vmorddte', ";        
				//$sql .= "                    scustpo = '$vmcustpo', szone = '$vmzone', ";
        //$sql .= "                    remark = '$vmremark',";        
				//$sql .= "                    modified_by = '$var_loginid', modified_on='$vartoday' ";
				//$sql .= "  Where sordno ='$vmordno'";
        
				//mysql_query($sql) or die ("Cant update 1 : ".mysql_error());  
        
 				if ($matcode <> "") {

				$sql = "Update salesentrydet Set sptype ='$mattype', ";        
				$sql .= "                    sproqty = '$matqty', sprouom = '$matuom', ";
				$sql .= "                    sprounipri = '$matuprice' ";
				$sql .= "  Where sordno ='$vmordno' and sprocd = '$matcode'";
                    
						mysql_query($sql) or die ("Cant insert : ".mysql_error());
         }	          

         $var_upddata = false; 
         
        echo "<script>";
        echo 'alert("Updated Successfully");';
        echo "</script>";               

       } else {
       
         $var_upddata = false;
         /*
         $prodcd = $matcode;
         $produom = $matuom;
         $produqty = $_POST['procouqty'];
         $prodtype = $mattype;
         $produpri = $matuprice;
         $prodqty = $matqty;
         $prodtotqty = $_POST['procototpcs'];  */
                
       }  // if($upd == "Y)
       
      $var_ordno = $_POST['sordno'];
    }
   }
   
 if(isset($_POST['Reset'])) {
  if($_POST['Reset'] == "Reset") {
    
      //phpinfo();
      
   	  $vmordno   = $_POST['sordno'];
		  $vmorddte  = date('Y-m-d', strtotime($_POST['saorddte']));
		  //$vmcustcd  = $_POST['sacustcd'];
		  $vmcustpo  = $_POST['sacustpo'];
      //$vmzone    = $_POST['szone']; 
      $vmzone = "";
      
      $var_ordno = $_POST['sordno'];  
      $var_upddata = false;
      
    }
  } 
            
    
    if(isset($_GET['act'])) {  $var_action  = $_GET['act'];  }
    if(isset($_GET['sorno']))  {  $var_salesno = $_GET['sorno']; }
    if(isset($_GET['i']))  {  $var_item = $_GET['i']; }
      
       
     if ($var_action == "del") { 
        if($var_salesno != "" && $var_item != "") { 
        
	       	mysql_query("delete from  `salesentrydet` where `sordno` = '$var_salesno' and `sprocd` = '$var_item';", $db_link);

          $var_upddata = false;
           //$var_pstrefno = $var_selrefno;
           //$var_pstserialno = $var_selserialno;

        }
      }
 
     if ($var_action == "upd") { 
        if($var_salesno != "" && $var_item != "") { 
        
           $var_upddata = true;

        }
      }  
     
    
  if ($var_upddata == true) {
  
      $sql = "select * from salesentrydet where sordno = '".$var_salesno."'";
      $sql .= " and sprocd = '".$var_item."'";
      
      $tmprst = mysql_query($sql) or die ("Cant get item det for update : ".mysql_error());
      $rst = mysql_fetch_object($tmprst);      

      $prodcd = $rst->sprocd;
      $produom = $rst->sprouom;
      $prodtype = $rst->sptype;
      $produpri = $rst->sprounipri;
      $prodqty = $rst->sproqty;
      
     $sql = " select uom_pack from prod_uommas";
     $sql .= " where uom_code = '".$produom."'";

     $result = mysql_query($sql) or die ("Error uom : ".mysql_error());
     
     if(mysql_numrows($result) > 0) {
       $data = mysql_fetch_object($result);
       $produqty = $data->uom_pack;
       if ($produqty == "") { $produqty = 1; }         
      }  else { $produqty = 1; }  
      
      $prodtotqty = $prodqty * $produqty;  
             
      
   } else {
     if ($matcode == "") {
        $prodcd = "";
        $produom = "";
        $prodtype = "";
        $produpri = "";
        $prodqty = "";
        $produqty = "";
        $prodtotqty = "";   
     }
   }
   
    
  //----------- get special authority ------------------//
  $sqlauth = " select * from progauth";
  $sqlauth .= " where username = '$var_loginid'";
  $sqlauth .= " and program_name = '99'";
  
  $tmpauth = mysql_query($sqlauth) or die ("Cant get auth : ".mysql_error());
  
  if (mysql_numrows($tmpauth) > 0) {
     $speauth = "Y";
  } else {  $speauth = "N"; }
  
        
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">


<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";

.general-table #prococode                        { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #procoucost                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #prococompt                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}

.style2 {
	margin-right: 0px;
}


</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>


<script type="text/javascript"> 

function decision(msg)
{
	var msg;
	return confirm(msg);
}

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function setup() {

		document.InpPO.prococode.focus();
				
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
        
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "saorddte");
		dateMask1.validationMessage = errorMessage;  
}


function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}		 	
		return xmlhttp;
}

function validateForm2()
{ 
 
  var x=document.forms["InpPO2"]["sacustcd"].value;
	if (x==null || x=="s")
	{
	alert("Customer Must Not Be Blank");
	document.InpPO.sacustcd.focus;
	return false;
	}

   var x=document.forms["InpPO2"]["saorddte"].value;
	if (x==null || x=="")
	{
	alert("Order Date Must Not Be Blank");
	document.getElementById("saorddte").focus();
	return false;
	}
}

function validateForm()
{
   var x=document.forms["InpPO"]["procoprice"].value;
	if (x==null || x=="" || x=="0")
	{
	alert("Unit Price Must Not Be Blank");
	document.getElementById("procoprice").focus();
	return false;
	}  
    
   var x=document.forms["InpPO"]["procoqty"].value;
	if (x==null || x=="" || x=="0")
	{
	alert("Ord. Qty Must Not Be Blank");
	document.getElementById("procoqty").focus();
	return false;
	} 
}


function getItemDet()
{

 var rand = Math.floor(Math.random() * 101);
 var custinfo = document.getElementById("sacustcd").value;
 var iteminfo = document.getElementById("prococode").value;

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
    var priamt = xmlhttp.responseText;
    
    var result = priamt.split("k");
    //alert (result[0]+" : "+result[1]+" : "+result[2]);
    document.getElementById("procotype").value=result[0];   
    document.getElementById("procoprice").value=result[1]; 
    document.getElementById("procouqty").value=result[2];
    document.getElementById("procouom").value=result[3];
    document.getElementById('procoqty').focus();

    //if(result[1] == 0) { alert ("Unit Price is 0"); }      
    }
  }
xmlhttp.open("GET","getitemdet.php?s="+custinfo+"&i="+iteminfo+"&m="+rand,true);
xmlhttp.send();
}

function get_totpcs ( ) {

 var ordqty = document.getElementById("procoqty").value;
 var uomqty = document.getElementById("procouqty").value;
 //var price = document.getElementById("procoprice"+str).value;
 
 var totpcs = ordqty * uomqty; 
    
 document.getElementById("procototpcs").value = totpcs;

}

</script>
</head>

<body onload="setup()" >
	  <?php include("../topbarm.php"); ?> 
<!--  <?php include("../sidebarm.php"); ?> -->

  <?php
  	 $sql = "select * from salesentry";
     $sql .= " where sordno ='".$var_ordno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $custcd = $row['scustcd'];
     $orddte = date('d-m-Y', strtotime($row['sorddte']));
     $order_no = htmlentities($row['sordno']);
     $cust_po = htmlentities($row['scustpo']);
     $remark = $row['remark'];
     
     $sql = "SELECT y.zone_desc FROM customer_master x, zone_master y ";
     $sql .= " where x.custno = '".$custcd."'";
     $sql .= " and y.zone_code = x.zone ";

     $result = mysql_query($sql) or die ("Error : ".mysql_error());
     $data = mysql_fetch_object($result);

     $zone = $data->zone_desc; 
     
  ?> 
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 900px;" class="style2">
	 <legend class="title">UPDATE SALES ENTRY</legend>
	  <br>	 
	  
	  <form name="InpPO2" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm2()">
	   
		<table style="width: 900px; font-family : verdana, helvetica; font-size : 12px;">
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Order No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="textnoentry" name="sordno" id="sordnoid" type="text" readonly style="width: 204px;" value = "<?php echo $order_no; ?>">         
		   </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   &nbsp;</td>
	  	  </tr>

	   	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Customer</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<select name="sacustcd" id="sacustcd" style="width: 268px" class="textnoentry">
			 <?php
              $sql = "select custno, name from customer_master ORDER BY custno ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['custno'].'"';
        if ($custcd == $row['custno']) { echo "selected"; }
        echo '>'.$row['custno']." | ".$row['name'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

		   </td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Order Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="saorddte" id ="saorddte" type="text" style="width: 128px;" value="<?php  echo $orddte; ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('saorddte','ddMMyyyy')" style="cursor:pointer"></td>
		   </td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px"><div id="msgcd"></div></td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Customer PO</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="sacustpo" id="sacustpoid" type="text" maxlength="45" style="width: 204px;" onchange ="upperCase(this.id)" value = "<?php echo $cust_po; ?>"></td>		   </td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Zone</td>
		   <td>:</td>
		   <td style="width: 284px">
		   <input class="textnoentry" name="szone" id ="szonecd" type="text" style="width: 128px;" value="<?php  echo $zone; ?>" readonly>
		   </td>
		  </tr> 
		  <tr>
	  	   <td ></td>
	  	   <td >Remark</td>
	  	   <td >:</td>
	  	   <td colspan="5">
			<input class="inputtxt" name="saremark" id="saremarkid" type="text" maxlength="100" style="width: 463px;" onchange ="upperCase(this.id)" value="<?php echo $remark; ?>" >
      <input type ="submit" name = "UpdHeader" value = "Upd_Header" class="butsub">   
         </td>
	  	  </tr>	      
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">&nbsp;</td>
	  	   <td style="width: 13px">&nbsp;</td>
	  	   <td style="width: 201px">
		   &nbsp;</td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   &nbsp;</td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px">
		   <td style="width: 10px"></td>
		   <td style="width: 204px"></td>
		   <td></td>
		   <td style="width: 284px">
	  	  </tr>	  	  
	  	  </table>
		 
		  <br><br>
      </form>
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
		  <table id="itemsTable" style="width: 900px; padding : 6px; font-family : verdana, helvetica; font-size : 12px;">
          	 <tr>      
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Item Code</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Unit Qty</th>
              <th class="tabheader">Type</th>
              <th class="tabheader">Unit Price</th>
              <th class="tabheader">Ord. Qty</th>
              <th class="tabheader">Total(pc)</th>              
              <th class="tabheader" colspan="2" style="width: 284px">Action</th>              
             </tr>
            </thead>
            <tbody>
              <td ></td>
              <td >
              <select name="prococode" id="prococode" style="width: 300px" onChange="getItemDet()">
			 <?php
              $sql = "select productcode, description from product where status = 'A' ORDER BY productcode ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 >$prodcd</option>";
                       
			        if(mysql_num_rows($sql_result)) 
		      	  {
			          while($row = mysql_fetch_assoc($sql_result)) 
			          { 
			          	echo '<option value="'.$row['productcode'].'"';
                  echo '>'.$row['productcode']." | ".$row['description'].'</option>';
			          } 
		          } 
	         ?>				   
	            </select>
              </td>
              <td ><input name="procouom" id="procouom" class="tInput" readonly="readonly" style="width: 60px" value ="<?php echo $produom; ?>"></td>
              <td ><input name="procouqty" value="<?php echo $produqty; ?>" class="tInput" id="procouqty" style="width: 48px; text-align : right" readonly="readonly"></td>
              <td >
			       	<input name="procotype" id="procotype" class="tInput" 
              <?php if ($speauth == "N") { echo 'readonly="readonly"'; } else { echo ' onBlur = "upperCase(this.id);"'; } ?> 
              style="width: 75px" value ="<?php echo $prodtype; ?>">              
              </td>
              <td >
			      	<input name="procoprice" class="tInput" id="procoprice" 
              <?php if ($speauth == "N") { echo 'readonly="readonly"'; } ?> 
              style=" width: 75px; text-align : right" value ="<?php echo $produpri; ?>" >
              </td>
              <td ><input name="procoqty" id="procoqty" style="width: 48px; text-align : right" value ="<?php echo $prodqty; ?>" onBlur="get_totpcs()"></td>
              <td ><input name="procototpcs" class="tInput" id="procototpcs" style=" width: 48px; text-align : right" readonly="readonly" value ="<?php echo $prodtotqty; ?>"></td>              
              <td colspan="2">
 <?php
 
  if ($var_upddata == true) {
      echo '<input type ="submit" name = "Upd" value = "UpdRec" class="butsub">';
      echo '<input type ="submit" name = "Reset" value = "Reset" class="butsub">';
  } else {
          echo '<input type ="submit" name = "Submit" value = "Submit" class="butsub">';
  }

?>              
              </td>              
             </tr>            
             <?php
             
             	$sql = "SELECT * FROM salesentrydet";
             	$sql .= " Where sordno ='".$var_ordno."'"; 
	    	      $sql .= " ORDER BY sprocd";  
			  	    $rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
       
          $sql2 = " select uom_pack from prod_uommas";
          $sql2 .= " where uom_code = '".$rowq['sprouom']."'";

          $result = mysql_query($sql2) or die ("Error uom : ".mysql_error());
          
          if(mysql_numrows($result) > 0) {
            $data = mysql_fetch_object($result);
            $var_uqty = $data->uom_pack;
            if ($var_uqty == "") { $var_uqty = 1; }         
           }  else { $var_uqty = 1; }  
           
           $var_totpcs = $rowq['sproqty'] * $var_uqty; 
           
           
    if($var_bgcol == "#ffffff") { $var_bgcol = "#efefef"; }
    else { $var_bgcol = "#ffffff"; }
  
  echo "<tr bgcolor=".$var_bgcol." onMouseOver=\"this.bgColor = '#DEE7E7'\" onMouseOut =\"this.bgColor ='".$var_bgcol."'\">";
  echo '<td>'.$i.'</td>'; 
  echo '<td>'.htmlentities($rowq['sprocd']).'</td>'; 
  echo '<td>'.$rowq['sprouom'].'</td>'; 
  echo '<td align="center">'.$var_uqty.'</td>';   
  echo '<td >'.$rowq['sptype'].'</td>'; 
  echo '<td align="right">'.$rowq['sprounipri'].'</td>';         
  echo '<td align="right">'.$rowq['sproqty'].'</td>';
  echo '<td align="center">'.$var_totpcs.'</td>';
  
   //echo '<td align="center"><a href = "'.$_SERVER['PHP_SELF'].'?act=del&sorno='.$var_ordno.'&menucd='.$var_menucode.'&i='.htmlentities($rowq['sprocd']).'">';
   //echo '<img src = "../images/b_drop.png" border="0" width="16" height = "16" alt="Delete the Current Record">';
   //echo '</a></td>';
   
   echo '<td align = "center" ><a onClick="javascript:return decision(\'Are you sure u want to ';
   echo 'DELETE this item : `'.$rowq['sprocd'].'` ?\')"';  
   echo ' href="'.$_SERVER['PHP_SELF'].'?act=del&sorno='.$var_ordno.'&menucd='.$var_menucode.'&i='.htmlentities($rowq['sprocd']).'">';
   echo '<img src = "../images/b_drop.png" border="0" width="16" height = "16" alt="Delete the Current Record">';
   echo '</a></td>';
   

   echo '<td align="center"><a href = "'.$_SERVER['PHP_SELF'].'?act=upd&sorno='.$var_ordno.'&menucd='.$var_menucode.'&i='.htmlentities($rowq['sprocd']).'">';
   echo '<img src = "../images/b_edit.png" border="0" width="16" height = "16" alt="Update the Current Record">';
   echo '</a></td>'; 
   echo '</tr>';
                
                	$i = $i + 1;          
          
             } // while
          ?>     
            </tbody>
           </table>
           
     <br />
      <table class="general-table">
      <thead>
      <tr>
      <th class="tabheader" colspan="4">View Discount
      			<input name="sordno" type="hidden"  value = "<?php echo $order_no; ?>">         
      </th>
      </tr>
      </thead>
      <tbody>
      <?php
        $sql = "select distinct (sptype) as disctype from salesentrydet";
        $sql .= " Where sordno ='".$var_ordno."'";
      
        $tmp = mysql_query ($sql) or die("cant get type : ".mysql_error());
        
        if (mysql_numrows($tmp) > 0) {
          while ($row2 = mysql_fetch_array($tmp)) {
          
            $sql2 = " select * from salesentrydisct ";
            $sql2 .= " where sordno = '".$var_ordno."'";
            $sql2 .= " and sptype = '".$row2['disctype']."'";
            
            $tmp2 = mysql_query ($sql2) or die ("Cant get disct : ".mysql_error());
            
            if (mysql_numrows($tmp2) > 0) {
               $rst = mysql_fetch_object($tmp2);
               $var_dtype = $rst->disctype;
               $var_damt = $rst->discamt; 
            }   else {  $var_dtype = "a";  $var_damt = ""; }
            
            echo '<tr>';
            echo '<td>Type : </td>';
            echo '<td><input  name = "dtype[]" type = "text" value="'.$row2['disctype'].'" style="border-style: none; " readonly></td>';
            echo '<td><select name = "dsel[]">';
            echo '<option value= "a">-SELECT-</option>';
            echo '<option value= "1"';
            if ($var_dtype == "1") { echo "selected"; }
            echo '>%</option>';
            echo '<option value= "2"';
            if ($var_dtype == "2") { echo "selected"; }
            echo '>RM</option>';
            echo '</select></td>';     
            echo '<td><input  name = "damt[]" type = "text" value="'.$var_damt.'" style="width: 100px; text-align : right" readonly  ></td>';
            echo '</tr>';
          
          }
        }
      
      ?>      
      
      </tbody>      
      </table>           
     
     <br /><br />
     
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_sales_mas.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					//include("../Setting/btnupdate.php");
          
          $locatr = "../sales/upd_saleentrydisct.php?menucd=".$var_menucode.'&sorno='.$var_ordno;
				 echo '<input type="button" value="Discount" class="butsub" style="width: 100px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				?>
				</td>
			</tr>
			<tr>
				<td style="width: 1150px" colspan="5">
				<span style="color:#FF0000">Message :</span>
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
							echo("<span>Duplicated Found Or Code Number Fall In Same Range</span>");
							break;
						case 4:
							echo("<span>Please Fill In The Data To Save</span>");
							break;
						case 5:
							echo("<span>This Product Code And Rev No Has A Record</span>");
							break;
						case 6:
							echo("<span>Duplicate Job File ID Found; Process Fail.</span>");
							break;
						case 7:
							echo("<span>This Product Code Dost Not Exits</span>");
							break;			
						default:
							echo "";
						}
					}	
          
         mysql_close ($db_link); 
				?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
	  	</table>
	   </form>	
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
