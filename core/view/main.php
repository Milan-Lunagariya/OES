<?php 

global $managecategory;

?>

<div class="menu_parent_category_container">
    <?php
    
    $parent_category = array( "Mobiles", "Laptops", "Powerbank" );
    
    echo $managecategory->all_parent_categories( $parent_category );
    ?>
</div>

