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
        
    if ($_POST['Submit'] == "Deactive") {
     if(!empty($_POST['countcd']) && is_array($_POST['countcd'])) 
     {
           $custmoby= $var_loginid;
           $custmoon= date("Y-m-d H:i:s");
           foreach($_POST['countcd'] as $value ) {
		    $sql = "Update counter set Status ='D',";
            $sql .= " modified_by='$custmoby',";
            $sql .= " modified_on='$custmoon' WHERE Counter ='".$value."'";
 
		 	mysql_query($sql); 
		   }
		   $backloc = "../main_mas/m_comm_mas.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }      
    }
    
     if ($_POST['Submit'] == "Active") {
     if(!empty($_POST['countcd']) && is_array($_POST['countcd'])) 
     {
           $custmoby= $var_loginid;
           $custmoon= date("Y-m-d H:i:s");
           foreach($_POST['countcd'] as $value ) {
		    $sql = "Update counter set Status ='A',";
            $sql .= " modified_by='$custmoby',";
            $sql .= " modified_on='$custmoon' WHERE Counter ='".$value."'";
 
		 	mysql_query($sql); 
		   }
		   $backloc = "../main_mas/m_comm_mas.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
       }      
    }


    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
            
        $fname = "comm_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $dest .= urlencode(realpath($fname));

        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
     	 $backloc = "../main_mas/m_comm_mas.php?stat=1&menucd=".$var_menucode;
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
    					null,
    					null,
    					null
    				]
			
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

function AjaxFunctioncd(suppcd)
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
	
	var url="aja_chk_comm.php";
	
	url=url+"?suppcdg="+suppcd;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
	httpxml.send(null);
}	

function AjaxFunction(email)
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
		document.getElementById("msg").innerHTML=httpxml.responseText;
	  }
    }
	
	var url="../Setting/email-ajax.php";
	
	url=url+"?email="+email;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
	httpxml.send(null);
}	
</script>
</head>
<?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 
<body>
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 1200px;" class="style2">
	 <legend class="title">COUNTER COMMISSION MASTER</legend>
	  <br>
	   
		 <form name="LstColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
         <table>
		 <tr>
		      
           <td style="width: 1209px; height: 38px;" align="left">
			   <?php
			   $locatr = "comm_mas.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
    	  	   //$msgdel = "Are You Sure Active Selected Commission Code?";
    	  	   //include("../Setting/btnactive.php"); 
			       //$msgdel = "Are You Sure Deactive Selected Commission Code?";
    	  	   //include("../Setting/btndeactive.php"); 
    	  	   include("../Setting/btnprint.php");
		       ?>
            </td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
         	<tr>
         	 <th style="width: 8px"></th>
         	 <th style="width: 68px">Counter</th>
         	 <th style="width: 225px">Name</th>
         	 <th style="width: 102px">Supervisor ID</th>
         	 <th style="width: 116px">B. Over Rate</th>
         	 <th style="width: 119px">B. Short Rate</th>
         	 <th style="width: 68px"></th>
         	 <th style="width: 47px"></th>
         	 <th></th>
         	</tr>

         	<tr>
         	 <th class="tabheader" style="width: 8px; height: 35px">#</th>
         	 <th class="tabheader" style="width: 68px; height: 35px;">Counter</th>
         	 <th class="tabheader" style="width: 225px; height: 35px;">Name</th>
         	 <th class="tabheader" style="width: 102px; height: 35px;">Supervisor ID</th>
         	 <th class="tabheader" style="width: 116px; height: 35px;">B. Over Rate</th>
         	 <th class="tabheader" style="width: 119px; height: 35px;">B. Short Rate</th>
         	 <th class="tabheader" style="width: 68px; height: 35px">View Detail</th>
         	 <th class="tabheader" style="height: 35px; width: 47px">Update</th>
         	 <th class="tabheader" style="height: 35px">Status</th>
         	</tr>
         </thead>
		 <tbody>
		 <?php 
		 	$sql = "SELECT Counter, Sup_ID, b_over, b_sort ";
			$sql .= " FROM counter";
   		$sql .= " ORDER BY Counter";  
          
        //echo $sql;  
		    $rs_result = mysql_query($sql) or die("Cant get counter : ".mysql_error()); 
		   			
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
      
        $sql = " select name from  customer_master ";
        $sql .= " where custno = '".$row['Counter']."'";
        
        $tmp = mysql_query($sql) or die ("Cant get cust name : ".mysql_error());
        
        if (mysql_numrows($tmp) > 0) {
           $res = mysql_fetch_object($tmp);
           $name = $res->name;
        }
      
			
			$urlpop = 'upd_comm_mas.php';
			$urlvm = 'vm_comm_mas.php';
			//if ($row['modified_on'] <> "" || $row['modified_on'] == "0000-00-00") { 
      //   $showdte = date('d-m-Y', strtotime($row['modified_on'])); }
      //else { $showdte = ""; }
			echo '<tr>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$row['Counter'].'</td>';
            echo '<td>'.$name.'</td>';
            echo '<td>'.$row['Sup_ID'].'</td>';
            echo '<td>'.$row['b_over'].'</td>';
            echo '<td>'.$row['b_sort'].'</td>';
            //echo '<td>'.$row['modified_by'].'</td>';
            //echo '<td>'.$showdte.'</td>';  
            //echo '<td>'.$row['Status'].'</td>';
			
			if ($var_accvie == 0){
            echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            }else{
            echo '<td align="center"><a href="'.$urlvm.'?countcd='.$row['Counter'].'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            }
			
			if ($var_accupd == 0){
            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
            }else{
            echo '<td align="center"><a href="'.$urlpop.'?countcd='.$row['Counter'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            }

            echo '<td align="center"><input type="checkbox" name="countcd[]" value="'.$row['Counter'].'" />'.'</td>';
                   
            
            echo '</tr>';
            $numi = $numi + 1;
			}
		 ?>
		 </tbody>
		 </table>
		 
		 <div class="spacer"></div>
         </form>
	 
	</fieldset>
	</div>
</body>

</html>
