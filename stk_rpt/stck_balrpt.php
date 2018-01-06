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
        $baseCost = $_POST['calculateCostBaseOn'];
        
        #----------------Prepare Temp Table For Printing -----------------------------------
        $sql  = " Delete From tmpbal_rpt where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        
        $sql = "SELECT ProductCode, OwnCode, Description, ExFacPrice, ExUnit ";
        $sql .= " FROM product ";
        //$sql .= " where ProductCode = 'M068-PK-32'";
        $sql .= " Order BY OwnCode, ProductCode";
        
        $rs_result = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($rs_result)) {
            $procd  = mysql_real_escape_string($row['ProductCode']);
            $ownccd = mysql_real_escape_string($row['OwnCode']);
            $itmdes = mysql_real_escape_string($row['Description']);
            $excost = 0;
            $exfacprice = mysql_real_escape_string($row['ExFacPrice']);
            $exuom  = mysql_real_escape_string($row['ExUnit']);
            
            
            //to get the average cost from receiving price
            
            /* $sql = " select SUM(qtyin*prounipri) as amt1 FROM invthist X, invtrcvddet_nlg Y ";
             $sql .= " where reftype = 'RC'";
             $sql .= " and prodcode = '".$procd."'";
             $sql .= " and refdate <= '$frdte'";
             $sql .= " AND prodcode = procd ";
             $sql .= " AND refid = rcvdno ";
             $tmp = mysql_query ($sql) or die ("Cant get amt1 : ".mysql_error());
             if (mysql_numrows($tmp) > 0) {
             $rst = mysql_fetch_object($tmp);
             $var_amt1 = $rst->amt1;
             if ($var_amt1 == "") { $var_amt1 = 0; }
             }else{
             $var_amt1 = 0;
             } */
            //echo "amt 1 - ". $sql . "</br>";
            
            /* $sql2 = " select SUM(qtyin*prounipri) as amt2 FROM invthist X, invtrcvddet Y ";
             $sql2 .= " where reftype = 'RC'";
             $sql2 .= " and prodcode = '".$procd."'";
             $sql2 .= " and refdate <= '$frdte'";
             $sql2 .= " AND prodcode = procd ";
             $sql2 .= " AND refid = rcvdno ";
             $tmp2 = mysql_query ($sql2) or die ("Cant get amt2 : ".mysql_error());
             if (mysql_numrows($tmp2) > 0) {
             $rst2 = mysql_fetch_object($tmp2);
             $var_amt2 = $rst2->amt2;
             if ($var_amt2 == "") { $var_amt2 = 0; }
             }else{
             $var_amt2 = 0;
             } */
            //echo "amt 2 - ". $sql . "</br>"; break;
            $amt = 0;
            $amt = $var_amt1 + $var_amt2;
            $sellingPrice = 0;
            // Get Selling price
            if ($baseCost == "SELLING_PRICE") {
                $sqlCost = " select * from prodprice ";
                $sqlCost .= " where productcode = '".$procd."' and pricecode = 'PRICE1'";
                $tmpCost = mysql_query ($sqlCost) or die ("Cant get selling price : ".mysql_error());
                
                if (mysql_numrows($tmpCost) > 0) {
                    $rstCost = mysql_fetch_object($tmpCost);
                    $sellingPrice = $rstCost->uprice;
                    //if ($sellingPrice == "") { $sellingPrice = 0; }
                }
                
                if ($sellingPrice == "") {
                    $sqlCost = " select * from prodprice ";
                    $sqlCost .= " where productcode = '".$procd."' and pricecode = 'PRICE2'";
                    $tmpCost = mysql_query ($sqlCost) or die ("Cant get selling price : ".mysql_error());
                    
                    if (mysql_numrows($tmpCost) > 0) {
                        $rstCost = mysql_fetch_object($tmpCost);
                        $sellingPrice = $rstCost->uprice;
                        //if ($sellingPrice == "") { $sellingPrice = 0; }
                    }
                }
                
                if ($sellingPrice == "") {
                    $sqlCost = " select * from prodprice ";
                    $sqlCost .= " where productcode = '".$procd."' and pricecode = 'PRICE3'";
                    $tmpCost = mysql_query ($sqlCost) or die ("Cant get selling price : ".mysql_error());
                    
                    if (mysql_numrows($tmpCost) > 0) {
                        $rstCost = mysql_fetch_object($tmpCost);
                        $sellingPrice = $rstCost->uprice;
                    }
                }
                if ($sellingPrice == "") { $sellingPrice = 0; }
            }
            
            $sql = " select SUM(qtyin) as totqty FROM invthist ";
            $sql .= " where reftype = 'RC'";
            $sql .= " and prodcode = '".$procd."'";
            $sql .= " and refdate <= '$frdte'";
            $tmp = mysql_query ($sql) or die ("Cant get totqty : ".mysql_error());
            if (mysql_numrows($tmp) > 0) {
                $rst = mysql_fetch_object($tmp);
                $var_totqty = $rst->totqty;
                if ($var_totqty == "") { $var_totqty = 0; }
            }else{
                $var_totqty = 0;
            }
            /* if ($var_totqty > 0)
             {
             $excost = $amt / $var_totqty;
             } */
            //echo 	$amt . "</br>";
            //echo  $var_totqty. "</br>";
            //echo $excost; break;
            if ($excost==0)
            {
                $excost = $exfacprice;
            }
            
            if ($baseCost == "SELLING_PRICE") {
                $excost = $sellingPrice;
            }
            // end get price //
            
            $sqlc  = "select category_desc";
            $sqlc .= " from mdfcategory_master";
            $sqlc .= " where category_code ='$ownccd'";
            $sql_resultc = mysql_query($sqlc);
            $rowc = mysql_fetch_array($sql_resultc);
            $owndes = $rowc['category_desc'];
            
            #------------OnHand QTy-----------------------------
            //---- for opening qty : OP -----//
            $sql = " select sum(qtyin) as cnt from invthist";
            $sql .= " where reftype = 'OP'";
            $sql .= " and prodcode = '".$procd."'";
            $sql .= " and refdate <= '$frdte'";
            $tmp = mysql_query ($sql) or die ("Cant get 1 : ".mysql_error());
            if (mysql_numrows($tmp) > 0) {
                $rst = mysql_fetch_object($tmp);
                $var_begbal = $rst->cnt;
                if ($var_begbal == "") { $var_begbal = 0; }
            }else{
                $var_begbal = 0;
            }
            
            //---- for rec qty : RC -----//
            $sql = " select sum(qtyin) as cnt from invthist";
            $sql .= " where reftype = 'RC'";
            $sql .= " and prodcode = '".$procd."'";
            $sql .= " and refdate <= '$frdte'";
            $tmp = mysql_query ($sql) or die ("Cant get 1 : ".mysql_error());
            if (mysql_numrows($tmp) > 0) {
                $rst = mysql_fetch_object($tmp);
                $var_rec = $rst->cnt;
                if ($var_rec == "") { $var_rec = 0; }
            }else{
                $var_rec = 0;
            }
            
            
            //---- for transfer in qty : TI -----//
            $sql = " select sum(qtyin) as cnt from invthist";
            $sql .= " where reftype = 'TI'";
            $sql .= " and prodcode = '".$procd."'";
            $sql .= " and refdate <= '$frdte'";
            $tmp = mysql_query ($sql) or die ("Cant get 2 : ".mysql_error());
            if (mysql_numrows($tmp) > 0) {
                $rst = mysql_fetch_object($tmp);
                $var_trfin = $rst->cnt;
                if ($var_trfin == "") { $var_trfin = 0; }
            }else{
                $var_trfin = 0;
            }
            
            //---- for transfer out qty : TO -----//
            $sql = " select sum(qtyout) as cnt from invthist";
            $sql .= " where reftype = 'TO'";
            $sql .= " and prodcode = '".$procd."'";
            $sql .= " and refdate <= '$frdte'";
            $tmp = mysql_query ($sql) or die ("Cant get 3 : ".mysql_error());
            if (mysql_numrows($tmp) > 0) {
                $rst = mysql_fetch_object($tmp);
                $var_trfout = $rst->cnt;
                if ($var_trfout == "") { $var_trfout = 0; }
            }else{
                $var_trfout = 0;
            }
            
            //---- for ret from : RN -----//
            $sql = " select sum(qtyin) as cnt from invthist";
            $sql .= " where reftype = 'RN'";
            $sql .= " and prodcode = '".$procd."'";
            $sql .= " and refdate <= '$frdte'";
            $tmp = mysql_query ($sql) or die ("Cant get 4 : ".mysql_error());
            if (mysql_numrows($tmp) > 0) {
                $rst = mysql_fetch_object($tmp);
                $var_rtnfrm = $rst->cnt;
                if ($var_rtnfrm == "") { $var_rtnfrm = 0; }
            }else{
                $var_rtnfrm = 0;
            }
            
            //---- for ship qty : SA -----//
            $sql = " select sum(qtyout) as cnt from invthist";
            $sql .= " where reftype = 'SA'";
            $sql .= " and prodcode = '".$procd."'";
            $sql .= " and refdate <= '$frdte'";
            $tmp = mysql_query ($sql) or die ("Cant get 5 : ".mysql_error());
            if (mysql_numrows($tmp) > 0) {
                $rst = mysql_fetch_object($tmp);
                $var_ship = $rst->cnt;
                if ($var_ship == "") { $var_ship = 0; }
            }else{
                $var_ship = 0;
            }
            
            //---- for adj qty : AD -----//
            $sql = " select sum(qtyin - qtyout) as cnt from invthist";
            $sql .= " where reftype = 'AD'";
            $sql .= " and prodcode = '".$procd."'";
            $sql .= " and refdate <= '$frdte'";
            $tmp = mysql_query ($sql) or die ("Cant get 6 : ".mysql_error());
            if (mysql_numrows($tmp) > 0) {
                $rst = mysql_fetch_object($tmp);
                $var_adj = $rst->cnt;
                if ($var_adj == "") { $var_adj = 0; }
            }else{
                $var_adj = 0;
            }
            
            //---- for return to qty : RS -----//
            $sql = " select sum(qtyout) as cnt from invthist";
            $sql .= " where reftype = 'RS'";
            $sql .= " and prodcode = '".$procd."'";
            $sql .= " and refdate <= '$frdte'";
            $tmp = mysql_query ($sql) or die ("Cant get 7 : ".mysql_error());
            if (mysql_numrows($tmp) > 0) {
                $rst = mysql_fetch_object($tmp);
                $var_rtnto = $rst->cnt;
                if ($var_rtnto == "") { $var_rtnto = 0; }
            }else{
                $var_rtnto = 0;
            }
            
            $var_rtnall = $var_rtnfrm - $var_rtnto;
            $var_onhand =  $var_begbal + $var_rec + $var_trfin - $var_trfout + $var_rtnfrm - $var_ship + $var_adj - $var_rtnto;
            
            if ($var_onhand != 0){
                $balamt = $excost * $var_onhand;
                if (empty($excost)){$excost = 0;}
                $sqli  = " Insert Into tmpbal_rpt (subcode, description, cat, cat_desc, ";
                $sqli .= "  balqty, itmuom, avgcst, balamt, usernm)";
                $sqli .= " Values ('$procd', '$itmdes', '$ownccd', '$owndes', '$var_onhand',";
                $sqli .= "   '$exuom', '$excost', '$balamt','$var_loginid')";
                mysql_query($sqli) or die("Unable Save In Temp Table ".mysql_error());
            }
        }
        #-----------------------------------------------------------------------------------
        
        $var_sql = " SELECT count(*) as cnt from tmpbal_rpt where usernm = '$var_loginid'";
        $query_id = mysql_query($var_sql) or die ("Cant Check Temp Table");
        $res_id = mysql_fetch_object($query_id);
        
        if ($res_id->cnt > 0 ) {
            
            //if (($baseCost == "SELLING_PRICE_1") || ($baseCost == "SELLING_PRICE_2") || ($baseCost == "SELLING_PRICE_3")) {
            //$baseCost == "SELLING_PRICE";
            //}
            // Redirect browser
            $fname = "fnl_stkbal_rpt.rptdesign&__title=myReport";
            $dest = "http://".$var_prtserver.":8080/birtfg/frameset?__report=".$fname."&fd=".$frdte."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
            $dest .= "&baseCost=".$baseCost;
            $dest .= urlencode(realpath($fname));
            
            //header("Location: $dest" );
            echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
            $backloc = "../stk_rpt/stck_balrpt.php?menucd=".$var_menucode;
            echo "<script>";
            echo 'location.replace("'.$backloc.'")';
            echo "</script>";
        }else{
            echo "<script>";
            echo "alert('No Data Found!');";
            echo "</script>";
        }
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
	
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	
	var myDate = new Date();
	var then = myDate.getDate()+'-'+(myDate.getMonth()+1)+'-'+myDate.getFullYear(); 
	
    var fromdate = x.split('-');
        from_date = new Date();
        from_date.setFullYear(fromdate[2],fromdate[1]-1,fromdate[0]); 
    
    var todate = then.split('-');
        to_date = new Date();
        to_date.setFullYear(todate[2],todate[1]-1,todate[0]);
    if (from_date > to_date ) 
    {
       alert("As At Date Cannot Larger Than To Date");
	   document.InpRawOpen.rptofdte.focus();
		return false;
    }
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 560px; " class="style2">
	 <legend class="title">STOCK BALANCE REPORT</legend>
	  <br>
	  <form name="InpRawOpen" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 527px;">
		<table style="width: 500px">
	  	  <tr>
	  	    <td style="width: 6px"></td>
	  	    <td style="width: 240px" class="tdlabel">As At Date</td>
	  	    <td style="width: 19px">:</td> 
	  	    <td style="width: 304px">
				<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer">
			</td>
		  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td class="tdlabel">Calculate Cost Base On</td>
	  	    <td>:</td> 
            <td>
            	<select name="calculateCostBaseOn" id="calculateCostBaseOn">
            		<option value="COST_PRICE" selected>Cost Price</option>
            		<option value="SELLING_PRICE">Selling Price</option>           		
        		</select>
            </td> 
	   	  </tr> 
	   	
	  	  <tr>
	  	   <td>
	  	   <td></td>
	  	   <td></td>
	  	   <td>
	  	   <?php
	  	   		include("../Setting/btnprint.php");
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
