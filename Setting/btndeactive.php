<?php
   if ($var_accdel == 0){
     echo '<input type=submit name = "Submit" value="Deactive" disabled="disabled" class="butsub" style="width: 70px; height: 32px">';
   }else{
     echo '<input type=submit name = "Submit" value="Deactive" class="butsub" style="width: 70px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
   } 
?>
