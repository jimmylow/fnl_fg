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
     	if(!empty($_POST['menun']) && is_array($_POST['menun'])) 
     	{
           foreach($_POST['menun'] as $key) {
             $defarr = explode(",", $key);
             
             $var_rptprog = $defarr[0];
             $sql = "Delete From arpthea_set Where menucode ='$var_rptprog'";  
			 mysql_query($sql) or die(mysql_error());

			 $sql = "Delete From arptrmk_set Where menucode ='$var_rptprog'";  
			 mysql_query($sql) or die(mysql_error());
 
		   }
		   $backloc = "../admin_set/m_rpthead_set.php?stat=1&menucd=".$var_menucode;
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
   
  <div class ="contentc">


	<fieldset name="Group1" style=" width: 1143px;" class="style2">
	 <legend class="title">PROGRAM REPORT ELEMENT</legend>
	  <br>
	 
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 1131px; height: 38px;" align="left">
           <?php
                $locatr = "rpthead_set.php?menucd=".$var_menucode;
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
    	  	   $msgdel = "Confirm to Delete the Selected Program Report Element?";
    	  	   include("../Setting/btndelete.php");
    	      ?></td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
           <tr>
          	<th></th>
          	<th style="width: 427px">Program Name</th>
          	<th style="width: 122px">Modified By</th>
          	<th style="width: 90px">Modified On</th>
          	<th></th>
          	<th></th>
		  	<th></th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 427px">Program Name</th>
          <th class="tabheader" style="width: 122px">Modified By</th>
          <th class="tabheader" style="width: 90px">Modified On</th>
          <th class="tabheader" style="width: 12px">Detail</th>
          <th class="tabheader" style="width: 12px">Update</th>
		  <th class="tabheader" style="width: 12px">Delete</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT menucode, lastlogin, lastupd";
		    $sql .= " FROM arpthea_set";
       		$sql .= " ORDER BY lastupd desc";  
			$rs_result = mysql_query($sql); 
	
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
				$sql = "select menu_name from menud ";
        		$sql .= " where menu_code ='".$rowq['menucode']."'";
        		$sql_result = mysql_query($sql);
        		$row2 = mysql_fetch_array($sql_result);
        		$menuname = $row2[0];
			
				$showdte = date('d-m-Y', strtotime($rowq['lastupd']));
				
				$urlpop = 'upd_rpthead.php';
				$urlvie = 'vm_rpthead.php';
				echo '<tr bgcolor='.$defaultcolor.'>';
            	echo '<td>'.$numi.'</td>';
           		echo '<td>'.$menuname.'</td>';
            	echo '<td>'.$rowq['lastlogin'].'</td>';
            	echo '<td>'.$showdte.'</td>';
                           
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?menun='.$rowq['menucode'].'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	}
	            if ($var_accupd == 0){
		            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
	            }else{
		            echo '<td align="center"><a href="'.$urlpop.'?menun='.$rowq['menucode'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
	            }
	            if ($var_accdel == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="menun[]" />'.'</td>';
	            }else{
	              $values = implode(',', $rowq);	
	              echo '<td align="center"><input type="checkbox" name="menun[]" value="'.$values.'" />'.'</td>';
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
