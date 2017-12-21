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
       $varmenucd = $_GET['menudcd'];
	   $var_menucode = $_GET['menucd'];
    }
    
    
    if ($_POST['Submit'] == "Update") {
       $varmenucd = $_POST['menucd'];
	   $var_menucode  = $_POST['menudcd'];
	   
       if ($varmenucd <> "") {
       
         $menustat = $_POST['selactive'];
         $menudname = $_POST['menudname'];
         $menudesc = $_POST['menude'];
         $menutype = $_POST['selmenutype'];
         $menupath = $_POST['menupath'];
         $menuseq = $_POST['menuseq'];
         $menupar = $_POST['selmenupar'];
         $menumoby= $var_loginid;
         $menumoon= date("Y-m-d H:i:s");
       
         $sql = "Update menud set menu_name ='$menudname', ";
         $sql .= " menu_desc = '$menudesc', menu_seq = '$menuseq', menu_path = '$menupath', ";
         $sql .= " menu_type = '$menutype', menu_parent= '$menupar', menu_stat='$menustat', ";
         $sql .= " modified_by='$menumoby',";
         $sql .= " modified_on='$menumoon' WHERE menu_code = '$varmenucd'";
          
         mysql_query($sql);
         $var_menucode  = $_POST['menudcd'];
         $backloc = "../admin_set/menu_set.php?menucd=".$var_menucode;
	
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
       }      
    }

    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcd'];
         $backloc = "../admin_set/menu_set.php?menucd=".$var_menucode;
	
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>MENU MASTER</title>

	
<style media="all" type="text/css">@import "../css/styles.css";
.style2 {
	margin-right: 0px;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>

<script type="text/javascript" charset="utf-8"> 
$(document).ready(function() {
				$('#example').dataTable( {
					"sPaginationType": "full_numbers"
				} );
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

<body OnLoad="document.InpMenuMas.selactive.focus();">
<?php include("../topbarm.php"); ?> 
 <!--<?php include("../sidebarm.php"); ?>--> 
<div class ="contentc">

     <?php
        $sql = "select menu_stat, menu_name, menu_desc, menu_type,";
        $sql .= " menu_path, menu_seq, menu_parent";
        $sql .= " from menud";
        $sql .= " where menu_code ='".$varmenucd."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $menuact = $row[0];
        $menuname = $row[1];
        $menudesc = $row[2];
        $menutype = $row[3];
        $menupath = $row[4];
        $menuseq = $row[5];
        $menuparent  = $row[6];
      
        $sql = "select menu_name from menud  ";
        $sql .= " where menu_code ='".$menuparent."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $menuparnm = $row[0];

    ?>		

	<fieldset name="Group1" style=" width: 911px; height: 330px;" class="style2">
	 <legend class="title">EDIT MENU SETTING :<?php echo $varmenucd;?></legend>
	  <br>
	  <form name="InpMenuMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 800px;">
	    <input name="menudcd" type="hidden" value="<?php echo $var_menucode;?>">
		<table style="width: 884px">
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 283px" class="tdlabel">Menu Code</td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" readonly="readonly" name="menucd" id ="menucdid" type="text" style="width: 95px" value="<?php echo $varmenucd; ?>">
			</td>
			<td>
			</td>
		    <td style="width: 224px" class="tdlabel">Status</td>
	  	    <td>:</td>
	  	    <td style="width: 97px">
			   <select name="selactive" style="width: 125px">
			    <option><?php echo $menuact;?></option>
			    <option>ACTIVE</option>
			    <option>DEACTIVATE</option>
			   </select>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td>
	  	    </td> 
	  	    <td style="width: 283px" class="tdlabel"></td>
	  	    <td>
	  	    </td>
	  	    <td><div id="msgcd"></div></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 283px" class="tdlabel">Menu Name</td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="menudname" id ="menunmid" type="text" maxlength="100" style="width: 417px" value="<?php echo $menuname; ?>">
			</td>
			<td>
			</td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	    <td style="height: 28px">
	  	    </td>
	  	    <td style="width: 283px; height: 28px;" class="tdlabel">Description</td>
	  	    <td style="height: 28px">:</td>
	  	    <td style="width: 431px; height: 28px;">
			<input class="inputtxt" name="menude" id ="menudeid" type="text" maxlength="100" style="width: 417px" value="<?php echo $menudesc; ?>">
			</td>
			<td style="height: 28px">
			</td>
		  </tr>
		  <tr><td></td></tr>
		  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 283px" class="tdlabel">Menu Type</td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			   <select name="selmenutype" style="width: 125px">
			    <option><?php echo $menutype;?></option>
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
	  	    <td style="width: 283px" class="tdlabel">Menu Path</td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="menupath" id ="menupathid" type="text" style="width: 417px" value="<?php echo $menupath; ?>"></td>
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
	  	    <td style="width: 283px" class="tdlabel">Menu #</td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="menuseq" id ="menuseqid" type="text" style="width: 79px" onBlur="chkInt(this.value);" value="<?php echo $menuseq; ?>">
			</td>
			<td>
			</td>
		  </tr>
		  <tr>
	  	    <td>
	  	    </td>
	  	    <td></td>
	  	    <td></td>
	  	    <td><div font color="red" id="msgint"></div></td>
			<td></td>
		  </tr>
		   <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 283px" class="tdlabel">Parent </td>
	  	    <td>:</td>
	  	    <td style="width: 431px">
			   <select name="selmenupar" style="width: 417px">
			    <?php
                   $sql = "select menu_code, menu_name from menud ";
                   $sql .= " WHERE menu_type in ('Main Menu','Sub Menu')";
                   $sql .= " ORDER BY menu_name ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option value=".$menuparent.">".$menuparnm."</option>";
                       
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
	  	    <td></td>
	  	    <td></td>
	  	    <td></td>
			<td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td></td>
			<td></td>
			<td> 
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" >
	  	   <input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
	  	    </td>
		  </tr>
	  	</table>
	   </form>	
	 </fieldset>
   </div>
</body>

</html>
