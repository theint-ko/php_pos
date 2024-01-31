<?php

function getParentCategory($mysqli, $parent_id,$screen) 
{   
    $category_sql    = "SELECT * FROM  `category` WHERE parent_id = 0 AND deleted_at IS NULL";
    $category_result = $mysqli->query($category_sql);

    while ($category_row = $category_result->fetch_assoc()) {
        $category_dis ='';
        $disable = '';
        $parent_cat_id = (int) $category_row['id'];
        $parent_cat_name = htmlspecialchars($category_row['name']);
        if($screen['item']){
            $check_child_exist = checkChildCategoryExist($parent_cat_id,$mysqli);
            if($check_child_exist){
                $disable ='disabled';
            }
        }
        if($screen['category']){
            $item_exit =checkItemExistByCatId($parent_cat_id,$mysqli);
            if($item_exit){
                $category_dis ='disabled';
            }
        }

        if ($parent_id == $parent_cat_id) {
            echo "<option value='$parent_cat_id' selected $disable $category_dis>$parent_cat_name</option>";
        } else {
            echo "<option value='$parent_cat_id' $disable $category_dis>$parent_cat_name</option>";
        }

        getChildCategory($parent_cat_id, 1, $parent_id,$screen,$mysqli);
    }
}

function getChildCategory($parent_cat_id, $level, $parent_id,$screen, $mysqli) {
    $child_sql = "SELECT id, name FROM `category` WHERE parent_id = '$parent_cat_id' AND deleted_at IS NULL";
    $child_result = $mysqli->query($child_sql);

    while ($child_row = $child_result->fetch_assoc()) {
        $disable = '';
        $category_dis='';
        $child_cat_id = (int) ($child_row['id']);
        $child_cat_name = htmlspecialchars($child_row['name']);
        $dashes = str_repeat('&nbsp;--', $level);
        $spaces = str_repeat('&nbsp;', $level * 2);
        if($screen['item']){
            $check_child_exist = checkChildCategoryExist($child_cat_id,$mysqli);
            if($check_child_exist){
                $disable = 'disabled';
            }
        }

        if($screen['category']){
            $item_exit =checkItemExistByCatId($child_cat_id,$mysqli);
            if($item_exit){
                $category_dis ='disabled';
            }
        }
        if ($parent_id == $child_cat_id) {
            echo "<option value='$child_cat_id' selected $disable $category_dis>$dashes$spaces$child_cat_name</option>";
        } else {
            echo "<option value='$child_cat_id' $disable $category_dis>$dashes$spaces$child_cat_name</option>";
        }
        $is_child_exist_sql   = "SELECT count(id) AS total FROM `category` WHERE parent_id = '$child_cat_id' AND deleted_at is NULL";
        $is_child_exist_result = $mysqli->query($is_child_exist_sql);
        while($is_child_exist_row=$is_child_exist_result->fetch_assoc()){
            $child_total = $is_child_exist_row['total'];
        }
         if($child_total>0){
            getChildCategory($child_cat_id, $level + 1, $parent_id,$screen, $mysqli);
         }
        
    }
}

function checkChildCategoryExist($parent_cat_id,$mysqli){
    $sql = "SELECT count(id) AS total FROM `category` WHERE parent_id='$parent_cat_id' AND deleted_at IS NULL";
    $result = $mysqli->query($sql);
    while($row = $result->fetch_assoc()){
        $total = $row ['total'];
    }
    return $total;
}
 

function checkItemExistByCatId($category_id,$mysqli){
    $sql = "SELECT count(id) AS total FROM `item` WHERE category_id='$category_id' AND deleted_at IS NULL";
    $result = $mysqli->query($sql);
    while($row = $result->fetch_assoc()){
        $total = $row ['total'];
    }
    return $total;
}

?>
