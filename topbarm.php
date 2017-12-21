<html>
	<head>
	<title>NL FINISHED GOOD SYSTEM</title>

    <?php
      $approot1 = substr(dirname(__FILE__),strlen($_SERVER['DOCUMENT_ROOT'])); 
      $approot = str_replace('\\', '/', $approot1);
           
      $relurl = $approot;
      $stylecss = $relurl."/css/style.css";
      $jsbase = $relurl."/css/ddlevelsmenu-base.css";
      $jstopbar = $relurl."/css/ddlevelsmenu-topbar.css";
      $jssidebar = $relurl."/css/ddlevelsmenu-sidebar.css";
      $jsmenu =  $relurl."/css/ddlevelsmenu.js";
      $logoutpa = $relurl."/logout.php";
      $logoutpic = $relurl."/images/logout.png";
     
      $comphome = $relurl."/home.php";
      
      $jqjs   =  $relurl."/js/jquery-1.4.2.min.js";
      $jqcloc =  $relurl."/js/jquery.jclock.js";
    ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $stylecss; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo $jsbase; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo $jstopbar; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo $jssidebar; ?>">
	
	<script type="text/javascript" language="javascript" src="<?php echo $jsmenu; ?>"></script>
	
  <script type="text/javascript" src="<?php echo $jqcloc; ?>"></script>

	
	<script type="text/javascript">
	
var dayarray=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday")
var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")

      $(function($) {
      var options1 = {
        format: '%A %B %d, %Y %I:%M:%S %P' // 12-hour
      }
      $('#jclock1').jclock(options1);

    });
		
	ddlevelsmenu.setup("ddtopmenubar", "topbar") 
	//ddlevelsmenu.setup("ddsidemenubar", "sidebar")
	
	  function redirect(URL)
     {
       document.location=URL;
       return false;
     }

    </script>


</head>
<body>	
      <?php
        $sql = "select apphea_txt, appbg_col, apphea_log from apphea_set ";
       	$sql_result = mysql_query($sql);
       	$row = mysql_fetch_array($sql_result);
       	$heatitle = $row[0];
       	$heacolor = $row[1];
		$complogo = $row[2];
		
		 $complogo = $relurl."/images/".$complogo;
    ?>
	
    <div style="top:0px; left:0px; ">
     <a href="<?php echo $comphome; ?>"><img border="0" height="100" src="<?php echo $complogo; ?>" width="290"></a>
    </div>
    
  

    
    <div id="framecontent" style="background-color: #<?php echo $heacolor;?>">
		<div class="innertube" style="position:absolute; left:0px; width:100%;">
		 
		<br>&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:x-large"><?php echo $heatitle; ?></span>&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span style="font-size:medium;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; User&nbsp;:&nbsp;<?php echo $var_loginid;?></span>
		<span style="font-size:medium;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<a href="<?php echo $logoutpa; ?>"><img src="<?php echo $logoutpic; ?>" alt="Logout From The System" title='Logout From The System'>
		<span style="font-size: small; font-color: black"><span></span></span>
		</a><span style="font-size: small; font-color: black"></span>&nbsp;&nbsp;&nbsp;&nbsp;
		<span id="jclock1" style="font-weight:bold;">
		</span><span style="font-size:medium;"></span>
   </div>
</div>



<div id="ddtopmenubar" class="mattblackmenu" style="position:absolute; width:100%; left:290px; top:71px;">

<ul>
<?php

          //Process Main Menu
     $sqlm = "SELECT menu_code, menu_name, menu_seq, menu_path, menu_desc ";
	 $sqlm .= " FROM menud Where menu_stat = 'ACTIVE'";
	 $sqlm .= " and menu_type = 'Main Menu'";
   $sqlm .= " and menu_code <> '99'";
     $sqlm .= " ORDER BY menu_seq";  
	 $rs_resultm = mysql_query($sqlm);
   
   //echo $sqlm; 

     $vart = 0;
     while ($rowm = mysql_fetch_assoc($rs_resultm)) { 
        
        $useraccr = 0;
        $sql = "select accessr from progauth  ";
        $sql .= " where username ='".$var_loginid."'";
        $sql .= " and program_name ='".$rowm['menu_code']."'";
        $sql_result = mysql_query($sql) or die(mysql_error());
       
        $row = mysql_fetch_array($sql_result);
        $useraccr = $row[0];

        if ($useraccr == 1){
              $progpath = $approot.$rowm['menu_path']; 
       	echo '<li><a href="'.$progpath.'" rel="ddsubmenu'.$vart.'" title="'.$rowm['menu_desc'].'" onclick="return false">'.$rowm['menu_name'].'</a></li>';
       	       	
       	       	
       	              	display_children($rowm['menu_code'], '1', $vart, "Sub Menu", $var_loginid);
       
      	$vart = $vart + 1; 
      }
     }
     
     function display_children($parent, $level, $tabid, $menutyp, $uid) 
	{ 
		
          $approot1 = substr(dirname(__FILE__),strlen($_SERVER['DOCUMENT_ROOT'])); 
          $approot = str_replace('\\', '/', $approot1); 
         
	
	      	 	// retrieve all children of $parent 
		 $sqls = "SELECT menu_code, menu_name, menu_seq,  menu_path, menu_type,  menu_desc ".
		         " FROM menud Where menu_stat = 'ACTIVE'".
		         " and menu_type in ('Sub Menu','Program') and menu_parent = '".$parent."'".
    		         " ORDER BY menu_seq"; 
                    $rs_results = mysql_query($sqls);  
			
                       if ($menutyp == 'Sub Menu'){	    
	        echo '<ul class="ddsubmenustyle" id="ddsubmenu'.$tabid.'">';
	        }
	         while ($row = mysql_fetch_assoc($rs_results)) {
	         
	         	$usersuba = 0;
	         	$sql = "select accessr from progauth  ";
      			 $sql .= " where username ='".$uid."'";
      			 $sql .= " and program_name ='".$row['menu_code']."'";
      			 $sql_result = mysql_query($sql);
      
      			 $row1 = mysql_fetch_array($sql_result);
      			 $usersuba = $row1[0];

			if ($usersuba == 1){

	            echo "<li>";
	            if ($row['menu_path'] !=  ""){
				
					
				    //$key = "This encrypting key should be long and complex.";
					//$row['menu_code'];
					
					//$encrurl = strtr(base64_encode($row['menu_code']), '+/=', '-_,111');
				   
					$progpath = $approot.$row['menu_path'].'?menucd='.$row['menu_code']; 
					$hidestat = "window.status=".$approot;
                      
				         if ($row['menu_type'] == 'Sub Menu'){
				        echo '<a href="#" title="'.$row['menu_desc'].'" onclick="return false;">'.$row['menu_name'].'</a>'; 
				     }else{    
						echo '<a href="#" title="'.$row['menu_desc'].'" onclick="return redirect(\''.$progpath.'\');">'.$row['menu_name'].'</a>';
	            	  }
	            }else{
	             echo '<a href="#" onclick="return false">'.$row['menu_name'].'</a>';
	            }
	            
	            if ($menutyp != 'Sub Menu'){
	            echo  '</li>';
	            }
	            
	            
	            display_children($row['menu_code'], $level+1, $tabid+1, $row['menu_type'], $uid);
	         
   }
	         }
	        if ($menutyp == 'Sub Menu'){ 
		        echo "</ul>";
		        }
                   
    	} 


?>

</ul>
			</div>

</body>
</html>

