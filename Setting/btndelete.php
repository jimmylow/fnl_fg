<?php
   if ($var_accdel == 0){
     echo '<input type=submit name = "Submit" value="Delete" disabled="disabled" class="butsub" style="width: 60px; height: 32px">';
   }else{
     echo '<input type=submit name = "Submit" value="Delete" class="butsub" style="width: 60px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
   } 
?>
