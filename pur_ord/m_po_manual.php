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
   
   	 if ($_GET['p'] == "Print"){
   	 	$pdponum = htmlentities($_GET['po']);
               
        
        $fname = "po_mas2.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&ponum=".$pdponum."&menuc=".$var_menucode."&dbsel=".$varrpturldb."&__pageFooterFloatFlag=False";
        $dest .= urlencode(realpath($fname));

        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../pur_ord/m_po.php?menucd=".$var_menucode;
       	echo "<script>";
       	echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 
      	
		
   	 }	
 
	 if ($_POST['Submit'] == "Delete") {
     	if(!empty($_POST['procd']) && is_array($_POST['procd'])) 
     	{
           foreach($_POST['procd'] as $key) {
             $defarr = explode(",", $key);
             
             $var_po_no = $defarr[0];
                        
		     $sql = "UPDATE po_master_manual "; 
             $sql .= "SET active_flag = 'CANCEL'";
		     $sql .= "WHERE po_no ='".$var_po_no."' ";  
         
		 	   mysql_query($sql) or die ("Delete master error ".mysql_error()); 
		 	 
		 	   $sql = "DELETE FROM po_trans_manual "; 
		     $sql .= "WHERE po_no ='".$var_po_no."' ";  
		 	   mysql_query($sql) or die ("Delete details error ".mysql_error());   

		   }
		   $backloc = "../pur_ord/m_po.php?stat=1&menucd=".$var_menucode;
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
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";
@import "../css/demo_table.css";
thead th input { width: 90% }

.style2 {
	margin-right: 0px;
}
</style>

<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript"> 
$(document).ready(function() {
	$('#example').dataTable( {
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
		"bStateSave": true,
		"bFilter": true,
		"sPaginationType": "full_numbers",
		"bAutoWidth":false,
		"aoColumns": [
    null,
    null,
    null,
    { "sType": "uk_date" },
    { "sType": "uk_date" },
    null,
    null,
    null,
    null
    ]

	})
	
	.columnFilter({sPlaceHolder: "head:after",

		aoColumns: [ 
					 null,	
					 { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     null,
				     null,
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
 <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->
<body>
   
  <div class="contentc">


	<fieldset name="Group1" style=" width: 1200px;" class="style2">
	 <legend class="title">PURCHASE ORDER (MANUAL) LISTING</legend>
	  <br>
	 
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 1131px; height: 38px;" align="left">
           <?php
                $locatr = "po_manual.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
  			/*	$locatr = "copy_pro_cost.php?menucd=".$var_menucode;
  				if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Copy" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Copy" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}   */
    	  	   $msgdel = "Confirm to Delete the Selected Purchase Order?";
    	  	   include("../Setting/btndelete.php");
    	      ?></td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
           <tr>
          <th></th>
          <th>Purchase Order</th>
          <th>Supplier</th>
          <th>PO. Date</th>
          <!-- <th>Order No</th>
          <th>Style No</th> -->
          <th>Delivery Date</th>
          <th></th>
          <th></th>
          <th></th>
		  <th></th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 139px">Purchase Order</th>
          <th class="tabheader" style="width: 283px">Supplier</th>
          <th class="tabheader" style="width: 89px">PO. Date</th>
          <!-- <th class="tabheader" style="width: 106px">Order No</th>
          <th class="tabheader" style="width: 118px">Style No</th> -->
          <th class="tabheader" style="width: 131px">Delivery Date</th>
          <th class="tabheader" style="width: 12px">Detail</th>
          <th class="tabheader" style="width: 12px">Print</th>
          <th class="tabheader" style="width: 12px">Update</th>
		  <th class="tabheader" style="width: 12px">Delete</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT po_no, supplier, po_date, del_date ";
		    $sql .= " FROM po_master_manual";
        $sql .= " WHERE active_flag = 'ACTIVE'";
    		$sql .= " ORDER BY po_no";  
			$rs_result = mysql_query($sql); 
	
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
			/*	$sql = "select prod_desc from pro_cd_master ";
        		$sql .= " where prod_code ='".$rowq['prod_code']."'";
        		$sql_result = mysql_query($sql);
        		$row2 = mysql_fetch_array($sql_result);
			
				$prodcd = htmlentities($rowq['prod_code']);
				$revno = htmlentities($rowq['revno']);
				$showdte = date('Y-m-d', strtotime($rowq['modified_on']));
				$docdte = date('Y-m-d', strtotime($rowq['docdate']));  */
        
        $sqlsupp = "select name from supplier_master ";
        $sqlsupp .= " where suppno = '".$rowq['supplier']."'";
        
        $tmpsupp = mysql_query ($sqlsupp) or die ("Cant get supplier info : ".mysql_error());
        
        if(mysql_numrows($tmpsupp) > 0) {
        
          $rstsupp = mysql_fetch_object($tmpsupp);
          $var_suppdesc = $rstsupp->name;
          
        }  else {  $var_suppdesc = "";  }
        
				$urlpop = 'upd_po_manual.php';
				$urlvie = 'vm_po_manual.php';
				echo '<tr bgcolor='.$defaultcolor.'>';
            	echo '<td>'.$numi.'</td>';
           		echo '<td>'.$rowq['po_no'].'</td>';
            	echo '<td>'.$var_suppdesc.'</td>';
            	echo '<td>'.date('d-m-Y', strtotime($rowq['po_date'])).'</td>';
            	//echo '<td>'.$rowq['order_no'].'</td>';
            	//echo '<td>'.$rowq['style_no'].'</td>';
            	echo '<td>'.date('d-m-Y', strtotime($rowq['del_date'])).'</td>';
            
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?po='.$rowq['po_no'].'&menucd='.$var_menucode.'" title="View Detail P/O">[VIEW]</a>';'</td>';
            	}
            	
            	echo '<td align="center"><a href="m_po.php?p=Print&po='.$rowq['po_no'].'&menucd='.$var_menucode.'" title="Print P/O"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate P/O" /></a></td>'; 

            	
	            if ($var_accupd == 0){
		            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
	            }else{
		            echo '<td align="center"><a href="'.$urlpop.'?po='.$rowq['po_no'].'&menucd='.$var_menucode.'" title="Update Detail P/O">[EDIT]</a>';'</td>';
	            }
	            if ($var_accdel == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
	            }else{
	              $values = implode(',', $rowq);	
	              echo '<td align="center"><input type="checkbox" name="procd[]" value="'.$values.'" />'.'</td>';
    	        }
           		 echo '</tr>';
            $numi = $numi + 1;
			}
      
      mysql_close ($db_link);
      
		 ?>
		 </tbody>
		 </table>
		</form>
	   </fieldset>
	  </div>	
	  <div class="spacer"></div>
	
</body>

</html>
