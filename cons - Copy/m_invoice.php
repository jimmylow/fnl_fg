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
             //print_r($defarr);
             $var_sale = $defarr[0];
             $var_cust = $defarr[2];
                        
		         $vartoday = date("Y-m-d H:i:s");
			       $sql  = "Update cinvoicemas Set stat = 'C', modified_by = '$var_loginid', modified_on = '$vartoday' ";
             $sql .=	" Where invno ='".$var_sale."' And custcd='".$var_cust."'";
             //echo $sql;
             mysql_query($sql) or die(mysql_error()." 1");		 	 
		   }
		   $backloc = "../cons/m_invoice.php?stat=1&menucd=".$var_menucode;
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
			       $sql  = "Update cinvoicemas Set stat = 'A', modified_by = '$var_loginid', modified_on = '$vartoday' ";
             $sql .=	" Where invno ='".$var_sale."' And custcd='".$var_cust."'";
             
             mysql_query($sql) or die(mysql_error()." 1");		 	 
		   }
		   $backloc = "../cons/m_invoice.php?stat=1&menucd=".$var_menucode;
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
    					{ "sType": "uk_date" },
    					{ "sType": "uk_date" },
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


	<fieldset name="Group1" style=" width: 900px;" class="style2">
	 <legend class="title">INVOICE LISTING</legend>
	  <br>
	 
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 1131px; height: 38px;" align="left">
           <?php
                $locatr = "invoice.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
  				
    	  	   $msgdel = "Are You Sure Delete Selected Invoice?";
    	  	   if ($var_accdel == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Cancel" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type=submit name = "Submit" value="Cancel" class="butsub" style="width: 60px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
  				}
  				
			   $msgdel = "Are You Sure Active Selected Invoice?";
    	  	   if ($var_accdel == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Cancel" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type=submit name = "Submit" value="Active" class="butsub" style="width: 60px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
  				}

    	      ?></td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
           <tr>
          <th></th>
          <th style="width: 234px">Invoice No</th>
          <th style="width: 129px">Counter</th>
          <th style="width: 128px">MM/YY</th>
          <th style="width: 124px">Period</th>
          <th>Status</th>
          <!-- <th></th> -->
          <th></th>
		  <th></th>
		  <th></th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 129px">Invoice No.</th>
          <th class="tabheader" style="width: 234px">Counter</th>
          <th class="tabheader" style="width: 128px">MM/YY</th>
          <th class="tabheader" style="width: 124px">Period</th>
          <th class="tabheader" style="width: 124px">Status</th>
          <th class="tabheader" style="width: 12px">Detail</th>
          <!-- <th class="tabheader" style="width: 12px">Update</th> -->
		  <th class="tabheader" style="width: 12px">Cancel</th>
		  <th class="tabheader" style="width: 12px">Active</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT invno, invdte, custcd, mthyr, period, stat ";
		    $sql .= " FROM cinvoicemas";
    		$sql .= " ORDER BY invno desc";  
			$rs_result = mysql_query($sql); 
	
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
				$salorno = htmlentities($rowq['invno']);
				//$orddte = date('d-m-Y', strtotime($rowq['sorddte']));
				
				$urlpop = 'upd_invoice.php';
				$urlvie = 'vm_invoice.php';
				//$urlvie = 'ship_mas.php';
        
        $sqlcust = "select name from customer_master";
        $sqlcust .= " where custno = '".$rowq['custcd']."'";
        
        $tmpcust = mysql_query($sqlcust) or die ("Cant get custname : ".mysql_error());
        
        if (mysql_numrows($tmpcust) >0) {
          $rstcust = mysql_fetch_object($tmpcust);
          $var_cname = $rstcust->name;
        } else { $var_cname = $rowq['custcd']; }
        
				echo '<tr>';
            	echo '<td>'.$numi.'</td>';
           		echo '<td>'.$salorno.'</td>';
            	echo '<td>'.$var_cname.'</td>';
            	echo '<td>'.$rowq['mthyr'].'</td>';
            	echo '<td>'.$rowq['period'].'</td>';
            	echo '<td>'.$rowq['stat'].'</td>';
            
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?sorno='.$salorno.'&custcd='.$rowq['custcd'].'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	}
	           /* if ($var_accupd == 0){
		            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
	            }else{
	            	if ($rowq['stat'] == "C"){
	            		echo '<td align="center"><a href="#" title="This Debit Note is Cancelled; Edit Is Not Allow">[EDIT]</a>';'</td>';
	            	}else{ 
		            	echo '<td align="center"><a href="'.$urlpop.'?sorno='.$salorno.'&custcd='.$rowq['custcd'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
	            	}
	            } */
	            if ($var_accdel == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
	            }else{
	            //  if ($rowq['stat'] == "A"){
					//echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
				 // }else{	
	              	$values = implode(',', $rowq);	
	              	echo '<td align="center"><input type="checkbox" name="salorno[]" value="'.$values.'" />'.'</td>';
    	         // }	
    	        }
           		
           		 if ($var_accdel == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
	            }else{
	              //if ($rowq['stat'] == "C"){
					//echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
				  //}else{	
	              	$values = implode(',', $rowq);	
	              	echo '<td align="center"><input type="checkbox" name="salorno[]" value="'.$values.'" />'.'</td>';
    	        //  }	
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
