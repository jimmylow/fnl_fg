<?php
 
	$var_loginid = $_SESSION['sid'];

    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "index.php"';
      echo "</script>";
    } else {
      $var_stat = $_GET['stat'];
	  $var_menucode = $_GET['menucd'];
    }
    
    $settpath = $approot."/admin_set/user_scut_set.php";
    $logupath = $approot."/logout.php";
?>

<div id="ddsidemenubar" class="markermenu">
<ul>
<?php
    
     $approot1 = substr(dirname(__FILE__),strlen($_SERVER['DOCUMENT_ROOT'])); 
     $approot = str_replace('\\', '/', $approot1);
     
     $sql = " select prog_name, prog_code, sh_seq from shou_user ";
     $sql .= " where usern ='".$var_loginid."'";
	 $sql .= " order by sh_seq, prog_name";
	
     $sql_result = mysql_query($sql);
            
	 while ($row = mysql_fetch_assoc($sql_result)) { 
		   $sqlw = "select menu_path, menu_desc from menud ";
           $sqlw .= " where menu_code ='".$row['prog_code']."'";
           $sql_resultq = mysql_query($sqlw);
           $rowq = mysql_fetch_array($sql_resultq);
           $menupath = $rowq[0];
           $menudesc = $rowq[1];
           $progpath = $approot.$menupath.'?menucd='.$row['prog_code']; 
		   echo '<li><a href="'.$progpath.'" title="'.$menudesc.'" title="'.$menudesc.'">'.$row['prog_name'].'</a></li>';
		   
		
	 } 
?>
	<li><a href="<?php echo $settpath; ?>" title="ShortCut Setting">ShortCut Setting</a></li>
	<li><a href="<?php echo $logupath;?>" style="border-bottom-width: 0" title="Logout From System">Logout</a></li>		
</ul>
</div>
