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
	  $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }
        
	if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['xmthyr']) && is_array($_POST['xmthyr'])) 
     {
           foreach($_POST['xmthyr'] as $key) {
           
             $defarr = explode(",", $key);
             $var_mth = $defarr[0];
             $var_yr  = $defarr[1];
            
		     $sql = "DELETE FROM curr_xrate "; 
		     $sql .= "WHERE xmth ='".$var_mth."' And xyr= '".$var_yr."' "; 
		 	 mysql_query($sql) or die("Query Delete :".mysql_error()); 
		   }
		   $backloc = "../main_mas/m_xrate_mas.php?menucd=".$var_menucode;
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
thead th input { width: 90% }

.style2 {
	margin-right: 0px;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript" charset="utf-8"> 
$(document).ready(function() {
		$('#example').dataTable( {
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
			"bStateSave": true,
			"bFilter": true,
			"bAutoWitdh": false,
			"sPaginationType": "full_numbers"
			
		} )
		.columnFilter({sPlaceHolder: "head:after",
		aoColumns: [ 
					 null,	
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
<body>
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->

  <div class="contentc" style="width: 657px">

	<fieldset name="Group1" style=" width: 539px;" class="style2">
	 <legend class="title">CURRENCY EXCHANGE RATE</legend>
	  <br>
	   
		 <form name="LstColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
         <table>
		 <tr>
		      
           <td style="width: 1191px; height: 38px;" align="left">
			   <?php
			   $locatr = "xrate_mas.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
    	  	
    	  	   $msgdel = "Are You Sure Delete Selected Exchange Rate For The Selected Month & Year?";
    	  	   include("../Setting/btndelete.php");  	  	       
		       ?>
            </td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" style="width: 99%">
         <thead>
         	<tr>
         	 <th style="width: 10px;"></th>
         	 <th style="width: 25px">Exchange Month</th>
         	 <th style="width: 25px">Exchange Year</th>
         	 <th style="width: 20px"></th>
         	 <th style="width: 20px"></th>
         	 <th style="width: 20px"></th>
         	</tr>

         	<tr>
         	 <th class="tabheader">#</th>
         	 <th class="tabheader">Exchange Month</th>
         	 <th class="tabheader">Exchange Year</th>
         	 <th class="tabheader">View Detail</th>
         	 <th class="tabheader">Update</th>
         	 <th class="tabheader">Delete</th>
         	</tr>
         </thead>
		 <tbody>
		 <?php 
		 	$sql = "SELECT distinct xmth, xyr ";
			$sql .= " FROM curr_xrate";
   		    $sql .= " ORDER BY xyr desc, xmth desc";  
		    $rs_result = mysql_query($sql) or die(mysql_error()); 
		   			
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			
				$urlpop = 'upd_xrate_mas.php?m='.$row['xmth']."&y=".$row['xyr']."&menucd=".$var_menucode;
				$urlvm = 'vm_xrate_mas.php?m='.$row['xmth']."&y=".$row['xyr']."&menucd=".$var_menucode;
			
				echo '<tr>';
            	echo '<td>'.$numi.'</td>';
            	echo '<td>'.$row['xmth'].'</td>';
            	echo '<td>'.$row['xyr'].'</td>';
			
				if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvm.'">[VIEW]</a>';'</td>';
            	}
			
				if ($var_accupd == 0){
            		echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlpop.'">[EDIT]</a>';'</td>';
            	} 
            
				if ($var_accdel == 0){
			 		echo '<td align="center"><input type="checkbox" DISABLED name="xmthyr[]" />'.'</td>';
				}else{
					$values = implode(',', $row); 
             		echo '<td align="center"><input type="checkbox" name="xmthyr[]" value="'.$values.'" />'.'</td>';
				}       
            
            	echo '</tr>';
            	$numi = $numi + 1;
			}
		 ?>
		 </tbody>
		 </table>
		 
		 <div class="spacer"></div>
         </form>
	 
	</fieldset>
	</div>
</body>

</html>
