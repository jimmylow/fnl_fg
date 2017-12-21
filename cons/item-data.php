<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
  $var_loginid = $_SESSION['sid'];

    $return_arr = array();
    $param = $_GET["term"];
    
  //----------- get special authority ------------------//
  $sqlauth = " select * from progauth";
  $sqlauth .= " where username = '$var_loginid'";
  $sqlauth .= " and program_name = '99'";
  
  $tmpauth = mysql_query($sqlauth) or die ("Cant get auth : ".mysql_error());
  
  if (mysql_numrows($tmpauth) > 0) {
     $speauth = "Y";
  } else {  $speauth = "N"; }    

    //$fetch = mysql_query("SELECT productcode, exunit, description FROM product WHERE productcode REGEXP '^$param' and invflg = 'N' and stat = 'A' and stype = 'C' LIMIT 10");
    //$fetch = mysql_query("SELECT productcode, description FROM product WHERE productcode REGEXP '^$param' and status = 'A' LIMIT 10");
    $fetch = mysql_query("SELECT distinct groupcode, description FROM product WHERE groupcode REGEXP '^$param' and status in ('A', 'D') and groupcode <> '' order by productcode LIMIT 10");
    //echo $fetch; break;

    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
        
        $row_array['rm_code'] = $row['groupcode'];
        
        //$row_array['uom'] = $row['exunit'];
        if (empty($row['description'])) { $row_array['desc'] = ""; } else { $row_array['desc'] = htmlspecialchars_decode($row['description']); }
        
        $row_array['auth'] = $speauth;
        
        array_push( $return_arr, $row_array );  
    }

   
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
?>
