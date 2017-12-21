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
   
 
	 if ($_POST['Submit'] == "Delete") {
     	if(!empty($_POST['shipno']) && is_array($_POST['shipno'])) 
     	{
           foreach($_POST['shipno'] as $key) {
             $defarr = explode(",", $key);
             $var_sale = $defarr[0];
             $var_cust = $defarr[2];
             
             mysql_query("update `salesentry` set `shipflg` = 'N'
                       where `sordno` = '$var_sale'", $db_link) 
                      or die("Cant Update Sales Order No ".mysql_error()); 
                      
             $sql = " delete from salesshipdet ";
             $sql .= " where shipno = '".$var_sale."'";
             
             mysql_query($sql) or die ("Delete shipdet fail : ".mysql_error());
                                     
                          
             $sql = " delete from invthist ";
             $sql .= " where refid = '".$var_sale."'";
             
             mysql_query($sql) or die ("Delete hist fail : ".mysql_error());
             
		         $vartoday = date("Y-m-d H:i:s");
			       $sql  = "Update salesshipmas Set stat = 'C', modified_by = '$var_loginid', modified_on = '$vartoday' ";
             $sql .=	" Where shipno ='".$var_sale."' And scustcd='".$var_cust."'";
             
             mysql_query($sql) or die(mysql_error()." 1");
             
             		 	 
		   }
		   $backloc = "../sales/m_ship_mas.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }

	 if ($_POST['Submit'] == "Post") {
     	if(!empty($_POST['shipno']) && is_array($_POST['shipno'])) 
     	{
           foreach($_POST['shipno'] as $key) {
             $defarr = explode(",", $key);
             $vmordno = $defarr[0];
             //$var_cust = $defarr[2];
                        
        $vartoday = date("Y-m-d H:i:s"); 
        
        $sql = "select shipdte from salesshipmas";
				$sql .= "  Where shipno ='$vmordno'";
				$tmprst = mysql_query($sql) or die ("Cant query master : ".mysql_error());        

        $row = mysql_fetch_array($tmprst);
        $shipdte = date('Y-m-d', strtotime($row['shipdte']));                
        
				$sql =  "select * from salesshipdet";
				$sql .= "  Where shipno ='$vmordno'";
        $sql .= " order by sproseq";        
				
				$tmprst = mysql_query($sql) or die ("Cant query details : ".mysql_error());        

        if(mysql_numrows($tmprst) > 0) {

          while ($row = mysql_fetch_array($tmprst)) {
              $procd = $row['sprocd'];
              $proqty = $row['shipqty'];

              $sql2 = " select uom_pack from prod_uommas";
              $sql2 .= " where uom_code = '".$row['sprouom']."'";

              $result = mysql_query($sql2) or die ("Error uom : ".mysql_error());
          
              if(mysql_numrows($result) > 0) {
                $data = mysql_fetch_object($result);
                $var_uqty = $data->uom_pack;
                if ($var_uqty == "") { $var_uqty = 1; }         
              }  else { $var_uqty = 1; }  
             
               $var_totpcs = $proqty * $var_uqty; 
              
							$sql = "INSERT INTO invthist values 
						    		('SA', '$vmordno', '$shipdte', '$vartoday', '$procd', '0','$var_totpcs')";
                    
							mysql_query($sql) or die ("Cant insert hist : ".mysql_error());
            }  
         }
         
        $sql = "update salesshipmas";
        $sql .= " set posted = 'Y'";
				$sql .= "  Where shipno ='$vmordno' and stat='A'";
				$tmprst = mysql_query($sql) or die ("Cant update master : ".mysql_error());          	

             		 	 
		   }
		   $backloc = "../sales/m_ship_mas.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
    
    /*
   if ($_POST['Submit'] == "Active") {
     	if(!empty($_POST['shipno']) && is_array($_POST['shipno'])) 
     	{
           foreach($_POST['shipno'] as $key) {
             $defarr = explode(",", $key);
             $var_sale = $defarr[0];
             $var_cust = $defarr[2];
                        
		     $vartoday = date("Y-m-d H:i:s");
			 $sql  = "Update salesshipmas Set stat = 'A', modified_by = '$var_loginid', modified_on = '$vartoday' ";
             $sql .=	" Where shipno ='".$var_sale."' And scustcd='".$var_cust."'";
             
             mysql_query($sql) or die(mysql_error()." 1");		 	 
		   }
		   $backloc = "../sales/m_ship_mas.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
    */
    
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
	 <legend class="title">SHIPPING LISTING</legend>
	  <br>
	 
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 1131px; height: 38px;" align="left">
           <?php
                $locatr = "ship_mas.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
  				
    	  	   $msgdel = "Are You Sure Delete Selected Shipping Entry?";
    	  	   include("../Setting/btndelete.php"); 
             include("../Setting/btnpost.php");           
            /*
    	  	   $msgdel = "Are You Sure Delete Selected Shipping Entry?";
    	  	   if ($var_accdel == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Cancel" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type=submit name = "Submit" value="Cancel" class="butsub" style="width: 60px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
  				}
  				
			   $msgdel = "Are You Sure Active Selected Shipping Entry?";
    	  	   if ($var_accdel == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Cancel" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type=submit name = "Submit" value="Active" class="butsub" style="width: 60px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
  				}
          */
          
    	      ?></td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
           <tr>
          <th></th>
          <th style="width: 234px">Order No</th>
          <th style="width: 129px">Shipping Date</th>
          <th style="width: 128px">Customer</th>
          <th style="width: 124px">Posted</th>
          <th>Status</th>
          <th></th>
          <th></th>
		      <th></th>
		      <th></th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 129px">Order No.</th>
          <th class="tabheader" style="width: 129px">Shipping Date</th>
          <th class="tabheader" style="width: 128px">Customer</th>
          <th class="tabheader" style="width: 12px">Posted</th>
          <th class="tabheader" style="width: 12px">Status</th>
          <th class="tabheader" style="width: 12px">Detail</th>
          <th class="tabheader" style="width: 12px">Update</th>
          <th class="tabheader" style="width: 12px">Post</th>
		  <th class="tabheader" style="width: 12px">Delete</th>
		      <!-- <th class="tabheader" style="width: 12px">Active</th> -->
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT shipno, shipdte, scustcd, sprinted, stat, doflg, invflg, posted ";
		    $sql .= " FROM salesshipmas";
		    
    		$sql .= " ORDER BY shipno desc, stat";  
			$rs_result = mysql_query($sql); 
	//echo $sql;
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
				$shipno = htmlentities($rowq['shipno']);
				$shipdte = date('d-m-Y', strtotime($rowq['shipdte']));
				
			/*	$sql1 = "select app_stat from salesappr";
        		$sql1 .= " where sordno ='".$salorno."' ";
        		$sql1 .= " and sbuycd ='".$rowq['sbuycd']."' ";
        		$sql_result1 = mysql_query($sql1) or die("error query sales order status :".mysql_error());
        		$row2 = mysql_fetch_array($sql_result1);
				$sstat = $row2[0];   */
				
				$urlpop = 'upd_shipping.php';
				$urlvie = 'vm_shipping.php';
        
				echo '<tr>';
            	echo '<td>'.$numi.'</td>';
           		echo '<td>'.$shipno.'</td>';
            	echo '<td>'.$shipdte.'</td>';
            	echo '<td>'.$rowq['scustcd'].'</td>';
            	echo '<td>'.$rowq['posted'].'</td>';
            	echo '<td>'.$rowq['stat'].'</td>';
            
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?shipno='.$shipno.'&custcd='.$rowq['scustcd'].'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	}
	            if ($var_accupd == 0){
		            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
	            }else{
	            	if ($rowq['posted'] == "Y" || $rowq['stat'] == "C" ){
	            		echo '<td align="center"><a href="#" title="This Shipment Is Posted / Cancelled; Edit Is Not Allow">[EDIT]</a>';'</td>';
	                    echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
	            	}else{ 
		            	echo '<td align="center"><a href="'.$urlpop.'?shipno='.$shipno.'&custcd='.$rowq['scustcd'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
	              	$values = implode(',', $rowq);	
	              	echo '<td align="center"><input type="checkbox" name="shipno[]" value="'.$values.'" />'.'</td>';
	            	}
	            }
	            if ($var_accdel == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
	            }else{
	               //echo 'do flag'.$rowq['doflg'];
	               //echo 'inv flag'.$rowq['invflg'];
	               if ($rowq['doflg'] == "Y" || $rowq['invflg'] == "Y"){

                    $var_dono = "";  $var_inv = "";                    
                    if( $rowq['doflg'] == "Y") {
                       $sql2 = " select delordno from salesdo ";
                       $sql2 .= " where sordno = '".$shipno."'";
                       $sql2 .= " and stat = 'A'";
                    
                       $tmp2 = mysql_query($sql2) or die ("cant get ship : ".mysql_error());
                    
                       if(mysql_numrows($tmp2) >0) {
                          $rst = mysql_fetch_object($tmp2);
                          $var_dono = $rst->delordno;
                       }
                    }

                    if( $rowq['invflg'] == "Y") {
                       $sql2 = " select x.invno from invdet x, invmas y";
                       $sql2 .= " where sordno = '".$shipno."'";
                       $sql2 .= " and x.invno = y.invno and stat = 'A'";

                    
                       $tmp2 = mysql_query($sql2) or die ("cant get inv : ".mysql_error());
                    
                       if(mysql_numrows($tmp2) >0) {
                          $rst = mysql_fetch_object($tmp2);
                          $var_inv = $rst->invno;
                       }
                    }                    
                    
                    $var_message = "Already Issue : ";
                    if($var_dono <> "") { $var_message .= " DO -> ".$var_dono; }
                    if($var_inv <> "") { $var_message .= " Inv -> ".$var_inv; }
                   

				  	        echo '<td align="center"><a href="#" title = "'.$var_message.'"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</a></td>';
				         }else{	
	               	  $values = implode(',', $rowq);	
	               	 
	               	  if ($rowq['posted'] == "Y" ){
	               	   
		              	  echo '<td align="center"><input type="checkbox" DISABLED   name="shipno[]" value="'.$values.'" />'.'</td>';
		              }else{
		             
		                echo '<td align="center"><input type="checkbox" name="shipno[]" value="'.$values.'" />'.'</td>';
		              }	  		  
    	           }	
    	        }
           		
              /*
           		 if ($var_accdel == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
	            }else{
	             // if ($rowq['sprinted'] == "Y"){
					//echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
				  //}else{	
	              	$values = implode(',', $rowq);	
	              	echo '<td align="center"><input type="checkbox" name="shipno[]" value="'.$values.'" />'.'</td>';
    	      //    }	
    	        }
           		*/
              
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
