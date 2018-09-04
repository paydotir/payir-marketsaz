<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $error_title; ?></title>
<base href="<?php echo $base; ?>" />
<style rel="stylesheet" media="screen, projection" type="text/css">
html {
	font-family:tahoma; font-size:12px; font-weight:normal; line-height:normal;
}
p {
	margin:0px 0px 20px; display:block;
}
.warning {
	width:450px; padding:10px; margin:20px auto; text-align:center; direction:rtl;
	border-bottom:2px solid #ff9999; border-radius:3px; box-sizing:border-box; background-color:#ffdfe0;
}
</style>
</head>
<body>
<div class="warning">
  <p><?php echo $error_warning; ?></p>
  <small><?php echo $error_wait; ?></small>
</div>
<script type="text/javascript"><!--
	setTimeout('location = \'<?php echo $continue; ?>\';', 5000);
//--></script>
</body>
</html>