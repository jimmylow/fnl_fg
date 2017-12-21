<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

 
	$query = mysql_query("SELECT prod_code, prod_desc FROM pro_cd_master Where actvty != 'Z'");
	$results = array();
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
   		$results[] = $row;
   	}
   
	//check the parameter
	
	$term = $_GET['term'];
	
	$matches = array();
	if(isset($term) and $term != '')
	{	
		//initialize the results array
		
		foreach($results as $prod_code)
		{
		
			if(stripos($prod_code['prod_code'], $term) !== false){
			        $prod_code['prod_desc'] = str_replace("^", "'", $prod_code['prod_desc']);
				// Add the necessary "value" and "label" fields and append to result set
				$prod_code['value'] = $prod_code['prod_code'];
			    $prod_code['label'] = "{$prod_code['prod_code']}, {$prod_code['prod_desc']}";
				$matches[] = $prod_code;
	    	}
			
		}
	}
	
    //print_r ($prod_code);
	// return the array as json with PHP 5.2
	$matches = array_slice($matches, 0, 5);
	
	print json_encode($matches);
?>
