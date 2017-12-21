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
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
     
     	$frdte = date("Y-m-d", strtotime($_POST['rptofdte']));
     	$todte = date("Y-m-d", strtotime($_POST['rptotdte']));
     	$rptopt = $_POST['rpttyp'];
     	
        if ($rptopt == '2'){
			// Redirect browser
        	$fname = "opening_rpt.rptdesign&__title=myReport"; 
        	$dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&fd=".$frdte."&td=".$todte."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        	$dest .= urlencode(realpath($fname));
        }else{
        	$fname = "opening_rptgpro.rptdesign&__title=myReport"; 
        	$dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&fd=".$frdte."&td=".$todte."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        	$dest .= urlencode(realpath($fname));
        }	

        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../stk_rpt/inq_raw_open.php?menucd=".$var_menucode;
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
	margin-right: 8px;
}
</style>
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>

<script type="text/javascript" charset="utf-8"> 

function setup() {

	document.InpRawOpen.rptofdte.focus();
						
 	//Set up the date parsers
    var dateParser = new DateParser("dd-MM-yyyy");
      
	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "rptofdte");
	dateMask1.validationMessage = errorMessage;		
		
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "rptotdte");
	dateMask1.validationMessage = errorMessage;	
}

function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}		 	
		return xmlhttp;
}

function chkSubmit()
{
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	if (x==null || x=="")
	{
		alert("Opening From Date Must Not Be Blank");
		document.InpRawOpen.rptofdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptotdte"].value;
	if (x==null || x=="")
	{
		alert("Opening To Date Must Not Be Blank");
		document.InpRawOpen.rptotdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	var y=document.forms["InpRawOpen"]["rptotdte"].value;
	
    var fromdate = x.split('-');
        from_date = new Date();
        from_date.setFullYear(fromdate[2],fromdate[1]-1,fromdate[0]); 
    
    var todate = y.split('-');
        to_date = new Date();
        to_date.setFullYear(todate[2],todate[1]-1,todate[0]);
    if (from_date > to_date ) 
    {
        alert("Opening To Date Must Larger Then From Date");
		document.InpRawOpen.rptofdte.focus();
		return false;
    }
    
    
    //Check the list of opening got date from date & to date-------------------------------
	var flgchk = 1;	
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	var y=document.forms["InpRawOpen"]["rptotdte"].value;
    var strURL="aja_chk_opendt.php?fd="+x+"&td="+y;
	
	var req = getXMLHTTP();
    if (req)
	{
		req.onreadystatechange = function()
		{
			if (req.readyState == 4)
			{
				// only if "OK"
				if (req.status == 200)
				{
					if (req.responseText == 0)
					{
					   	flgchk = 0;
					   	alert ('No Data Exist For The Select From Date : '+x+ ' To Date '+y);
						return false;
					}else{
						//alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
						return false;
					}
				}
			}	 
		}
		
	req.open("GET", strURL, false);
	req.send(null);    	  
    }
    if (flgchk == 0){
	   return false;
	}
    //---------------------------------------------------------------------------------------------------
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 673px; height: 150px;" class="style2">
	 <legend class="title">OPENING REPORT</legend>
	  <br>
	  <form name="InpRawOpen" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 662px;">
		<table>
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 155px" class="tdlabel">From Opening Date</td>
	  	    <td>:</td> 
	  	    <td style="width: 134px">
				<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer">
			</td>
			<td style="width: 27px"></td>
			<td style="width: 161px">To Opening Date</td>
			<td>:</td>
			<td>
				<input class="inputtxt" name="rptotdte" id ="rptotdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptotdte','ddMMyyyy')" style="cursor:pointer">
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 155px" class="tdlabel">Group By Product Code</td>
	  	    <td>:</td> 
            <td style="width: 134px">
            <select name="rpttyp" id="rpttyp">
            	<option value="1">YES</option>
            	<option value="2">NO</option>
            </select>
            </td> 
	   	  </tr> 
	   	  <tr><td></td></tr>
	  	  <tr>
	  	   <td style="width: 181px" colspan="8" align="center">
	  	   
	  	   <?php
	  	   		$locatr = "../invt/m_opening_mas.php?menucd=".$var_menucode;			
				echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
	  	   		include("../Setting/btnprint.php");
	  	   ?>
	  	   </td>
	  	  </tr>
	  	   <tr><td></td></tr>

	  	</table>
	   </form>	
	  
	  
	
	
	</fieldset>
	 </div>
    <div class="spacer"></div>
</body>

</html>
