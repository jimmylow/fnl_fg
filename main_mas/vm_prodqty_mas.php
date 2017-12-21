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
      $var_referer = $_GET['s'];
      //$var_prodcd = 'M001B-OC-36';
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_prodcd = $_POST['prodcd'];
         $var_menucode  = $_POST['menucd'];
         $var_referer = $_POST['referer'];
         if ($var_referer == "v") {
           $backloc = "../main_mas/vm_prod_mas.php?prodcd=".$var_prodcd."&menucd=".$var_menucode;         
         } else {
           $backloc = "../main_mas/upd_prod_mas.php?prodcd=".$var_prodcd."&menucd=".$var_menucode;
	       }
         
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
	
</script>
</head>

<body onload="document.InpSuppMas.prodcdid.focus();">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->

<?php

  
  //---- for opening qty : OP -----//
  $sql = " select sum(qtyin) as cnt from invthist";
  $sql .= " where reftype = 'OP'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  
  $tmp = mysql_query ($sql) or die ("Cant get 1 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_begbal = $rst->cnt;
      
      if ($var_begbal == "") { $var_begbal = 0; }
  } else { $var_begbal = 0; }  

  //---- for rec qty : RC -----//
  $sql = " select sum(qtyin) as cnt from invthist";
  $sql .= " where reftype = 'RC'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  
  $tmp = mysql_query ($sql) or die ("Cant get 1 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_rec = $rst->cnt;
      
      if ($var_rec == "") { $var_rec = 0; }
  } else { $var_rec = 0; }
  
  //---- for transfer in qty : TI -----//
  $sql = " select sum(qtyin) as cnt from invthist";
  $sql .= " where reftype = 'TI'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  $tmp = mysql_query ($sql) or die ("Cant get 2 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_trfin = $rst->cnt;
      
      if ($var_trfin == "") { $var_trfin = 0; }
  } else { $var_trfin = 0; } 
  
  //---- for transfer out qty : TO -----//
  $sql = " select sum(qtyout) as cnt from invthist";
  $sql .= " where reftype = 'TO'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  $tmp = mysql_query ($sql) or die ("Cant get 3 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_trfout = $rst->cnt;
      
      if ($var_trfout == "") { $var_trfout = 0; }
  } else { $var_trfout = 0; } 
  
  //---- for ret from : RN -----//
  $sql = " select sum(qtyin) as cnt from invthist";
  $sql .= " where reftype = 'RN'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  $tmp = mysql_query ($sql) or die ("Cant get 4 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_rtnfrm = $rst->cnt;
      
      if ($var_rtnfrm == "") { $var_rtnfrm = 0; }
  } else { $var_rtnfrm = 0; } 
  
     
  //---- for ship qty : SA -----//
  $sql = " select sum(qtyout) as cnt from invthist";
  $sql .= " where reftype = 'SA'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  $tmp = mysql_query ($sql) or die ("Cant get 5 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_ship = $rst->cnt;
      
      if ($var_ship == "") { $var_ship = 0; }
  } else { $var_ship = 0; }

  //---- for adj qty : AD -----//
  $sql = " select sum(qtyin - qtyout) as cnt from invthist";
  $sql .= " where reftype = 'AD'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  $tmp = mysql_query ($sql) or die ("Cant get 6 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_adj = $rst->cnt;
      
      if ($var_adj == "") { $var_adj = 0; }
  } else { $var_adj = 0; } 
  
  //---- for return to qty : RS -----//
  $sql = " select sum(qtyout) as cnt from invthist";
  $sql .= " where reftype = 'RS'";
  $sql .= " and prodcode = '".$var_prodcd."'";
  
  $tmp = mysql_query ($sql) or die ("Cant get 7 : ".mysql_error());
  
  if (mysql_numrows($tmp) > 0) {
      $rst = mysql_fetch_object($tmp);
      $var_rtnto = $rst->cnt;
      
      if ($var_rtnto == "") { $var_rtnto = 0; }
  } else { $var_rtnto = 0; }   
    

  $var_rtnall = $var_rtnfrm - $var_rtnto;
  $var_onhand =  $var_begbal + $var_rec + $var_trfin - $var_trfout + $var_rtnfrm - $var_ship + $var_adj - $var_rtnto;
  
  
?>  
  
        
  <div class="contentc">

	<!-- <fieldset name="Group1" style=" width: 760px;" class="style2"> -->
	 <legend class="title">ON HAND BALANCE - <?php echo $var_prodcd; php?></legend>
	  <br>
	  <fieldset name="Group1" style="width: 745px; height: 280px">
	  <form name="InpSuppMas" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px; width: 970px;">
	    <input name="menucd" type="hidden" value="<?php echo $var_menucode;?>">	
      <input name="referer" type="hidden" value="<?php echo $var_referer;?>">	   
     <table style="width: 745px;" >
	    <tr>
	      <td></td>
	      <td class="tdlabel" colspan="3">Product Code :
		  <input class="textnoentry" name="prodcd" id ="prodcdid" readonly="readonly" type="text" maxlength="50" style="width: 396px" value="<?php echo $var_prodcd; ?>">
		  </td>
      </tr>     
         <tr >
          <td></td>
          <td >
      <table class="general-table">
      <tbody>
      <tr>
      <td>On Hand</td>
      <td colspan="7"></td>
      </tr>
      <tr>
      <td><input class="textnoentry" readonly  type="text" style="width: 80px" value="<?php echo $var_onhand; ?>"></td>
      <td colspan="7"></td>
      </tr>      
      <tr>
      <td >Begbal</td>
      <td></td>
      <td>Rec Qty</td>
      <td></td>
      <td>Trans In</td>
      <td></td>
      <td>Return</td>
      <td></td>      
      </tr>
      <tr>
      <td><input class="textnoentry" readonly  type="text" style="width: 80px" value="<?php echo $var_begbal; ?>"></td>
      <td></td>
      <td><input class="textnoentry" readonly  type="text" style="width: 80px" value="<?php echo $var_rec; ?>"></td>
      <td></td>
      <td><input class="textnoentry" readonly  type="text" style="width: 80px" value="<?php echo $var_trfin; ?>"></td>
      <td></td>
      <td><input class="textnoentry" readonly  type="text" style="width: 80px" value="<?php echo $var_rtnall; ?>"></td>
      <td></td>      
      </tr>
      <tr>
      <td>Ship Qty</td>
      <td></td>
      <td>Adj Qty</td>
      <td></td>
      <td>Trans Out</td>
      <td></td>
      <!-- <td>Ret. To</td> -->
      <td></td>
      <td></td>      
      </tr>
      <tr>
      <td><input class="textnoentry" readonly  type="text" style="width: 80px" value="<?php echo $var_ship; ?>"></td>
      <td></td>
      <td><input class="textnoentry" readonly  type="text" style="width: 80px" value="<?php echo $var_adj; ?>"></td>
      <td></td>
      <td><input class="textnoentry" readonly  type="text" style="width: 80px" value="<?php echo $var_trfout; ?>"></td>
      <td></td>
      <!-- <td><input class="textnoentry" readonly  type="text" style="width: 80px" value="<?php //echo $var_rtnto; ?>"></td> -->
      <td></td>
      <td></td>      
      </tr>                                                           
      </tbody>      
      </table>                
          </td>
         </tr>                          
		  </table>
		  </fieldset>
      
       <br /><br />
   <fieldset>   
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
		 <tbody>
		 <?php 
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
      }
		 ?>
		 </tbody>
		 </table> 
     </fieldset>        
	  	 <table>
	  	 <tr><td style="width: 700px"></td></tr>
	  	 <tr>
	  	   <td align="center" style="width: 700px">
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	 </table>
      </form>	
	   <!-- </fieldset> -->
	</div>

</body>

</html>
