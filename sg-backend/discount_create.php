<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/checkauthentication.php');
require('../include/include_function.php');

?>
<?php
$discount_name = '';
$discount_amount = '';
$discount_type = 'percentage';
$start_date = '';
$end_date = '';
$discount_item = [];
$description = '';
$error = false;
$error_message = '';

$sql = "SELECT id,name,category_id,price FROM `item` WHERE status='$enable_status' ORDER BY category_id ASC, id ASC";
$result = $mysqli->query($sql);
if (isset($_POST['form-sub']) && $_POST['form-sub'] == 1) {
    $process_error = false;
    $discount_name = $mysqli->real_escape_string($_POST['name']);
    $discount_amount = $mysqli->real_escape_string($_POST['amount']);
    $discount_type = $mysqli->real_escape_string($_POST['discount_type']);
    $start_date = $mysqli->real_escape_string($_POST['start_date']);
    $end_date = $mysqli->real_escape_string($_POST['end_date']);
    //$discount_type = (isset($_POST['type'])) ? $_POST['type'] : '';
    $discount_item = (isset($_POST['item'])) ? $_POST['item'] : [];
    $description = $mysqli->real_escape_string($_POST['description']);

    if ($discount_name == '') {
        $process_error = true;
        $error = true;
        $error_message .= 'Please fill discount name.\n';

    }
    if ($discount_amount == '') {
        $process_error = true;
        $error = true;
        $error_message .= 'Please fill discount amount percentage or cash.\n';

    }
    if ($start_date > $end_date) {
        $process_error = true;
        $error = true;
        $error_message .= 'Discount start date must be less than end date.\n';

    }
    if (count($discount_item) <= 0) {
        $process_error = true;
        $error = true;
        $error_message .= 'Please choose at least one discount item.\n';
    }
    if ($discount_type == 'percentage') {
        if ($discount_amount > 100) {
            $process_error = true;
            $error = true;
            $error_message .= 'Discount percentage must be less than 100.\n';

        }

    }
    
    // function isOverlap($start_date1, $end_date1, $start_date2, $end_date2)
    // {
    //     return ($start_date1 <= $end_date2) && ($end_date1 >= $start_date2);
    // }

    // $overlap_item_names = [];
    // $last_inserted_id = $mysqli->insert_id;

    // foreach ($discount_item as $value) {
    //     $overlap_sql = "SELECT dp.id, di.item_id, i.name FROM `discount_promotion` dp
    //                 INNER JOIN `discount_item` di ON dp.id = di.discount_id
    //                 INNER JOIN `item` i ON di.item_id = i.id
    //                 WHERE di.item_id = '$value'
    //                 AND dp.deleted_at IS NULL AND di.deleted_at IS NULL AND'" . formatDateYmd($start_date) . "' <= dp.end_date
    //                 AND '" . formatDateYmd($end_date) . "' >= dp.start_date
    //                 AND dp.id != '$last_inserted_id'";
    //     $overlap_result = $mysqli->query($overlap_sql);

    //     if ($overlap_result && $overlap_result->num_rows > 0) {
    //         while ($row = $overlap_result->fetch_assoc()) {
    //             $overlap_item_names[] = $row['name'];
    //         }
    //     }
    // }

    // if (!empty($overlap_item_names)) {
    //     $process_error = true;
    //     $error = true;
    //     $error_message .= '(' . implode(', ', $overlap_item_names) . ') are already associated under promotion: ';
    // }
    if ($discount_type == 'cash') {
        $price_check = '';
        $price_error = false;
        foreach ($discount_item as $item_id) {

            $price_sql = "SELECT price, name FROM `item` WHERE id='$item_id'";
            $price_result = $mysqli->query($price_sql);
            $price_row = $price_result->fetch_assoc();
            $price = $price_row["price"];
            $name = $price_row['name'];
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


    if ($process_error == false) {
        $today_dt = date("Y-m-d H:i:s");
        $user_id = (isset($_SESSION['id']) ? $_SESSION['id'] : $_COOKIE['id']);
        $start_dt_format = formatDateYmd($start_date);
        $end_dt_format = formatDateYmd($end_date);
        
        if($discount_type == 'percentage'){
            
            $sql2 = "INSERT INTO `discount_promotion` (name, percentage, start_date, end_date, description,
                    created_at, created_by, updated_at, updated_by)
                    VALUES ('$discount_name', '$discount_amount', '$start_dt_format', '$end_dt_format', '$description',
                    '$today_dt', '$user_id', '$today_dt', '$user_id')";
  
        }else{
             $sql2 = "INSERT INTO `discount_promotion` (name, amount, start_date, end_date, description,
                     created_at, created_by, updated_at, updated_by)
                     VALUES ('$discount_name', '$discount_amount', '$start_dt_format', '$end_dt_format', '$description',
                    '$today_dt', '$user_id', '$today_dt', '$user_id')";
        }
        
       $result1=$mysqli->query($sql2);
        if (!$result1) {
            $url = $cp_base_url . "discount_list.php?err=create";
            header("Refresh:0,url=$url");
            exit();
        } else {
            $last_inserted_id = $mysqli->insert_id;
            foreach ($discount_item as $value) {
                $inserted_discount_item_sql = "INSERT INTO `discount_item`
             (item_id,discount_id,created_at, created_by, updated_at, updated_by) 
             VALUES('$value','$last_inserted_id','$today_dt', '$user_id', '$today_dt', '$user_id')";
                $discount_item_result = $mysqli->query($inserted_discount_item_sql);
            }

            $url = $cp_base_url . "discount_list.php?msg=create";
            header("Refresh:0,url=$url");
            exit();
        }

    }
}

$title = "Admin Panel::Create Discount";
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
                        <h2>Discount Promotion </h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="" action="discount_create.php" method="post" novalidate>

                            <span class="section">Discount Promotion </span>
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
                                <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">Discount
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
                                <label for="amount" class="col-form-label col-md-3 col-sm-3  label-align"><span
                                        class="amount_txt">Discount
                                        Percentage Amount</span><span class="required">*</span></label>
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
                                <label for="end_date" class="col-form-label col-md-3 col-sm-3  label-align">End
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

                            <div class="ln_solid">
                                <div class="form-group">
                                    <div class="col-md-6 offset-md-3">
                                        <button type='submit' class="btn btn-primary">Submit</button>
                                        <button type='reset' class="btn btn-success">Reset</button>
                                        <input type="hidden" value="1" name="form-sub" />
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
    // Add change event listener to the radio buttons
    $("input[name='type']").change(function () {
        if ($(this).val() === 'cash') {
            // If Percentage is selected, show the discount cash amount label
            $(".amount_text").text("Discount Cash Amount");
        } else {
            // If Cash is selected, hide the discount cash amount label
            $(".amount_text").text("Discount Percentage Amount");
        }
    });

    // Initialize datepicker for start date
    $("#start_date").daterangepicker({
        singleDatePicker: true,
        minDate: moment(), // Use moment.js for the current date
        locale: {
            format: 'MM-DD-YYYY' // Use the same date format as in your input field
        }
    });

    // Initialize datepicker for end date
    $("#end_date").daterangepicker({
        singleDatePicker: true,
        minDate: moment(),
        locale: {
            format: 'MM-DD-YYYY'
        }
    });
});
</script>
<?php
if ($error == true) {
    ?>
    <script>
        swal({
            title: "Error!",
            text: "<?php echo $error_message; ?>",
            type: "error",
            confirmButtonText: "Close"
        });
    </script>
    <?php
}
?>


<?php require('../templates/cp_template_html_end.php');
?>