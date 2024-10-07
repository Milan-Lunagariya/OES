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
    function dataTableView( $th = array(), $td = array(),  $classes = array(), $current_page = 1, $total_records = '', $limit = '' )
    {
        global $databasehandler, $commonhelper; 
        $content =  '';
        $class_td_checked = '';
        $classes['td'] = isset( $classes['td'] ) ? $classes['td'] : '';
        $classes['tr'] = isset( $classes['tr'] ) ? $classes['tr'] : '';
        $td[0][1] = isset( $td[0][1] ) ? $td[0][1] : '';
        
        $content = "<table border='2' align='center'>
        <tr>"; 
        
                $th = isset( $th['th'] ) ? $th['th'] : array();
                if( count($th) > 0 ){
                    $i = 0;
                    foreach( $th as $th_value => $width ){
                        $content .= "<th class='oes_th datatable_th_$i' align='center' width='{$width}' >$th_value</th>";
                        $i++;
                    }
                }
            $content.= "</tr>"; 
                
            if( count($td) > 0 ){
                
                foreach( $td as $td_key => $td_value ){
                    $td_value[1] = isset( $td_value[1] ) ? $td_value[1] : '';
                
                    $content .= "<tr class='".$classes['tr']."' id='{$td_value[1]}'>";
                
                    if( is_array( $td_value ) && count( $td_value ) ){

                        foreach( $td_value as $td_meta_key => $td_meta_value ){
                            $class_td_checked = ''; 
                            if( $td_meta_key == 0 ) {
                                $class_td_checked = "datatable_checked_td_{$td_value[1]}_0";
                            }  
                            if( is_string($td_meta_value) || is_numeric($td_meta_value) ) {

                                $content .= "<td class='$class_td_checked ".$classes['td']." ' id='$class_td_checked' >$td_meta_value</td>" ;
                
                            } else if( is_array( $td_meta_value ) && count( $td_meta_value ) > 0 ) { 
                                
                                foreach( $td_meta_value as $meta_of_key => $meta_of_value ){    
                                    $content .= "<td class='$class_td_checked ".$classes['td']."' id='$class_td_checked' >$meta_of_value</td>" ;
                                }
                
                            }
                        }
                    }
                    $content .= '</tr>';
                }
            } else{
                $colspan = count( $th );
                $content .= "<tr><td align='center' colspan='$colspan'>Record not found !</td></tr>";
            }  
        
        $content .= "</table>";
        $content .= "<div class='datatablefooter' style='margin-top: 10px;'>";
        if( ! empty( $total_records ) && $limit > 0 ) {
            $content .=  "<div class='showingContext'> Total $total_records entries</div>";
        }
        $content .=  "<div class='datatable_pagination'>"; 
 
                    $total_pages = ceil( $total_records / $limit );
                    $page_button = '';
                    $previousRecord = ( ($current_page - 1) > 0 ) ? ($current_page - 1): 1; 
                    $nextRecord = ( $current_page < $total_pages ) ? ($current_page + 1): $total_pages; 

                    $page_button .= '<button id="1" class="firstRecord pageButton_1" > 1 </button>';
                    $page_button .=  "<button id='{$previousRecord}'  class='previousRecord pageButton_{$previousRecord}' > < </button>";
                         
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
                        $page_button .=  "<button id='$i' class='pageButton_$i {$active}'>$i</button>";
                    }
                    
                    $page_button .= "<button id='{$nextRecord}' class='nextRecord pageButton_{$nextRecord}' > > </button>";
                    $page_button .=  "<button id='{$total_pages}' class='lastRecord pageButton_{$total_pages}' > $total_pages </button>";

                    $content .= $page_button; 
                $content .= '</div>
            </div> '; 
        return  $content;
    }
}
$datatable = new datatable();
?>