<?php

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
  include("../Setting/config_photo.php");  
    
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
	  $var_menucode1 = $_GET['menucd']; 
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode1  = $_POST['menucd'];
         $backloc = "../main_mas/m_prod_mas.php?menucd=".$var_menucode1;
	
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }    
    
   
    if ($_POST['Submit'] == "Price") {
         $var_menucode1  = $_POST['menucd'];
         $var_prodcd = $_POST['prodcd'];
         
         $backloc = "../main_mas/upd_prodprice_mas.php?prodcd=".$var_prodcd."&menucd=".$var_menucode1;
	
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    } 
    
    if ($_POST['Submit'] == "SKU") {
         $var_menucode1  = $_POST['menucd'];
         $var_prodcd = $_POST['prodcd'];
         
         $backloc = "../main_mas/upd_prodsku_mas.php?prodcd=".$var_prodcd."&menucd=".$var_menucode1;
	
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    } 
    
    if ($_POST['Submit'] == "Quantity") {
         $var_menucode1  = $_POST['menucd'];
         $var_prodcd = $_POST['prodcd'];
         
         $backloc = "../main_mas/vm_prodqty_mas.php?prodcd=".$var_prodcd."&menucd=".$var_menucode1."&s=v";
	
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }     
    
    
    if ($_POST['Submit'] == "Upload") { 
    
         //phpinfo(); 
      $var_menucode1 = $_POST['menucd'];  
         $var_prodcd = $_POST['prodcd'];    
      
       if(isset($_GET['pno'])) { $var_itemno = $_GET['pno'];  }
       if(isset($_POST['pno'])) { $var_itemno = $_POST['pno'];  }  
       
  //echo "photo--------------------------------------".$_FILES['pppho'];

if (!isset($_FILES['pppho'])) {
          echo "<script>";
          echo "alert('";
          echo "No Image File is selected!";
          echo "')";
          echo "</script>";
         }
       
 #--------------------------------for photo 1 ------------------------------------#
 
if (is_uploaded_file($_FILES['pppho']['tmp_name'])) {

if ($_FILES['pppho']['size']>$max_size) {
          echo "<script>";
          echo "alert('";
          echo "File Size Too Big!";
          echo "')";
          echo "</script>";
         } else {

if(($_FILES['pppho']['type']=="image/gif") || 
($_FILES['pppho']['type']=="image/pjpeg") || ($_FILES['pppho']['type']=="image/jpeg") || ($_FILES['pppho']['type']=="image/png")) {

        //if (file_exists("../".$rmpath .$var_postitemno. $HTTP_POST_FILES['rmpho']['name'])) {
        if (file_exists("../".$rmpath . $_FILES['pppho']['name'])) {
           echo "<script>";
           echo "alert('";
           echo "There already exists a file with this name, please rename your file and try again";
           echo "')";
           echo "</script>";
            } else {

        $res = copy($_FILES['pppho']['tmp_name'], "../".$rmpath .$_FILES['pppho']['name']);

         if (!$res) { echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\">Didn't work, please try again</font><br>\n"; exit; } 
         else {
               // $var_postpho = $var_postpassport.$HTTP_POST_FILES['rmpho']['name'];
               $var_postpho = $_FILES['pppho']['name'];

                   mysql_query("insert into tblphoto (`ProductCode`, `picname`)
                                     values ('$var_prodcd', '$var_postpho');",  $db_link) or die(mysql_error());  

         } 
        } 
       } 
     } 
    } 
 #---------------------------------- end photo 1 ---------------------------------#       
       
   } 
   
//----------------------------------------- delete photo -----------------------------------------------------------//

      $var_action     = $_REQUEST['action'];
      $var_post_photo = $_REQUEST['pho'];

      if($var_action == "del" ) {

       $filename = "../".$rmpath.$var_post_photo;
       $fh = fopen($filename, 'w') or die("can't open file");
       fclose($fh);
  
       unlink($filename);  // to delete the picture
       
       //phpinfo();

       mysql_query("delete from tblphoto  
                    where `ProductCode` = '$var_prodcd' and `picname` = '$var_post_photo';", $db_link) or die ("cant delete : ".mysql_error());

       //$var_photo = NULL;
       }

//----------------------------------------- delete photo -----------------------------------------------------------//
                
   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
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
<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>


<script type="text/javascript" charset="utf-8"> 

$(document).ready(function() {
	$('#example').dataTable( {
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
		"bStateSave": true,
		"bFilter": true,
		"sPaginationType": "full_numbers",
		"bAutoWidth":false
	})
	
	.columnFilter({sPlaceHolder: "head:after",

		aoColumns: [ 
					 null,	
					 { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     null
				   ]
		});	
} );
			
jQuery(function($) {
  
    $("tr :checkbox").live("click", function() {
        $(this).closest("tr").css("background-color", this.checked ? "#FFCC33" : "");
    });
  
});


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

function decision(msg)
{
	var msg;
	return confirm(msg);
}
			
</script>
</head>

<body onload="document.InpSuppMas.owncdid.focus();">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->
  
 <?php
   
        $sql = "select GroupCode, OwnCode, Category, Description, Size, Color, ";
        $sql .= " Selltype, ExFacPrice, ExUnit, Location ";
        $sql .= " from product";
        $sql .= " where ProductCode ='".$var_prodcd."'";
        
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $groupcd = $row[0];
        $owncd = $row[1];
        $category = $row[2];
        $desc = $row[3];
        $size = $row[4];
        $col  = $row[5];
        $selltype  = $row[6];
        $expri  = $row[7];                                                                                          
        $exunit = $row[8];
        //$exdoz = $row[9];
        $location = $row[9];

    ?>
      
  <div class="contentc">

	<fieldset name="Group1"  class="style2" >
	 <legend class="title">VIEW PRODUCT MASTER - <?php echo $var_prodcd; ?></legend>
     </fieldset>
	  <br>
	  <form name="InpSuppMas" method="POST"  enctype="multipart/form-data" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px; width: 970px;">
	    <input name="menucd" type="hidden" value="<?php echo $var_menucode1; ?>">	   
     <table border=0>
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 200px">Own Code</td>
	      <td>:</td>
	  	  <td>
		  <input class="textnoentry" name="owncode" id ="groupcdid0" type="text" readonly style="width: 396px" value="<?php echo $owncd; ?>" >
		  </td>
		  <td></td>
		  <td class="tdlabel">Category</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="textnoentry" name="category" id ="sizeid0" type="text" readonly style="width: 200px" value="<?php echo $category; ?>" ></td>
	  	 </tr>     
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 200px">Group Code</td>
	      <td>:</td>
	  	  <td>
		  <input class="textnoentry" name="groupcd" id ="groupcdid" type="text" readonly style="width: 396px" value="<?php echo $groupcd; ?>" >
		  </td>
		  <td></td>
		  <td class="tdlabel">Size</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="textnoentry" name="size" id ="sizeid" type="text" readonly style="width: 200px" value="<?php echo $size; ?>" ></td>
		  </td>
	  	 </tr>
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 200px">Product Code<span class="style4">*</span></td>
	      <td>:</td>
	  	  <td>
		  <input class="textnoentry" name="prodcd" id ="prodcdid" readonly="readonly" type="text" style="width: 396px" value="<?php echo $var_prodcd; ?>">
		  </td>
		  <td></td>
		  <td class="tdlabel">Color</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="textnoentry" name="col" id ="colid" type="text" readonly style="width: 200px" value="<?php echo $col; ?>"></td>
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
	  	  <td class="tdlabel">Description</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="textnoentry" name="desc" id ="descid" type="text" readonly  style="width: 396px" value="<?php echo $desc; ?>">
		  </td>
		  <td></td>
		  <td class="tdlabel">Location</td>
	  	  <td>:</td>
	  	  <td>
		   <input class="textnoentry" name="location" id ="prodcdid0" readonly="readonly" type="text" style="width: 150px" value="<?php echo $location; ?>"></td>
	  	 </tr>
         <tr>
          <td></td>
          <td>Sell Type</td>
          <td>:</td>
          <td>
		   <input class="textnoentry" name="selltype" id ="groupcdid1" type="text" readonly style="width: 396px" value="<?php echo $selltype; ?>" ></td>
          <td colspan="4">
          <input type=submit name = "Submit" value="Price" class="butsub" style="width: 60px; height: 32px" >
          <input type=submit name = "Submit" value="SKU" class="butsub" style="width: 60px; height: 32px" > 
          <input type=submit name = "Submit" value="Quantity" class="butsub" style="width: 90px; height: 32px" >          
          </td>
         </tr>
         <tr><td>&nbsp;</td></tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td colspan="2"></td>
          <td >
          <table width="100%" >
          <tr>
          <td>Unit</td>
          <td>Price</td>
          <!-- <td>DOZ</td> -->
          </tr>
          </table>
          </td>
          <td rowspan="3" colspan="4">
 
		  &nbsp;</td>
         </tr>
         <tr>
          <td></td>
          <td>Cost Price</td>
          <td >:</td>
          <td><table width="100%" >
          <tr>
          <td style="width: 179px">
		   <input class="textnoentry" name="costprice" id ="sizeid1" type="text" readonly style="width: 100px" value="<?php echo $exunit ; ?>" ></td>
          <td>
		  <input class="textnoentry" name="expri" id ="expriid" type="text" readonly style="width: 80px" value="<?php echo $expri; ?>"></td>
          <!-- <td><input class="inputtxt" name="exdoz" id ="exdozid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 80px" value="<?php //echo $exdoz; ?>"></td> -->
          </tr>
          </table>
          </td>
         </tr>
         <tr>
          <td></td>
          <td></td>
          <td ></td>
          <td>
          <!-- <table width="100%">
          <tr>
          <td><input class="inputtxt" name="agenunit" id ="agenunitid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 80px"></td>
          <td><input class="inputtxt" name="agenpri" id ="agenpriid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 80px"></td>
          <td><input class="inputtxt" name="agendoz" id ="agendozid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 80px"></td>
          </tr>
          </table>  -->
          </td>
         </tr>  
<?php        
            $sql = " select created_by, created_on, modified_by, modified_on";
            $sql .= " from product ";
            $sql .= " where ProductCode = '".$var_prodcd."'";
            
            $tmp = mysql_query($sql) or die("Cant get info : ".mysql_error());
            
            if (mysql_numrows($tmp) > 0) {
               $rst = mysql_fetch_object($tmp);
               $createby = $rst->created_by;
               $createon = $rst->created_on;
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
	  	       echo '<input readonly="readonly" class="textnoentry" input="text"  value="'.$createby.'" size="20">';
	  	    ?>
          </td>
			<td style="height: 30px"></td>
			<td style="width: 81px; height: 30px;">Create On</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
      
	  	    <?php
	  	       echo '<input readonly="readonly" class="textnoentry" input="text"  value="'.$createon.'" size="20">';
	  	    ?>          
			</td>
	  	  </tr>  
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Modified By</td>
	  	    <td>:</td>
	  	    <td>
	  	    <?php
	  	       echo '<input readonly="readonly" class="textnoentry" input="text"  value="'.$modiby.'" size="20">';
	  	    ?>
          </td>
			<td style="height: 30px"></td>
			<td style="width: 81px; height: 30px;">Modified On</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px"> 
	  	    <?php
	  	       echo '<input readonly="readonly" class="textnoentry" input="text"  value="'.$modion.'" size="20">';
	  	    ?>          
			</td>
	  	  </tr>                               
         
         <tr>
	  	    <td></td>
	  	    <td></td><td></td><td></td><td></td><td></td><td></td><td><div id="msg"></div></td>
		  </tr>

		  </table>
       <br /><br />
  <!-- <fieldset>   
	 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
           <tr>
          <th></th>
          <th style="width: 50px">Trx Type</th>
          <th style="width: 30px">Ref No</th>
          <th style="width: 15px">Trx Date</th>
          <th ></th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 50px">Trx Type</th>
          <th class="tabheader" style="width: 30px">Ref No.</th>
          <th class="tabheader" style="width: 15px">Trx Date</th>
          <th class="tabheader" style="width: 15px">Trx Qty</th>          
         </tr>
         </thead>
		 <tbody> -->
		 <?php /*
		    $sql = "SELECT reftype, refid, trxdate, qtyin, qtyout ";
		    $sql .= " FROM invthist where prodcode = '".mysql_real_escape_string($var_prodcd)."'";
		    $sql .= " ORDER BY trxdate";
            
        //echo $sql;    
			  $tmp = mysql_query($sql) or die ("Cant get hist : ".mysql_error());
        
        if(mysql_numrows($tmp)>0) { 

		       $numi = 1;
		    	 while ($rowq = mysql_fetch_assoc($tmp)) { 
			
			   	 $reftype = htmlentities($rowq['reftype']);
				   $refid = htmlentities($rowq['refid']);
				   $qtyin = htmlentities($rowq['qtyin']);
				   $qtyout = htmlentities($rowq['qtyout']);
           $trxdate = $rowq['trxdate'];
           
           switch ($reftype) {
            case "SA" : $var_desc = "Sales Order"; break;
            case "RN" : $var_desc = "Sales Return"; break;
            case "RC" : $var_desc = "Receive from Supplier"; break;
            case "RS" : $var_desc = "Return to Supplier"; break;
            case "TO" : $var_desc = "Transfer Out"; break;
            case "TI" : $var_desc = "Transfer In"; break;
            case "AD" : $var_desc = "Adjustment"; break;
            case "OP" : $var_desc = "Opening Balance"; break;
            
           }
           
           $qtydisp = 0;
           if ($qtyin > 0) { $qtydisp = $qtyin; }
           else { $qtydisp = $qtyout * -1; }
           
				   //$trxdate = date('Y-m-d', strtotime($rowq['trxdate']));
			  	 echo '<tr bgcolor='.$defaultcolor.'>';
				
            	echo '<td>'.$numi.'</td>';
           		echo '<td >'.$reftype." | ".$var_desc.'</td>';
           		echo '<td >'.$refid.'</td>';
             	echo '<td align="center">'.$trxdate.'</td>';              
             	echo '<td align="right">'.$qtydisp.'</td>';
            	echo '</tr>';
            $numi = $numi + 1;
			   }
      } */
		 ?>
		 <!-- </tbody>
		 </table> 
     </fieldset>  -->    
	  	 <table width="100%">
	  	 <tr><td ></td></tr>
	  	 <tr>
	  	   <td align="center" >
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	 </table>     
      </form>	
	</div>

</body>

</html>
