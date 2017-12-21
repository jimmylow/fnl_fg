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
    
    if ($_POST['Submit'] == "Save") {
     $menucd   = $_POST['menucd'];
     $menuact = $_POST['selactive'];
     $menunm = $_POST['menudname'];
     $menudesc = $_POST['menude'];
     $menutyp= $_POST['selmenutype'];
     $menupath = $_POST['menupath'];
     $menuseq  = $_POST['menuseq'];
     $menuparent  = $_POST['selmenupar'];
       
     if ($menucd <> "") {
      
      	$var_sql = " SELECT count(*) as cnt from menud ";
        $var_sql .= " WHERE menu_code = '$menucd'";

      	$query_id = mysql_query($var_sql) or die ("Cant Check Menu Code");
      	$res_id = mysql_fetch_object($query_id);

      	if ($res_id->cnt > 0 ) {
	  	   $backloc = "../admin_set/menu_set.php?stat=3&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
      	}else {
      	
      	  if ($menuseq <> "") {
      	 
      	   $vartoday = date("Y-m-d H:i:s");
      	   $sql = "INSERT INTO menud values 
      	          ('$menunm', '$menudesc', '$menuseq','$menupath',
      	           '$menutyp','$menucd','$menuparent','$menuact',
      	           '$var_loginid', '$vartoday')";
     		 mysql_query($sql); 
     		 
     		 $backloc = "../admin_set/menu_set.php?stat=1&menucd=".$var_menucode;
          	 echo "<script>";
           	 echo 'location.replace("'.$backloc.'")';
           	 echo "</script>";     	 
          }else{
     	   $backloc = "../admin_set/menu_set.php?stat=5&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
     	  }
	  	}
     }else{
       $backloc = "../admin_set/menu_set.php?stat=4&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";     }
    }
       
    if ($_POST['Submit'] == "Deactive") {
     if(!empty($_POST['menucd']) && is_array($_POST['menucd'])) 
     {
           $menumoby= $var_loginid;
           $menumoon= date("Y-m-d H:i:s");
           foreach($_POST['menucd'] as $value ) {
		    $sql = "Update menud set menu_stat ='DEACTIVE',";
            $sql .= " modified_by='$menumoby',";
            $sql .= " modified_on='$menumoon' WHERE menu_code ='".$value."'";
 
		 	mysql_query($sql); 
		   }
		   $backloc = "../admin_set/menu_set.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";       }      
    }
    
     if ($_POST['Submit'] == "Active") {
     if(!empty($_POST['menucd']) && is_array($_POST['menucd'])) 
     {
           $menumoby= $var_loginid;
           $menumoon= date("Y-m-d H:i:s");
           foreach($_POST['menucd'] as $value ) {
		    $sql = "Update menud set menu_stat ='ACTIVE',";
            $sql .= " modified_by='$menumoby',";
            $sql .= " modified_on='$menumoon' WHERE menu_code ='".$value."'";
 
		 	mysql_query($sql); 
		   }
		   $backloc = "../admin_set/menu_set.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
       }      
    }
    
     if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['menucd']) && is_array($_POST['menucd'])) 
     {
           $menumoby= $var_loginid;
           $menumoon= date("Y-m-d H:i:s");
           foreach($_POST['menucd'] as $value ) {
		    $sql = "Delete from menud ";
            $sql .= " WHERE menu_code ='".$value."'";
 
		 	mysql_query($sql); 
		   }
		   $backloc = "../admin_set/menu_set.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
       }      
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
    
   		$here = getcwd();
       // Redirect browser
        $fname = "clr_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_server.":8080/birt-viewer/frameset?__report=clr_rpt.rptdesign";
        $dest .= urlencode(realpath($fname));
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
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
			"sPaginationType": "full_numbers"
			
		} )
		.columnFilter({sPlaceHolder: "head:after",
		aoColumns: [ 
					 null,	
					 { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     null,
				     null,
				     null,
				     null,
				     null,
				     null,
				     null
				     ]
		});

} );
			

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

jQuery(function($) {
  
    $("tr :checkbox").live("click", function() {
        $(this).closest("tr").css("background-color", this.checked ? "#FFCC33" : "");
    });
  
});

function chkInt(pvalue)
{
  if(pvalue !== ""){  
   if((parseFloat(pvalue) == parseInt(pvalue)) && !isNaN(pvalue)){
      document.getElementById("msgint").innerHTML="";

      return true;
   } else {
      alert('This Is Not Integer; Sequence No Should In Integer Only'); 
      return false;
   }   
  }else{
   document.getElementById("msgint").innerHTML="This Seq number Cannot Be Black";

  }		
}	

function AjaxFunction(menucd)
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
	
	var url="aja_chk_menucd.php";
	
	url=url+"?menucd="+menucd;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
	httpxml.send(null);
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>-->

<body OnLoad="document.InpMenuMas.menucd.focus();">
<?php include("../topbarm.php"); ?> 
<div class ="contentc">

	<fieldset name="Group1" style=" width: 911px;" class="style2">
	 <legend class="title">MENU SETTING</legend>
	  <br>
	  <fieldset name="Group1" style="height:300px">
	  <form name="InpMenuMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 800px;">
		<table style="width: 884px">
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 134px" class="tdlabel">Menu Code</td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="menucd" id ="menucdid" type="text" maxlength="10" onchange ="upperCase(this.id)" style="width: 95px" onBlur="AjaxFunction(this.value);">
			</td>
			<td>
			</td>
		    <td style="width: 224px" class="tdlabel">Status</td>
	  	    <td>:</td>
	  	    <td style="width: 97px">
			   <select name="selactive" style="width: 125px">
			    <option>ACTIVE</option>
			    <option>DEACTIVATE</option>
			   </select>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td>
	  	    </td> 
	  	    <td style="width: 134px" class="tdlabel"></td>
	  	    <td>
	  	    </td>
	  	    <td><div id="msgcd"></div></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 134px" class="tdlabel">Menu Name</td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="menudname" id ="menunmid" type="text" maxlength="100" style="width: 417px">
			</td>
			<td>
			</td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	    <td style="height: 28px">
	  	    </td>
	  	    <td style="width: 134px; height: 28px;" class="tdlabel">Description</td>
	  	    <td style="height: 28px">:</td>
	  	    <td style="width: 431px; height: 28px;">
			<input class="inputtxt" name="menude" id ="menudeid" type="text" maxlength="100" style="width: 417px">
			</td>
			<td style="height: 28px">
			</td>
		  </tr>
		  <tr><td></td></tr>
		  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 134px" class="tdlabel">Menu Type</td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			   <select name="selmenutype" style="width: 125px">
			    <option>Main Menu</option>
			    <option>Sub Menu</option>
			    <option>Program</option>
			   </select>
            </td>
			<td>
			</td>
		  </tr>
		  <tr><td></td></tr>
		   <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 134px" class="tdlabel">Menu Path</td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="menupath" id ="menupathid" type=""text" maxlength="200" style="width: 417px"></td>
			<td>
			</td>
		    <td></td>
	  	    <td></td>
	  	    <td>
			</td>
		   </tr>
		  <tr><td></td></tr>
		  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 134px" class="tdlabel">Menu #</td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="menuseq" id ="menuseqid" type="text" maxlength="5" style="width: 79px" onBlur="chkInt(this.value);">
			</td>
			<td>
			</td>
		  </tr>
		  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 134px"></td>
	  	    <td></td>
	  	    <td><div font color="red" id="msgint"></div></td>
			<td></td>
		  </tr>
		   <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 134px" class="tdlabel">Parent </td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			   <select name="selmenupar" style="width: 417px">
			    <?php
                   $sql = "select menu_code, menu_name from menud ";
                   $sql .= " WHERE menu_type in ('Main Menu','Sub Menu')";
                   $sql .= " ORDER BY menu_name ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =60 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['menu_code'].'">'.$row['menu_name'].'</option>';
				 	 } 
				   } 
	            ?>	
			   </select>
			</td>
			<td>
			</td>
		  </tr>
		 		   <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 134px"></td>
	  	    <td></td>
	  	    <td></td>
			<td></td>
		  </tr>
		  <tr>
	  	   <td colspan="8">
	  	   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  	  <?php
	  	   include("../Setting/btnsave.php");
	  	   ?>
	  	   </td>
	  	   </tr>
	  	   <tr>
	  	     <td></td>
	  	     <td></td>
	  	     <td></td>
	  	              <td style="width: 505px" colspan="7"><span style="color:#FF0000">Message :</span>
            <?php
			  
			  if (isset($var_stat)){
			    switch ($var_stat)
				{
				case 1:
  					echo("<span>Success Process</span>");
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
				default:
  					echo "";
				}
			  }	
			?>
           </td>
	  	  </tr>

	  	</table>
	   </form>	
	   </fieldset>
	    <br/><br/>
	  	<?php
				$sql = "SELECT menu_code, menu_name, menu_type, menu_seq, menu_stat ";
				$sql .= " FROM menud";
    		    $sql .= " ORDER BY menu_code";  
			    $rs_result = mysql_query($sql); 
        ?>
        <form name="LstColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		 <table>
		 <tr>
             <td style="width: 900px; height: 38px;" align="right">
             
			  <?php
    	  	   include("../Setting/btnprint.php");
			   $msgdel = "Are You Sure Active Selected Menu?";
    	  	   include("../Setting/btnactive.php"); 
			   $msgdel = "Are You Sure Deactive Selected Menu?";
    	  	   include("../Setting/btndeactive.php"); 
		       $msgdel = "Are You Sure Delete Selected Menu?";
    	  	   include("../Setting/btndelete.php"); 
		      ?>
			</td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" id="example"  class="display" width="100%">
         <thead>
         	<tr>
         	 <th></th>
         	 <th>Menu Code</th>
         	 <th>Name</th>
         	 <th>Type</th>
         	 <th>Menu Sequence</th>
         	 <th>Active</th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
	       </tr>

         	<tr>
         	 <th class="tabheader" style="width: 27px">#</th>
         	 <th class="tabheader" style="width: 94px">Menu Code</th>
         	 <th class="tabheader" style="width: 264px">Name</th>
         	 <th class="tabheader" style="width: 144px">Type</th>
         	 <th class="tabheader" style="width: 141px">Menu Sequence</th>
         	 <th class="tabheader" style="width: 37px">Active</th>
         	 <th class="tabheader" style="width: 47px">View Detail</th>
         	 <th class="tabheader" style="width: 41px">Update</th>
         	 <th class="tabheader" style="width: 37px">Status</th>
         	 <th class="tabheader" style="width: 37px">Delete</th>
	       </tr>
	      </thead>
		 <tbody>
		 <?php 
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			
			$urlpop = 'upd_menu_set.php';
			$urlvm = 'vm_menu_set.php';
			echo '<tr>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$row['menu_code'].'</td>';
            echo '<td>'.$row['menu_name'].'</td>';
            echo '<td>'.$row['menu_type'].'</td>';
            echo '<td>'.$row['menu_seq'].'</td>';
            echo '<td>'.$row['menu_stat'].'</td>';
			
			if ($var_accvie == 0){
            echo '<td><a href="#">[VIEW]</a>';'</td>';
            }else{
            echo '<td><a href="'.$urlvm.'?menudcd='.$row['menu_code'].'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            }
            if ($var_accupd == 0){
            echo '<td><a href="#">[EDIT]</a>';'</td>';
            }else{
            echo '<td><a href="'.$urlpop.'?menudcd='.$row['menu_code'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            }
           			
			echo '<td><input type="checkbox" name="menucd[]" value="'.$row['menu_code'].'" />'.'</td>';
            echo '<td><input type="checkbox" name="menucd[]" value="'.$row['menu_code'].'" />'.'</td>';

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
