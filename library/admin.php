<?php if( isset($_REQUEST['page']) ) : ?> 

    <script src="../js/jquery.js"></script>
    <script src="../js/admin.js"></script>
    <script src="../js/common.js"></script>
<?php

    
    switch( strtolower($_REQUEST['page']) ){
        case 'add_categories':   
        case 'manage_categories':   
            echo '<link rel="stylesheet" href="../css/forms.css">'; 
            echo '<script src="../js/admin_forms.js"></script>'; 
        break;
    }
         
?>



<?php endif; ?>