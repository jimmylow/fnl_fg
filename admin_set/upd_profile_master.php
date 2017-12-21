<?php
    /* Title     : View Profile Master Detail
       Table Use : profile_master, profile_access
       Version   : 1.00
       Date Create : 26/05/2012
    */

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
      $var_procd  = $_GET['procd'];
	  $var_menucode = $_GET['menucd'];
    }
    
    if ($_POST['Submit'] == "Back") {
        $var_menucode  = $_POST['menudcd'];
        $backloc = "../admin_set/profile_master.php?menucd=".$var_menucode;
	
        echo "<script>";
        echo 'location.replace("'.$backloc.'")';
        echo "</script>";

    }
    
    
     if ($_POST['Submit'] == "Update") {
       $profilecd   = $_POST['profilecd'];
       $profilename = $_POST['profilename'];
       
       if ($profilecd <> "") {
           $vartoday = date("Y-m-d H:i:s");
      	   $sql = "Update profile_master set profile_desc ='$profilename',";
           $sql .= " modified_by='$var_loginid',";
           $sql .= " modified_on='$vartoday' WHERE profile_code = '$profilecd'";
       	   mysql_query($sql); 
       	   
       	   $sql = "delete from profile_access WHERE profile_code = '$profilecd'";
       	   mysql_query($sql); 
       	   
       	   
       	   //insert to profile_access table	
           if(!empty($_POST['menucdac']) && is_array($_POST['menucdac'])) 
     	   {
                foreach($_POST['menucdac'] as $value ) {
                	$var_acc = 1;
                	$var_add = 0;
                	$var_upd = 0;
                	$var_vie = 0;
                	$var_del = 0;
         
                	//looking access to add record
                	if(!empty($_POST['menucdad']) && is_array($_POST['menucdad'])){
                	  foreach($_POST['menucdad'] as $valuead ) {
                	     if ($value == $valuead){
                	       $var_add = 1;
                	     }
                	   }
                	}
            		//-----------------------------------------------
            	    
            	    //looking access to upd record
                	if(!empty($_POST['menucdup']) && is_array($_POST['menucdup'])){
                	  foreach($_POST['menucdup'] as $valueup ) {
                	     if ($value == $valueup){
                	       $var_upd = 1;
                	     }
                	   }
                	}
            		//-----------------------------------------------
            		
            		//looking access to view record
                	if(!empty($_POST['menucdvi']) && is_array($_POST['menucdvi'])){
                	  foreach($_POST['menucdvi'] as $valuevi ) {
                	     if ($value == $valuevi){
                	       $var_vie = 1;
                	     }
                	   }
                	}
            		//-----------------------------------------------
            		
            		//looking access to delete record
                	if(!empty($_POST['menucdde']) && is_array($_POST['menucdde'])){
                	  foreach($_POST['menucdde'] as $valuede ) {
                	     if ($value == $valuede){
                	       $var_del = 1;
                	     }
                	   }
                	}
            		//-----------------------------------------------

                	$sql = "INSERT INTO profile_access (profile_code,".
                	       " menu_code, pa_access, pa_add, pa_update, pa_view, pa_delete)".
                	       " values ". 
      	        	       " ('$profilecd', '$value', '$var_acc', '$var_add', '$var_upd',".
      	        	       "  '$var_vie', '$var_del')";
      	        
		    		mysql_query($sql); 
               }
       	   }
       	    $var_menucode  = $_POST['menudcd'];
			$backloc = "../admin_set/profile_master.php?menucd=".$var_menucode;
	
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
<title>PROFILE MASTER</title>

	
<style media="all" type="text/css">@import "../css/styles.css";
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>

<script type="text/javascript" charset="utf-8"> 

function showhide(oCheckbox, vtabno)
 {
     var oForm = oCheckbox.form
     var objtabno = "AutoNumber"+vtabno;
     
     var tr, i = 0, table = document.getElementById(objtabno);
     var toggles = table.getElementsByTagName('tr');
     while (tr = toggles.item(i++))
         if (tr.className == 'toggleable'){
           
              if (tr.style.display == 'none'){
              //alert (tr.style.display);
                 tr.style.display = '';
              }else{
                 tr.style.display = 'none';
              }
            //tr.style.display = (bWhich) ? 'block' : 'none';
        	}  
                

}

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

</script>
</head>

<body OnLoad="document.InpProfileMas.profilename.focus();">

<?php include("../topbarm.php"); ?> 
 <!--<?php include("../sidebarm.php"); ?>--> 
<div class ="contentc">

<div class="title">UPDATE PROFILE CODE <?php echo $var_procd;?></div>
<?php
        $sql = "select profile_desc";
        $sql .= " from profile_master";
        $sql .= " where profile_code ='".$var_procd."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $prode = $row[0];
       
?>		

			<br>
	        <form name="InpProfileMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 296px; width: 881px;">
            <input name="menudcd" type="hidden" value="<?php echo $var_menucode;?>">
	 		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 79px" class="tdlabel">Profile Code</td>
	  	    <td style="width: 19px">:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="profilecd" id ="profilecdid" type="text" style="width: 95px" readonly="readonly" value="<?php echo $var_procd; ?>">
			</td>
			<td>
			</td>
		  
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 2px">
	  	    </td> 
	  	    <td style="width: 79px" class="tdlabel"></td>
	  	    <td style="width: 19px">
	  	    </td>
	  	    <td><div id="msgcd"></div></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 79px" class="tdlabel">Profile Name</td>
	  	    <td style="width: 19px">:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt"  name="profilename" id ="profilenmid" type="text" style="width: 417px" value="<?php echo $prode; ?>">
			</td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td>
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px">
	  	   <input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px"></tr> 
	  	  
		 </table>  
	   	  
		 <br> 		 
		  <table border style="width: 877px" >
		  <tr>
          <th style="width: 820px" class="tabheader">Menu Name</th>
          <th style="width: 40px" class="tabheader">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
          <th style="width: 114px" class="tabheader">Access</th>
		  <th style="width: 114px" class="tabheader">Add Record</th>
          <th style="width: 114px" class="tabheader">Update Record</th>
		  <th style="width: 114px" class="tabheader">View Detail</th>
          <th style="width: 114px" class="tabheader">Delete Record</th>
          </tr>
          </table> 

		    <?php
		    
		        //Process Main Menu
				$sqlm = "SELECT menu_code, menu_name, menu_seq, menu_type ";
				$sqlm .= " FROM menud Where menu_stat = 'ACTIVE'";
				$sqlm .= " and menu_type = 'Main Menu'";
    		    $sqlm .= " ORDER BY menu_seq";  
			    $rs_resultm = mysql_query($sqlm); 
               
                $vart = 1;
               
                while ($rowm = mysql_fetch_assoc($rs_resultm)) { 
                	echo '<table id="AutoNumber'.$vart.'" border style="width: 877px">';
                
                    $sql = "select pa_access";
                    $sql .= " from profile_access";
                    $sql .= " where profile_code ='".$var_procd."'";
                    $sql .= " and menu_code ='".$rowm['menu_code']."'";
                    $sql_result = mysql_query($sql);
                    $row = mysql_fetch_array($sql_result);
                    $prochk = $row[0];
                    
                    if ($prochk == 1){
                      $varchk = "checked";
                    }else{
                      $varchk = "";
                    }  
                    
    				echo '<tr>';
               		echo '<td style="width: 401px; font-weight:bold;">'.$rowm['menu_name']." (".$rowm['menu_type'].")".'</td>';
                	echo '<td style="width: 36px" align="center"><a href="javascript:showhide(this,'.$vart.');">Show</a></td>';
                 	echo '<td align="center" style="width: 82px"><input type="checkbox" name="menucdac[]" id= "mmenuacid" value="'.$rowm['menu_code'].'" '.$varchk.'/>'.'</td>';
                	echo '<td></td><td></td><td></td><td></td>';
                	echo '</tr>';
                	display_childrenpro($rowm['menu_code'], '1',$var_procd);
                    $vart = $vart +1;
                    echo '</table>';
         		}
         		
         		function display_childrenpro($parent, $level, $procdf) 
				{ 
				    // retrieve all children of $parent 
				    $sqls = "SELECT menu_code, menu_name, menu_type, menu_seq ".
				            " FROM menud Where menu_stat = 'ACTIVE'".
			                " and menu_type in ('Sub Menu','Program') and menu_parent = '".$parent."'".
    		 	            " ORDER BY menu_seq"; 
    		 	    
			 	    $rs_results = mysql_query($sqls);  
				    
				    // display each child 
				    $i = 0;
				    while ($i <> $level){
				    	$ind = $ind."---";
				    	$i = $i + 1;
				    }	
				    
				    while ($row = mysql_fetch_assoc($rs_results)) { 
			           echo '<tr class="toggleable" style="display:none">';
                       if ($row['menu_type'] == "Sub Menu"){
			               echo '<td style="color:#3333CC; font-weight:bold;">'.$ind.'>'.$row['menu_name']." (".$row['menu_type'].")".'</td>';
 		               }else{
				           echo '<td style="color:green; font-weight:bold;">'.$ind.'>'.$row['menu_name']." (".$row['menu_type'].")".'</td>';
				       }
				       
				       $sql1 = "select pa_access, pa_add, pa_update, pa_view, pa_delete";
                       $sql1 .= " from profile_access";
                       $sql1 .= " where profile_code ='".$procdf."'";
                       $sql1 .= " and menu_code ='".$row['menu_code']."'";
                       $sql_result1 = mysql_query($sql1);
                       $row1 = mysql_fetch_array($sql_result1);
                       $prochkac = $row1[0];
                       $prochkad = $row1[1];
					   $prochkup = $row1[2];
					   $prochkvi = $row1[3];
					   $prochkde = $row1[4];

                       if ($prochkac == 1){
                         $varchk1ac = 'checked';
                       }else{
                         $varchk1ac = "";
                       }
                       if ($prochkad == 1){
                         $varchk1ad = "checked";
                       }else{
                         $varchk1ad = "";
                       }  
					   if ($prochkup == 1){
                         $varchk1up = "checked";
                       }else{
                         $varchk1up = "";
                       }  
					   if ($prochkvi == 1){
                         $varchk1vi = "checked";
                       }else{
                         $varchk1vi = "";
                       }  
					   if ($prochkde == 1){
                         $varchk1de = "checked";
                       }else{
                         $varchk1de = "";
                       }  

                       if ($row['menu_type'] == "Sub Menu"){
                     	echo '<td></td>';
   	                 	echo '<td align="center"><input type="checkbox" name="menucdac[]" id= "mmenuacid" value="'.$row['menu_code'].'" '.$varchk1ac.'/>'.'</td>';
                   	   }else{
                     	echo '<td></td>';
                     	echo '<td align="center"><input type="checkbox" name="menucdac[]" id= "mmenuacid" value="'.$row['menu_code'].'" '.$varchk1ac.'/>'.'</td>';
                     	echo '<td align="center"><input type="checkbox" name="menucdad[]" id= "mmenuadid" value="'.$row['menu_code'].'" '.$varchk1ad.'/>'.'</td>';
                     	echo '<td align="center"><input type="checkbox" name="menucdup[]" id= "mmenuupid" value="'.$row['menu_code'].'" '.$varchk1up.'/>'.'</td>';
                     	echo '<td align="center"><input type="checkbox" name="menucdvi[]" id= "mmenuviid" value="'.$row['menu_code'].'" '.$varchk1vi.'/>'.'</td>';
                     	echo '<td align="center"><input type="checkbox" name="menucdde[]" id= "mmenudeid" value="'.$row['menu_code'].'" '.$varchk1de.'/>'.'</td>';
                   		} 
                	    echo '</tr>';
						// call this function again to display this 
    			    	// child's children 
    				    display_childrenpro($row['menu_code'], $level+1, $procdf); 
    			    } 
				} 
         		

            ?>
             </form>	
         </div>    
		 <div class="spacer"></div>
</body>
</html>
