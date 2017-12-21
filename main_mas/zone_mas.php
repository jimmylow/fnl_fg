<?php

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];
	    
    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "../login.php"';
      echo "</script>";
    } else {
      $var_stat = $_GET['stat'];
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }
    
   
    if ($_POST['Submit'] == "Save") {
     $zonecd   = $_POST['zonecd'];
     $zonedesc = $_POST['zonede'];
     if ($zonecd <> "") {

      $var_sql = " SELECT count(*) as cnt from zone_master ";
      $var_sql .= " WHERE zone_code = '$zonecd'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	     $backloc = "../main_mas/zone_mas.php?stat=3&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>"; 
      }else {
      
         $sql = "INSERT INTO zone_master values 
                ('$zonecd', '$zonedesc', '$var_loginid', CURDATE(), '$var_loginid', CURDATE())";

     	 mysql_query($sql); 
     	 $backloc = "../main_mas/zone_mas.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";      
      } 
     }else{
       $backloc = "../main_mas/zone_mas.php?stat=4&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
     }
    }
       
    if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['zonecd']) && is_array($_POST['zonecd'])) 
     {
        
           foreach($_POST['zonecd'] as $value ) {
		    $sql = "DELETE FROM zone_master WHERE zone_code ='".$value."'"; 
		 	mysql_query($sql); 
		   }
		  $backloc = "../main_mas/zone_mas.php?stat=1&menucd=".$var_menucode;
          echo "<script>";
          echo 'location.replace("'.$backloc.'")';
          echo "</script>";
       }      
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
    
        // Redirect browser
        $fname = "zone_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $dest .= urlencode(realpath($fname));

        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=800,width=1000,left=200,top=10');</script>";
        $backloc = "../main_mas/zone_mas.php?menucd=".$var_menucode;
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

.style2 {
	margin-right: 0px;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript" charset="utf-8"> 
$(document).ready(function() {
	$('#example').dataTable( {
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
		"bStateSave": true,
		"bFilter": true,
		"sPaginationType": "full_numbers",
		"aoColumns": [
    					null,
    					null,
    					null,
    					null,
    					null
    				]

	})
	.columnFilter({sPlaceHolder: "head:after",

		aoColumns: [ 
					 null,	
					 { type: "text" },
				     { type: "text" },
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

function AjaxFunction(currcd)
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
	
	var url="aja_chk_zone.php";
	
	url=url+"?zonecd="+currcd;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",encodeURI(url),true);
	httpxml.send(null);
}	
</script>
</head>

  <!--<?php include("../sidebarm.php"); ?>-->
<body onload="document.InpCurrMas.zonecd.focus();">
  <?php include("../topbarm.php"); ?> 
  <div class="contentc">

	<fieldset name="Group1" style=" width: 933px;" class="style2">
	 <legend class="title">ZONE MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 910px; height: 128px">
	  <form name="InpCurrMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 907px;">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Zone ID</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="zonecd" id ="zoneid" type="text" maxlength="5" onchange ="upperCase(this.id)" style="width: 48px" onBlur="AjaxFunction(this.value);">
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td>
	  	    </td> 
	  	    <td style="width: 138px" class="tdlabel"></td>
	  	    <td>
	  	    </td>
	  	    <td><div id="msgcd"></div>
	  	    </td>
	   	  </tr> 
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Description</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="zonede" id ="zonedeid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 354px">
			</td>
	  	  </tr>  
	  	  <tr>
	  	   <td>
	  	   </td>
	  	   <td>
	  	   </td>
	  	   <td>
	  	   </td>
	  	   <td>
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
	  	<?php
				$sql = "SELECT zone_code, zone_desc ";
				$sql .= " FROM zone_master";
    		    $sql .= " ORDER BY zone_code";  
			    $rs_result = mysql_query($sql); 
        ?>
        <form name="LstColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		 <table>
		 <tr>
           <td style="width: 918px; height: 38px;" align="right">
             <?php
    	  	   include("../Setting/btnprint.php");
		       $msgdel = "Are You Sure Delete Selected Zone ID?";
    	  	   include("../Setting/btndelete.php"); 
		      ?>
		   </td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         	<thead>
         	<tr>
          		<th></th>
          		<th>Zone ID</th>
          		<th>Description</th>
          		<!-- <th>Modified By</th>
          		<th>Modified On</th> -->
          		<th></th>
          		<th></th>
         	</tr>
         	<tr>
          		<th class="tabheader" style="width: 27px">#</th>
          		<th class="tabheader" style="width: 138px">Zone ID</th>
          		<th class="tabheader" style="width: 303px">Description</th>
          		<!-- <th class="tabheader" style="width: 81px">Modified By</th>
          		<th class="tabheader" style="width: 80px">Modified On</th>  -->
          		<th class="tabheader" style="width: 50px">Update</th>
          		<th class="tabheader">Delete</th>
          		
         	</tr>

         	</thead>
         
		 <tbody>
		 <?php 
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			
			//$showdte = date('d-m-Y', strtotime($row['modified_on']));
			$urlpop = 'upd_zone_mas.php';
			echo '<tr>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$row['zone_code'].'</td>';
            echo '<td>'.$row['zone_desc'].'</td>';
            //echo '<td>'.$row['modified_by'].'</td>';
            //echo '<td>'.$showdte.'</td>';
            
            if ($var_accupd == 0){
            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
            }else{
            echo '<td align="center"><a href="'.$urlpop.'?zonecd='.$row['zone_code'].'&zonede='.$row['zone_desc'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            }
            if ($var_accdel == 0){
              echo '<td align="center"><input type="checkbox" DISABLED  name="zonecd[]" value="'.$rowq['zone_code'].'" />'.'</td>';
            }else{
              echo '<td align="center"><input type="checkbox" name="zonecd[]" value="'.$row['zone_code'].'" />'.'</td>';
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
