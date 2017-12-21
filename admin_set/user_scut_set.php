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
    }
    
    if ($_POST['Submit'] == "Save") {
     $shcusenr   = $_POST['usernm'];
     $shcprog = $_POST['selprog'];
     $shcseq = $_POST['shcseq'];
            
     if ($shcprog <> "") {
      
      	$var_sql = " SELECT count(*) as cnt from shou_user ";
        $var_sql .= " WHERE prog_code = '$shcprog' and usern='$shcusenr'";

      	$query_id = mysql_query($var_sql) or die ("Cant Check Menu Code");
      	$res_id = mysql_fetch_object($query_id);

      	if ($res_id->cnt > 0 ) {
	  	   header("location: ".$_SERVER['PHP_SELF']."?stat=3");
      	}else {
      	
      	    $sql = "select menu_name from menud ";
        	$sql .= " where menu_code ='".$shcprog."'";
        	$sql_result = mysql_query($sql);
        	$row = mysql_fetch_array($sql_result);
        	$shcprognm = $row[0];

      	   $vartoday = date("Y-m-d H:i:s");
      	   $sql = "INSERT INTO shou_user values 
      	          ('$shcusenr', '$shcprognm', '$shcprog','$shcseq',
      	            '$vartoday', '$var_loginid')";
      	           
     		 mysql_query($sql); 
     		 header("location: ".$_SERVER['PHP_SELF']."?stat=1");
	  	}
     }else{
       header("location: ".$_SERVER['PHP_SELF']."?stat=4");
     }
    }
     
    if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['shcprgcd']) && is_array($_POST['shcprgcd'])) 
     {
           foreach($_POST['shcprgcd'] as $key) {
             $defarr = explode("|", $key);
             $var_shcpname = $defarr[0];
             $var_shcseq = $defarr[1];
             $var_shcpcode = $defarr[2];
             $var_shcuser = $defarr[3];
              
             
		    $sql = "DELETE FROM shou_user WHERE prog_code ='".$var_shcpcode."'"; 
		    $sql .= " And usern ='".$var_shcuser."'";
			
		 	mysql_query($sql); 
		   }
		   header("location: ".$_SERVER['PHP_SELF']."?stat=1");
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

function is_int(value){

  if (value != ""){
  	if((parseFloat(value) == parseInt(value)) && !isNaN(value)){   
        return true;
    }else {
        alert ("Sequence Number Must Be In Integer");
        document.InpMenuMas.shcseqid.focus();
        return false;
    }
  }  
}

function validateForm()
{
    var x=document.forms["InpMenuMas"]["usernm"].value;
	if(!x.match(/\S/)){	
		alert("User Name Cannot Not Be Blank");
		document.InpMenuMas.selprog.focus();
		return false;
	}
	
	var x=document.forms["InpMenuMas"]["selprog"].value;
	if(!x.match(/\S/)){	
		alert("Program Name Cannot Not Be Blank");
		document.InpMenuMas.selprog.focus();
		return false;
	}
	
	var x=document.forms["InpMenuMas"]["shcseq"].value;
	if(!x.match(/\S/)){	
		alert("Sequence No Cannot Not Be Blank");
		document.InpMenuMas.shcseq.focus();
		return false;
	}


}
</script>
</head>

<body OnLoad="document.InpMenuMas.menucd.focus();">
<?php include("../topbarm.php"); ?> 
 <!--<?php include("../sidebarm.php"); ?>-->
<div class ="contentc">

	<fieldset name="Group1" style=" width: 700px;" class="style2">
	 <legend class="title">MY SHORTCUT SETTING</legend>
	  <br>
	  <fieldset name="Group1" style="height:200px; width: 650px;">
	  <form name="InpMenuMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" style="height: 134px; width: 650px;" onsubmit="return validateForm()">
		<table style="width: 884px">
	  	  <tr>
	  	    <td></td>
	  	    <td>User Name</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="usernm" id ="usernmid" type="text" maxlength="45" readonly="readonly" style="width: 188px" value="<?php echo $var_loginid; ?>">
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td></td>
	  	    <td></td>
	  	    <td></td> 
	   	  </tr> 
	   	  <tr>
	   	    <td></td>
	  	    <td>Program Name</td>
	  	    <td>:</td>
	  	    <td>
			  <select name="selprog" style="width: 483px">
			    <option></option>
			    <?php
                   $sql = " select x.menu_name, x.menu_code from menud x, progauth y ";
                   $sql .= " where x.menu_code = y.program_name ";
				   $sql .= " and x.menu_stat = 'ACTIVE' and y.username ='".$var_loginid."'";
				   $sql .= " and y.accessr = '1'        and x.menu_type = 'Program'";
				   $sql .= " and x.menu_code not in (select prog_code from shou_user ";
                   $sql .= "                          where usern ='".$var_loginid."')";
                   $sql .= " order by x.menu_name ";
                  
                   $sql_result = mysql_query($sql);
                  
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
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	    <td></td>
	  	    <td>Sequence No</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="shcseq" id ="shcseqid" type="text" maxlength="3" style="width: 63px" onBlur="is_int(this.value);">
			</td>
 		  </tr>
		  <tr><td></td></tr>
		  <tr>
	  	    <td></td>
	  	    <td></td>
	  	    <td></td>
	  	    <td></td>
			<td></td>
		  </tr>
		  <tr>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td><input type=submit name = "Submit" value="Save" class="butsub" style="width: 60px; height: 32px" >
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
	    <br>
	  	<?php
				$sql = "SELECT prog_name, sh_seq, prog_code, usern ";
				$sql .= " FROM shou_user";
				$sql .= " Where usern ='".$var_loginid."'";
    		    $sql .= " ORDER BY sh_seq";  
			    $rs_result = mysql_query($sql); 
        ?>
        <form name="LstColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?var_stat='.$var_stat; ?>">
		 <table>
		 <tr>
             <td style="width: 900px; height: 38px;" align="right">
             
			  <?php
		       $msgdel = "Are You Sure Delete Selected ShortCut Menu?";
    	  	    echo '<input type=submit name = "Submit" value="Delete" class="butsub" style="width: 60px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">'; 
		      ?>
			</td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" border id="example"  class="display" width="100%">
          <thead>
          <tr>
          	<th class="tabheader" style="width: 20px">#</th>
          	<th class="tabheader" style="width: 20px">Seq No</th>
          	<th class="tabheader" style="width: 154px">Shortcut Program Name</th>
           	<th class="tabheader" style="width: 20px">Update</th>
          	<th class="tabheader" style="width: 20px">Delete</th>
          </tr>
          </thead>
		 <tbody>
		 <?php 
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			
			$urlpop = 'upd_scut_set.php';
		
			echo '<tr>';
            echo '<td align="center">'.$numi.'</td>';
            echo '<td align="center">'.$row['sh_seq'].'</td>';
            echo '<td>'.$row['prog_name'].'</td>';
        
            echo '<td align="center"><a href="'.$urlpop.'?shrcode='.$row['prog_code'].'&shrseq='.$row['sh_seq'].'&shrpname='.htmlentities($row['prog_name']).'">[EDIT]</a>';'</td>';
            
            $values = implode('|', $row);
            echo '<td align="center"><input type="checkbox" name="shcprgcd[]" value="'.$values.'" />'.'</td>';
                      
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
