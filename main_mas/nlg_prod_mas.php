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
    
    

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<link rel="stylesheet" href="../css/lightbox.css" type="text/css" media="screen">	
<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";
thead th input { width: 90% }

.style2 {
	margin-right: 28px;
}
.style3 {
	color: #FF0000;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript" charset="utf-8"> 
</script>
</head>
 
  <!--<?php include("../sidebarm.php"); ?>-->
<body >
  <?php include("../topbarm.php"); ?>
  <div class="contentc">

	<fieldset name="Group1" class="style2" style="width: 900px">
	 <legend class="title">NLG PRODUCT CODE MASTER</legend>
	  <br>

	     <br><br>
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">

		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
         	<tr>
         	 <th></th>
         	 <th>Product Code </th>
         	 <th>Description </th>           
         	 <th>Buyer</th>
         	 <!-- <th>Type</th> -->
         	 <th>Category</th>
         	 <th>Stat</th>
         	 <!-- <th>Modified By</th>
         	 <th>Modified On</th> -->
         	 <th></th>
         	</tr>

         	<tr>
         	 <th class="tabheader" style="width: 20px; height: 36px;">#</th>
         	 <th class="tabheader" style="width: 100px; height: 36px;">Product Code </th>
         	 <th class="tabheader" style="width: 300px; height: 36px;">Description</th>
         	 <th class="tabheader" style="width: 50px; height: 36px;">Buyer</th>
         	 <!-- <th class="tabheader" style="width: 50px; height: 36px;">Type</th> -->
         	 <th class="tabheader" style="width: 50px; height: 36px;">Category</th>
         	 <th class="tabheader" style="width: 20px; height: 36px;">Stat</th>
         	 <!-- <th class="tabheader" style="width: 100px; height: 36px;">Modified By</th>
         	 <th class="tabheader" style="width: 128px; height: 36px;">Modified On</th> -->
         	 <th class="tabheader" style="width: 20px; height: 36px;">Detail</th>

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
  
       
		    $sql = "SELECT * ";
		    $sql .= " FROM pro_cd_master";
    		$sql .= " ORDER BY modified_on";  
			$rs_result = mysql_query($sql, $db_link2) or die ("error : ".mysql_error()); 
		 
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
					 
				//$showdte = date('d-m-Y', strtotime($rowq['modified_on']));
				$urlvie = 'vm_procd_mas.php';
				echo '<tr bgcolor='.$defaultcolor.'>';
            	echo '<td>'.$numi.'</td>';
            	echo '<td>'.htmlentities($rowq['prod_code']).'</td>';
              echo '<td align="center">'.$rowq['prod_desc'].'</td>';
            	echo '<td align="center">'.$rowq['prod_buyer'].'</td>';
            	//echo '<td align="center">'.$rowq['prod_type'].'</td>';
            	echo '<td align="center">'.$rowq['pro_cat'].'</td>';
            	echo '<td align="center">'.$rowq['actvty'].'</td>';
            	//echo '<td>'.$rowq['modified_by'].'</td>';
            	//echo '<td>'.$showdte.'</td>';
            
            if ($var_accvie == 0){
            echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            }else{
            echo '<td align="center"><a href="'.$urlvie.'?procd='.htmlentities($rowq['prod_code']).'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
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
