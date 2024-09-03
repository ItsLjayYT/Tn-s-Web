<?php
include 'cred/_dbconnect.php';
include 'Global.php';
if($maintenance == false){
     $dbconnect->query("DELETE FROM `userkeys` WHERE `CreatedKeys` = '".$_GET['no']."'");   
}
?>
<script type="text/javascript">
	alert("Key successfully removed");
	window.location.href='panel.php';
</script>