<?php

	include("../Setting/Configifx.php");
	//include("../Setting/Connection.php");
			 		$var_server = '192.168.0.142:9909';
		        $var_userid = 'root';
		        $var_password = 'admin9002';
		        $var_db_name='nl_db'; 
	     
		 		$db_link2  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
	  	 		mysql_select_db("$var_db_name")or die("cannot select DB ".$var_db_name.mysql_error());
	
		 		mysql_query("SET NAMES 'utf8'", $db_link2)or die(mysql_error()); 	//this will take effect both retrieve, update or insert data of utf-8 
		 		//---- END connect to nl_db database -----//

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
     	if(!empty($_POST['procd']) && is_array($_POST['procd'])) 
     	{
           foreach($_POST['procd'] as $key) {
             $defarr = explode(",", $key);
             
             $var_proccd = $defarr[0];
                         
		     $sql = "DELETE FROM sew_entry "; 
		     $sql .= "WHERE ticketno ='".$var_proccd."'";  
		     mysql_query($sql) or die("Error deleting in Sew Entry:".mysql_error(). ' Failed SQL is -->'. $sql);
		 	 
		 	 $sql = "DELETE FROM sew_barcode "; 
		     $sql .= "WHERE ticketno ='".$var_proccd."'";  
		 	 mysql_query($sql) or die("Error deleting in Sew Barcode:".mysql_error(). ' Failed SQL is -->'. $sql);
		 	 
		 	 $sql = "DELETE FROM wip_tran "; 
		     $sql .= "WHERE rm_receive_id ='".$var_proccd."' AND tran_type = 'REC'";  
		 	 mysql_query($sql) or die("Error deleting in WIP tran :".mysql_error(). ' Failed SQL is -->'. $sql);
		 	

		   }
		   $backloc = "../prod/m_sew_entry.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
    //print_r($_GET);
    if ($_GET['p'] == "Print"){
        $prcode = $_GET['prod_code'];
        $ticketno = $_GET['tic'];
        
        //$fname = "prcost_rpt.rptdesign&__title=myReport"; 
        //$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&prc=".$prcode."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $fname = "sew_barentry.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&tic=".$ticketno."&menuc=".$var_menucode."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../prod/m_sew_entry.php?menucd=".$var_menucode;
       	echo "<script>";
       	//echo 'location.replace("'.$backloc.'")';
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
    					null,
    					null,
    					null,
    					null,
    					null,
    					{ "sType": "uk_date" },
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
				     { type: "text" },
				     { type: "text" },
				     { type: "text" }
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
  
  <div class ="contentc">


	<fieldset name="Group1" style=" width: 1200px;" class="style2">
	 <legend class="title">NYOK LAN SEWING TICKET ENTRY LISTING</legend>
&nbsp;<form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table cellpadding="0" cellspacing="0" id="example" class="display" style="width: 99%">
         <thead>
           <tr>
          <th></th>
          <th style="width: 9px">Ticket No</th>
          <th>Prod Date</th>
          <th>Product Code</th>
          <th style="width: 64px">Pro. Qty</th>
          <th style="width: 64px">Buyer</th>
          <th style="width: 144px">Delivery Date</th>
          <th style="width: 64px">Batch No.</th>
          <th style="width: 93px">QC Date</th>
          <th style="width: 107px">Buyer Order</th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 9px">Ticket No</th>
          <th class="tabheader" style="width: 106px">Prod Date</th>
          <th class="tabheader" style="width: 106px">Product Code</th>
          <th class="tabheader" style="width: 64px">Prod. Qty</th>
          <th class="tabheader" style="width: 64px">Buyer</th>
          <th class="tabheader" style="width: 144px">Delivery Date</th>
          <th class="tabheader" style="width: 64px">Batch No.</th>
          <th class="tabheader" style="width: 93px">QC Date</th>
          <th class="tabheader" style="width: 107px">Buyer Order</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		 		$var_server = '192.168.0.142:9909';
		        $var_userid = 'root';
		        $var_password = 'admin9002';
		        $var_db_name='nl_db'; 
	     
		 		$db_link2  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
	  	 		mysql_select_db("$var_db_name")or die("cannot select DB ".$var_db_name.mysql_error());
	
		 		mysql_query("SET NAMES 'utf8'", $db_link2)or die(mysql_error()); 	//this will take effect both retrieve, update or insert data of utf-8 
		 		//---- END connect to nl_db database -----//

		    $sql = "SELECT ticketno, productiondate, productcode, productqty, buyer, deliverydate, batchno, qcdate, buyerorder, modified_by, modified_on ";
		    $sql .= " FROM sew_entry";
    		$sql .= " ORDER BY ticketno";  
			$rs_result = mysql_query($sql); 
			//echo $sql;
	
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
				$sql = "select prod_desc from pro_cd_master ";
        		$sql .= " where prod_code ='".$rowq['prod_code']."'";
        		$sql_result = mysql_query($sql);
        		$row2 = mysql_fetch_array($sql_result);
			
				$ticketno = htmlentities($rowq['ticketno']);
				$productcode= htmlentities($rowq['productcode']);
				$productqty= htmlentities($rowq['productqty']);
				$buyer= htmlentities($rowq['buyer']);
				$deliverydate= date('d-m-Y', strtotime($rowq['deliverydate']));		
				$batchno= htmlentities($rowq['batchno']);
				$buyerorder= htmlentities($rowq['buyerorder']);
				$productiondate = date('d-m-Y', strtotime($rowq['productiondate']));
				$qcdate = date('d-m-Y', strtotime($rowq['qcdate']));
				if ($qcdate <= '01-01-1970')
				{
					$qcdate = '';
				}
				//$docdte = date('d-m-Y', strtotime($rowq['docdate']));
				$urlpop = 'upd_sew_entry.php';
				$urlvie = 'vm_sew_entry.php';
				echo '<tr bgcolor='.$defaultcolor.'>';
            	echo '<td>'.$numi.'</td>';
           		//echo '<td align="left">'.$prodcd.'</td>';
            	echo '<td>'.$ticketno.'</td>';
            	echo '<td>'.$productiondate.'</td>';    
            	echo '<td>'.$productcode.'</td>';      
            	echo '<td>'.$productqty.'</td>'; 
            	echo '<td>'.$buyer.'</td>'; 
            	echo '<td>'.$deliverydate.'</td>'; 
            	echo '<td>'.$batchno.'</td>'; 
            	echo '<td>'.$qcdate.'</td>'; 
            	echo '<td>'.$buyerorder.'</td>'; 
            
            	//if ($var_accvie == 0){
            	//	echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	//}else{
            	//	echo '<td align="center"><a href="'.$urlvie.'?ticketno='.$ticketno.'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	//}
            	
            	//echo '<td align="center"><a href="m_sew_entry.php?p=Print&tic='.$ticketno.'&menucd='.$var_menucode.'" title="Print Sewing Ticket"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Print Sewing Ticket" /></a></td>'; 
if ($varlogin='supera')
{
$cnt =  0; 
$cnt2= 0;
}
/*
	            if ($var_accupd == 0 or $cnt <> 0 or $cnt2 <> 0){
		            echo '<td align="center"><a href="#" title="You Are Not Authorice To Update Product Costing/Ticket in Workdone/QC">[EDIT]</a>';'</td>';
	            }else{
	            	echo '<td align="center"><a href="'.$urlpop.'?ticketno='.$ticketno.'&menucd='.$var_menucode.'" title="'.$apstat.'">[EDIT]</a>';'</td>';
	            }
	            if ($var_accdel == 0 or $cnt <> 0 or $cnt2 <> 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" title="This Ticket# Already In Work Done/QC. Not Allowed To Delete" value="'.$values.'" />'.'</td>';
	            }else{
	              $values = implode(',', $rowq);	
	              echo '<td align="center"><input type="checkbox" name="procd[]" value="'.$values.'" />'.'</td>';
    	        }
           		 echo '</tr>';
           		 */
            $numi = $numi + 1;
			}

		 ?>
		 </tbody>
		 </table>
		 <br>
		</form>
	   </fieldset>
	  </div>	
	  <div class="spacer"></div>
	
</body>

</html>
