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
      $var_selmth   = $_GET['m'];
      $var_selyr   = $_GET['y'];
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }    
   
    if ($_POST['Submit'] == "Update") {
    	$vpmth = $_POST['selmth'];
		$vpyr = $_POST['selyr'];
	
            
		if ($vpmth  <> ""){
		
			if(!empty($_POST['currcd']) && is_array($_POST['currcd'])) 
			{	
				$sql = "delete from curr_xrate where xmth ='$vpmth' And xyr = '$vpyr'";
				mysql_query($sql) or die("Cant Query Exchange Rate Table;".mysql_error());
					
				// Array does not have duplicates
				 foreach($_POST['currcd'] as $row=>$curr ) 
		 		 {
					$currx = mysql_real_escape_string($curr);
																		
					$xrate = $_POST['xrate'][$row];
					$brate = $_POST['brate'][$row];
					if ($xrate == ""){$xrate = 0;}
					if ($brate == ""){$brate = 0;}
									
					if ($currx <> ""){
						$sql = "INSERT INTO curr_xrate values 
							('$curr', '$vpmth', '$vpyr', '$xrate', '$brate', '$var_loginid', CURDATE(), '$var_loginid', CURDATE())";
						mysql_query($sql) or die(mysql_error());
					}			
         		}	
			}
			
			$backloc = "../main_mas/m_xrate_mas.php?menucd=".$var_menucode;
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

function addRow(tableID){

	var table = document.getElementById(tableID);
	var rowCount = table.rows.length; 
	var row = table.insertRow(rowCount);             
	var colCount = table.rows[0].cells.length;             
	
	for(var i = 0; i < colCount; i++) {                 
		var newcell = row.insertCell(i);                 
		newcell.innerHTML = table.rows[rowCount-1].cells[i].innerHTML;
		newcell.id = rowCount;
     
		switch(newcell.childNodes[0].type) {
		//case "hidden":                            
			//newcell.childNodes[0].value = "";
           // newcell.childNodes[0].id = rowCount;				
			//break; 
		case "text": 
			switch(i){
		    case 1:
		        newcell.childNodes[0].value = "";
                newcell.childNodes[0].id = 'xrateid'+rowCount;	   
               	break;
		    case 2:
		      	newcell.childNodes[0].value = "";
                newcell.childNodes[0].id = 'brateid'+rowCount;	
                break;
		    }    		             
		case "checkbox":                            
			newcell.childNodes[0].checked = false; 
            //newcell.childNodes[0].id = rowCount;			
			break;                    
		case "select-one":                            
			newcell.childNodes[0].selectedIndex = 0;
			newcell.childNodes[0].options[0] = new Option('', ''); 
            newcell.childNodes[0].id = 'k'+rowCount;			
			break; 
       	}            
	}
 
}

function deleteRow(tableID) {
	try {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
         
        if (rowCount > 2){
          table.deleteRow(rowCount - 1);
        }else{
          alert ("No More Row To Remove");
        }
	}catch(e) {
		alert(e);
	}
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
	var table = document.getElementById('dataTable');
	var rowCount = table.rows.length; 
	
	var mylist = new Array();	
	for(var i = 1; i <= rowCount; i++) { 
	    var idr = "k"+i;            
		var e = document.getElementById(idr).value;
        //var vprocdbuy = e.options[e.selectedIndex].value;		 
		if (e != ""){
		
			    mylist.push(e); 
		}  
	
	}
     
	mylist.sort();
	var last = mylist[0];

	for (var i=1; i <= mylist.length; i++) {
		if (mylist[i] == last){ 
			alert ("Duplicate Currency Code Found; " + last);
			 return false;
		}	
		last = mylist[i];
	}
	
	
	var table = document.getElementById('dataTable');
	var rowCount = table.rows.length; 
		
	var mylist = new Array();	
	for(var i = 1; i < rowCount; i++) { 
	    var k = "xrateid"+i;
	    var b = "brateid"+i;
	            
		var e = document.getElementById(i);
        var vprocdbuy = e.options[e.selectedIndex].value;
     
		if (vprocdbuy != ""){
			if (document.getElementById(k).value == ""){

				alert ("Currency Sell Rate Cannot Be Blank");
				document.getElementById(k).focus();
				return false;
			}   
			if (document.getElementById(b).value == ""){

				alert ("Currency Buy Rate Cannot Be Blank");
				document.getElementById(b).focus();
				return false;
			}   
		}   
	}

}

function getdecpoi(vval, vvid)
{
	if(isNaN(vval)){
    	alert('Please Enter a valid number for Currency Rate:' + vval);
    	vval = 0;
    	document.getElementById(vvid).focus();
    }
    
    if(vval <= 0) {
    	alert('Not Accept Negative Value Or Zero:' + vval);
    	document.getElementById(vvid).focus();
    }
			
	document.getElementById(vvid).value = parseFloat(vval).toFixed(4);
							
}

function chkDupRec(){
	var x = document.forms["InpCurrMas"]["selxmth"].value;
	var y = document.forms["InpCurrMas"]["selxyr"].value;
	
	var strURL="aja_chk_xraterec.php?mth="+x+"&yr="+y;

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
					if (req.responseText == 1){
						document.getElementById("msgcd").innerHTML = "<font color=red>This Exchange Month & Year ALready Have A Record.</font>";
					}
				} 
			}
		}	 
	}
	req.open("GET", strURL, true);
	req.send(null);

}

</script>
</head>

 
<body>
  <?php include("../topbarm.php"); ?> 
   <!--<?php include("../sidebarm.php"); ?>-->
   
  	<div class="contentc" style="width: 621px; height: 531px;">
	<fieldset name="Group1" style=" width: 424px; height: 515px;" class="style2">
	 <legend class="title">CURRENCY EXCHANGE RATE</legend>
	  <form name="InpCurrMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 405px;" onSubmit= "return chkSubmit()">
	  	<br>
		<table style="width: 422px">
	  	  <tr>
	  	    <td style="width: 133px" class="tdlabel">Exchange Month</td>
	  	    <td style="width: 15px">:</td>
	  	    <td>
			<input class="inputtxt" name="selmth" id ="selmth" type="text" readonly="readonly" style="width: 71px;" value="<?php echo $var_selmth; ?>" >
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 133px" class="tdlabel"></td>
	  	    <td style="width: 15px;"></td>
	  	    <td></td>
	   	  </tr> 
	   	   <tr>
	  	    <td style="width: 133px" class="tdlabel">Exchange Year</td>
	  	    <td style="width: 15px">:</td>
	  	    <td>
			<input class="inputtxt" name="selyr" id ="selyr" type="text" readonly="readonly" style="width: 71px;" value="<?php echo $var_selyr; ?>" >		
			</td>
	  	  </tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td></td>
	  	  	<td><div id="msgcd"></div></td>
	  	  </tr>  
	  	  </table>
	  	  <br>
	  	  <table id="dataTable" style="width: 316px" border="1px solid black;" class="general-table">
	  	     <thead>
	  	     	<tr>
	  	        	<th class="tabheader" style="width: 164px;">Currency Code </th>
          			<th class="tabheader" style="width: 83px;">Sell Rate</th>
          			<th class="tabheader" style="width: 83px;">Buy Rate</th>
			 	</tr>
         	 </thead>
         	 <?php
         	 	$i=1;
         	 	$sql = "SELECT curr_code, sellrate, buyrate ";
				$sql .= " FROM curr_xrate";
				$sql .= " Where xmth= '$var_selmth' And xyr = '$var_selyr'";
   		    	$sql .= " ORDER BY curr_code";  
		    	$rs_result = mysql_query($sql) or die(mysql_error()); 

         	 	while ($row = mysql_fetch_assoc($rs_result)){
         	 	
         	 		$sql1 = "select currdesc from currency_master ";
        			$sql1 .= " where currcode ='".$row['curr_code']."'";
        			$sql_result1 = mysql_query($sql1);
        			$row1 = mysql_fetch_array($sql_result1);
        			$currdesc = $row1[0];
         	 	 
         	  		echo '<tr>';
         	  		echo '<td align="center">';
         	  			echo '<select name="currcd[]" style="width: 200px" id="k'.$i.'">';
         	  			
         	  			echo "<option value=".$row['curr_code'].">".$row['curr_code']." | ".$currdesc."</option>"; 
                  echo "<option></option>";  
			       		
			       		$sqlq = "select currcode, currdesc from currency_master ORDER BY currcode";
             		$sql_resultq = mysql_query($sqlq) or die ("Err 1 : ".mysql_error());
				   		
				   		if(mysql_num_rows($sql_resultq)) 
				   		{
				   	 		while($rowq = mysql_fetch_assoc($sql_resultq)) 
				     		{ 
					  			echo '<option value="'.$rowq['currcode'].'">'.$rowq['currcode']." | ".$rowq['currdesc'].'</option>';
				 	 		} 
				   		} 
	            		echo '</select>';
              		echo '</td>';
         	               
              		echo '<td style="width: 69px" align="center">';
              		echo '<input class="inputtxt" name="xrate[]" id ="xrateid'.$i.'" type="text" style="width: 83px;" onchange="getdecpoi(this.value,this.id)" value="'.$row['sellrate'].'">';
              		echo '</td>';
              		
              		echo '<td style="width: 69px" align="center">';
              		echo '<input class="inputtxt" name="brate[]" id ="brateid'.$i.'" type="text" style="width: 83px;" onchange="getdecpoi(this.value,this.id)" value="'.$row['buyrate'].'">';
              		echo '</td>';

             		echo '</tr>';
             		$i++;
               }
             ?>
           </table>
			<a href="javascript:addRow('dataTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row">Add Row</span></a>
			<a href="javascript:deleteRow('dataTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>
			<br>
	  	  	&nbsp;<table style="width: 424px">
	  	  		<tr>
	  	   			<td align="center">
	  	   		
	  	   			<?php
	  	   				$locatr = "m_xrate_mas.php?menucd=".$var_menucode;
				 		echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';

	  	   				include("../Setting/btnupdate.php");
	  	   			?>
	  	   			</td>
	  	  		</tr>
	  		</table>
	   </form>	
	   </fieldset>
     </div>
     <div class="spacer"></div>
</body>

</html>
