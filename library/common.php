<?php 
    $common_php = ( isset($_REQUEST['page']) ) ?  '../js/common.js' : 'js/common.js';   
?>
    <script src="../js/jquery.js"></script>
    <script src="<?= $common_php; ?>"></script>