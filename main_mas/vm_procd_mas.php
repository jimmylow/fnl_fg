<?php

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];

    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "../login.htm"';
      echo "</script>";
    } else {
      $var_stat = $_GET['stat'];
      $var_prodcode = htmlentities($_GET['procd']);
      $var_menucode = $_GET['menucd'];    
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../main_mas/nlg_prod_mas.php?menucd=".$var_menucode;
        
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }     
   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<link rel="stylesheet" href="../css/lightbox.css" type="text/css" media="screen">	
<style media="all" type="text/css">@import "../css/styles.css";
.style2 {
	margin-right: 0px;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="../js/imgjs/prototype.js"></script>
<script type="text/javascript" src="../js/imgjs/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="../js/imgjs/lightbox.js"></script>


<script type="text/javascript" charset="utf-8"> 
</script>
</head>
<body>

		
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->
  
 <?php
 
    
	$var_server2 = '192.168.0.142:9909';
	$var_userid2 = 'root';
	$var_password2 = 'admin9002';
	$var_db_name2='nl_db'; 
  
	$db_link2  = mysql_connect($var_server2, $var_userid2, $var_password2)or die("cannot connect");
  mysql_select_db("$var_db_name2")or die("cannot select DB ".$var_db_name.mysql_error());
	
	mysql_query("SET NAMES 'utf8'", $db_link2)or die(mysql_error()); 	//this will take effect both retrieve, update or insert data of utf-8 
  
        $sql = "select *";
        $sql .= " from pro_cd_master";
        $sql .= " where prod_code ='".$var_prodcode."'";
        
        $sql_result = mysql_query($sql, $db_link2);
        $row = mysql_fetch_array($sql_result);

        $prodbuyer = $row[1];
        $prodtype = $row[2];
        $prodcat = $row[14];
        $prodsiz = $row[4];
        $prodcol = $row[5];
        $proddesc = $row[6];
        $produom = $row[7];
        $prodrmk = $row[8];
        $prodcreby = $row[10];
        $prodcreon = date('d-m-Y', strtotime($row[11]));
        $prodmodby = $row[12];
        $prodmodon = date('d-m-Y', strtotime($row[13]));
        $prodimg = $row[9];

        $sql = "select pro_buy_desc from pro_buy_master  ";
        $sql .= " where pro_buy_code ='".$prodbuyer."'";
        $sql_result = mysql_query($sql, $db_link2);
        $row = mysql_fetch_array($sql_result);
        $prodbuyerde = $row[0];
        
        $sql = "select type_desc from protype_master ";
        $sql .= " where type_code ='".$prodtype."'";
        $sql_result = mysql_query($sql, $db_link2);
        $row = mysql_fetch_array($sql_result);
        $prodtypede = $row[0];
        
        $sql = "select clr_desc from pro_clr_master  ";
        $sql .= " where clr_code ='".$prodcol."'";
        $sql_result = mysql_query($sql, $db_link2);
        $row = mysql_fetch_array($sql_result);
        $prodcolde = $row[0];        
        
        $dirimg = "../../nl_pro/bom_master/procdimg/";
        $imgname = $dirimg.$prodimg;
        
     //--------ex factory price -------------//
     
  	 $sql = "select exftrycost from prod_matmain";
     $sql .= " where prod_code ='".$var_prcode."'";
     $sql_result = mysql_query($sql) or die ("cant get cost : ".mysql_error());
     
     if (mysql_numrows($sql_result) > 0) {
       $row = mysql_fetch_array($sql_result);
       $expri = $row['exftrycost'];
     } else { $expri = 0; }          
        
    if ($_POST['Submit'] == "Copy") {
    
        $var_prcode = $_POST['prodcode'];
        $var_prodcode = $var_prcode;
        
        $sql = "select *";
        $sql .= " from pro_cd_master";
        $sql .= " where prod_code ='".$var_prcode."'";
        
        $sql_result = mysql_query($sql, $db_link2);
        $row = mysql_fetch_array($sql_result);

        $prodbuyer = $row[1];
        $prodtype = $row[2];
        $prodcat = $row[14];
        $prodsiz = $row[4];
        $prodcol = $row[5];
        $proddesc = $row[6];
        $produom = $row[7];
        $prodrmk = $row[8];
        $prodcreby = $row[10];
        $prodcreon = date('d-m-Y', strtotime($row[11]));
        $prodmodby = $row[12];
        $prodmodon = date('d-m-Y', strtotime($row[13]));
        $prodimg = $row[9];        
        
        
     //--------ex factory price -------------//
     
  	 $sql = "select exftrycost from prod_matmain";
     $sql .= " where prod_code ='".$var_prcode."'";
     $sql_result = mysql_query($sql) or die ("cant get cost : ".mysql_error());
     
     if (mysql_numrows($sql_result) > 0) {
       $row = mysql_fetch_array($sql_result);
       $expri = $row['exftrycost'];
     } else { $expri = 0; }  

     if ($var_prcode <> "") {
 
     include("../Setting/Connection.php");
 
      $var_sql = " SELECT count(*) as cnt from product ";
      $var_sql .= " WHERE ProductCode = '$var_prcode'";

      $query_id = mysql_query($var_sql, $db_link) or die ("Cant Check Product ".mysql_error());
      $res_id = mysql_fetch_object($query_id);

      //echo "CNT : ".$res_id->cnt;
      if ($res_id->cnt > 0 ) {
	    
         echo "<script>";
         echo "alert('This Product Existed !');";
         echo "</script>";       
      }else {
         $vartoday = date("Y-m-d H:i:s");
         //$expri = 0;
         $exdoz = 0;
         
         $sql = "INSERT INTO product "; 
         $sql .= " (ProductCode, Category, Description, Size, Color, ";
         $sql .= " ExFacPrice, ExUnit, ExDozen, ";
         $sql .= " created_by, created_on, modified_by, modified_on ) values ";
         $sql .= " ('$var_prcode', '$prodcat',  ";
         $sql .= " '$proddesc','$prodsiz','$prodcol', ";
         $sql .= " '$expri', '$exunit', '$exdoz', ";
         $sql .= " '$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
         mysql_query($sql, $db_link) or die ("Insert failed : ".mysql_error()); 
              
     	   //$backloc = "../main_mas/upd_prod_mas.php?prodcd=".$prodcd."&menucd=".$var_menucode;
         //echo "<script>";
         //echo 'location.replace("'.$backloc.'")';
         //echo "</script>";   
         
         echo "<script>";
         echo "alert('This Product ".$var_prcode." Copied');";
         echo "</script>";             
       } 
     }
				        
    
    
         //$var_menucode  = $_POST['menudcode'];
         //$backloc = "../main_mas/nlg_prod_mas.php?menucd=".$var_menucode;
        
 		     //echo "<script>";
         //echo 'location.replace("'.$backloc.'")';
         //echo "</script>";
    }         
?> 

  <div class="contentc">

	<fieldset name="InpProCDMasV" class="style2" style="width: 800px; height: 420px;">
	 <legend class="title">PRODUCT CODE MASTER <?php echo $var_prodcode;?></legend>
	  <br>
	 	<form name="InpProCDMasV" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" enctype="multipart/form-data" style="height: 200px; width: 837px;">
		<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">

		<table style="width: 843px" >
	  	  <tr>
	  	    <td style="height: 28px"></td>
	  	    <td style="width: 113px;">Product Code</td>
	  	    <td style="width: 10px">:</td>
	  	    <td style="width: 197px;">
			<input class="inputtxt" readonly="readonly" name="prodcode" id ="prodcodeid" type="text" value="<?php echo $var_prodcode;?>" style="width: 106px"></td>
			<td></td>
			<td colspan="3" rowspan="6">
			<a href="<?php echo $imgname; ?>" rel="lightbox">
			<img id="proimgpre" height="120" width="150" src="<?php echo $imgname; ?>">
			</a>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 113px"></td>
	  	    <td style="width: 10px"></td> 
            <td style="width: 197px" colspan="5"></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 113px">Product Buyer</td>
	  	    <td style="width: 10px">:</td>
	  	    <td style="width: 197px" colspan="5">
			<input class="inputtxt" readonly="readonly" name="prodbuy" id ="prodbuyid" type="text" value="<?php echo $prodbuyer;?>" style="width: 53px">
			<label id="Label1"><?php echo $prodbuyerde;?></label>
			</td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 113px">Product Type</td>
	  	   <td style="width: 10px">:</td>
	  	   <td style="width: 197px" colspan="5">
           <input class="inputtxt" readonly="readonly" name="prodtyp" id ="prodtypid" type="text" value="<?php echo $prodtype;?>" style="width: 75px; height: 22px;">
		   <label id="Label1"><?php echo $prodtypede;?></label>
		   </td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 113px"></td>
	  	   <td style="width: 10px"></td>
	  	   <td style="width: 197px"><div id="msgcd"></div></td>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 113px">Product Category</td>
	  	    <td style="width: 10px">:</td>
	  	    <td style="width: 197px">
			<input class="inputtxt" readonly="readonly" name="procat" id ="procatid" type="text" style="width: 74px" value="<?php echo $prodcat; ?>"></td>
			<td style="width: 8px"></td>
			<td style="width: 71px">&nbsp;</td>
			<td style="width: 7px">&nbsp;</td>
			<td>
			&nbsp;</td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 113px"></td>
	  	   <td style="width: 10px"></td>
	  	   <td style="width: 197px"></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 113px">Size</td>
	  	   <td style="width: 10px">:</td>
	  	   <td style="width: 197px">
		   <input class="inputtxt" readonly="readonly" name="prosiz" id ="prosizid" type="text" style="width: 148px" value="<?php echo $prodsiz; ?>">
		   </td>
		   <td style="width: 8px"></td>
		   <td>Colour</td>
		   <td style="width: 7px">:</td>
		   <td>
		   <input class="inputtxt" readonly="readonly" name="procol" id ="procol" type="text" style="width: 71px" value="<?php echo $prodcol; ?>">
		   <label id="Label1"><?php echo $prodcolde;?></label>
		   </td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 113px"></td>
	  	   <td style="width: 10px"></td>
	  	   <td style="width: 197px"></td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td style="width: 113px">Description</td>
	  	   <td style="width: 10px">:</td>
	  	   <td style="width: 197px">
   			<input class="inputtxt" readonly="readonly" name="procddesc" id ="procddescid" type="text" style="width: 363px;" value="<?php echo $proddesc; ?>"></td>
	  	   <td style="width: 8px"></td>
           <td>UOM</td>
           <td style="width: 7px">:</td>
           <td>
		   <input class="inputtxt" readonly="readonly" name="prouom" id ="procol0" type="text" style="width: 71px" value="<?php echo $produom; ?>"></td>
	  	  </tr>
	  	   <tr>
	  	    <td></td>
	  	  </tr>
	  	   <tr>
	  	    <td style="height: 14px"></td>
	  	    <td style="width: 113px; height: 14px;">Remark</td>
            <td style="height: 14px; width: 10px;">:</td>
            <td style="width: 197px; height: 14px;">
		   <input class="inputtxt" readonly="readonly" name="procdrmk" id ="procdrmkid" type="text" style="width: 361px;" value="<?php echo $prodrmk; ?>"></td>
		   <td style="width: 8px"></td>
       <td>Ex. Factory Price</td>
		   <td style="width: 7px">:</td>
		   <td>
		   <input class="inputtxt" readonly="readonly" name="procol" id ="procol" type="text" style="width: 71px" value="<?php echo $expri; ?>">
		   </td>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 113px">Create By</td>
	  	   <td style="width: 10px">:</td>
	  	   <td>
		   <input class="inputtxt" readonly="readonly" name="procdcreby" id ="procdcrebyid" type="text" style="width: 172px;" value="<?php echo $prodcreby; ?>"></td> 
	  	   <td style="width: 8px"></td>
	  	   <td>Create On</td>
	  	   <td>:</td>
	  	   <td>
	  	   <input class="inputtxt" readonly="readonly" name="procdcreon" id ="procdcreonid" type="text" style="width: 172px;" value="<?php echo $prodcreon; ?>"> 
	  	   </td>  			
	  	  </tr>
    	  <tr>
	  	    <td></td>
	  	    <td style="width: 113px">&nbsp;</td>
	  	    <td style="width: 10px"></td>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 113px">Modified By</td>
	  	    <td style="width: 10px">:</td>
	  	    <td> 
		    <input class="inputtxt" readonly="readonly" name="procdmodby" id ="procdcrebyid0" type="text" style="width: 172px;" value="<?php echo $prodmodby; ?>">
		    </td>
		    <td></td>
		    <td>Modified On</td>
		    <td>:</td>
		    <td>
	  	   <input class="inputtxt" readonly="readonly" name="procdmodon" id ="procdcreonid0" type="text" style="width: 172px;" value="<?php echo $prodmodon; ?>"></td>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	  </tr>
	  	</table>
	    <table>
  	 	<tr>
		<td align="center" style="width: 853px">
	  	<input type=submit name = "Submit" value="Copy" class="butsub" style="width: 60px; height: 32px" >
	  	<input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px"></td>
       </td>
        </tr>
	  	</table>
	   </form>	
	   </fieldset>
	  </div> 
</body>
</html>
