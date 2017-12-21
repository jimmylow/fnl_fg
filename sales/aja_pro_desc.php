<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	if(!isset($_GET['procode']) || !$method = $_GET['procode']) exit;
	$vprocode = $_GET['procode'];
    
	$sql = "select description, exunit, selltype from product ";
    $sql .= " where productcode ='".$vprocode."'";
    $sql_result = mysql_query($sql);
    $row1 = mysql_fetch_array($sql_result);
	
    $row_array['desc'] = $row1[0];
	$row_array['uom'] = $row1[2];
    	
    echo json_encode($row_array);
    
?>