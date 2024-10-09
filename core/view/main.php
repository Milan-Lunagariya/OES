<?php

global $managecategory, $databasehandler, $commonhelper;
$databasehandler = class_exists('databasehandler') ? new databasehandler() : '';

?>

<div class="homeMainPart">
    <div class="homeMainLeft"></div>
    <section class="homeMainCenterSection">
        <div class="home_categories_container">
            <?php 
            $categories_dbdata = $databasehandler->select('categories', '*', array(), '', 'categoryid DESC', 10);
            if( count( $categories_dbdata ) > 0 ){

                foreach ($categories_dbdata as $key => $value_categories) {
                    $images = isset($value_categories['images']) ? json_decode($value_categories['images'], true) : false;
                    $categoryImage = isset($images[0]) ? $images[0] : '';
                    if (defined('OESFRONT_MEDIA_PATH')) {
                        echo "<div style='float: left; padding: 10px;'>" . $commonhelper->set_imaage(OESFRONT_MEDIA_PATH . '/categories/' . $categoryImage) . "</div>";
                    } else {
                        echo 'Categories could not loaded. . .';
                        break;
                    }
                }
            } else {
                echo '<h4 align="center"> Category not available </h4>';
            }
            $parent_category = array("Mobiles", "Laptops", "Powerbank");
            ?>
        </div>
 
        <div class="HomeSliders">
            <div class="container"> 
                <div id="myCarousel" class="carousel slide" data-ride="carousel"> 
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                    </ol>
 
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="<?php echo OESFRONT_IMAGES_PATH . '/HomeSliders/home_slider_1.jpg'; ?>" alt="Online Electronic Shoppings" style="width:100%;">
                        </div>
                        <?php 
                            global $home_slider_array;
                            if( is_array( $home_slider_array ) && count( $home_slider_array ) ){

                                foreach( $home_slider_array as $image ){
                                    if( file_exists( OESFRONT_IMAGES_PATH .'/HomeSliders/'.$image ) ){
                                        
                                        echo '<div class="item">
                                        <img src="'.OESFRONT_IMAGES_PATH .'/HomeSliders/'.$image.'" alt="Online Electronic Shoppings" style="width:100%;">
                                        </div>';
                                    } else {
                                        echo "OESFRONT_IMAGES_PATH .'/HomeSliders/'.$image Does not exist ";
                                    }
                                    
                                }
                            }
                        ?>


                    </div>
  
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <div class="homeMainRight"></div>
</div>