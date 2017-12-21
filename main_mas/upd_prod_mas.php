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
    
    if ($_POST['Submit'] == "Update") {
     $var_prodcd = $_POST['prodcd'];
     
     if($var_prodcd  <> "") {
      
     $owncd   = $_POST['owncd'];
     $category = $_POST['category'];
     $desc =  mysql_real_escape_string($_POST['desc']);
     $groupcd = $_POST['groupcd'];
     $size = $_POST['size'];
     $col = $_POST['col']; 
     $location = $_POST['location'];              
     
     $selltype = $_POST['selltype'];
     $exunit = $_POST['exunit'];
     $expri = $_POST['expri'];
     //$exdoz = $_POST['exdoz'];
     //$agenunit  = $_POST['agenunit'];
     //$agenpri  = $_POST['agenpri'];
     //$agendoz = $_POST['agendoz'];
     
     $suppmoby= $var_loginid;
     $suppmoon= date("Y-m-d H:i:s");     
     
     $var_menucode1  = $_POST['menucd'];
     
         $sql = "Update product set OwnCode ='$owncd', ";
         $sql .= " category = '$category', GroupCode = '$groupcd', ";
         $sql .= " Description = '$desc', Size = '$size', Color ='$col', ";
         $sql .= " Selltype = '$selltype', ExFacPrice = '$expri', ExUnit = '$exunit', ";
         $sql .= " location = '$location',  ";
         $sql .= " modified_by='$suppmoby',";
         $sql .= " modified_on='$suppmoon' WHERE ProductCode = '$var_prodcd'";
         
         mysql_query($sql) or die("cant update : ".mysql_error());
         $backloc = "../main_mas/m_prod_mas.php?menucd=".$var_menucode1;
	
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";     

    }
        
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
         
         $backloc = "../main_mas/vm_prodqty_mas.php?prodcd=".$var_prodcd."&menucd=".$var_menucode1;
	
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }     
    
    
    if ($_POST['Submit'] == "Upload") { 
    
        //phpinfo(); 
         $var_menucode1  = $_POST['menucd'];
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
	 <legend class="title">EDIT PRODUCT MASTER - <?php echo $var_prodcd; php?></legend>
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
		   <select name="owncd" id = "owncdid" >

       <?php
         
         $sql = "select category_code, category_desc from mdfcategory_master";
         $sql .= " order by category_code";
         
         $tmp = mysql_query($sql) or die ("Cant get category : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['category_code']."'";
             if ($owncd == $row['category_code']) { echo " selected"; }
             echo ">".$row['category_code']." - ".$row['category_desc']."</option>";
           
           }
          
         }
       ?>
		   </select>  
		  </td>
		  <td></td>
		  <td class="tdlabel">Category</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="category" >

       <?php
         
         $sql = "select category_code, category_desc from category_master";
         $sql .= " order by category_code";
         
         $tmp = mysql_query($sql) or die ("Cant get category : ".mysql_error());
         
		 echo "<option value = '".$category."'>".$category."</option>";
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['category_code']."'";
             if ($category == $row['category_code']) { echo " selected"; }
             echo ">".$row['category_code']." - ".$row['category_desc']."</option>";
           
           }
          
         }
       ?>
		   </select>  
		  </td>
	  	 </tr>     
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 200px">Group Code</td>
	      <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="groupcd" id ="groupcdid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 396px" value="<?php echo $groupcd; ?>" >
		  </td>
		  <td></td>
		  <td class="tdlabel">Size</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="size" id ="sizeid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 200px" value="<?php echo $size; ?>" ></td>
		  </td>
	  	 </tr>
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 200px">Product Code<span class="style4">*</span></td>
	      <td>:</td>
	  	  <td>
		  <input class="textnoentry" name="prodcd" id ="prodcdid" readonly="readonly" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 396px" value="<?php echo $var_prodcd; ?>">
		  </td>
		  <td></td>
		  <td class="tdlabel">Color</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="col" id ="colid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 200px" value="<?php echo $col; ?>"></td>
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
		  <input class="inputtxt" name="desc" id ="descid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 396px" value="<?php echo $desc; ?>">
		  </td>
		  <td></td>
		  <td class="tdlabel">Location</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="location" >

       <?php
         
         $sql = "select location_code, location_desc from location_master";
         $sql .= " order by location_code desc";
         
         $tmp = mysql_query($sql) or die ("Cant get location : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option ";
             if($location == $row['location_code']) { echo "selected"; }
             echo " value = '".$row['location_code']."'>";
             echo $row['location_code']." - ".$row['location_desc']."</option>";
           
           }
          
         }
       ?>
		   </select>        
		  </td>
	  	 </tr>
         <tr>
          <td></td>
          <td>Sell Type</td>
          <td>:</td>
          <td>
		   <select name="selltype" >

       <?php
         
         $sql = "select salestype_code, salestype_desc from salestype_master";
         $sql .= " order by salestype_code";
         
         $tmp = mysql_query($sql) or die ("Cant get sales type : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['salestype_code']."'";
             if($selltype == $row['salestype_code']) { echo " selected"; }
             echo ">".$row['salestype_code']." - ".$row['salestype_desc']."</option>";
           }
         }
       ?>
		   </select>          
          </td>
          <td colspan="4">
          <input type=submit name = "Submit" value="Price" class="butsub" style="width: 60px; height: 32px" >
          <input type=submit name = "Submit" value="SKU" class="butsub" style="width: 60px; height: 32px" > 
          <input type=submit name = "Submit" value="Quantity" class="butsub" style="width: 90px; height: 32px" >          
          </td>
         </tr>
         <tr><td></td></tr>
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
 
<?php


               $var_sql = "select count(*) as cnt from tblphoto";
               $var_sql .= " where ProductCode = '".$var_prodcd."'";

               $que_exist = mysql_query($var_sql) or die (mysql_error());
               $res_exist = mysql_fetch_object($que_exist);

               //echo $res_exist->cnt;

               if($res_exist->cnt < 1) {  

?>
                <input type="file" class="texta" name="pppho" size = "30">
                <br />(Maximum Picture Size = 1MB)
                <br /><br /><input type="submit" value="Upload" name="Submit" class="butsub">

<?php }   else {

              $var_sql = "select picname from tblphoto";
              $var_sql .= " where ProductCode = '".$var_prodcd."'";

              //echo $var_sql;
              $que_photo = mysql_query($var_sql, $db_link) or die (mysql_error());

             while ($res_photo = mysql_fetch_array($que_photo)) { 

               echo '<a href = "#" onClick="newWindow(\'viewpic.php?pic='.$res_photo['photo'].'\',\'window2\')">'; 
               echo '<img src = "../'.$rmpath.'/'.$res_photo['picname'].'" width="200px" border="0"></a>';
               echo '<br>';
               echo '<span class="header" style="text-align:center; height:15px"><b>'.$res_photo['picname'].'</b></span>';
               echo '<br><span class="header">Delete Picture</span>';

               echo '<a onClick="javascript:return decision(\'Are you sure u want to ';
               echo 'DELETE this picture ?\')"';
               echo ' href=\''.$_SERVER['PHP_SELF'].'?action=del&menucd='.$var_menucode1.'&prodcd='.$var_prodcd.'&pho='.$res_photo['picname'].'\'>';
               echo '<img src="../images/deleterow.png" border="0" width="16" height="16" hspace="2" alt="To delete this picture" /></a><br><br>'; 

            }
  }

 ?>          
          
          </td>
         </tr>
         <tr>
          <td></td>
          <td>Cost Price</td>
          <td >:</td>
          <td><table width="100%" >
          <tr>
          <td>
		   <select name="exunit" id ="exunitid" >

       <?php
         
         $sql = "select uom_code, uom_desc from prod_uommas";
         $sql .= " order by uom_code";
         
         $tmp = mysql_query($sql) or die ("Cant get UOM type : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['uom_code']."'";
             if($exunit == $row['uom_code']) { echo " selected"; }
             echo ">".$row['uom_code']." - ".$row['uom_desc']."</option>";
           }
         }
       ?>
		   </select> 
          </td>
          <td><input class="inputtxt" name="expri" id ="expriid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 80px" value="<?php echo $expri; ?>"></td>
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
            
			//echo $sql;
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
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$createby.'" size="20">';
	  	    ?>
          </td>
			<td style="height: 30px"></td>
			<td style="width: 81px; height: 30px;">Create On</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
      
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$createon.'" size="20">';
	  	    ?>          
			</td>
	  	  </tr>  
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Modified By</td>
	  	    <td>:</td>
	  	    <td>
	  	    <?php
	  	       echo '<input readonly="readonly" class="inputtxt" input="text"  value="'.$modiby.'" size="20">';
	  	    ?>
          </td>
			<td style="height: 30px"></td>
			<td style="width: 81px; height: 30px;">Modified On</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px"> 
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
	  	 <table width="100%">
	  	 <tr><td ></td></tr>
	  	 <tr>
	  	   <td align="center" >
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" >
	  	   <input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	 </table>
      </form>	
	</div>

</body>

</html>
