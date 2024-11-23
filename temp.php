<!-- Select function -->
<?php

use function PHPSTORM_META\type;

function selectData($table_name, $data = '*', $where_clause = '', $params = [], $groupby = '', $orderby = '', $limit = '') {
    try {
        // Database connection (adjust DSN, username, and password as per your setup)
        $dsn = 'mysql:host=your_host;dbname=your_db;charset=utf8mb4';
        $username = 'your_username';
        $password = 'your_password';
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Build base query
        $query = "SELECT $data FROM $table_name";

        // Add where clause if provided
        if (!empty($where_clause)) {
            $query .= " WHERE $where_clause";
        }

        // Add group by clause if provided
        if (!empty($groupby)) {
            $query .= " GROUP BY $groupby";
        }

        // Add order by clause if provided
        if (!empty($orderby)) {
            $query .= " ORDER BY $orderby";
        }

        // Add limit if provided
        if (!empty($limit)) {
            $query .= " LIMIT $limit";
        }

        // Prepare and execute the query
        $stmt = $pdo->prepare($query);
        $stmt->execute($params); 

        // Fetch data
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
/* -------------- */
// Where clause with placeholders
$where_clause = "name = ? AND category_id <> ?";

// Parameters to bind to placeholders (for 'test' and 102)
$params = ['test', 102];

// Call the selectData function
/* $result = selectData('categories', '*', $where_clause, $params); */

// Output the result 


?>
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
        $select = $databasehandler->select( 'categories', '*', array( array( 'column' => 'categoryid', 'value' => $value['parentid'], 'type' => PDO::PARAM_INT ) ) );    
        $parent = '';
        foreach( $select as $k => $v ){
            $parent = isset($v['name']) ? $v['name']."(".$v['categoryid'].')' : ''; 
        }
    } 
    $image = json_decode( $value['images'], true );
    $image_path = "../media/categories/.$image[0]";
?>
    <tr>  
        <td><?php echo ( isset($value['categoryid']) && !empty($value['categoryid']) ) ? $value['categoryid']: '-'; ?></td>
        <td><?php echo ( isset($value['images']) && !empty($value['images']) ) ? "<div class='image_parent'><a href='$image_path' ><img src='$image_path' alt='Not Found' width='100'></a></div>": '-'; ?></td>
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


if( ! defined( 'OES_EDIT_ICON' ) ){
            define( 'OES_EDIT_ICON', "<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' fill='#000000a8' version='1.1' id='Capa_1' width='30px' height='30px' viewBox='-49.49 -49.49 593.92 593.92' xml:space='preserve' stroke='#000000a8' stroke-width='0.00494936' transform='rotate(0)matrix(1, 0, 0, 1, 0, 0)'><g id='SVGRepo_bgCarrier' stroke-width='0' transform='translate(0,0), scale(1)'><rect x='-49.49' y='-49.49' width='593.92' height='593.92' rx='0' fill='#c1ffd0' stroke-width='0'/></g><g id='SVGRepo_tracerCarrier' stroke-linecap='round' stroke-linejoin='round' stroke='#28a745' stroke-width='43.554368'><g><g><path d='M389.844,182.85c-6.743,0-12.21,5.467-12.21,12.21v222.968c0,23.562-19.174,42.735-42.736,42.735H67.157 c-23.562,0-42.736-19.174-42.736-42.735V150.285c0-23.562,19.174-42.735,42.736-42.735h267.741c6.743,0,12.21-5.467,12.21-12.21 s-5.467-12.21-12.21-12.21H67.157C30.126,83.13,0,113.255,0,150.285v267.743c0,37.029,30.126,67.155,67.157,67.155h267.741 c37.03,0,67.156-30.126,67.156-67.155V195.061C402.054,188.318,396.587,182.85,389.844,182.85z'/><path d='M483.876,20.791c-14.72-14.72-38.669-14.714-53.377,0L221.352,229.944c-0.28,0.28-3.434,3.559-4.251,5.396l-28.963,65.069 c-2.057,4.619-1.056,10.027,2.521,13.6c2.337,2.336,5.461,3.576,8.639,3.576c1.675,0,3.362-0.346,4.96-1.057l65.07-28.963 c1.83-0.815,5.114-3.97,5.396-4.25L483.876,74.169c7.131-7.131,11.06-16.61,11.06-26.692 C494.936,37.396,491.007,27.915,483.876,20.791z M466.61,56.897L257.457,266.05c-0.035,0.036-0.055,0.078-0.089,0.107 l-33.989,15.131L238.51,247.3c0.03-0.036,0.071-0.055,0.107-0.09L447.765,38.058c5.038-5.039,13.819-5.033,18.846,0.005 c2.518,2.51,3.905,5.855,3.905,9.414C470.516,51.036,469.127,54.38,466.61,56.897z'/></g></g></g><g id='SVGRepo_iconCarrier'><g><g><path d='M389.844,182.85c-6.743,0-12.21,5.467-12.21,12.21v222.968c0,23.562-19.174,42.735-42.736,42.735H67.157 c-23.562,0-42.736-19.174-42.736-42.735V150.285c0-23.562,19.174-42.735,42.736-42.735h267.741c6.743,0,12.21-5.467,12.21-12.21 s-5.467-12.21-12.21-12.21H67.157C30.126,83.13,0,113.255,0,150.285v267.743c0,37.029,30.126,67.155,67.157,67.155h267.741 c37.03,0,67.156-30.126,67.156-67.155V195.061C402.054,188.318,396.587,182.85,389.844,182.85z'/><path d='M483.876,20.791c-14.72-14.72-38.669-14.714-53.377,0L221.352,229.944c-0.28,0.28-3.434,3.559-4.251,5.396l-28.963,65.069 c-2.057,4.619-1.056,10.027,2.521,13.6c2.337,2.336,5.461,3.576,8.639,3.576c1.675,0,3.362-0.346,4.96-1.057l65.07-28.963 c1.83-0.815,5.114-3.97,5.396-4.25L483.876,74.169c7.131-7.131,11.06-16.61,11.06-26.692 C494.936,37.396,491.007,27.915,483.876,20.791z M466.61,56.897L257.457,266.05c-0.035,0.036-0.055,0.078-0.089,0.107 l-33.989,15.131L238.51,247.3c0.03-0.036,0.071-0.055,0.107-0.09L447.765,38.058c5.038-5.039,13.819-5.033,18.846,0.005 c2.518,2.51,3.905,5.855,3.905,9.414C470.516,51.036,469.127,54.38,466.61,56.897z'/></g></g></g></svg>" );
          }
          if( ! defined( 'OES_DELETE_ICON' ) ){
            define( 'OES_DELETE_ICON', "<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' fill='#000000' version='1.1' id='Capa_1' width='30px' height='30px' viewBox='-48.24 -48.24 578.91 578.91' xml:space='preserve' stroke='#000000' stroke-width='0.00482428'><g id='SVGRepo_bgCarrier' stroke-width='0'><rect x='-48.24' y='-48.24' width='578.91' height='578.91' rx='0' fill='#ff767624' strokewidth='0'/></g><g id='SVGRepo_tracerCarrier' stroke-linecap='round' stroke-linejoin='round' stroke='#dc3545' stroke-width='24.1214'><g><g><path d='M381.163,57.799h-75.094C302.323,25.316,274.686,0,241.214,0c-33.471,0-61.104,25.315-64.85,57.799h-75.098c-30.39,0-55.111,24.728-55.111,55.117v2.828c0,23.223,14.46,43.1,34.83,51.199v260.369c0,30.39,24.724,55.117,55.112,55.117h210.236c30.389,0,55.111-24.729,55.111-55.117V166.944c20.369-8.1,34.83-27.977,34.83-51.199v-2.828C436.274,82.527,411.551,57.799,381.163,57.799z M241.214,26.139c19.037,0,34.927,13.645,38.443,31.66h-76.879C206.293,39.783,222.184,26.139,241.214,26.139z M375.305,427.312c0,15.978-13,28.979-28.973,28.979H136.096c-15.973,0-28.973-13.002-28.973-28.979V170.861h268.182V427.312z M410.135,115.744c0,15.978-13,28.979-28.973,28.979H101.266c-15.973,0-28.973-13.001-28.973-28.979v-2.828c0-15.978,13-28.979,28.973-28.979h279.897c15.973,0,28.973,13.001,28.973,28.979V115.744z'/><path d='M171.144,422.863c7.218,0,13.069-5.853,13.069-13.068V262.641c0-7.216-5.852-13.07-13.069-13.07c-7.217,0-13.069,5.854-13.069,13.07v147.154C158.074,417.012,163.926,422.863,171.144,422.863z'/><path d='M241.214,422.863c7.218,0,13.07-5.853,13.07-13.068V262.641c0-7.216-5.854-13.07-13.07-13.07c-7.217,0-13.069,5.854-13.069,13.07v147.154C228.145,417.012,233.996,422.863,241.214,422.863z'/><path d='M311.284,422.863c7.217,0,13.068-5.853,13.068-13.068V262.641c0-7.216-5.852-13.07-13.068-13.07c-7.219,0-13.07,5.854-13.07,13.07v147.154C298.213,417.012,304.067,422.863,311.284,422.863z'/></g></g></g><g id='SVGRepo_iconCarrier'><g><g><path d='M381.163,57.799h-75.094C302.323,25.316,274.686,0,241.214,0c-33.471,0-61.104,25.315-64.85,57.799h-75.098c-30.39,0-55.111,24.728-55.111,55.117v2.828c0,23.223,14.46,43.1,34.83,51.199v260.369c0,30.39,24.724,55.117,55.112,55.117h210.236c30.389,0,55.111-24.729,55.111-55.117V166.944c20.369-8.1,34.83-27.977,34.83-51.199v-2.828C436.274,82.527,411.551,57.799,381.163,57.799z M241.214,26.139c19.037,0,34.927,13.645,38.443,31.66h-76.879C206.293,39.783,222.184,26.139,241.214,26.139z M375.305,427.312c0,15.978-13,28.979-28.973,28.979H136.096c-15.973,0-28.973-13.002-28.973-28.979V170.861h268.182V427.312z M410.135,115.744c0,15.978-13,28.979-28.973,28.979H101.266c-15.973,0-28.973-13.001-28.973-28.979v-2.828c0-15.978,13-28.979,28.973-28.979h279.897c15.973,0,28.973,13.001,28.973,28.979V115.744z'/><path d='M171.144,422.863c7.218,0,13.069-5.853,13.069-13.068V262.641c0-7.216-5.852-13.07-13.069-13.07c-7.217,0-13.069,5.854-13.069,13.07v147.154C158.074,417.012,163.926,422.863,171.144,422.863z'/><path d='M241.214,422.863c7.218,0,13.07-5.853,13.07-13.068V262.641c0-7.216-5.854-13.07-13.07-13.07c-7.217,0-13.069,5.854-13.069,13.07v147.154C228.145,417.012,233.996,422.863,241.214,422.863z'/><path d='M311.284,422.863c7.217,0,13.068-5.853,13.068-13.068V262.641c0-7.216-5.852-13.07-13.068-13.07c-7.219,0-13.07,5.854-13.07,13.07v147.154C298.213,417.012,304.067,422.863,311.284,422.863z'/></g></g></g></svg>" );
          }


          /* 
$( document ).on('submit', '#add_categoryform, #edit_categoryform', function(){  
    var is_categoryname, is_parentcategory, is_categoryimage;
    var form_id = $( this ).attr( 'id' );
    var is_editCategory = ( form_id == 'edit_categoryform' ) ? true : false;
    var action = ( is_editCategory ) ? 'edit_category' : 'add_categoryform';
    var button_value = ( is_editCategory ) ? 'Edit' : 'Add';
    var is_pageRefresh = false;
    var current_page = $( '.managecategory_currentpage' ).val();
    current_page = ( current_page != '' || current_page != undefined || current_page != null ) ? current_page : 1;
    var category_record_showLimit = $( '.category_record_showLimit' ).val(); 
    category_record_showLimit = ( category_record_showLimit != '' || category_record_showLimit != undefined || category_record_showLimit != null ) ? category_record_showLimit : 5;

    if( form_id == 'add_categoryform' ){
        is_categoryimage =  field_validation('#categoryimage');
        is_categoryname =  field_validation('#categoryname');
        is_parentcategory =  field_validation('#parentcategory');
    } else{
        is_categoryimage = true;
        is_categoryname =  field_validation('#categoryname');
        is_parentcategory =  field_validation('#parentcategory');
    }
     
    const url = '../core/models/modelcategory.php';
    const callback = function(data){ 
        var data = JSON.parse( data );
        var created_message, success_message = '', success_selector = '';

        console.log( '---------- action ----------: ' + action );
        console.log( '---------- edit_category data ----------: ' + JSON.stringify(data) );
        console.log( '---------- data.is_image_upload ----------: ' + data.is_image_upload );
        oes_loader( '#category_form_submit', false, button_value );  

        if( ( data.success == 1 || data.success == true || data.success == '1' ) ){  
            $('.category_form_message').removeClass('message_error'); 
            success_message = (is_editCategory) ? "Success! The category has been updated." : "Success! The category has been added.";
            success_selector = (is_editCategory) ? ".manageCategories_message" : ".category_form_message";            
            created_message = $( success_selector ).fadeIn().html( success_message );
            show_message_popup(created_message, 2000);
            $( '#' + form_id )[0].reset();  
            $('#parentcategory').append(data.parentCategoryOption);
            if( is_editCategory ){
                $( '.editCategory_popup_container' ).fadeOut();
                console.log( 'After edit category check the crrent page: ' + current_page );
                refreshCategory_DataTable( current_page, category_record_showLimit, function(){
                    $( '.datatable tr:odd' ).css('background-color', 'aliceblue');
                } );
            }
        }else{
            $('.category_form_message').addClass('message_error'); 
            var message = $('.message_error').  css('transform','scale(1)').fadeOut().fadeIn().html("Error: "+ data.error); 
        }
    }; 

    var extra_data = { 
        'action': action, 
    }; 

    if( is_categoryimage == true && is_categoryname == true && is_parentcategory == true ){ 
        oes_loader( '#category_form_submit', true );
        ajax_form_submitor( url, callback, this, extra_data ); 
    } 
    return is_pageRefresh; 
});
 */

 product


 INSERT INTO `products` (`categoryids`, `images`, `name`, `description`, `price`, `stock`, `createdat`) VALUES
('[\"120\",\"131\"]', '[[\"1728834668_samsung_1.jpeg\"]]', 'Samsung 1', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellat eos, reiciendis rem nesciunt architecto eaque laudantium. Sit quas nam doloribus vero similique tempore ut culpa ex ipsum, saepe laudantium? Iure natus dolorem commodi minima dicta cumque sapiente placeat ab ut nostrum, molestiae enim iste ducimus suscipit voluptatum in perferendis id consequatur repudiandae excepturi aliquam soluta corrupti, quis ipsam. Laboriosam corporis, eaque natus aspernatur error temporibus harum? Eaque exercitationem laudantium eos temporibus voluptatum commodi possimus magni sapiente, doloribus perferendis ullam odit veniam asperiores dolorum sint non accusamus, perspiciatis fugit at? Itaque suscipit excepturi quo voluptatem, vitae maxime dolore optio, commodi impedit repellendus delectus porro nobis facilis minus ut, sequi debitis quasi? Vel aliquam sunt nihil voluptatum illum laudantium maxime rerum quas!', 50000, 25, current_timestamp ),
('[\"120\",\"131\"]', '[[\"1728834760_onePlus.jpeg\"]]', 'One Plus', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellat eos, reiciendis rem nesciunt architecto eaque laudantium. Sit quas nam doloribus vero similique tempore ut culpa ex ipsum, saepe laudantium? Iure natus dolorem commodi minima dicta cumque sapiente placeat ab ut nostrum, molestiae enim iste ducimus suscipit voluptatum in perferendis id consequatur repudiandae excepturi aliquam soluta corrupti, quis ipsam. Laboriosam corporis, eaque natus aspernatur error temporibus harum? Eaque exercitationem laudantium eos temporibus voluptatum commodi possimus magni sapiente, doloribus perferendis ullam odit veniam asperiores dolorum sint non accusamus, perspiciatis fugit at? Itaque suscipit excepturi quo voluptatem, vitae maxime dolore optio, commodi impedit repellendus delectus porro nobis facilis minus ut, sequi debitis quasi? Vel aliquam sunt nihil voluptatum illum laudantium maxime rerum quas!', 12000, 10, current_timestamp ),
('[\"120\",\"132\"]', '[[\"1728835423_iphone_13.jpeg\"]]', 'iPhone 13', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellat eos, reiciendis rem nesciunt architecto eaque laudantium. Sit quas nam doloribus vero similique tempore ut culpa ex ipsum, saepe laudantium? Iure natus dolorem commodi minima dicta cumque sapiente placeat ab ut nostrum, molestiae enim iste ducimus suscipit voluptatum in perferendis id consequatur repudiandae excepturi aliquam soluta corrupti, quis ipsam. Laboriosam corporis, eaque natus aspernatur error temporibus harum? Eaque exercitationem laudantium eos temporibus voluptatum commodi possimus magni sapiente, doloribus perferendis ullam odit veniam asperiores dolorum sint non accusamus, perspiciatis fugit at? Itaque suscipit excepturi quo voluptatem, vitae maxime dolore optio, commodi impedit repellendus delectus porro nobis facilis minus ut, sequi debitis quasi? Vel aliquam sunt nihil voluptatum illum laudantium maxime rerum quas!', 49000, 20, current_timestamp ),
('[\"120\",\"132\"]', '[[\"1728835452_iphone_14.jpeg\"]]', 'IPhone 14', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellat eos, reiciendis rem nesciunt architecto eaque laudantium. Sit quas nam doloribus vero similique tempore ut culpa ex ipsum, saepe laudantium? Iure natus dolorem commodi minima dicta cumque sapiente placeat ab ut nostrum, molestiae enim iste ducimus suscipit voluptatum in perferendis id consequatur repudiandae excepturi aliquam soluta corrupti, quis ipsam. Laboriosam corporis, eaque natus aspernatur error temporibus harum? Eaque exercitationem laudantium eos temporibus voluptatum commodi possimus magni sapiente, doloribus perferendis ullam odit veniam asperiores dolorum sint non accusamus, perspiciatis fugit at? Itaque suscipit excepturi quo voluptatem, vitae maxime dolore optio, commodi impedit repellendus delectus porro nobis facilis minus ut, sequi debitis quasi? Vel aliquam sunt nihil voluptatum illum laudantium maxime rerum quas!', 74000, 10, current_timestamp ),
('[\"120\",\"132\"]', '[[\"1728835520_iphone_12.jpeg\"]]', 'iPhone 12', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellat eos, reiciendis rem nesciunt architecto eaque laudantium. Sit quas nam doloribus vero similique tempore ut culpa ex ipsum, saepe laudantium? Iure natus dolorem commodi minima dicta cumque sapiente placeat ab ut nostrum, molestiae enim iste ducimus suscipit voluptatum in perferendis id consequatur repudiandae excepturi aliquam soluta corrupti, quis ipsam. Laboriosam corporis, eaque natus aspernatur error temporibus harum? Eaque exercitationem laudantium eos temporibus voluptatum commodi possimus magni sapiente, doloribus perferendis ullam odit veniam asperiores dolorum sint non accusamus, perspiciatis fugit at? Itaque suscipit excepturi quo voluptatem, vitae maxime dolore optio, commodi impedit repellendus delectus porro nobis facilis minus ut, sequi debitis quasi? Vel aliquam sunt nihil voluptatum illum laudantium maxime rerum quas!', 53000, 6, current_timestamp ),
('[\"119\",\"130\"]', '[[\"1728835584_hp.jpeg\"]]', 'HP 858', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellat eos, reiciendis rem nesciunt architecto eaque laudantium. Sit quas nam doloribus vero similique tempore ut culpa ex ipsum, saepe laudantium? Iure natus dolorem commodi minima dicta cumque sapiente placeat ab ut nostrum, molestiae enim iste ducimus suscipit voluptatum in perferendis id consequatur repudiandae excepturi aliquam soluta corrupti, quis ipsam. Laboriosam corporis, eaque natus aspernatur error temporibus harum? Eaque exercitationem laudantium eos temporibus voluptatum commodi possimus magni sapiente, doloribus perferendis ullam odit veniam asperiores dolorum sint non accusamus, perspiciatis fugit at? Itaque suscipit excepturi quo voluptatem, vitae maxime dolore optio, commodi impedit repellendus delectus porro nobis facilis minus ut, sequi debitis quasi? Vel aliquam sunt nihil voluptatum illum laudantium maxime rerum quas!', 56000, 3, current_timestamp ),
('[\"119\",\"129\"]', '[[\"1728835772_Display.jpg\",\"1728835772_front.jpg\",\"1728835772_front_2.jpg\",\"1728835772_info.jpg\"]]', 'Lanovo', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellat eos, reiciendis rem nesciunt architecto eaque laudantium. Sit quas nam doloribus vero similique tempore ut culpa ex ipsum, saepe laudantium? Iure natus dolorem commodi minima dicta cumque sapiente placeat ab ut nostrum, molestiae enim iste ducimus suscipit voluptatum in perferendis id consequatur repudiandae excepturi aliquam soluta corrupti, quis ipsam. Laboriosam corporis, eaque natus aspernatur error temporibus harum? Eaque exercitationem laudantium eos temporibus voluptatum commodi possimus magni sapiente, doloribus perferendis ullam odit veniam asperiores dolorum sint non accusamus, perspiciatis fugit at? Itaque suscipit excepturi quo voluptatem, vitae maxime dolore optio, commodi impedit repellendus delectus porro nobis facilis minus ut, sequi debitis quasi? Vel aliquam sunt nihil voluptatum illum laudantium maxime rerum quas!', 90000, 5, current_timestamp ),
('[\"121\",\"122\",\"123\"]', '[[\"1728835954_1.jpg\",\"1728835954_2.jpg\",\"1728835954_3.jpg\",\"1728835954_4.jpg\"]]', 'Rado W282', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellat eos, reiciendis rem nesciunt architecto eaque laudantium. Sit quas nam doloribus vero similique tempore ut culpa ex ipsum, saepe laudantium? Iure natus dolorem commodi minima dicta cumque sapiente placeat ab ut nostrum, molestiae enim iste ducimus suscipit voluptatum in perferendis id consequatur repudiandae excepturi aliquam soluta corrupti, quis ipsam. Laboriosam corporis, eaque natus aspernatur error temporibus harum? Eaque exercitationem laudantium eos temporibus voluptatum commodi possimus magni sapiente, doloribus perferendis ullam odit veniam asperiores dolorum sint non accusamus, perspiciatis fugit at? Itaque suscipit excepturi quo voluptatem, vitae maxime dolore optio, commodi impedit repellendus delectus porro nobis facilis minus ut, sequi debitis quasi? Vel aliquam sunt nihil voluptatum illum laudantium maxime rerum quas!', 92000, 5, current_timestamp ),
('[\"124\",\"125\"]', '[[\"1728836037_iphone_cover.jpeg\",\"1728836037_usb_cover.jpeg\"]]', 'Cover Light', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellat eos, reiciendis rem nesciunt architecto eaque laudantium. Sit quas nam doloribus vero similique tempore ut culpa ex ipsum, saepe laudantium? Iure natus dolorem commodi minima dicta cumque sapiente placeat ab ut nostrum, molestiae enim iste ducimus suscipit voluptatum in perferendis id consequatur repudiandae excepturi aliquam soluta corrupti, quis ipsam. Laboriosam corporis, eaque natus aspernatur error temporibus harum? Eaque exercitationem laudantium eos temporibus voluptatum commodi possimus magni sapiente, doloribus perferendis ullam odit veniam asperiores dolorum sint non accusamus, perspiciatis fugit at? Itaque suscipit excepturi quo voluptatem, vitae maxime dolore optio, commodi impedit repellendus delectus porro nobis facilis minus ut, sequi debitis quasi? Vel aliquam sunt nihil voluptatum illum laudantium maxime rerum quas!', 50, 6, current_timestamp );


For the Category search button 
<div class="searchRecord">
    <input type="search" name="" class="datatable_field searchCategoriesOnMC" placeholder="Search category name" id=""><button class="datatable_field searchCategoriesButton"> '.$search_icon.' </button>
</div>