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
    function dataTableView( $th = array(), $td = array(),  $tr_td_class = '', $total_pages, $current_page )
    {
        global $databasehandler, $commonhelper; 
        
?> 
        <div class="datatable" >
            <div class="dataTableHeader">
                <div class="showRecordsSelectPicker">
                    Show 
                    <select class="datatable_field record_limit" name="" id="">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    records
                </div>
                <div class="searchRecord">
                    <input type="search" name="" class="datatable_field" placeholder="Search Record" id="">
                </div>
                <div class="showHideColumn datatable_field"> Show/Hide Column </div>
            </div>
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
            <div class="datatablefooter" style="margin-top: 10px;">
                <div class="showingContext">Showing 1 to 2 of 2 entries</div>
                <div class="datatable_pagination"> 

                    <?php
                        $previousRecord = ( ($current_page - 1) > 0 ) ? ($current_page - 1): 1; 
                        $nextRecord = ( $current_page < $total_pages ) ? ($current_page + 1): $total_pages; 
                    
                    ?>
                    <button id="1" class="firstRecord pageButton_1" > << </button>
                    <button id="<?php echo $previousRecord; ?>" class="previousRecord pageButton_<?php echo $previousRecord; ?>" > < </button>
                        
                        <?php
                            $active = '';
                            $end_pageno = $current_page + 2;
                            $start_pageno = $current_page - 2;
                            if( $current_page <= 2 ){
                                $start_pageno = 1; 
                                $end_pageno = $current_page + 3; 
                            }
                            if( $current_page >= ($total_pages-2) ){
                                $end_pageno = $total_pages; 
                            }

                            for( $i = $start_pageno; $i <= $end_pageno; $i++ ){
                                $active = ( $current_page == $i ) ? 'oesDatatableActive' : '';
                                echo "<button id='$i' class='pageButton_$i {$active}'>$i</button>";
                            }
                        ?>
                        
                    <button id='<?php echo $nextRecord; ?>' class="nextRecord pageButton_<?php echo $nextRecord; ?>" > > </button>    
                    <button id="<?php echo $total_pages; ?>" class="lastRecord pageButton_<?php echo $total_pages; ?>" > >> </button>    
                </div>
            </div>
        </div>
<?php
    }
}
$datatable = new datatable();
?>