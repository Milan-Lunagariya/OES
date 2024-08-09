<?php

global $databasehandler, $datatable;
$databasehandler = new DatabaseHandler();



class datatable
{
    function __construct()
    {
    }
    function dataTableView()
    {
        global $databasehandler;
        $data = $databasehandler->select( 'categories', '*');
        
?> 
        <div class="datatable" >
            <table border="2" align="center">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Category Name</th>
                    <th>Parent Categort</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                    <th>Action</th>
                </tr>
                
                <?php
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
                }?>

            </table>
        </div>
<?php
    }
}
$datatable = new datatable();
?>