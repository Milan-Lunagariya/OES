<?php if( isset($_REQUEST['page']) ) : ?>
    <!-- <script src="../js/jquery.js"></script> -->
<?php

    echo '<script src="../js/jquery.js"></script>'; 
    echo '<script src="../js/admin.js"></script>';
    
    
    switch( strtolower($_REQUEST['page']) ){
        case 'add_categories':   
            echo '<link rel="stylesheet" href="../css/forms.css">'; 
            echo '<script src="../js/admin_forms.js"></script>';
        break;
    }
         
?>



<?php endif; ?>