<?php 
    $common_js = ( isset($_REQUEST['page']) ) ?  '../js/common.js' : 'js/common.js';   
    $common_css = ( isset($_REQUEST['page']) ) ?  '../css/common.css' : 'css/common.css';   
?>
    <script src="../js/jquery.js"></script>
    <script src="<?= $common_js; ?>"></script>

    <link rel="stylesheet" href="<?php echo $common_css; ?>">
