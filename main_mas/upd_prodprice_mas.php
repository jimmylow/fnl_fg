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
      $var_prodcd = $_GET['prodcd'];
	  $var_menucode = $_GET['menucd'];
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_prodcd = $_POST['prodcd'];
         $var_menucode  = $_POST['menucd'];
         $backloc = "../main_mas/upd_prod_mas.php?prodcd=".$var_prodcd."&menucd=".$var_menucode;
	
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }    
    
    if ($_POST['Submit'] == "Update") {
     $var_prodcd = $_POST['prodcd'];
     
     if($var_prodcd  <> "") {
       
     $var_menucode  = $_POST['menucd'];
     
				$sql =  "Delete From prodprice";
				$sql .= "  Where productcode ='$var_prodcd'";
				
				mysql_query($sql) or die ("Cant delete price : ".mysql_error());        
				
				if(!empty($_POST['pcode']) && is_array($_POST['pcode'])) 
				{	
					foreach($_POST['pcode'] as $row=>$key ) {
						$pritype   = $key;
						$uprice   = $_POST['damt'][$row];

            //echo "<br><br><br><br>t : ".$sptype." DIsc : ".$disctype." Amt : ".$discamt;
                        
						if ($pritype <> "" && $uprice > 0)
						{

							$sql = "INSERT INTO prodprice values 
						    		('$var_prodcd', '$pritype', '$uprice')";
                    
							mysql_query($sql) or die ("Cant insert : ".mysql_error());
           				}	
					}
				}     
             /*
         $sql = "Update product set P1 = '$p1', P2 = '$p2', P3 = '$p3', P4 = '$p4', P5 = '$p5', P6 = '$p6', P7 = '$p7', P8 = '$p8', P9 = '$p9', P10 = '$p10',  ";
         $sql .= " P11 = '$p11', P12 = '$p12', P13 = '$p13', P14 = '$p14', P15 = '$p15', P16 = '$p16', P17 = '$p17', P18 = '$p18', P19 = '$p19', P20 = '$p20',  "; 
         $sql .= " P21 = '$p21', P22 = '$p22', P23 = '$p23', P24 = '$p24', P25 = '$p25', P26 = '$p26', P27 = '$p27', P28 = '$p28', P29 = '$p29', P30 = '$p30',  "; 
         $sql .= " P31 = '$p31', P32 = '$p32', P33 = '$p33', P34 = '$p34', P35 = '$p35', P36 = '$p36', P37 = '$p37', P38 = '$p38', P39 = '$p39', P40 = '$p40',  "; 
         $sql .= " Unit1 = '$u1', Unit2 = '$u2', Unit3 = '$u3', Unit4 = '$u4', Unit5 = '$u5', Unit6 = '$u6', Unit7 = '$u7', Unit8 = '$u8', Unit9 = '$u9', Unit10 = '$u10',  ";
         $sql .= " Unit11 = '$u11', Unit12 = '$u12', Unit13 = '$u13', Unit14 = '$u14', Unit15 = '$u15', Unit16 = '$u16', Unit17 = '$u17', Unit18 = '$u18', Unit19 = '$u19', Unit20 = '$u20',  "; 
         $sql .= " Unit21 = '$u21', Unit22 = '$u22', Unit23 = '$u23', Unit24 = '$u24', Unit25 = '$u25', Unit26 = '$u26', Unit27 = '$u27', Unit28 = '$u28', Unit29 = '$u29', Unit30 = '$u30',  "; 
         $sql .= " Unit31 = '$u31', Unit32 = '$u32', Unit33 = '$u33', Unit34 = '$u34', Unit35 = '$u35', Unit36 = '$u36', Unit37 = '$u37', Unit38 = '$u38', Unit39 = '$u39', Unit40 = '$u40' "; 
         $sql .= " WHERE ProductCode = '$var_prodcd'";
         
         //echo $sql;
         mysql_query($sql) or die("cant update : ".mysql_error());  */
         $backloc = "../main_mas/upd_prod_mas.php?prodcd=".$var_prodcd."&menucd=".$var_menucode;
	
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
</script>
</head>

<body onload="document.InpSuppMas.prodcdid.focus();">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->
        
  <div class="contentc">

	<fieldset name="Group1" style=" width: 760px;" class="style2">
	 <legend class="title">EDIT PRODUCT PRICE MASTER - <?php echo $var_prodcd; ?></legend>
	  <br>
	  <fieldset name="Group1" style="width: 745px; height: 380px">
	  <form name="InpSuppMas" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px; width: 970px;">
	    <input name="menucd" type="hidden" value="<?php echo $var_menucode;?>">	   
     <table>
	    <tr>
	      <td></td>
	      <td class="tdlabel" colspan="3">Product Code :
		  <input class="inputtxt" name="prodcd" id ="prodcdid" readonly="readonly" type="text" maxlength="50" style="width: 396px" value="<?php echo $var_prodcd; ?>">
		  </td>
      </tr>     
         <tr>
          <td></td>
          <td>
      <table class="general-table">
      <tbody>
      <?php
        $sql = "select price_code, price_desc from price_master";
        $sql .= " order by price_code";
      
        $tmp = mysql_query ($sql) or die("cant get price : ".mysql_error());
        
        if (mysql_numrows($tmp) > 0) {
          while ($row2 = mysql_fetch_array($tmp)) {
          
            $sql2 = " select uprice from prodprice ";
            $sql2 .= " where productcode = '".$var_prodcd."'";
            $sql2 .= " and pricecode = '".$row2['price_code']."'";
            
            $tmp2 = mysql_query ($sql2) or die ("Cant get disct : ".mysql_error());
            
            if (mysql_numrows($tmp2) > 0) {
               $rst = mysql_fetch_object($tmp2);
               $var_uprice = $rst->uprice;
            }   else {  $var_uprice = ""; }  
            
            echo '<tr>';
            echo '<td><input  name = "pcode[]" type = "text" value="'.$row2['price_code'].'" style="border-style: none; " readonly></td>';
            echo '<td> - '.$row2['price_desc'].'</td>'; 
            echo '<td style="width : 30px;">&nbsp;</td>';    
            echo '<td><input  name = "damt[]" type = "text" value="'.$var_uprice.'" style="width: 100px; text-align : right" ></td>';
            echo '</tr>';
          
          }
        }
      
      ?>      
      
      </tbody>      
      </table>           
          </td>
         </tr>         
                           
		  </table>
		  </fieldset>
	  	 <table>
	  	 <tr><td style="width: 700px"></td></tr>
	  	 <tr>
	  	   <td align="center" style="width: 700px">
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
