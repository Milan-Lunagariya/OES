<?php


global $databasehandler, $datatable, $commonhelper;
if( class_exists('DatabaseHandler') ){
    $databasehandler = new DatabaseHandler();
}
if( class_exists('commonhelper') ){
    $commonhelper = new commonhelper();
}

class datatable
{
    function __construct()
    {
    }
    function dataTableView( $th = array(), $td = array(), $tr_td_class = '' )
    {
        global $databasehandler, $commonhelper;
        $data = $databasehandler->select( 'categories', '*');
        
?> 
        <div class="datatable" >
            <table border="2" align="center">
                <tr>
                    <?php 
                        $th = isset( $th['th'] ) ? $th['th'] : array();

                        if( count($th) > 0 ){
                            foreach( $th as $th_key => $th_value ){
                                echo "<th>$th_value</th>";
                            }
                        }
                    ?>
                </tr>
               
                <?php 
                    if( count($td) > 0 ){
                        foreach( $td as $td_key => $td_value ){
                            echo "<tr class='$tr_td_class'>";
                                foreach( $td_value as $td_meta_key => $td_meta_value ){                                 
                                    if( is_string($td_meta_value) || is_numeric($td_meta_value) ){
                                        echo  "<td>$td_meta_value</td>" ;
                                    } else if( is_array( $td_meta_value ) ){ 
                                        foreach( $td_meta_value as $meta_of_key => $meta_of_value ){    
                                            echo  "<td>$meta_of_value</td>" ;
                                        }
                                    }
                                }
                            echo '</tr>';
                        }
                    } else{
                        $colspan = count( $th );
                        echo "<tr><td align='center' colspan='$colspan'>The record set is empty</td></tr>";
                    }
                ?>  
              
            </table>
        </div>
<?php
    }
}
$datatable = new datatable();
?>