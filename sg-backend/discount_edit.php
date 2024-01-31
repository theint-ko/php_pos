<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/checkauthentication.php');
require('../include/include_function.php');


?>
<?php
$error = false;
$error_message = '';

$sql = "SELECT id,name,category_id,price FROM `item` WHERE status='$enable_status' ORDER BY category_id ASC, id ASC";
$result = $mysqli->query($sql);

if (isset($_POST['form-sub']) && $_POST['form-sub'] == 1) {
    $process_error = false;
    $id = $mysqli->real_escape_string($_POST['id']);
    $discount_name = $mysqli->real_escape_string($_POST['name']);
    $discount_amount = $mysqli->real_escape_string($_POST['amount']);
    $discount_type = $mysqli->real_escape_string($_POST['discount_type']);
    $start_date = $mysqli->real_escape_string($_POST['start_date']);
    $end_date = $mysqli->real_escape_string($_POST['end_date']);
    //$discount_type = (isset($_POST['type'])) ? $_POST['type'] : '';
    $discount_item = (isset($_POST['item'])) ? $_POST['item'] : [];
    $description = $mysqli->real_escape_string($_POST['description']);
    $status = $mysqli->real_escape_string($_POST['status']);


    if ($discount_name == '') {
        $process_error = true;
        $error = true;
        $error_message .= 'Please fill discount name.\n';

    }
    if ($discount_amount == '') {
        $process_error = true;
        $error = true;
        $error_message .= 'Please fill discount amount.\n';

    }
    if (count($discount_item) <= 0) {
        $process_error = true;
        $error = true;
        $error_message .= 'Please choose at least one to give discount item.\n';
    }
    if ($start_date > $end_date) {
        $process_error = true;
        $error = true;
        $error_message .= 'Discount start date must be less than end date.\n';

    }
    if ($discount_type == 'percentage') {
        if ($discount_amount > 100) {
            $process_error = true;
            $error = true;
            $error_message .= 'Discount percentage must be less than 100.\n';

        }
    }

    if ($discount_type == 'cash') {
        $price_check = '';
        $price_error = false;
        foreach ($discount_item as $item_id) {

            $price_sql = "SELECT price, name FROM `item` WHERE id='$item_id'";
            $price_result = $mysqli->query($price_sql);
            $price_row = $price_result->fetch_assoc();
            $price = $price_row["price"];
            $name = htmlspecialchars($price_row['name']);
            if ($price < $discount_amount) {

                $price_error = true;
                $price_check .= $name . ',';

            }

        }
        if ($price_error == true) {
            $process_error = true;
            $error = true;
            $error_message .= 'Discount amount of (' . rtrim($price_check, ',') . ') must be less than item price.\n';


        }
    }

    $overlap_item_names = [];
    foreach ($discount_item as $value) {
        $overlap_sql = "SELECT di.item_id, i.name FROM `discount_promotion` dp
                        INNER JOIN `discount_item` di ON dp.id = di.discount_id
                        INNER JOIN `item` i ON di.item_id = i.id
                        WHERE dp.id != '$id'
                        AND di.item_id = '$value'
                        AND dp.deleted_at IS NULL AND di.deleted_at IS NULL AND i.deleted_at IS NULL
                        AND '" . formatDateYmd($start_date) . "' <= dp.end_date
                        AND '" . formatDateYmd($end_date) . "' >= dp.start_date
                        AND dp.id != '$id'";  // Use the discount ID being updated to exclude it from the check

        $overlap_result = $mysqli->query($overlap_sql);

        if ($overlap_result && $overlap_result->num_rows > 0) {
            while ($row = $overlap_result->fetch_assoc()) {
                $overlap_item_names[] = $row['name'];
            }
        }
    }

    if (!empty($overlap_item_names)) {
        $process_error = true;
        $error = true;
        $error_message .= '(' . implode(', ', $overlap_item_names) . ') are already associated under promotion: ';
    }


    $check_dis_sql = "SELECT count(id) AS total FROM `discount_promotion` WHERE name='$discount_name' AND id!='$id' AND deleted_at IS
            NULL";
    $check_dis_result = $mysqli->query($check_dis_sql);

    while ($check_dis_row = $check_dis_result->fetch_assoc()) {
        $check_dis_total = $check_dis_row['total'];

    }
    if ($check_dis_total > 0) {
        $process_error = true;
        $error = true;
        $error_message = "Your Discount Name is already exist";
    }
    if ($process_error == false) {
        $today_dt = date("Y-m-d H:i:s");
        $user_id = (isset($_SESSION['id']) ? $_SESSION['id'] : $_COOKIE['id']);
        $start_date_db = formatDateYmd($start_date);
        $end_date_db   = formatDateYmd($end_date);
        // Use $start_date_db and $end_date_db in your SQL query
        $update_sql = "UPDATE `discount_promotion` SET 
                    name='$discount_name',
                    amount = CASE WHEN '$discount_type' = 'cash' THEN '$discount_amount' ELSE NULL END,
                    percentage = CASE WHEN '$discount_type' = 'percentage' THEN '$discount_amount' ELSE NULL END,
                    start_date='$start_date_db', end_date='$end_date_db', description='$description', status='$status',
                    updated_at='$today_dt',
                    updated_by='$user_id' WHERE id='$id'";
        $update_result = $mysqli->query($update_sql);

        if ($update_result) {
            $delete_sql = "DELETE FROM `discount_item` WHERE discount_id='$id'";
            $delete_result = $mysqli->query($delete_sql);
            // $delete_sql1 = "DELETE FROM `discount_promotion` WHERE id='$id'";
            // $delete_result1 = $mysqli->query($delete_sql1);
            foreach ($discount_item as $value) {
                $inserted_item_id = "INSERT INTO discount_item (item_id,discount_id
            ,created_at, created_by, updated_at, updated_by)
             VALUES('$value','$id','$today_dt', '$user_id', '$today_dt', '$user_id')";
                $inserted_item_result = $mysqli->query($inserted_item_id);

            }
        }
        $url = $cp_base_url . "discount_list.php?msg=edit";
        header("Refresh:0,url=$url");
        exit();


        if (!$update_result) {
            $error = true;
            $error_message = "Oop! Something Wrong";
            $url = $cp_base_url . "discount_list.php?err=edit";
            header("Refresh:0,url=$url");
            exit();
        }
    }

} 
else {
    $id = (int) ($_GET['id']);
    $id = $mysqli->real_escape_string($id);
    $sql1 = "SELECT id, name, amount, percentage, start_date, end_date, description, status
             FROM `discount_promotion` WHERE id='$id' AND deleted_at IS NULL";
    $result1 = $mysqli->query($sql1);
    $res_row = $result1->num_rows;

    if ($res_row <= 0) {
        $form = false;
        $error = true;
        $error_message = "This discount doesn't exist.";
    } else {
        $row = $result1->fetch_assoc();
        $discount_name = htmlspecialchars($row['name']);
        $amount = $row['amount'];
        $percentage = $row['percentage'];
        $start_date = formatDateDmy($row['start_date']);
        $end_date = formatDateDmy($row['end_date']);
        $description = htmlspecialchars($row['description']);
        $status = (int) $row['status'];

        // Determine the discount type and amount
        $discount_type = ($amount == null) ? 'percentage' : 'cash';
        $discount_amount = ($amount == null) ? $percentage : $amount;
        $discount_item = array();
        $sql_item = "SELECT item_id FROM `discount_item` WHERE discount_id='$id'";
        $result_item = $mysqli->query($sql_item);
        while ($row_item = $result_item->fetch_assoc()) {
            array_push($discount_item, $row_item['item_id']);
        }
    }

}

?>
<?php
$title = "Admin Panel::Update Discount";
require('../templates/cp_template_header.php');
require('../templates/cp_template_sidebar.php');
require('../templates/cp_template_top_nav.php');
?>


<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Discount Promotion Update </h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="" action="discount_edit.php" method="post" novalidate>

                            <span class="section">Discount Promotion Update </span>
                            <div class="field item form-group">
                                <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Discount
                                    Name<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" id="name" data-validate-length-range="6"
                                        data-validate-words="2" name="name" required="required"
                                        value="<?php echo $discount_name; ?>" />
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">
                                    Discount Type<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">

                                    <label for="percentage">
                                        <input type="radio" <?php if ($discount_type == 'percentage') {
                                            echo "checked";
                                        } ?> value="percentage" id="percentage" name="discount_type">
                                        Percentage
                                    </label for="cash">


                                    <label for="cash">
                                        <input type="radio" value="cash" <?php if ($discount_type == 'cash') {
                                            echo "checked";
                                        } ?> id="cash" name="discount_type">
                                        Cash
                                    </label>

                                </div>
                            </div>
                            <div class="field item form-group">
                                <label for="amount" class="col-form-label col-md-3 col-sm-3 label-align">
                                    <span class="amount_txt">
                                        <?php echo ($discount_type == 'percentage') ? "Discount Percentage Amount" : "Discount Cash Amount"; ?>
                                    </span>
                                    <span class="required">*</span>
                                </label>

                                <div class="col-md-6 col-sm-6">
                                    <input id="amount" class="form-control" type="number" class='number' name="amount"
                                        data-validate-minmax="10,100" required='required'
                                        value="<?php echo $discount_amount; ?>" />
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label for="start_date" class="col-form-label col-md-3 col-sm-3  label-align">Start
                                    date<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <fieldset>
                                        <div class="control-group">
                                            <div class="controls">
                                                <div class="col-md-11 xdisplay_inputx form-group row has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        id="start_date" aria-describedby="inputSuccess2Status"
                                                        name="start_date" value="<?php echo $start_date; ?>" />
                                                    <span class="fa fa-calendar-o form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                    <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label for="single_cal3" class="col-form-label col-md-3 col-sm-3  label-align">End
                                    date<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <fieldset>
                                        <div class="control-group">
                                            <div class="controls">
                                                <div class="col-md-11 xdisplay_inputx form-group row has-feedback">
                                                    <input type="text" class="form-control has-feedback-left"
                                                        id="end_date" aria-describedby="inputSuccess2Status"
                                                        name="end_date" value="<?php echo $end_date; ?>" />
                                                    <span class="fa fa-calendar-o form-control-feedback left"
                                                        aria-hidden="true"></span>
                                                    <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label for="amount" class="col-form-label col-md-3 col-sm-3  label-align"><span
                                        class="amount_text">Items</span><span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <div class="row">
                                        <?php
                                        while ($row = $result->fetch_assoc()) {
                                            $item_id = (int) $row['id'];
                                            $item_name = htmlspecialchars($row['name']);
                                            ?>

                                            <div class="col-md-4">
                                                <label>
                                                    <input type="checkbox" class="flat" name="item[]" <?php if (in_array($item_id, $discount_item)) {
                                                        echo 'checked';
                                                    } ?> value="<?php echo $item_id; ?>">
                                                    <?php echo $item_name; ?>
                                                </label>
                                            </div>

                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label for="description" class="col-form-label col-md-3 col-sm-3  label-align"><span
                                        class="amount_text">Discount Description</span><span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <textarea id="description" class="form-control"
                                        name="description"><?php echo $description; ?></textarea>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Select<span
                                        class="required">*</span></label>
                                <div class="col-md-9 col-sm-9 ">
                                    <select class="form-control" name="status">
                                        <option value="0" <?php if ($status == 0) {
                                            echo 'selected';
                                        } ?>>Enable</option>
                                        <option value="1" <?php if ($status == 1) {
                                            echo 'selected';
                                        } ?>>Disable</option>
                                    </select>
                                </div>
                            </div>
                            <div class="ln_solid">
                                <div class="form-group">
                                    <div class="col-md-6 offset-md-3">
                                        <button type='submit' class="btn btn-primary">Submit</button>
                                        <button type='reset' class="btn btn-success">Reset</button>
                                        <input type="hidden" value="1" name="form-sub" />
                                        <input type="hidden" value="<?php echo $id; ?>" name="id">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
<?php require('../templates/cp_template_footer_start.php');
?>
</div>
</div>

<script src="<?php echo $base_url ?>asset/js/jquery1.9/jquery.1.9.min.js"></script>
<!-- <script src="<?php echo $base_url ?>asset/js/validator/multifield.js"></script> -->
<script src="<?php echo $base_url ?>asset/js/jquery/jquery-3.6.4.min.js"></script>
<!-- <link rel="stylesheet" href="<?php echo $base_url ?>asset/css/jquery-ui.css"> -->
<!-- <script src="<?php echo $base_url ?>asset/js/jquery/jquery-ui.js"></script> -->
<?php require('../templates/cp_template_footer_end.php');
?>

<script type="text/javascript">
    $(document).ready(function () {
        $.noConflict();

        // Add change event listener to the radio buttons
        $("input[name='discount_type']").change(function () {
            if ($(this).val() === 'cash') {
                // If Percentage is selected, show the discount cash amount label
                $(".amount_txt").text("Discount Cash Amount");
            } else {
                // If Cash is selected, hide the discount cash amount label
                $(".amount_txt").text("Discount Percentage Amount");
            }
        });

    //     // Initialize datepicker for start date
    //     $("#single_cal1").datepicker({
    //         numberOfMonths: 1,
    //         dateFormat: 'dd-mm-yy',
    //         onSelect: function (selectedDate) {
    //             // Set the minDate for the end date based on the selected start date
    //             $("#single_cal3").datepicker("option", "minDate", new Date(selectedDate));
    //         },
    //     });

    //     // Initialize datepicker for end date
    //     $("#single_cal3").datepicker({
    //         numberOfMonths: 1,
    //         dateFormat: 'dd-mm-yy',
    //     });
    // });
</script>
<?php
if ($error == true) {
    ?>
    <script>
        swal({
            title: "Error!",
            text: "<?php echo $error_message; ?> ",
            type: "error",
            confirmButtonText: "Close"
        });
    </script>
    <?php
}
?>


<?php require('../templates/cp_template_html_end.php');
?>