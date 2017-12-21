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
	 
	  $menudd = $_GET['menucd'];
	  $decrypted =  base64_decode(strtr($_GET['menucd'], '-_,111', '+/='));
	 
	  $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }
    
    if ($_POST['Submit'] == "Save") {
     $userid   = $_POST['useridcd'];
     $userstat = $_POST['selactive'];
     $userlogin = $_POST['userlog'];
     $userpass = $_POST['userpass'];
     $userfn = $_POST['userfname'];
     $userln = $_POST['userlname'];
     $usereml = $_POST['usereml'];
     $usernob  = $_POST['usermonum'];
     $userpprofile  = $_POST['userppro'];
       
     if ($userlogin <> "") {
        if ($userpass <> "") {
			$var_sql = " SELECT count(*) as cnt from user_account ";
			$var_sql .= " WHERE username = '$userlogin'";
			$query_id = mysql_query($var_sql) or die ("Cant Check Menu Code");
			$res_id = mysql_fetch_object($query_id);

			if ($res_id->cnt > 0 ) {
				$backloc = "../admin_set/user_master.php?stat=3&menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
           		echo "</script>";
			}else {
				$enc_userpass=md5($userpass);
				$vartoday = date("Y-m-d H:i:s");
				$sql = "INSERT INTO user_account values 
						('$userlogin', '$userid', '$userfn','$userln',
						'$usereml','$usernob','$userpprofile','$enc_userpass','$userstat')";
				mysql_query($sql); 
				$var_stat = 1;
     	 
				$rediuseracc = "../admin_set/user_access.php?usern=".$userlogin."&userppfil=".$userpprofile.'&menucd='.$var_menucode;
				echo "<script>";
				echo 'location.replace("'.$rediuseracc.'")';
				echo "</script>";
			} 
		}else{
		    $backloc = "../admin_set/user_master.php?stat=5&menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
           	echo "</script>";
        }		
     }else{
       $backloc = "../admin_set/user_master.php?stat=4&menucd=".$var_menucode;
       echo "<script>";
       echo 'location.replace("'.$backloc.'")';
       echo "</script>";

     }
    }
       
    if ($_POST['Submit'] == "Deactive") {
     if(!empty($_POST['usernm']) && is_array($_POST['usernm'])) 
     {
          
           foreach($_POST['usernm'] as $value ) {
		    $sql = "Update user_account set status ='DEACTIVE'";
            $sql .= " WHERE username ='".$value."'";

		 	mysql_query($sql); 
		   }
		   $backloc = "../admin_set/user_master.php?stat=1&menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
           		echo "</script>";
       }      
    }
    
     if ($_POST['Submit'] == "Active") {
     if(!empty($_POST['usernm']) && is_array($_POST['usernm'])) 
     {
           foreach($_POST['usernm'] as $value ) {
		    $sql = "Update user_account set status ='ACTIVE'";
            $sql .= " WHERE username ='".$value."'";
 
		 	mysql_query($sql); 
		   }
		   $backloc = "../admin_set/user_master.php?stat=1&menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
           		echo "</script>";

       }      
    }
    
    if ($_POST['Submit'] == "Delete") {

     if(!empty($_POST['usernm']) && is_array($_POST['usernm'])) 
     {      
	        
           foreach($_POST['usernm'] as $value ) {
		    $sql = "Delete from user_account";
            $sql .= " WHERE username ='".$value."'";
		 	mysql_query($sql); 
		 	
		 	$sql = "Delete from progauth";
            $sql .= " WHERE username ='".$value."'";
		 	mysql_query($sql);
		 	
			$sql = "Delete from shou_user";
            $sql .= " WHERE usern ='".$value."'";
		 	mysql_query($sql);  

		   }
		   $backloc = "../admin_set/user_master.php?stat=1&menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
           		echo "</script>";
       }      
    }



    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
    
   		$here = getcwd();
       // Redirect browser
        $fname = "clr_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_server.":8080/birt-viewer/frameset?__report=clr_rpt.rptdesign";
        $dest .= urlencode(realpath($fname));
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
     }
    } 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>NL SYSTEM - USER MASTER</title>
	
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
			
var newwindow;
function poptastic(url)
{
	newwindow=window.open(url,'name','height=400,width=911,left=200,top=100');
	if (window.focus) {newwindow.focus()}
}

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

function AjaxFunction(userlogin)
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
	
	var url="aja_chk_userlogin.php";
	
	url=url+"?userlog="+userlogin;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
	httpxml.send(null);
}	


function AjaxEml(usereml)
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
			document.getElementById("msgeml").innerHTML=httpxml.responseText;
		}
}
	
	var url="../Setting/email-ajax.php";
	
	url=url+"?email="+usereml;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
	httpxml.send(null);
}	

</script>
</head>

<body OnLoad="document.UserAccMas.useridcd.focus();">
<?php include("../topbarm.php"); ?> 
 
 <div class ="contentc">

	<fieldset name="Group1" style=" width: 911px;" class="style2">
	 <legend class="title">USER ACCOUNT MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="height:320px">
	  <form name="UserAccMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 800px;">
	    <table style="width: 884px">
	  	  <tr>
	  	    <td style="width: 15px">
	  	    </td>
	  	    <td style="width: 117px" class="tdlabel">User ID</td>
	  	    <td style="width: 10px">:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="useridcd" id ="useridcd" type="text" maxlength="20" onchange ="upperCase(this.id)" style="width: 150px">
			</td>
			<td>
			</td>
		    <td style="width: 72px" class="tdlabel">Status</td>
	  	    <td>:</td>
	  	    <td style="width: 97px">
			   <select name="selactive" style="width: 125px">
			    <option>ACTIVE</option>
			    <option>DEACTIVATE</option>
			   </select>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 15px">
	  	    </td> 
	  	    <td style="width: 117px" class="tdlabel"></td>
	  	    <td style="width: 10px">
	  	    </td>
	  	    <td></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td style="width: 15px">
	  	    </td>
	  	    <td style="width: 117px" class="tdlabel">User Login</td>
	  	    <td style="width: 10px">:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="userlog" id ="userlog" type="text" maxlength="20" style="width: 152px" onBlur="AjaxFunction(this.value);">
			</td>
			<td></td>
			<td style="width: 72px">Password</td>
            <td>:</td>
            <td>
			<input name="userpass" style="width: 155px; height: 21px" type="password"></td>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	    <td></td>
	  	    <td></td>
	  	    <td><div id="msgcd"></div></td>
	  	  </tr>
	  	  <tr>
	  	    <td style="height: 28px; width: 15px;">
	  	    </td>
	  	    <td style="width: 117px; height: 28px;" class="tdlabel">First Name</td>
	  	    <td style="height: 28px; width: 10px;">:</td>
	  	    <td style="width: 431px; height: 28px;">
			<input class="inputtxt" name="userfname" id ="userfnameid" type="text" maxlength="80" style="width: 417px">
			</td>
			<td style="height: 28px">
			</td>
		  </tr>
		  <tr><td style="width: 15px"></td></tr>
		  <tr>
	  	    <td style="width: 15px">
	  	    </td>
	  	    <td style="width: 117px" class="tdlabel">Last Name</td>
	  	    <td style="width: 10px">:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="userlname" id ="userlnameid" type="text" maxlength="80" style="width: 417px">
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td style="width: 15px"></td></tr>
		   <tr>
	  	    <td style="width: 15px">
	  	    </td>
	  	    <td style="width: 117px" class="tdlabel">Email</td>
	  	    <td style="width: 10px">:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="usereml" id ="useremlid" type="text" maxlength="50" style="width: 299px" onBlur="AjaxEml(this.value);"></td>
			<td>
			</td>
		    <td style="width: 72px"></td>
	  	    <td></td>
	  	    <td>
			</td>
		   </tr>
		  <tr>
		    <td></td>
		    <td></td>
		    <td></td>
		    <td><div id="msgeml"></div></td>
		  </tr>
		  <tr>
	  	    <td style="width: 15px">
	  	    </td>
	  	    <td style="width: 117px" class="tdlabel">Mobile Number</td>
	  	    <td style="width: 10px">:</td>
	  	    <td style="width: 431px">
			<input class="inputtxt" name="usermonum" id ="usermonumid" type="text" maxlength="50" style="width: 217px">
			</td>
			<td>
			</td>
		  </tr>
		  <tr>
	  	    <td style="width: 15px">
	  	    </td>
	  	    <td style="width: 117px"></td>
	  	    <td style="width: 10px"></td>
	  	    <td></td>
			<td></td>
		  </tr>
		   <tr>
	  	    <td style="width: 15px">
	  	    </td>
	  	    <td style="width: 117px" class="tdlabel">Prefer Profile</td>
	  	    <td style="width: 10px">:</td>
	  	    <td style="width: 431px">
			   <select name="userppro" style="width: 332px">
			    <?php
                   $sql = "select profile_code, profile_desc from profile_master ";
                   $sql .= " ORDER BY profile_desc ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =60 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['profile_code'].'">'.$row['profile_desc'].'</option>';
				 	 } 
				   } 
	            ?>	
			   </select>
			</td>
			<td>
			</td>
		  </tr>
		 
		   <tr>
	  	    <td style="width: 15px">
	  	    </td>
	  	    <td style="width: 117px"></td>
	  	    <td style="width: 10px"></td>
	  	    <td></td>
			<td></td>
		  </tr>
		  <tr>
	  	   <td colspan="8" align="center">
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
				case 5:
				    echo("<span>User Password Cannot Be Blank</span>");
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
	  	<?php
				$sql = "SELECT userid, username, email, status ";
				$sql .= " FROM user_account";
				$sql .= " WHERE userid != 'supera'";
    		    $sql .= " ORDER BY userid";  
			    $rs_result = mysql_query($sql); 
        ?>
        <form name="LstColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 900px; height: 38px;" align="right">
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		      <?php
    	  	   include("../Setting/btnprint.php");
			   $msgdel = "Are You Sure Active Selected User Account?";
    	  	   include("../Setting/btnactive.php"); 
			   $msgdel = "Are You Sure Deactive Selected User Account?";
    	  	   include("../Setting/btndeactive.php"); 
		       $msgdel = "Are You Sure Cancel Selected User Account?";
    	  	   include("../Setting/btndelete.php"); 
		      ?>
         </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" border id="example" class="display" width="100%">
         <thead><tr>
          <th class="tabheader" style="width: 27px">#</th>
          <th class="tabheader" style="width: 94px">User ID</th>
          <th class="tabheader" style="width: 264px">User Login</th>
          <th class="tabheader" style="width: 144px">Email</th>
          <th class="tabheader" style="width: 37px">Active</th>
          <th class="tabheader" style="width: 47px">View Detail</th>
          <th class="tabheader" style="width: 41px">Update</th>
          <th class="tabheader" style="width: 37px">Status</th>
          <th class="tabheader" style="width: 37px">Cancel</th>
         </tr></thead>
		 <tbody>
		 <?php 
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			
			$urlpop = 'upd_user_mas.php';
			$urlvm = 'vm_user_mas.php';
			echo '<tr>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$row['userid'].'</td>';
            echo '<td>'.$row['username'].'</td>';
            echo '<td>'.$row['email'].'</td>';
            echo '<td>'.$row['status'].'</td>';
			
			if ($var_accvie == 0){
            echo '<td><a href="#">[VIEW]</a>';'</td>';
            }else{
            echo '<td><a href="'.$urlvm.'?userid='.$row['userid'].'&usernm='.$row['username'].'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            }
			
			if ($var_accupd == 0){
            echo '<td><a href="#">[EDIT]</a>';'</td>';
            }else{
            echo '<td><a href="'.$urlpop.'?userid='.$row['userid'].'&usernm='.$row['username'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            }
			
			 
			echo '<td><input type="checkbox" name="usernm[]" value="'.$row['username'].'" />'.'</td>';
            echo '<td><input type="checkbox" name="usernm[]" value="'.$row['username'].'" />'.'</td>';
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
