<?php
    /* Table Use : profile_master, profile_access
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
      $var_stat = $_GET['stat'];
	  $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }
    
    if ($_POST['Submit'] == "Save") {
       $profilecd   = $_POST['profilecd'];
       $profilename = $_POST['profilename'];
       
       if ($profilecd <> "") {
       
        $var_sql = " SELECT count(*) as cnt from profile_master ";
        $var_sql .= " WHERE profile_code = '$profilecd'";

      	$query_id = mysql_query($var_sql) or die ("Cant Check profile Code");
      	$res_id = mysql_fetch_object($query_id);
        
        if ($res_id->cnt > 0 ) {
	  	   $backloc = "../admin_set/profile_master.php?stat=3&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
      	}else {
       	   $vartoday = date("Y-m-d H:i:s");
      	   $sql = "INSERT INTO profile_master values 
      	          ('$profilecd', '$profilename', '$var_loginid', '$vartoday',   	           
      	           '$var_loginid', '$vartoday')";
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
					if ($profilecd <> "") {
                	$sql = "INSERT INTO profile_access (profile_code,".
                	       " menu_code, pa_access, pa_add, pa_update, pa_view, pa_delete)".
                	       " values ". 
      	        	       " ('$profilecd', '$value', '$var_acc', '$var_add', '$var_upd',".
      	        	       "  '$var_vie', '$var_del')";
      	            }
		    		mysql_query($sql); 
		    	}

		 	
		    //---------------------------------------------------
		   $backloc = "../admin_set/profile_master.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";

           } 
         } 

       }else{
         $backloc = "../admin_set/profile_master.php?stat=4&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
         
       }
      
    }
       
     if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['lstprocd']) && is_array($_POST['lstprocd'])) 
     {
           foreach($_POST['lstprocd'] as $value ) {
		    $sql = "Delete from profile_master ";
            $sql .= " WHERE profile_code ='".$value."'";
		 	mysql_query($sql); 
		 	
		 	$sql = "Delete from profile_access ";
            $sql .= " WHERE profile_code ='".$value."'";
		 	mysql_query($sql); 

		 	
		   }
		   $backloc = "../admin_set/profile_master.php?stat=1&menucd=".$var_menucode;
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
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>

<script type="text/javascript" charset="utf-8"> 

$(document).ready(function() {
				$('#example').dataTable( {
					"sPaginationType": "full_numbers"
				} );
			} );

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

function checkac(checkboxElem, vtabno) 
{
    var objtabno = "AutoNumber"+vtabno;
   
    var trs = document.getElementById(objtabno).getElementsByTagName('tr');
       
    for( var i = 0; i < trs.length; i++ ) {
      var tds = trs[i].getElementsByTagName('td');
      for( var k = 0; k < tds.length; k++ ) {
          if( tds[k].innerText == checkboxElem.getAttribute('data-name') 
                || tds[k].textContent == checkboxElem.getAttribute('data-name')) {
             
                trs[0].getElementsByTagName('input')[0].checked = checkboxElem.checked;
            }  
        }
    }

}



function AjaxFunction(profilecd)
{
     

		var httpxml;
		try	{
			// Firefox, Opera 8.0+, Safari
			httpxml=new XMLHttpRequest();
		}catch (e){
		  // Internet Explorer
		  try{
			  httpxml=new ActiveXObject("Msxml2.XMLHTTP");
		  }catch (e){
		    try{
			   httpxml=new ActiveXObject("Microsoft.XMLHTTP");
		    }catch (e){
			   alert("Your browser does not support AJAX!");
			   return false;
		    }
		}
		
}

function stateck()
{
		if(httpxml.readyState==4)
		{
			document.getElementById("msgcd").innerHTML=httpxml.responseText;
		}
}
	
	var url="aja_chk_profilecd.php";
	
	url=url+"?profilecd="+profilecd;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
	httpxml.send(null);
}	


</script>
</head>

<body OnLoad="document.InpProfileMas.profilecdid.focus();">
<?php include("../topbarm.php"); ?> 
 <!--<?php include("../sidebarm.php"); ?>-->
<div class ="contentc">

      <div class="title">PROFILE SETTING</div>
      <form name="InpProfileMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 296px; width: 881px;">

	  <br>
	  <table style="width: 877px">
		   <tr>
		   <td align="right">
		     <?php
    	  	   $msgdel = 'Are You Sure Delete Selected Profile?';
    	  	   include("../Setting/btndelete.php"); 
		      ?>
	  	   </td>
           </tr>
         </table>
	  <table cellpadding="0" cellspacing="0" border id="example"  class="display" width="100%">
         <thead><tr>
          <th class="tabheader" style="width: 27px">#</th>
          <th class="tabheader" style="width: 94px">Profile Code</th>
          <th class="tabheader" style="width: 264px">Description</th>
          <th class="tabheader" style="width: 47px">View Detail</th>
          <th class="tabheader" style="width: 41px">Update</th>
          <th class="tabheader" style="width: 37px">Delete</th>

         </tr></thead>
		 <tbody>
		 <?php 
		 
		    $sql = "SELECT profile_code, profile_desc";
				$sql .= " FROM profile_master";
    		    $sql .= " ORDER BY profile_code";  
			    $rs_result = mysql_query($sql); 
 
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			
			$urlpop = 'upd_profile_master.php';
			$urlvm = 'vm_profile_master.php';
			echo '<tr>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$row['profile_code'].'</td>';
            echo '<td>'.$row['profile_desc'].'</td>';
			
			if ($var_accvie == 0){
            echo '<td><a href="#">[VIEW]</a>';'</td>';
            }else{
            echo '<td><a href="'.$urlvm.'?procd='.$row['profile_code'].'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            }
 
            if ($var_accupd == 0){
            echo '<td><a href="#">[EDIT]</a>';'</td>';
            }else{
            echo '<td><a href="'.$urlpop.'?procd='.$row['profile_code'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            }
            echo '<td><input type="checkbox" name="lstprocd[]" value="'.$row['profile_code'].'" />'.'</td>';
         
            echo '</tr>';
            $numi = $numi + 1;
			}
		 ?>
		 </tbody>
		 
		 </table>
		 
	  <br>
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 79px" class="tdlabel">Profile Code</td>
	  	    <td style="width: 19px">:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="profilecd" id ="profilecdid" type="text" maxlength="10" onchange ="upperCase(this.id)" onBlur="AjaxFunction(this.value);" style="width: 95px" >
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
			<input class="inputtxt" name="profilename" id ="profilenmid" type="text" maxlength="100" style="width: 417px">
			</td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td>
	  	    <?php
	  	   include("../Setting/btnsave.php");
	  	   ?>
	  	   </tr> 
	  	  
	  	   <tr><td></td></tr>
	  	   <tr><td></td><td></td><td></td>
	  	   <td>
		   <span style="color:#FF0000">Message :</span>
		   <?php
						  if (isset($var_stat)){
			    switch ($var_stat)
				{
				case 1:
  					echo("<span>Process Success </span>");
  					break;
				case 0:
 					echo("<span>Process Fail</span>");
					break;
				case 3:
				    echo("<span>Fail! Duplicated Found</span>");
  					break;
  				case 4:
				    echo("<span>Please Fill In The Data To Save</span>");
  					break;
  				case 5:
				    echo'<span style="color:red">Please Fill In The Menu Sequence No</span>';
  					break;
				default:
  					echo "";
				}
			  }	
			?>
          </td>
          </tr>
		 </table>  
	   	  
<br> 		 
		   <table border style="width: 877px; " >
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
                echo '<table id="AutoNumber'.$vart.'" border  style="width: 877px">';
    				echo '<tr>';
               		echo '<td style="width: 401px; font-weight:bold;">'.$rowm['menu_name']." (".$rowm['menu_type'].")".'</td>';
                	
                	
                	echo '<td style="width: 36px" align="center"><a href="javascript:showhide(this,'.$vart.');">Show</a></td>';
                
                 	echo '<td align="center" style="width: 77px"><input type="checkbox" name="menucdac[]" id= "mmenuacid" value="'.$rowm['menu_code'].'" />'.'</td>';
                	echo '<td></td><td></td><td></td><td></td>';
                	echo '</tr>';
                  	display_childrenpro($rowm['menu_code'], '1', $vart);
	                $vart = $vart +1;
					echo '</table>';
         		}
         		
         		function display_childrenpro($parent, $level, $tabid) 
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
			        echo '<tr class="toggleable" style="display:none;">';
			        
			         if ($row['menu_type'] == "Sub Menu"){

				               echo '<td style="color:#3333CC; font-weight:bold;">'.$ind.'>'.$row['menu_name']." (".$row['menu_type'].")".'</td>';
				               }else{
				               echo '<td style="color:green; font-weight:bold;">'.$ind.'>'.$row['menu_name']." (".$row['menu_type'].")".'</td>';
				               }
                   
                   if ($row['menu_type'] == "Sub Menu"){
                     echo '<td></td>';
   	                 echo '<td align="center"><input type="checkbox" name="menucdac[]" id= "mmenuacid" value="'.$row['menu_code'].'" />'.'</td>';
   	                 echo '<td></td><td></td><td></td><td></td>';

                   }else{
                     echo '<td></td>';
                     echo '<td align="center"><input type="checkbox" name="menucdac[]" id= "mmenuacid" value="'.$row['menu_code'].'" />'.'</td>';
                     echo '<td align="center"><input type="checkbox" name="menucdad[]" id= "mmenuadid" value="'.$row['menu_code'].'" onClick="checkac(this,'.$tabid.')"/>'.'</td>';
                     echo '<td align="center"><input type="checkbox" name="menucdup[]" id= "mmenuupid" value="'.$row['menu_code'].'" />'.'</td>';
                     echo '<td align="center"><input type="checkbox" name="menucdvi[]" id= "mmenuviid" value="'.$row['menu_code'].'" />'.'</td>';
                     echo '<td align="center"><input type="checkbox" name="menucdde[]" id= "mmenudeid" value="'.$row['menu_code'].'" />'.'</td>';
                   } 
                   echo '</tr>';
					// call this function again to display this 
    			    // child's children 
    				    display_childrenpro($row['menu_code'], $level+1, $tabid); 
    				} 
				} 
            ?>
	   </form>	
	   </div>
	<div class="spacer"></div>
</body>
</html>
