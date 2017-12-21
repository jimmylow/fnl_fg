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
   
 
	 if ($_POST['Submit'] == "Cancel") {
     	if(!empty($_POST['salorno']) && is_array($_POST['salorno'])) 
     	{
           foreach($_POST['salorno'] as $key) {
             $defarr = explode(",", $key);
             print_r($defarr);
             $var_sale = $defarr[0];
             $var_cust = $defarr[2];
                        
		     $vartoday = date("Y-m-d H:i:s");
			 $sql  = "Update salesentry Set stat = 'C', modified_by = '$var_loginid', modified_on = '$vartoday' ";
             $sql .=	" Where sordno ='".$var_sale."' And scustcd='".$var_cust."'";
             //echo $sql;
             mysql_query($sql) or die(mysql_error()." 1");		 	 
		   }
		   $backloc = "../sales/m_sales_mas.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
        
   if ($_POST['Submit'] == "Active") {
     	if(!empty($_POST['salorno']) && is_array($_POST['salorno'])) 
     	{
           foreach($_POST['salorno'] as $key) {
             $defarr = explode(",", $key);
             $var_sale = $defarr[0];
             $var_cust = $defarr[2];
                        
		     $vartoday = date("Y-m-d H:i:s");
			 $sql  = "Update salesentry Set stat = 'A', modified_by = '$var_loginid', modified_on = '$vartoday' ";
             $sql .=	" Where sordno ='".$var_sale."' And scustcd='".$var_cust."'";
             
             mysql_query($sql) or die(mysql_error()." 1");		 	 
		   }
		   $backloc = "../sales/m_sales_mas.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
    
    if ($_GET['p'] == "Print") {
        $pdordno = $_GET['sorno'];
        $var_menucode = $_GET['menucd'];
                
        $fname = "salesform.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&ponum=".$pdordno."&menuc=".$var_menucode."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        
        $backloc = "../sales/m_sales_mas.php?menucd=".$var_menucode;
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
    					{ "sType": "uk_date" },
    					{ "sType": "uk_date" },
    					null,
    					null,
    					null,
    					null,
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
				     { type: "text" },
             		 null,
             		 null,
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
  <!--<?php include("../sidebarm.php"); ?>--> 

<body>
  <div class="contentc">


	<fieldset name="Group1" style=" width: 1200px;" class="style2">
	 <legend class="title">SALES ORDER LISTING</legend>
	  <br>
	 
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 1131px; height: 38px;" align="left">
           <?php
                $locatr = "sale_mas.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
  				
    	  	   $msgdel = "Are You Sure Delete Selected Sales Entry?";
    	  	   if ($var_accdel == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Cancel" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type=submit name = "Submit" value="Cancel" class="butsub" style="width: 60px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
  				}
  				
			   $msgdel = "Are You Sure Active Selected Sales Entry?";
    	  	   if ($var_accdel == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Cancel" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type=submit name = "Submit" value="Active" class="butsub" style="width: 60px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
  				}

    	      ?></td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" style="width: 98%">
         <thead>
           <tr>
          <th></th>
          <th style="width: 234px">Order No</th>
          <th style="width: 129px">Order Date</th>
          <th style="width: 128px">Customer</th>
          <th style="width: 124px">Cust. PO</th>
          <th>Status</th>
          <th></th>
          <th></th>
          <th></th>
		  <th></th>
		  <th></th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 129px">Order No.</th>
          <th class="tabheader" style="width: 129px">Order Date</th>
          <th class="tabheader" style="width: 234px">Customer</th>
          <th class="tabheader" style="width: 124px">Cust. PO</th>
          <th class="tabheader" style="width: 80px">Status</th>
          <th class="tabheader" style="width: 50px">Shipped</th>          
          <th class="tabheader" style="width: 12px">Detail</th>
          <th class="tabheader" style="width: 12px">Print</th>
          <th class="tabheader" style="width: 12px">Update</th>
		  <th class="tabheader" style="width: 12px">Cancel</th>
		  <th class="tabheader" style="width: 12px">Active</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT sordno, sorddte, scustcd, scustpo, stat, shipflg ";
		    $sql .= " FROM salesentry";
    		$sql .= " ORDER BY sordno desc";  
			$rs_result = mysql_query($sql); 
	
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
				$salorno = htmlentities($rowq['sordno']);
				$orddte = date('d-m-Y', strtotime($rowq['sorddte']));
				
			/*	$sql1 = "select app_stat from salesappr";
        		$sql1 .= " where sordno ='".$salorno."' ";
        		$sql1 .= " and sbuycd ='".$rowq['sbuycd']."' ";
        		$sql_result1 = mysql_query($sql1) or die("error query sales order status :".mysql_error());
        		$row2 = mysql_fetch_array($sql_result1);
				$sstat = $row2[0];   */
				
				$urlpop = 'upd_saleentry.php';
				$urlvie = 'vm_saleentry.php';
				//$urlvie = 'ship_mas.php';
        
        $sqlcust = "select name from customer_master";
        $sqlcust .= " where custno = '".$rowq['scustcd']."'";
        
        $tmpcust = mysql_query($sqlcust) or die ("Cant get custname : ".mysql_error());
        
        if (mysql_numrows($tmpcust) >0) {
          $rstcust = mysql_fetch_object($tmpcust);
          $var_cname = $rstcust->name;
        } else { $var_cname = $rowq['scustcd']; }        
        
				echo '<tr>';
            	echo '<td>'.$numi.'</td>';
           		echo '<td>'.$salorno.'</td>';
            	echo '<td>'.$orddte.'</td>';
            	echo '<td>'.$var_cname.'</td>';
            	echo '<td>'.$rowq['scustpo'].'</td>';
            	echo '<td>'.$rowq['stat'].'</td>';
              echo '<td>'.$rowq['shipflg'].'</td>';
            
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?sorno='.$salorno.'&custcd='.$rowq['scustcd'].'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	}
            	
            	echo '<td align="center"><a href="m_sales_mas.php?p=Print&sorno='.$salorno.'&menucd='.$var_menucode.'" title="Print Sales Order"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate Sales Order" /></a></td>'; 

	            if ($var_accupd == 0){
		            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
	            }else{
	            	if ($rowq['shipflg'] == "Y"){
	            		echo '<td align="center"><a href="#" title="This Sales Order\'s Shipment Is Created; Edit Is Not Allow">[EDIT]</a>';'</td>';
	            	}else{ 
		            	echo '<td align="center"><a href="'.$urlpop.'?sorno='.$salorno.'&custcd='.$rowq['scustcd'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
	            	}
	            }
	            if ($var_accdel == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
	            }else{
	              if ($rowq['shipflg'] == "Y"){
					echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
				  }else{	
	              	$values = implode(',', $rowq);	
	              	echo '<td align="center"><input type="checkbox" name="salorno[]" value="'.$values.'" />'.'</td>';
    	          }	
    	        }
           		
           		 if ($var_accdel == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
	            }else{
	              if ($rowq['shipflg'] == "Y"){
					echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
				  }else{	
	              	$values = implode(',', $rowq);	
	              	echo '<td align="center"><input type="checkbox" name="salorno[]" value="'.$values.'" />'.'</td>';
    	          }	
    	        }
           		
           		echo '</tr>';
            $numi = $numi + 1;
			}
		 ?>
		 </tbody>
		 </table>
		</form>
	   </fieldset>
	  </div>	
	  <div class="spacer"></div>
	
</body>

</html>
