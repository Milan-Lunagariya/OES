    <script src="../js/jquery.js"></script>
<?php 
    $common_js = ( isset($_REQUEST['page']) ) ?  '../js/common.js' : 'js/common.js';   
    /* $common_css = ( isset($_REQUEST['page']) ) ?  '../css/common.css' : 'css/common.css'; */   
?>
    <script src="<?php /* echo */ $common_js; ?>"></script> 
    <link rel="stylesheet" href="<?php /* echo */ $common_css; ?>">

