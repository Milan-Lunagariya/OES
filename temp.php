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
