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
    
    if ($_POST['Submit'] == "Generate") {
    
		$vminvoicedte = date('d-m-Y', strtotime($_POST['invoicedte']));
		$vmmthyr  = $_POST['samthyr'];
		$vmperiod = $_POST['speriod'];
		$vmfrmcd = $_POST['frmctr'];
		$vmtocd  = $_POST['toctr']; 
    $vmgst  = $_POST['gst'];   
        
    }    
    
    if ($_POST['Submit'] == "Save") {

    //phpinfo();
		$vminvoicedte = date('Y-m-d', strtotime($_POST['invoicedte']));
		$vmmthyr  = $_POST['samthyr'];
		$vmperiod = $_POST['speriod'];
		$vmfrmcd = $_POST['frmctr'];
		$vmtocd  = $_POST['toctr'];  
    $vmgst  = $_POST['gst']; 
		
		
		
		  $query  = "SELECT count(*) FROM cinvoicemas ";
		  $query .= " WHERE custcd='$vmfrmcd'";
		  $query .= " AND mthyr='$vmmthyr'";
		  $query .= " AND stat = 'A'";
		  //echo $query;
		  $result = mysql_query($query) or die(mysql_error());
		  $row2 = mysql_fetch_array($result);
		  $cnt = $row2[0];
				
		  if ($cnt > 0){
		  		$backloc = "../cons/m_invoice.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo "alert('Invoice Has Been Created For This Counter and Month');"; 
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
       
          }	
		
            
		if ($vmfrmcd <> "") {
          
      $sql = " select scustcd, sordno, less_type, less_amt from csalesmas";
      $sql .= " where smthyr = '$vmmthyr'";
      $sql .= " and speriod = '$vmperiod'";
      //$sql .= " and scustcd between '$vmfrmcd' and '$vmtocd'";
      $sql .= " and scustcd = '$vmfrmcd' ";
      $sql .= " and stat = 'A'";

      $sql .= " order by scustcd";
      
   
      $tmp = mysql_query($sql) or die ("Cant get cust : ".mysql_error());
    
      if(mysql_numrows($tmp) > 0) {
       while ($row = mysql_fetch_array($tmp)) {
         $var_ctr = $row['scustcd'];
         $var_lesstype = $row['less_type'];
         $var_lessamt = $row['less_amt'];    
         //echo 'kk - '. $row['sordno'];
                      
            /*----------------------------- Cash Bill details ------------------------------------ */
              $chk_invno_query = mysql_query("select count(*) from `ctrl_sysno` where `descrip` = 'CONSINV' and counter = 'HQ'; ", $db_link);

              $chk_invno_res = mysql_fetch_array($chk_invno_query) or die("cant Get Inv No Info ".mysql_error());
              
              if ($chk_invno_res[0] > 0 ) {
                  $get_invno_query = mysql_query("select noctrl from `ctrl_sysno` where `descrip` = 'CONSINV' and counter = 'HQ' ", $db_link);
                  
                  $get_invno_res = mysql_fetch_object($get_invno_query) or die("Cant Get Inv No 2 ".mysql_error()); 

                  $var_invno = vsprintf("%06d",$get_invno_res->noctrl+1); 
                  //$var_invno = $vmcust$var_invno; 
                  
 		          mysql_query("update `ctrl_sysno` set `noctrl` = `noctrl` + 1
                           where `descrip` = 'CONSINV'
                           and counter = 'HQ'", $db_link) 
                           or die("Cant Update Cash Bill Auto No ".mysql_error());              
               
                }  else { 

		          mysql_query("insert into `ctrl_sysno` 
                          (`descrip`, `counter`, `noctrl`)
                   values ('CONSINV', 'HQ', 1);",$db_link) or die("Cant Insert Into Cash Bill Auto No");

                   $var_invno = "0000000001";

                }  

            /*--------------------------- end Inv no details ---------------------------------- */
         //echo '</br>'. 'xx - '. $row['sordno'];   
         $sql2 = " select sptype from csalesdet";
         $sql2 .= " where sordno = '".$row['sordno']."'";
         $sql2 .= " and soldqty > 0";
         $sql2 .= " group by sptype ";
         $sql2 .= " order by sptype ";
         //echo '</br>'. $sql2;
      

         $tmp2 = mysql_query($sql2) or die ("cant get type : ".mysql_error());
         
         $var_rate = 0;
         if(mysql_numrows($tmp2) > 0) {
             while ($row2 = mysql_fetch_array($tmp2)) {	
                $var_type = $row2['sptype'];
                //echo '</br>'. 'kkk-'.$var_type;
                
                //add here---------------------------------------------------------------                
                switch ($var_type) {
                case "B" : $sql = "select best_rate from counter where counter = '". $var_ctr. "'"; 
                		   break;
                case "N" : $sql = "select norm_rate from counter where counter = '". $var_ctr. "'"; 
                           break;
                case "O" : $sql = "select offe_rate from counter where counter = '". $var_ctr. "'"; 
                           break;
                case "SBB" : $sql = "select spc_rate from counter where counter = '". $var_ctr. "'"; 
                           break;

                default : $var_rate = 0;
                }   
                			                
				
				$sql_result = mysql_query($sql);
				if ($sql_result <> FALSE)
				{
					$rowk = mysql_fetch_array($sql_result);
					$var_rate= $rowk[0];
				}
				//if ($var_rate=='' or $var_rate == ' ' or $var_rate == null)
				$var_rate = number_format($var_rate,2, '.',',');				
				if ($var_rate== NULL || $var_rate== '' || $var_rate== ' ' )

				{
					$var_rate=0.00;
				}

				                
                //add here---------------------------------------------------------------             
               
			     	$sql = "INSERT INTO cinvoicedet2 values 
						   ('$var_invno', '$var_type','$var_rate')";
						  // echo $sql. "</br>";
			        mysql_query($sql) or die ("Cant insert invoice2 : ".mysql_error());
              
             }
          }
 //echo '</br>'. 'zz - '. $row['sordno'];   

         $sql2 = " select sprocd, sprounipri, sptype, soldqty from csalesdet";
         $sql2 .= " where sordno = '".$row['sordno']."'";
         $sql2 .= " and soldqty > 0";
         $sql2 .= " order by sptype ";
//echo $sql2; //break;

         $tmp2 = mysql_query($sql2) or die ("cant get prod : ".mysql_error());
         
         if(mysql_numrows($tmp2) > 0) {
             while ($row2 = mysql_fetch_array($tmp2)) {			
                 $var_procd = $row2['sprocd'];
                 $var_upri = $row2['sprounipri'];
                 $var_type = $row2['sptype'];
                 $var_qty = $row2['soldqty'];
                 
                 $sql3 = "select exunit from product";
                 $sql3 .= " where productcode = '".$row2['sprocd']."'";
             
                 $tmp3 = mysql_query($sql3) or die ("cant get uom : ".mysql_error());
                 if(mysql_numrows($tmp3)>0) {
                    $rst3 = mysql_fetch_object($tmp3);
                    $var_uom = $rst3->exunit;
             
                  }      
             
             $var_amt = 0;    $var_exgstamt = 0;        
             $var_amt =  $var_upri *  $var_qty; 
             
             $var_gstrate = $vmgst + 100;
                          
             $var_exgstamt = $var_amt / $var_gstrate * 100;
             $var_exgstamt = round($var_exgstamt,3);  //case 12.99 / 106 * 100 = 12.2547; round to 12.26        
             $var_exgstamt = round($var_exgstamt,2);  // so need to round to 3 decimal then only 2 decimal
             
             //---- dun wan unit price to get GST uprice, only based on amount to get exclude GST amount
             //$var_exgstupri = 0;             
             //$var_exgstupri =  $var_upri / $var_gstrate * 100; 
             //$var_exgstupri = round($var_exgstupri,2);                              
      
			     	$sql = "INSERT INTO cinvoicedet1 values 
						   ('$var_invno', '$var_procd','$var_upri','$var_type','$var_qty', '$var_uom', '$var_exgstamt')";
			        mysql_query($sql) or die ("Cant insert 1 : ".mysql_error());
              
             }
          }   
      
      	if ($var_lessamt == NULL || $var_lessamt == '' || $var_lessamt == ' ' )
      	{
      		$var_lessamt = 0;
      	}
        $vartoday = date("Y-m-d H:i:s"); 
        
				$sql = "INSERT INTO cinvoicemas values 
						('$var_invno', '$vminvoicedte','$var_ctr','$vmmthyr','$vmperiod', '$var_lesstype', '$var_lessamt', 
						 '$var_loginid','$vartoday', '$var_loginid', '$vartoday', 'A', '$vmgst')";
				//echo $sql;
				mysql_query($sql) or die ("Cant insert mas : ".mysql_error());
				
       }
				
				$backloc = "../cons/m_invoice.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
       
       } else {
       
         echo "<script>";   
         echo "alert('No data to create invoice');"; 
         echo 'location.replace("'.$backloc.'")';
         echo "</script>"; 
 
         //echo "<script>";
         //echo 'location.replace("'.$backloc.'")';
         //echo "</script>";       
       
       }
              
      ///        
					
		}else{
			$backloc = "../cons/m_invoice.php?stat=4&menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 
		}  
    }  
  
 if ($vminvoicedte == "") { $vminvoicedte = date("t-m-Y"); }  
 if ($vmmthyr == "") { $vmmthyr = date("m/Y"); }  
 
 if ($vmgst == "") {
    $sql = " select gst from gst_master";
    $tmp = mysql_query($sql) or die ("Cant get gst : ".mysql_error());
    
    $gst = 0;
    if(mysql_numrows($tmp) > 0) {
        $rst = mysql_fetch_object($tmp);
        $vmgst = $rst->gst;
    }    
    if ($vmgst == "" || $vmgst == 0) {
       echo "GST AMOUNT IS 0. Please contact administrator";
    }  
  }
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">


<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";


.style2 {
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>


<script type="text/javascript"> 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function AjaxFunction(){

   var c = document.getElementById("frmctr").value;	
   var x = document.getElementById("samthyrid").value;	
   //alert(c);
   if (x != ""){	
   	var strURL="aja_chk_invoice.php?d="+x+"&c="+c;
   	//alert(strURL);
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
   	            //document.getElementById("msgcd").innerHTML = req.responseText;

   	        	if (req.responseText == 1){    	
					document.getElementById("msgcd").innerHTML = "<font color='red'>This Counter Has Created Invoice For This Month</font>";
					document.InpPO.samthyrid.focus();
					return false;
		    	}else{
		    		document.getElementById("msgcd").innerHTML = "";
		    	}
		    }else{
   	      		alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
	     	}
      }
     } 
   	}
   	req.open("GET", strURL, true);
   	req.send(null);
   }	
}


function setup() {

		document.InpPO.invoicedte.focus();
				
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
        //alert('xxx');
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "invoicedte");
		dateMask1.validationMessage = errorMessage;
 
}

function setup2() {

 		//Set up the date parsers
        var dateParser = new DateParser("MM/yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("MM/yyyy", "samthyr");
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

function validateForm()
{

  var x=document.forms["InpPO"]["frmctr"].value;
	if (x==null || x=="s")
	{
	alert("From Counter Cannot Be Blank");
	document.InpPO.frmctr.focus;
	return false;
	}
  
  var x=document.forms["InpPO"]["toctr"].value;
	if (x==null || x=="s")
	{
	alert("To Counter Cannot Be Blank");
	document.InpPO.toctr.focus;
	return false;
	}  

   var x=document.forms["InpPO"]["invoicedte"].value;     
	if (x==null || x=="")
	{
	alert("Invoice Date Must Not Be Blank");
	document.InpPO.invoicedte.focus;
	return false;
	}
 
  var x=document.forms["InpPO"]["samthyr"].value;           
	if (x==null || x=="")
	{
	alert("MM/YYYY Must Not Be Blank");
	document.InpPO.samthyr.focus;
	return false;
	} 
  
  var x=document.forms["InpPO"]["gst"].value;           
	if (x==null || x=="")
	{
	alert("GST Must Not Be Blank");
	document.InpPO.gst.focus;
	return false;
	}   
  
}

</script>
</head>
<body onload="setup(); setup2();">
  <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">INVOICE ENTRY</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 993px; ">
	   	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Counter</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<select name="frmctr" id="frmctr" style="width: 268px" >
			 <?php
              $sql = "select x.counter, y.name from counter x, customer_master y";
              $sql .= " where y.custno = x.counter";
              $sql .= " and prn_inv = 'Y'"; //only those counter need to print invoice
              $sql .= " ORDER BY x.counter ASC";
              $sql_result = mysql_query($sql);
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['counter'].'"';;
        if ($vmfrmcd == $row['counter']) { echo "selected"; }
        echo '>'.$row['counter']." | ".$row['name'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>
		     </td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Invoice Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="invoicedte" id ="invoicedte" type="text" style="width: 128px;" value="<?php echo $vminvoicedte; ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('invoicedte','ddMMyyyy')" style="cursor:pointer"></td>
		   </td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px">&nbsp;</td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">MM/YYYY</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="samthyr" id="samthyrid" type="text" maxlength="45" style="width: 100px;" value="<?php echo $vmmthyr; ?>" onBlur="AjaxFunction();"></td>		   
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Period</td>
		   <td>:</td>
		   <td style="width: 284px">
		   	<select name="speriod" id="speriodcd" >
			 <?php
				echo '<option value="1"';
        if ($vmperiod == '1') { echo "selected"; }
        echo '>1 | FIRST HALF MONTH</option>';
        
				echo '<option value="2"';
        if ($vmperiod == '2') { echo "selected"; }
        echo '>2 | SECOND HALF MONTH</option>';        
                    
	         ?>				   
	       </select>

		   </td>
		  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td colspan="5">
			<div id="msgcd"></div></td>		   
	  	  </tr>       
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">&nbsp;</td>
	  	   <td style="width: 13px">&nbsp;</td>
	  	   <td style="width: 201px">
			&nbsp;</td>		   
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   </td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">GST %</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="gst" id="gstid" type="text" maxlength="6" style="width: 60px;" value="<?php echo $vmgst; ?>" ></td>		   
        </td>         
		   <td style="width: 10px"></td>
		   <td style="width: 204px"></td>
		   <td></td>
		   <td style="width: 284px">
       <input type=submit name = "Submit" value="Generate" class="butsub" style="width: 90px; height: 32px" >       
       </td>
	  	  </tr>	  	  
	  	  </table>
         <br />
<?php

		echo '<table width="100%">';

    if ($_POST['Submit'] == "Generate") {
    
		$vminvoicedte = date('d-m-t', strtotime($_POST['invoicedte']));
		$vmmthyr  = $_POST['samthyr'];
		$vmperiod = $_POST['speriod'];
		$vmfrmcd = $_POST['frmctr'];
		$vmtocd  = $_POST['toctr']; 
    $vmgst  = $_POST['gst'];   
    
    /*
    $sql = " select gst from gst_master";
    $tmp = mysql_query($sql) or die ("Cant get gst : ".mysql_error());
    
    $gst = 0;
    if(mysql_numrows($tmp) > 0) {
        $rst = mysql_fetch_object($tmp);
        $gst = $rst->gst;
    }    
    if ($gst == "" || $gst == 0) {
       echo "GST AMOUNT IS 0. Please contact administrator";
    }  
      */
      
    $sql = " select scustcd, sordno, less_type, less_amt from csalesmas";
    $sql .= " where smthyr = '$vmmthyr'";
    $sql .= " and speriod = '$vmperiod'";
    //$sql .= " and scustcd between '$vmfrmcd' and '$vmtocd'";
    $sql .= " and scustcd = '$vmfrmcd'";
    $sql .= " and stat = 'A'";
    $sql .= " order by scustcd";

    
    $tmp = mysql_query($sql) or die ("Cant get cust : ".mysql_error());
    
    if(mysql_numrows($tmp) > 0) {
    
       echo '<tr>';
       echo '<th class="tabheader">Product</th>';
       echo '<th class="tabheader">Description</th>';       
       echo '<th class="tabheader" align="right">Qty</th>';
       echo '<th class="tabheader" align="right">UOM</th>';
       echo '<th class="tabheader" align="right">Qty(PC)</th>';                     
       echo '<th class="tabheader" align="right">U.Price</th>';
       echo '<th class="tabheader" align="right">Gross Amount Incl. '.$vmgst.' % GST</th>';
       //echo '<th class="tabheader" align="right">U.Price Excl. '.$vmgst.' % GST</th>';
       echo '<th class="tabheader" align="right">Gross Amount Excl. '.$vmgst.' % GST</th>';
       echo '<th class="tabheader" align="right">Discount Less(%)</th>';
       echo '<th class="tabheader" align="right">Less (RM)</th>'; 
       echo '<th class="tabheader" align="right">Total Excl. '.$vmgst.' % GST (RM)</th>';                   
       echo '</tr>';
                 
       while ($row = mysql_fetch_array($tmp)) {
         
         $sql2 = "select name from customer_master ";
         $sql2 .= " where custno = '".$row['scustcd']."'";
         
         $tmp2 = mysql_query($sql2) or die ("cant get name : ".mysql_error());
         
         $var_name = $row['scustcd'];
         
         if(mysql_numrows($tmp2) > 0) {
             $rst = mysql_fetch_object($tmp2);
             $var_name .= " | ".$rst->name;
             
          }            
          
         echo '<tr>';
         echo '<td colspan="7"><b>Counter : '.$var_name.'</b></td>';
         echo '</tr>';
         
         $var_currtype = "";   $var_prevtype = "";  $var_amt = 0;  $var_subamt = 0;  
         $var_lessamt = 0;  $var_namt = 0;   $var_tamt = 0;   $var_tlessamt = 0;   $var_tnamt = 0;
         $var_exgstamt = 0;  $var_subexgstamt = 0;    $var_texgstamt = 0;
         
         $sql2 = " select sprocd, sprounipri, sptype, soldqty from csalesdet";
         $sql2 .= " where sordno = '".$row['sordno']."'";
         $sql2 .= " and soldqty > 0";
         $sql2 .= " order by sptype, sprocd ";

         $tmp2 = mysql_query($sql2) or die ("cant get prod : ".mysql_error());
         
         if(mysql_numrows($tmp2) > 0) {
             while ($row2 = mysql_fetch_array($tmp2)) {            
             
             $var_uqty = 1;
             
             $product = $row2['sprocd'].'%';
             
             $sql3 = "select distinct description, exunit from product";
             $sql3 .= " where productcode like '$product'";
             //echo $sql3; echo "</br>";
             
             $tmp3 = mysql_query($sql3) or die ("cant get uom : ".mysql_error());
             if(mysql_numrows($tmp3)>0) {
               $rst3 = mysql_fetch_object($tmp3);
               $var_desc = $rst3->description;
               $var_uom = $rst3->exunit;
             
               $sql4 = " select uom_pack from prod_uommas";
               $sql4 .= " where uom_code = '".$var_uom."'";
               
               $tmp4 = mysql_query($sql4) or die ("cant get uom pack : ".mysql_error());
               if(mysql_numrows($tmp4) > 0) {
                 $rst4 = mysql_fetch_object($tmp4);
                 $var_uqty = $rst4->uom_pack;
               }
             } 
             
             //$var_totqty = $var_uqty * $row2['soldqty']; 
             $var_totqty = $row2['soldqty']; 			 
             $var_currtype = $row2['sptype'];             
              
             //echo "Amt : ".$var_amt." sub : ".$var_subamt;
              
             if($var_prevtype <> "" && $var_currtype <> $var_prevtype) {
             
               $sql3 = "select salestype_desc from salestype_master ";
               $sql3 .= " where salestype_code = '$var_prevtype'";
               
               //echo $sql3;
               $tmptype = mysql_query($sql3) or die ("Cant get type : ".mysql_error());
               $rsttype = mysql_fetch_object($tmptype);
               
               $var_rate = 0;
               /*
               switch ($row['less_type']) {
                case "1" : $var_rate = 0; $var_type = ""; $var_lessamt = 0; break;
                case "2" : $var_rate = $row['less_amt']; $var_type = "%";
                           $var_lessamt =  $var_subamt * $var_rate / 100;
                           break;
                case "3" : $var_rate = $row['less_amt']; $var_type = "RM";
                           $var_lessamt =  $var_subamt - $var_rate;
                           break;
                default : $var_rate = 0; $var_type = "";  $var_lessamt = 0;
                }  
                */
                                
                //add here---------------------------------------------------------------                
                $var_ctr = $row['scustcd'];
                $var_type = $var_prevtype; 
                switch ($var_prevtype) 
                {
                case "B" : $sql = "select best_rate from counter where counter = '". $vmfrmcd. "'"; 
                		   break;
                case "N" : $sql = "select norm_rate from counter where counter = '". $vmfrmcd. "'"; 
                           break;
                case "O" : $sql = "select offe_rate from counter where counter = '". $vmfrmcd. "'"; 
                           break;
                case "SBB" : $sql = "select spc_rate from counter where counter = '". $vmfrmcd. "'"; 
                           break;

                default : $var_rate = 0;
                }   
                //echo $var_prevtype . ' - '. $sql. '</br>';
                			                
				
				$sql_result = mysql_query($sql);
				if ($sql_result <> FALSE)
				{
					$row = mysql_fetch_array($sql_result);
					$var_rate= $row[0];
				}
				if ($var_rate=='')
				{
					$var_rate=0;
				}
				//$var_lessamt =  $var_subamt * $var_rate / 100;   //use include gst to calculate
        $var_lessamt =  $var_subexgstamt * $var_rate / 100;    //use exclude gst to calculate
        $var_lessamt = round ($var_lessamt , 3);
        $var_lessamt = round ($var_lessamt , 2);
                
                //add here---------------------------------------------------------------             
               $var_rate = number_format($var_rate,2, '.',',');
               $var_tlessamt += $var_lessamt;
               //$var_namt = $var_subamt - $var_lessamt;    //use include gst to calculate
               $var_namt = $var_subexgstamt - $var_lessamt;     //use exclude gst to calculate
               $var_tnamt += $var_namt;                         //namt use exclude gst
                            
                             
               //echo "###Amt : ".$var_amt." sub : ".$var_subamt;
               
               echo '<tr bgcolor="#efefef">';
               echo '<td colspan="3" >Type : '.$var_prevtype.' - '.$rsttype->salestype_desc.'</td>';
               echo '<td align="right"></td>';
               echo '<td align="right"></td>';               
               echo '<td align="right"></td>';
               echo '<td align="right">'.number_format($var_subamt,2, '.',',').'</td>';
               //echo '<td align="right"></td>';
               echo '<td align="right">'.number_format($var_subexgstamt,2, '.',',').'</td>';
               //echo '<td align="right"></td>';               
               echo '<td align="right">'.$var_prevtype.' -> '.$var_rate.'%</td>';
               echo '<td align="right">'.number_format($var_lessamt,2, '.',',').'</td>';
               echo '<td align="right">'.number_format($var_namt,2, '.',',').'</td>';               
               echo '</tr>';
               
               /*
               echo '<tr bgcolor="#efefef">';
               echo '<td colspan="5" ></td>';
               echo '<td align="right"></td>';
               echo '<td align="right"></td>';               
               echo '<td align="right">Discount('.$var_prevtype.' -> '.$var_rate.'%)</td>';
               echo '<td align="right">'.number_format($var_lessamt,2, '.',',').'</td>';
               echo '</tr>';
               
               
               echo '<tr bgcolor="#efefef">';
               echo '<td colspan="5" ></td>';
               echo '<td align="right"></td>';
               echo '<td align="right"></td>';               
               echo '<td align="right">NETT : </td>';
               echo '<td align="right">'.number_format($var_namt,2, '.',',').'</td>';
               echo '</tr>';               
               */
               
               echo '<tr><td>&nbsp;</td></tr>'; 
               //echo              
               
               $var_gamt = 0;  $var_subamt = 0;   $var_lessamt = 0;  $var_namt = 0;   $var_subexgstamt = 0;
             }  
             
             //$var_amt =  $row2['sprounipri'] *  $var_totqty; //close - used unit, not UOM per pack
             $var_amt =  $row2['sprounipri'] *  $row2['soldqty']; 
             $var_subamt += $var_amt;
             $var_tamt += $var_amt; 
             
             $var_gstrate = $vmgst + 100;
             
             //---- dun wan unit price to get GST uprice, only based on amount to get exclude GST amount
             //$var_exgstupri = 0;
             //$var_exgstupri =  $row2['sprounipri'] / $var_gstrate * 100; 
             //$var_exgstupri = roundup($var_exgstupri,2);      
             //$var_exgstamt = $var_exgstupri *  $row2['soldqty'];  
             
             $var_exgstamt = $var_amt / $var_gstrate * 100;
             $var_exgstamt = round($var_exgstamt,3);  //case 12.99 / 106 * 100 = 12.2547; round to 12.26        
             $var_exgstamt = round($var_exgstamt,2);  // so need to round to 3 decimal then only 2 decimal
             $var_subexgstamt += $var_exgstamt;
             $var_texgstamt += $var_exgstamt;
                        
             echo '<tr>';
             echo '<td>'.$row2['sprocd'].'</td>';
             echo '<td>'.$var_desc.'</td>'; 
             echo '<td align="right">'.$row2['soldqty'].'</td>';             
             echo '<td align="center">'.$var_uom.'</td>';
             echo '<td align="right">'.$var_totqty.'</td>';                           
             echo '<td align="right">'.$row2['sprounipri'].'</td>';
             echo '<td align="right">'.number_format($var_amt,2, '.',',').'</td>';
             //echo '<td align="right">'.number_format($var_exgstupri,2, '.',',').'</td>';
             echo '<td align="right">'.number_format($var_exgstamt,2, '.',',').'</td>';             
             echo '<td colspan="3"></td>';
             echo '</tr>';           
             
             $var_prevtype = $row2['sptype'];
             
            } //while ($row2 
            
               if($var_prevtype <> "" ) {
             
               $sql3 = "select salestype_desc from salestype_master ";
               $sql3 .= " where salestype_code = '$var_prevtype'";
               
               //echo $sql3;
               $tmptype = mysql_query($sql3) or die ("Cant get type : ".mysql_error());
               $rsttype = mysql_fetch_object($tmptype);
             
               $var_rate = 0;   
               /*          
               switch ($row['less_type']) {
                case "1" : $var_rate = 0; $var_type = ""; $var_lessamt = 0; break;
                case "2" : $var_rate = $row['less_amt']; $var_type = "%";
                           $var_lessamt =  $var_subamt * $var_rate / 100;
                           break;
                case "3" : $var_rate = $row['less_amt']; $var_type = "RM";
                           $var_lessamt =  $var_subamt - $var_rate;
                           break;
                default : $var_rate = 0; $var_type = "";  $var_lessamt = 0;
                }      
                */
                //add here---------------------------------------------------------------                
                $var_ctr = $row['scustcd'];
                $var_type = $var_prevtype; 
                switch ($var_prevtype) 
                {
                case "B" : $sql = "select best_rate from counter where counter = '". $vmfrmcd. "'"; 
                		   break;
                case "N" : $sql = "select norm_rate from counter where counter = '". $vmfrmcd. "'"; 
                           break;
                case "O" : $sql = "select offe_rate from counter where counter = '". $vmfrmcd. "'"; 
                           break;
                case "SBB" : $sql = "select spc_rate from counter where counter = '". $vmfrmcd. "'"; 
                           break;

                default : $var_rate = 0;
                }   
                //echo $var_prevtype . ' - '. $sql. '</br>';
                			                
				
				$sql_result = mysql_query($sql);
				if ($sql_result <> FALSE)
				{
					$row = mysql_fetch_array($sql_result);
					$var_rate= $row[0];
				}
				if ($var_rate=='')
				{
					$var_rate=0;
				}
				//$var_lessamt =  $var_subamt * $var_rate / 100;    //use include gst to calculate
        $var_lessamt =  $var_subexgstamt * $var_rate / 100;    //use exclude gst to calculate
        $var_lessamt = round ($var_lessamt , 3);
        $var_lessamt = round ($var_lessamt , 2);
        
                //add here---------------------------------------------------------------               
               $var_rate = number_format($var_rate,2, '.',',');
               $var_tlessamt += $var_lessamt;
               //$var_namt = $var_subamt - $var_lessamt;     //use include gst to calculate
               $var_namt = $var_subexgstamt - $var_lessamt;     //use exclude gst to calculate
               $var_tnamt += $var_namt;
               
               echo '<tr bgcolor="#efefef">';
               echo '<td colspan="3" >Type : '.$var_prevtype.' - '.$rsttype->salestype_desc.'</td>';
               echo '<td align="right"></td>';
               echo '<td align="right"></td>';               
               echo '<td align="right"></td>';
               echo '<td align="right">'.number_format($var_subamt,2, '.',',').'</td>';
               //echo '<td align="right"></td>';
               echo '<td align="right">'.number_format($var_subexgstamt,2, '.',',').'</td>';
               //echo '<td align="right"></td>';               
               echo '<td align="right">'.$var_prevtype.' -> '.$var_rate.'%</td>';
               echo '<td align="right">'.number_format($var_lessamt,2, '.',',').'</td>';
               echo '<td align="right">'.number_format($var_namt,2, '.',',').'</td>';                
               echo '</tr>';
               
               /*
               echo '<tr bgcolor="#efefef">';
               echo '<td colspan="5" ></td>';
               echo '<td align="right"></td>';
               echo '<td align="right"></td>';               
               echo '<td align="right">Discount('.$var_type.' -> '.$var_rate.'%)</td>';
               echo '<td align="right">'.number_format($var_lessamt,2, '.',',').'</td>';
               echo '</tr>';
               echo '<tr bgcolor="#efefef">';
               echo '<td colspan="5" ></td>';
               echo '<td align="right"></td>';
               echo '<td align="right"></td>';               
               echo '<td align="right">NETT : </td>';
               echo '<td align="right">'.number_format($var_namt,2, '.',',').'</td>';
               echo '</tr>'; 
               */
                                
               echo '<tr><td>&nbsp;</td></tr>'; 
               
               $var_gamt = 0;  $var_subamt = 0;   $var_lessamt = 0;  $var_namt = 0;    $var_subexgstamt = 0;
             } 
             
          }   // if(mysql_numrows($tmp2)
                
                
          echo '<tr ><td colspan="2"></td>';
          echo '<td align="right"></td>';
          echo '<td align="right"></td>';               
          echo '<td align="right" colspan="2">Total :</td>';
          echo '<td align="right">'.number_format($var_tamt,2, '.',',').'</td>';
          //echo '<td align="right"></td>'; 
          echo '<td align="right">'.number_format($var_texgstamt,2, '.',',').'</td>';
          echo '<td align="right"></td>';
          echo '<td align="right" >'.number_format($var_tlessamt,2, '.',',').'</td>';
          echo '<td align="right" >'.number_format($var_tnamt,2, '.',',').'</td>';
          echo '</tr>'; 
          
          echo '<tr><td>&nbsp;</td></tr>';  
          echo '<tr ><td ></td>';
          echo '<td align="right"></td>';
          //echo '<td align="right"></td>';               
          echo '<td align="right" colspan="8">GROSS TOTAL BEFORE GST :</td>';
          echo '<td align="right" >'.number_format($var_tnamt,2, '.',',').'</td>';
          echo '</tr>'; 
          
          $var_addgst = $var_tnamt * $vmgst / 100;
          $var_addgst = round($var_addgst, 3);
          $var_addgst = round($var_addgst, 2);
          $var_taddgst = $var_addgst + $var_tnamt;
          echo '<tr ><td ></td>';
          echo '<td align="right"></td>';
          //echo '<td align="right"></td>';               
          echo '<td align="right" colspan="8">ADD GST @ '.$vmgst.'% :</td>';
          echo '<td align="right" >'.number_format($var_addgst,2, '.',',').'</td>';
          echo '</tr>';                             
          echo '<tr ><td ></td>';
          echo '<td align="right"></td>';
          //echo '<td align="right"></td>';               
          echo '<td align="right" colspan="8">TOTAL SALES :</td>';
          echo '<td align="right" >'.number_format($var_taddgst,2, '.',',').'</td>';
          echo '</tr>';  
       }
    }  
        
  }  
	  	
   echo '</table>';
   
?>        
        
     <br /><br />
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_invoice.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnsave.php");
				?>
				</td>
			</tr>
			<tr>
				<td style="width: 1150px" colspan="5">
				<span style="color:#FF0000">Message :</span>
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
						case 4:
							echo("<span>Please Fill In The Sales Order No; Process Fail</span>");
							break;
						case 5:
							echo("<span>This Sales Order No Is Use For This Buyer Code; Process Fail</span>");
							break;
						default:
							echo "";
						}
					}	
				?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
	  	</table>
	   </form>	
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
