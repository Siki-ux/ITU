<?php
/**
 * @author xsikul15@stud.fit.vutbr.cz
 * Contains function which prints html with category options
 */
include_once("./data_layer/db_tickets.php");
//function which prints category as option
function print_categories(){
    $categories = get_categories();
    while($row = $categories->fetch()){
        echo '<option value="'.$row["id"].'">'.$row["description"].'</option>';
    }   
}
?>