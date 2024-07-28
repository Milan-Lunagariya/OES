<?php if( isset($_REQUEST['page']) ) : ?>
    <!-- <script src="../js/jquery.js"></script> -->
    <?php

    if( strtolower($_REQUEST['page']) == 'add_categories' ) {
        
        echo '<link rel="stylesheet" href="../css/forms.css">';
        
        echo '<script src="../js/jquery.js"></script>';
        echo '<script src="../js/admin_forms.js"></script>';
        
        
    }   
    
    ?>



<?php endif; ?>