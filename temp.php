<!-- Header -->
<header class="header_container"> 
    <div class="fixed_nav">
      <div class="left">
        <div class="logo">
          <img src="images/flipcart.png" height="100" class="image" alt="GadgetGalaxy">
        </div> 
      </div>

      <div class="center">
        <div class="searchbar">
          <!-- <label for="nav_search"></label> -->
          <input type="search" name="" placeholder=">Search for produc, brands and more" class="nav_search">
        </div>
      </div>

      <div class="right"> 
        <div class="cart_button">
          <i class="fa-solid fa-cart-shopping button cart_icon"></i>
        </div>
        <div class="login_button">
          <i class="fa-regular fa-user button"></i>
		</div> 
      </div>
    </div> 
</header>

<?php 
/*   if(isset($_FILES['categoryimage']['name']) && !empty($_FILES['categoryimage']['name']) ) {
            $imageName  = $_FILES['categoryimage']['name'];
            $imageName_withTime  = time().'_'.$_FILES['categoryimage']['name'];
            $imageTempName       = $_FILES['categoryimage']['tmp_name'];
            $imageSize           = $_FILES['categoryimage']['size']; 
            $imageExtension      = trim(pathinfo($_FILES['categoryimage']['name'], PATHINFO_EXTENSION));

            if( $imageSize <= ( 5 * 1024 * 1024 )  ){ 
                if( in_array(strtolower($imageExtension), ['jpeg', 'png', 'jpg']) ){ 
                    $imageDestination = 'media/categories/'.$imageName_withTime;
                    if(move_uploaded_file($imageTempName, $imageDestination)){
                        $is_imageValid = true;
                    } else{ $error .= "$br Move uploaded filed time error"; }

                } else{ $error .= "$br File is Invalid: $imageExtension not allowed"; }

            } else{ $error .= "$br Please ensure the file size is 5 MB or less."; }
        } 
        $imageName_withTime =  ( $is_imageValid == true ) ? $imageName_withTime : '';  */

/* ----------------------------------------------------------------------------- */
/* Data table of td for the view categories */ 
$i = 0;
foreach( $data as $key => $value ){  

    if( in_array($value['parentid'],['0', 0]) ) {
        $parent = "Parent (0)";  
    } else{
        $select = $databasehandler->select( 'categories', '*', array('categoryid' => $value['parentid']) );    
        $parent = '';
        foreach( $select as $k => $v ){
            $parent = isset($v['name']) ? $v['name']."(".$v['categoryid'].')' : ''; 
        }
    } 
    $image = json_decode( $value['images'], true );
?>
    <tr>  
        <td><?php echo ( isset($value['categoryid']) && !empty($value['categoryid']) ) ? $value['categoryid']: '-'; ?></td>
        <td><?php echo ( isset($value['images']) && !empty($value['images']) ) ? "<div class='image_parent'><img src='../media/categories/".$image[0]."' alt='Not Found' width='100'></div>": '-'; ?></td>
        <td><?php echo ( isset($value['name']) && !empty($value['name']) ) ? $value['name']: '-'; ?></td>
        <td><?php echo ( isset($parent) && !empty($parent) ) ? $parent : '-'; ?></td>
        <td><?php echo ( isset($value['createdat']) && !empty($value['createdat']) ) ? $value['createdat']: '-'; ?></td>
        <td><?php echo ( isset($value['updatedat']) && !empty($value['updatedat']) ) ? $value['updatedat']: '-'; ?></td>
        <td>
            <button class="edit"> Edit<!-- <i class="fa-solid fa-pen-to-square"></i> --> </button>
            <button class="remove"> Remove<!-- <i class="fa-solid fa-trash"></i> --> </button>
        </td>

    </tr>                    
<?php 
$i++;
}
?>

////////////
--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`name`, `parentid`, `createdat`) VALUES
('Mobile', 0, CURRENT_TIMESTAMP),
('iPhone 12', 32, CURRENT_TIMESTAMP),
('iPhone 13', 32, CURRENT_TIMESTAMP),
('IPhone 14', 32, CURRENT_TIMESTAMP),
('Laptops', 0, CURRENT_TIMESTAMP),
('HP', 36, CURRENT_TIMESTAMP),
('Lenovo', 36, CURRENT_TIMESTAMP),
('Mackbook', 36, CURRENT_TIMESTAMP),
('Accessories', 0, CURRENT_TIMESTAMP),
('Cable All in One', 40, CURRENT_TIMESTAMP),
('Smart Speaker', 40, CURRENT_TIMESTAMP),
('USB Cover', 40, CURRENT_TIMESTAMP);