<?php

global $managecategory, $databasehandler, $commonhelper;
$databasehandler = class_exists('databasehandler') ? new databasehandler() : '';

?>
<?php if( ! isset( $_REQUEST['page'] ) || isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'home' ){ ?>
    <div class="homeMainPart"> 
        <section class="homeMainCenterSection">
            <!-- <div class="home_categories_container">
                <?php 
                   
                /* $categories_dbdata = $databasehandler->select('categories', '*', array(), '', 'categoryid DESC', 10);
                if( count( $categories_dbdata ) > 0 ){

                    foreach ($categories_dbdata as $key => $value_categories) {
                        $images = isset($value_categories['images']) ? json_decode($value_categories['images'], true) : false;
                        $categoryImage = isset($images[0]) ? $images[0] : '';
                        if (defined('OESFRONT_MEDIA_PATH')) {
                            echo "<div style='float: left; padding: 10px;'>" . $commonhelper->set_image(OESFRONT_MEDIA_PATH . '/categories/' . $categoryImage) . "</div>";
                        } else {
                            echo 'Categories could not loaded. . .';
                            break;
                        }
                    }
                } else {
                    echo '<h4 align="center"> Category not available </h4>';
                }
                $parent_category = array("Mobiles", "Laptops", "Powerbank"); */
                ?>
            </div> -->
    
            <div class="HomeSliders">
                <div class="container"> 
                    <div id="myCarousel" class="carousel slide" data-ride="carousel"> 
                        
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
                            <span class="sr-only"><</span>
                        </a>
                        <a class="right carousel-control" href="#myCarousel" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                            <span class="sr-only">></span>
                        </a>
                    </div>
                </div>
            </div>
        </section> 
    </div>
    <?php 
         global $commonhelper;
         $commonhelper->front_categories();    
    ?>

<?php } elseif( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'about' || $_REQUEST['page'] == 'aboutus' || $_REQUEST['page'] == 'about_us' ){ ?>
    <div class="oes_aboutusFront">
        <div class="container">
            <h1>About Us</h1>
            
            <p>
                Welcome to Stellar Solutions! We are dedicated to providing innovative software solutions that empower businesses to achieve their goals. Founded in 2010, our mission has always been to create cutting-edge technology that enhances efficiency and productivity.
            </p>
            <p>
                At Stellar Solutions, we believe in the power of teamwork. Our diverse team of experts works collaboratively to deliver exceptional results for our clients. We pride ourselves on our commitment to excellence and customer satisfaction.
            </p>
            <p>
                Our core values include integrity, innovation, and dedication. We strive to foster an inclusive environment where creativity and ideas can thrive. Thank you for visiting our About Us page, and we look forward to partnering with you on your journey to success!
            </p>
        </div>
    </div>
<?php } else if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'categories' || $_REQUEST['page'] == 'subCategories' ){ 
$commonhelper;
$commonhelper->front_categories();    

} elseif( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'products' || isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'all_products' ){

    global $databasehandler;
    $page = $_REQUEST['page'];
    $show_productIds = array();
    $is_showAll_products = ($page == 'all_products' ) ? true : false;
    $error = '';
    $getcategoryid = isset( $_REQUEST['categoryid'] ) ? $_REQUEST['categoryid'] : 0;
    
    $dbProducts_for_categoryid = $databasehandler->select( 'products', 'categoryids, productid', array(), '', 'productid DESC' );
    if( is_array( $dbProducts_for_categoryid ) && count( $dbProducts_for_categoryid ) > 0 ){   
        foreach( $dbProducts_for_categoryid as $key => $value ){
            $categoryIds =  isset( $value['categoryids'] ) ? json_decode( $value['categoryids'], true ) : array();
            $productid =  isset( $value['productid'] ) ? json_decode( $value['productid'], true ) : array();
            
            if( $categoryIds != 0 ){
                if( in_array( $getcategoryid, $categoryIds ) ){ 
                    $show_productIds[] =  $productid;
                }
            }
        }

        if( count( $show_productIds ) > 0 ){ 
            $where = array( array( 'column' => 'productid', 'value' => $show_productIds, 'operator' => 'IN', 'type' => PDO::PARAM_INT ) );
            $products = $databasehandler->select( 'products', '*', $where, '', 'productid DESC' );
            foreach(  $products as $key => $value ){
                
                $images =  isset( $value['images'] ) ? json_decode( $value['images'], true ) : array(); 
                $image =  isset( $images[0][0] ) ? $images[0][0] : array(); // First Image only
                /* echo "<img src='" . OESFRONT_MEDIA_PATH . '/products/' . $image . "' class='card-img-top category-image' alt='{$value['name']}'>"; */
            }
        }
    } else{
        $error = '<h4 align="center">Products not available</h4>';
    }
    
    if (count($show_productIds) > 0 || $is_showAll_products == true ) {
        
        if( $is_showAll_products == true ){
            $products = $databasehandler->select('products', '*', array(), '', 'productid DESC');
        } else {
            $where = array(array('column' => 'productid', 'value' => $show_productIds, 'operator' => 'IN', 'type' => PDO::PARAM_INT));
            $products = $databasehandler->select('products', '*', $where, '', 'productid DESC');
        }
        if (count($products) > 0){
 
            echo "<div class='oesProductsCart'><div class='container'><h1>Products</h1><div class='row'>";
            foreach ($products as $key => $value) {
                $images = isset($value['images']) ? json_decode($value['images'], true) : array();
                $image = isset($images[0][0]) ? $images[0][0] : '';
                $description = isset($value['description']) ? $value['description'] : 'No description available';
                $shortDescription = strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                $productid = isset( $value['productid'] ) ? $value['productid'] : 0;

                echo "
                <div class='col-md-4 col-sm-6 mb-4'>
                    <div class='card h-100 shadow-sm transition-hover'> <!-- Shadow and transition on hover -->
                        <img src='" . OESFRONT_MEDIA_PATH . '/products/' . $image . "' class='card-img-top category-image' alt='{$value['name']}'>
                        <div class='card-body'>
                            <h5 class='card-title'>{$value['name']}</h5>
                            <p class='card-text'>Price: \${$value['price']}</p>
                            <div class='short-description' style='height: 60px; overflow: hidden;'>
                                <p class='card-text'>{$shortDescription}</p>
                            </div>
                            <a href='index.php?page=product_detail&productid=$productid' class='btn btn-primary'>View Details</a>
                        </div>
                    </div>
                </div>";
            }
            echo "</div></div></div>";
        }
    } else {
        $error =  '<h4 align="center">Here, No products available . . .</h4>';
    }
    echo $error;
    
} elseif( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'product_detail' ){
    global $commonhelper;
    $page = $_REQUEST['page'];
    $productid = isset( $_REQUEST['productid'] ) ? $_REQUEST['productid'] : 0;
    $error = '';
    if( $productid > 0 ){

        $where = array( array( 'column' => 'productid', 'value' => $productid, 'operator' => '=', 'type' => PDO::PARAM_INT ) );
        $products = $databasehandler->select( 'products', '*', $where, '', 'productid DESC' );
 
        /* if( is_array( $products ) && count( $products ) > 0 ){  
            foreach ($products as $key => $value) {
                $images = isset($value['images']) ? json_decode($value['images'], true) : array();
                $images = isset($images[0]) ? $images[0] : '';
                $description = isset($value['description']) ? $value['description'] : 'No description available';
                $shortDescription = strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                $productid = isset( $value['productid'] ) ? $value['productid'] : 0;
            }
        } */
        if (is_array($products) && count($products) > 0) {
            foreach ($products as $key => $value) {
                $images = isset($value['images']) ? json_decode($value['images'], true) : array();
                $description = isset($value['description']) ? $value['description'] : 'No description available';
                $shortDescription = strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                $productName = isset($value['name']) ? $value['name'] : 'Product Name';
                $price = isset($value['price']) ? $value['price'] : 'N/A';
                $stock = isset($value['stock']) ? $value['stock'] : 0; // Stock availability
                $createdAt = isset($value['createdat']) ? $value['createdat'] : 'N/A'; // Creation date
                ?>

                <div class="oesProductDetail container mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Image Carousel -->
                            <div id="productCarousel" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <?php if (!empty($images[0])): ?>
                                        <?php foreach ($images[0] as $index => $image): ?>
                                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                                <img src="<?php echo OESFRONT_MEDIA_PATH . '/products/' . $image; ?>" class="d-block w-100" alt="<?php echo $productName; ?>" style="height: 400px; object-fit: contain;">
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="carousel-item active">
                                            <img src="placeholder.jpg" class="d-block w-100" alt="No Image Available" style="height: 400px; object-fit: contain;"> <!-- Placeholder image -->
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only"><</span>
                                </a>
                                <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">></span>
                                </a>
                            </div>
                            <!-- Thumbnail Images -->
                            <div class="mt-2 d-flex showall_productImageContainer">
                                <?php foreach ($images[0] as $index => $image): ?>
                                    <img src="<?php echo OESFRONT_MEDIA_PATH . '/products/' . $image; ?>" 
                                         class="thumbnail-image" 
                                         alt="<?php echo $productName; ?>" 
                                         data-target="#productCarousel" 
                                         data-slide-to="<?php echo $index; ?>">
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h2><?php echo $productName; ?></h2>
                            <p class="lead">Price: $<?php echo $price; ?></p>
                            <p><strong>Stock:</strong> <?php echo $stock > 0 ? $stock . ' available' : 'Out of stock'; ?></p>
                            <p><strong>Created At:</strong> <?php echo $createdAt; ?></p> <!-- Adjusted title -->
                            <div class="product_cart_buy_ButtonContainer w-100">
                                <button class="btn btn-primary">Add to Cart</button>
                                <button class="btn btn-success">Buy Now</button> <!-- Buy Now Button -->
                            </div>
                            <p><strong>Description:</strong></p> <!-- Title for Description --> 
                            <p><?php echo $description; ?></p>
                        </div>
                    </div>
                </div>

                <?php
            }
        } else {
            $error = '<h2 align="center">This product is not available.</h2>';
        }
        $commonhelper->front_categories();    
    } else{
        $error = '<h2 align="center">Now this Product not availabel</h2>';
    }
    echo $error;
} else{ 
   global $commonhelper;
   echo $commonhelper->page_404();
}?>