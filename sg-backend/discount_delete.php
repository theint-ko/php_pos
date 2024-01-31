<?php
session_start();
require('../common/database.php');
require('../common/config.php');
require('../common/checkauthentication.php');
$today_dt = date("Y-m-d H:i:s");
$user_id = (isset($_SESSION['id']) ? $_SESSION['id'] : $_COOKIE['id']);
$shift_check_sql = "SELECT count(id) AS total FROM `shift` WHERE  started_date_time IS NOT NULL AND end_date_time IS NULL ";
$shift_check_result = $mysqli->query($shift_check_sql);
while ($shift_check_row = $shift_check_result->fetch_assoc()) {
    $shift_count = $shift_check_row["total"];
}
if ($shift_count > 0) {
    $url = $cp_base_url . "discount_list.php?err=shift";
    header("Refresh:0,url=$url");
    exit();
}
// Check if the request is a POST request and confirm is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    $id = (int) $_POST['id'];


    // Update the 'deleted_at' and 'deleted_by' fields
    $sql = "UPDATE `discount_item` SET deleted_at='$today_dt', deleted_by='$user_id' WHERE discount_id='$id'";
    $delete_result = $mysqli->query($sql);

    $promotion_delete_sql = "UPDATE `discount_promotion` SET deleted_at='$today_dt', deleted_by='$user_id' WHERE id='$id'";
    $promotion_delete_result = $mysqli->query($promotion_delete_sql);


    // Check if the update was successful
    if ($promotion_delete_result) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete item."]);
    }
    exit();
}

// If the request is not a POST request or confirm is not set, show the confirmation dialog
$id = (int) $_GET['id'];
require('../templates/cp_template_header.php');
?>
<?php require('../templates/cp_template_footer_end.php');
?>
<script>
Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
}).then((result) => {
    if (result.isConfirmed) {
        // User confirmed, send a POST request to item_delete.php with confirm=true
        fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'id': <?php echo $id; ?>,
                    'confirm': true
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success"
                    }).then(() => {
                        // Redirect to item_list.php after successful deletion
                        window.location.href =
                            '<?php echo $cp_base_url; ?>discount_list.php?msg=delete';
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: data.message || "Failed to delete item.",
                        icon: "error"
                    });
                }
            });
    } else {
        // User clicked "Cancel", redirect to category_list.php
        window.location.href = '<?php echo $cp_base_url; ?>discount_list.php';
    }
});
</script>


<?php require('../templates/cp_template_html_end.php');
?>