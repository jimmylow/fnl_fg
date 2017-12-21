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
     $spvcd   = mysql_real_escape_string($_POST['spvcd']);
     $spvname = mysql_real_escape_string($_POST['spvname']);
     //$ctrcd   = mysql_real_escape_string($_POST['ctrcd']);
     $mktcd   = mysql_real_escape_string($_POST['mktcd']); 
     $comm    = $_POST['comm'];    
     
     if ($spvcd <> "") {

      $var_sql = " SELECT count(*) as cnt from supervisor_master ";
      $var_sql .= " WHERE supervisor_code = '$spvcd'";

      $query_id = mysql_query($var_sql) or die ("Cant Check Supervisor Code ".mysql_error());
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	     $backloc = "../main_mas/supervisor_mas.php?stat=3&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";       
      }else {
       
         $sql = "INSERT INTO supervisor_master values 
                ('$spvcd', '$spvname', '$mktcd', '$var_loginid', CURDATE(), '$var_loginid', CURDATE(), '$comm')";
                           
     	 mysql_query($sql); 
     	 $backloc = "../main_mas/supervisor_mas.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";       } 
     }else{
       $backloc = "../main_mas/supervisor_mas.php?stat=4&menucd=".$var_menucode;
       echo "<script>";
       echo 'location.replace("'.$backloc.'")';
       echo "</script>";      
     }
    }
       
    if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['spvcd']) && is_array($_POST['spvcd'])) 
     {
        
           foreach($_POST['spvcd'] as $value ) {
		    $sql = "DELETE FROM supervisor_master WHERE supervisor_code ='".$value."'"; 
		 	mysql_query($sql); 
		   }
		   $backloc = "../main_mas/supervisor_mas.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";        
     }      
    }

	if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
    
        $fname = "supervisor_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=800,width=1000,left=200,top=10');</script>";
        $backloc = "../main_mas/supervisor_mas.php?menucd=".$var_menucode;
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
thead th input { width: 90% }
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

function AjaxFunction(uomcd)
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
	
	var url="aja_chk_supervisor.php";
	
	url=url+"?currcd="+uomcd;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",encodeURI(url),true);
	httpxml.send(null);
}

function chkSubmit (getdata) {
	if (document.getElementById("spvcdid").value == "") {
      	alert ("Please fill in the Supervisor Code to Continue");
      	document.InpColMas.spvcdid.focus();
      	return false;
   	}
     	
	if (document.getElementById("spvdeid").value == "") {
      	alert ("Please fill in Supervisor Name to Continue");
      	document.InpColMas.spvdeid.focus();
      	return false;
   	}
}	
</script>
</head>

 
  <!--<?php include("../sidebarm.php"); ?> -->
<body onload="document.InpColMas.spvcd.focus()">
  <?php include("../topbarm.php"); ?>
  <div class="contentc">
	<fieldset name="Group1" style="width: 887px;" class="style2">
	 <legend class="title">SUPERVISOR MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 760px; height: 168px">
	  <form name="InpColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 696px;" onSubmit= "return chkSubmit(this)">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Supervisor Code</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="spvcd" id ="spvcdid" type="text" maxlength="15" size = "17" onchange ="upperCase(this.id)" onBlur="AjaxFunction(this.value);">
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
	  	    <td style="width: 138px" class="tdlabel">Supervisor Name</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="spvname" id ="spvdeid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 354px">
			</td>
	  	  </tr>  
	  	  <tr>
	  	    <td>
	  	    </td> 
	  	    <td style="width: 138px" class="tdlabel"></td>
	   	  </tr> 
	   	  <!-- <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Customer Code</td>
	  	    <td>:</td>
	  	    <td>
		   <select name="ctrcd" id ="ctrcdid" >   -->

       <?php
           /*
         $sql = "select custno, name from customer_master";
         $sql .= " where status = 'A'";
         $sql .= " order by custno";
         
         $tmp = mysql_query($sql) or die ("Cant get customer : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['custno']."'>".$row['custno']." - ".$row['name']."</option>";
           
           }
          
         }  */
       ?>
		 <!--  </select>

			</td>
	  	  </tr>  -->
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Marketing Code</td>
	  	    <td>:</td>
	  	    <td>
		   <select name="mktcd" id ="mktcdid" >

       <?php
         
         $sql = "select mkt_code, mkt_name from marketing_master";
         $sql .= " where 1";
         $sql .= " order by mkt_code";
         
         $tmp = mysql_query($sql) or die ("Cant get marketing : ".mysql_error());
         
         if(mysql_numrows($tmp) > 0) {
           while ($row = mysql_fetch_array($tmp)) {
             echo "<option value = '".$row['mkt_code']."'>".$row['mkt_code']." - ".$row['mkt_name']."</option>";
           
           }
          
         }
       ?>
		   </select>

			</td>
	  	  </tr> 
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Commission (%)</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="comm" id ="commid" type="text" maxlength="15" size = "17">
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
	  	   <td style="width: 505px"><span style="color:#FF0000">Message :</span>
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
        <form name="LstColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		 <table>
		 <tr>
		   <td style="width: 880px; height: 38px;" align="right">
		       <?php
		        include("../Setting/btnprint.php");
			    $msgdel = "Are You Sure Delete Selected Supervisor Code?";
			    include("../Setting/btndelete.php");
    	
		      ?>
              
		   </td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
         	<tr>
         	 <th></th>
         	 <th style="width: 114px">Supervisor Code</th>
         	 <th style="width: 223px">Supervisor Name</th>
         	 <th style="width: 140px">Marketing Code</th>
         	 <th style="width: 140px">Commission (%)</th>           
         	 <!-- <th>Modified By</th>
         	 <th style="width: 99px">Modified On</th> -->
         	 <th></th>
         	 <th></th>
         	</tr>
         	<tr>
         	 <th class="tabheader" style="width: 27px">#</th>
         	 <th class="tabheader" style="width: 114px">Supervisor Code</th>
         	 <th class="tabheader" style="width: 223px">Supervisor Name</th>
         	 <th class="tabheader" style="width: 140px">Marketing Code</th>
         	 <th class="tabheader" style="width: 140px">Commission (%)</th>           
         	 <!-- <th class="tabheader" style="width: 81px">Modified By</th>
         	 <th class="tabheader" style="width: 99px">Modified On</th> -->
         	 <th class="tabheader" style="width: 50px">Update</th>
         	 <th class="tabheader">Delete</th>
         	</tr>
         </thead>
         <tbody>
		 <?php 
		 	$sql = "SELECT supervisor_code, supervisor_name, mkt_code, comm ";
			$sql .= " FROM supervisor_master";
    		$sql .= " ORDER BY supervisor_code";  
			$rs_result = mysql_query($sql);
		 
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			
				//$showdte = date('d-m-Y', strtotime($row['modified_on']));
				$urlpop = 'upd_supervisor_mas.php?spvcd='.htmlentities($row['supervisor_code']).'&spvname='.htmlentities($row['supervisor_name']).'&comm='.htmlentities($row['comm']).'&mktcd='.htmlentities($row['mkt_code']).'&menucd='.$var_menucode;
				echo '<tr>';
            	echo '<td>'.$numi.'</td>';
            	echo '<td>'.$row['supervisor_code'].'</td>';
            	echo '<td>'.$row['supervisor_name'].'</td>';
            	echo '<td>'.$row['mkt_code'].'</td>';
            	echo '<td>'.$row['comm'].'</td>';              
            	//echo '<td>'.$row['modified_by'].'</td>';
            	//echo '<td>'.$showdte.'</td>';
			
			 	if ($var_accupd == 0){
            		echo '<td><a href="#">[EDIT]</a>';'</td>';
            	}else{
            		echo '<td><a href="'.$urlpop.'">[EDIT]</a>';'</td>';
            	}
            	
            	if ($var_accdel == 0){
            		echo '<td><input type="checkbox" DISABLED  name="spvcd[]" value="'.$row['supervisor_code'].'" />'.'</td>';
            	}else{
            		echo '<td><input type="checkbox" name="spvcd[]" value="'.$row['supervisor_code'].'" />'.'</td>';
            	} 
         
            	echo '</tr>';
            	$numi = $numi + 1;
			}
		 ?></tbody>
		 </table>
         </form>
	</fieldset>
    </div>
</body>

</html>
