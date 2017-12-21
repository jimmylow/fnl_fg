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
     	if(!empty($_POST['rmscd']) && is_array($_POST['rmscd'])) 
     	{
           foreach($_POST['rmscd'] as $key) {
                        
		     $sql = "UPDATE rawmat_subcode "; 
		     $sql .= "SET active_flag = 'DEACTIVE' "; 
		     $sql .= "WHERE rm_code ='".$key."'"; 
		     echo $sql; 
		 	 mysql_query($sql) or dir(mysql_error); 
		   }
		   $backloc = "../stck_mas/m_rm_subcode.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
    
	 if ($_POST['Submit'] == "Active") {
     	if(!empty($_POST['rmscd']) && is_array($_POST['rmscd'])) 
     	{
           foreach($_POST['rmscd'] as $key) {
		     $sql = "UPDATE rawmat_subcode "; 
		     $sql .= "SET active_flag = 'ACTIVE' "; 
		     $sql .= "WHERE rm_code ='".htmlentities($key)."'";  
		 	 mysql_query($sql); 
		   }
		   $backloc = "../stck_mas/m_rm_subcode.php?stat=1&menucd=".$var_menucode;
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
			"bProcessing": true,
			"bServerSide": true,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
			"bStateSave": true,
			"bFilter": true,
			"sAjaxSource": "msubcode_serpro.php",
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
    					{ "bSortable": false },
    					{ "bSortable": false },
    					{ "bSortable": false },
    					{ "bSortable": false }
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
				     { type: "text" },
				     { type: "text" },
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
	
	var url="aja_chk_supp.php";
	
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

function poptastic(url)
{
	var newwindow;
	newwindow=window.open(url,'Manage Item Picture','height=250,width=700,left=160,top=300');
	if (window.focus) {newwindow.focus()}
}	
</script>
</head>
<?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 
<body>
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 1200px;" class="style2">
	 <legend class="title">RAW MAT SUB CODE</legend>
	  <br>
	   
		 <form name="LstColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
         <table>
		 <tr>
		      
           <td style="width: 1209px; height: 38px;" align="left">
			   <?php
			   $locatr = "rawmat_subcode.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
    	  	   $msgdel = "Are You Sure Active Selected Rawmat Sub-Code?";
    	  	   include("../Setting/btnactive.php"); 
			   $msgdel = "Are You Sure Deactive Selected Rawmat Sub-Code?";
    	  	   include("../Setting/btndeactive.php"); 
    	  	   //include("../Setting/btnprint.php");
		       ?>
            </td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
         	<tr>
         	 <th></th>
         	 <th>Item Code</th>
         	 <th style="width: 91px">Density</th>
         	 <th style="width: 200px">Description</th>
         	 <th style="width: 100px">Colour</th>
         	 <th style="width: 51px">Cost <br>Price</th>
		 	 <th style="width: 53px">Onhand <br>Bal</th>
		 	 <th>Status</th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
         	</tr>

         	<tr>
         	 <th class="tabheader" style="width: 10px">#</th>
         	 <th class="tabheader" style="width: 100px">Item Code</th>
         	 <th class="tabheader" style="width: 91px" >Density</th>
         	 <th class="tabheader" style="width: 200px" >Description</th>
         	 <th class="tabheader" style="width: 100px" >Colour</th>
         	 <th class="tabheader" style="width: 51px" >Cost <br>Price</th>
		 	 <th class="tabheader" style="width: 53px" >Onhand <br>Bal</th>
		 	 <th class="tabheader" >Status</th>
         	 <th class="tabheader" style="width: 40px" >View <br>Detail</th>
         	 <th class="tabheader" style="width: 40px" >Update</th>
         	 <th class="tabheader" style="width: 40px">Active/<br>Deactive</th>
         	 <th class="tabheader" style="width: 40px" >Picture</th>
         	</tr>
         </thead>
		 <tbody>
				<tr>
					<td colspan="11" class="dataTables_empty">Loading data from server</td>
				</tr>

		 </tbody>
		 </table>
		 
		 <div class="spacer"></div>
         </form>
	 
	</fieldset>
	</div>
</body>

</html>
