<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/checkauthentication.php');
require('../include/include_function.php');
$title="Setting Create";
require('../templates/cp_template_header.php');

?>
<?php
$error = false;
$error_message = '';
$show_sql = "SELECT * FROM `setting`";
$show_result = $mysqli->query($show_sql);

if (!$show_result) {
    $error = true;
    $error_message = 'Error executing the query: ' . $mysqli->error;
} else {
    if ($show_result->num_rows > 0) {
        $show_row = $show_result->fetch_assoc();
        $id = (int) ($show_row['id']);
        $company_name = htmlspecialchars($show_row['name']);
        $phone = htmlspecialchars($show_row['phone']);
        $email = htmlspecialchars($show_row['email']);
        $address = htmlspecialchars($show_row['address']);
        $image = htmlspecialchars($show_row['image']);
        $image_full_path = $base_url . 'asset/upload/setting/' . $id . '/' . $image;
    } else {
        $company_name = '';
        $phone = '';
        $email = '';
        $address = '';
        $image = '';
    }
}


if (isset($_POST['form-sub']) && $_POST['form-sub'] == 1) {
    $process_error = false;
    //$upload_process = true;
    $company_name = $mysqli->real_escape_string($_POST['name']);
    $phone = $mysqli->real_escape_string($_POST['phone']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $address = $mysqli->real_escape_string($_POST['address']);
    $file = $_FILES['file'];
    $upload_file = $file['name'];
    $allow_extension = array('jpg', 'jpeg', 'svg', 'png', 'gif');
    $explode = explode('.', $upload_file);
    $without_ext = $explode['0'];
    $extension = end($explode);
    if (!in_array($extension, $allow_extension)) {
        $process_error = true;
        $error = true;
        $error_message .= 'We Only access image file extension.\n';
    } else {
        $upload_path = "../asset/upload/setting/";
        $unique_name = $without_ext . "_" . date("Ymd_His") . "_" . uniqid() . "." . $extension;

    }
   
    $check_sql = "SELECT count(id) AS total FROM `setting`";
    $check_result = $mysqli->query($check_sql);
    while ($check_row = $check_result->fetch_assoc()) {
        $check_total = $check_row['total'];
    }
    if ($check_total <= 0) {
        if ($process_error == false) {
            $image = $file['name'];
            $sql = "INSERT INTO `setting` (name, phone, email, address,image) VALUES (
            '$company_name', '$phone', '$email', '$address','$unique_name')";
            $result = $mysqli->query($sql);
            if (!$result) {
                $url = $cp_base_url . "setting_list.php?err=create";
                header("Refresh:0,url=$url");
                exit();
            } else {
                $inserted_id = $mysqli->insert_id;
                $full_path_dir = $upload_path . $inserted_id;
                $full_path_img = $full_path_dir . "/" . $unique_name;
                if (!file_exists($full_path_dir)) {
                    mkdir($full_path_dir, 0777, true);
                }

                move_uploaded_file($file['tmp_name'], $full_path_img);

                $inputFile = $full_path_img;
                require('../asset/lib/crop_and_resize_image.php');
                $url = $cp_base_url . "setting_list.php?msg=create";
                header("Refresh:0,url=$url");
                exit();
            }

        }
    } else {
        $check_sql = "SELECT * FROM `setting`";
        $check_result = $mysqli->query($check_sql);
        if ($check_result) {
            $row = $check_result->fetch_assoc();
            $id = (int) ($row['id']);
            $old_img = $row['image'];

            $update_sql = "UPDATE `setting` SET 
                        name='$company_name',
                        phone='$phone',
                        email='$email',
                        address='$address'";

            if (!empty($file['name'])) {
                $allow_extension = array('jpg', 'jpeg', 'svg', 'png', 'gif');
                $explode = explode('.', $file['name']);
                $without_ext = $explode[0];
                $extension = end($explode);

                if (in_array($extension, $allow_extension)) {
                    $unique_name = $without_ext . "_" . date("Ymd_His") . "_" . uniqid() . "." . $extension;
                    $full_path_dir = $upload_path . $id;
                    $full_path_img = $full_path_dir . "/" . $unique_name;
                    if (!file_exists($full_path_dir)) {
                        mkdir($full_path_dir, 0777, true);
                    }
                    move_uploaded_file($file['tmp_name'], $full_path_img);
                    $inputFile = $full_path_img;
                    require('../asset/lib/crop_and_resize_image.php');
                    $full_old_img_path = $full_path_dir . '/' . $old_img;
                    unlink($full_old_img_path);

                    $update_sql .= ", image='$unique_name'";
                } else {
                    $error = true;
                    $error_message .= 'Invalid file extension. Please select a valid image file.';
                }
            }
            $update_sql .= " WHERE id = $id";

            $result = $mysqli->query($update_sql);

            if (!$result) {
                $error = true;
                $error_message .= 'Oops! Something went wrong.';
                $url = $cp_base_url . "setting_list.php?err=edit";
                header("Refresh:0,url=$url");
                exit();
            } else {
                $url = $cp_base_url . "setting_list.php?msg=edit";
                header("Refresh:0,url=$url");
                exit();
            }
        }

    }
}
$title = "Admin Panel::Setting";
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
                        <h2>Setting </h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="" action="setting_create.php" method="post" enctype="multipart/form-data"
                            novalidate>

                            <span class="section">Setting </span>
                            <div class="field item form-group">
                                <label for="name" class="col-form-label col-md-3 col-sm-3  label-align">
                                    Company Name<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" id="name" data-validate-length-range="6"
                                        data-validate-words="2" name="name" required="required"
                                        value="<?php echo $company_name; ?>" />
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label for="phone" class="col-form-label col-md-3 col-sm-3  label-align">
                                     Phone<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="number" class="form-control" id="phone" data-validate-length-range="6"
                                        data-validate-words="2" name="phone" required="required"
                                        value="<?php echo $phone; ?>" />
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label for="email" class="col-form-label col-md-3 col-sm-3  label-align">
                                     Email<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6">
                                <input type="email" class="form-control" id="email" name="email" required="required"
                                   pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" value="<?php echo $email; ?>" />

                                </div>
                            </div>

                            <div class="field item form-group">
                                <label for="address" class="col-form-label col-md-3 col-sm-3  label-align">
                                     Address<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" id="address" data-validate-length-range="6"
                                        data-validate-words="2" name="address" required="required"
                                        value="<?php echo $address; ?>" />
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Company
                                    Image</label>
                                <div class="col-md-9 col-sm-9 ">
                                    <div id="preview_viewer" style="display:none">
                                        <div class="vertical_center">
                                            <label class="choose_image" onclick="fileBrowse()">Choose Photo</label>
                                        </div>
                                    </div>
                                    <div id="preview_viewer_img">
                                        <div class="vertical_center">
                                            <img src="<?php echo $image_full_path; ?>" id="preview_image"
                                                style="width:100%;" /></br>
                                            <label class="choose_image" onclick="fileBrowse()">Choose Photo</label>
                                        </div>
                                    </div>
                                </div>
                                <input class="hide img-upload" type="file" name="file" onchange="fileSelect(this)" />
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
<script src="<?php echo $base_url ?>asset/js/jquery/jquery-3.6.4.min.js"></script>
<?php require('../templates/cp_template_footer_end.php');
?>
<script>
    function fileBrowse() {
        $('.img-upload').click();
    }

    function fileSelect(input) {
        const allow_extensions = ['jpeg', 'jpg', 'png', 'svg', 'gif'];
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const image_data_url = e.target.result;
                $("#preview_image").attr("src", image_data_url);
            };
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (allow_extensions.includes(fileExtension)) {
                reader.readAsDataURL(file);
                $('#preview_viewer').hide();
                $('#preview_viewer_img').show();
            } else {
                alert('Invalid file extension. Please select a valid image file.');

            }
        }
    }
    $(document).ready(function () {
    $("form").submit(function (event) {
        const emailInput = $("#email").val();
        const phoneInput = $("#phone").val();
        const companyName = $("#name").val();
        const addressInput = $("#address").val(); // Get the value of the address input

        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const phoneRegex = /^\d{10}$/; // Adjust the regex based on your desired phone number format

        if (companyName.trim() === '') {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Missing Information',
                text: 'Please fill in the company name.',
            });
        } else if (!emailRegex.test(emailInput)) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Email Address',
                text: 'Please enter a valid email address.',
            });
        } else if (!phoneRegex.test(phoneInput)) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Phone Number',
                text: 'Please enter a valid 10-digit phone number.',
            });
        } else if (addressInput.trim() === '') {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Missing Information',
                text: 'Please fill in the address.',
            });
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