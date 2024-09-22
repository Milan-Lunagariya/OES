<?php 

global $managecategory, $databasehandler, $commonhelper;
$databasehandler = class_exists( 'databasehandler' ) ? new databasehandler() : '';

?>

<div class="homeMainPart">
    <div class="homeMainLeft"></div>
    <section class="homeMainCenterSection">
            <div class="home_categories_container">
                <?php 
            $categories_dbdata = $databasehandler->select( 'categories', '*', array(), '', 'categoryid DESC', 10 );
            foreach( $categories_dbdata as $key => $value_categories ){        
                $images = isset( $value_categories['images'] ) ? json_decode( $value_categories['images'], true ) : false;
                $categoryImage = isset( $images[0] ) ? $images[0] : ''; 
                echo "<div style='float: left; padding: 10px;'>" . $commonhelper->set_imaage( MEDIA_PATH.'/categories/'.$categoryImage ) . "</div>";
            } 
            $parent_category = array( "Mobiles", "Laptops", "Powerbank" ); 
            ?>
        </div>
        
        <div class="HomeSliders">
            <div class="sliderPreviousArrow"> < </div>
            <div class="slider_container">
                <img src="<?php echo IMAGES_PATH.'/HomeSliders/home_slider_1.png'; ?>" alt="Slider 1">
            </div> 
            <div class="sliderNextArrow"> > </div>
        </div>
    </section>
    <div class="homeMainRight"></div>
</div>
