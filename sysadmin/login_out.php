<?php
require("session_check.php");	
	set_sess_admin('');
	set_session('admin_role','');
	echo "<script type='text/javascript'>window.location.href='login.php';</script>";
?>