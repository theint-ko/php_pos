<?php
session_start();
require('../common/config.php');
require('../common/database.php');
require('../common/checkauthentication.php');

$title = "Admin Panel:Shift form";
require('../templates/cp_template_header.php');
require('../templates/cp_template_sidebar.php');
require('../templates/cp_template_top_nav.php');

// Assuming you have a database table named 'shifts' with columns 'start_time' and 'end_time'
$query = "SELECT start_time, end_time FROM shifts";
$result = mysqli_query($connection, $query);

// Fetch the first row from the result set
$row = mysqli_fetch_assoc($result);

// Check if there are rows in the result set
if ($row) {
    $startTime = $row['start_time'];
    $endTime = $row['end_time'];
} else {
    // Default values if no data is found
    $startTime = "2023-12-15 10:00:00";
    $endTime = "2023-12-15 18:00:00";
}
?>

<!-- page content -->
<div class="right_col" role="main">
    <!-- ... your existing code ... -->

    <tbody>
        <tr>
            <td class="dd">
                <a href="<?php echo $cb_base_url; ?>shift_start.php" class="btn btn-primary" role="button">
                    <span class="glyphicon glyphicon-time" aria-hidden="true">&nbsp;<?php echo $startTime; ?></span>
                </a>
            </td>
            <td class="aa">
                <a href="<?php echo $cb_base_url; ?>shift_end.php" class="btn btn-secondary" role="button">
                    <span class="glyphicon glyphicon-off" aria-hidden="true">&nbsp;<?php echo $endTime; ?></span>
                </a>
            </td>
        </tr>
    </tbody>
</table>
</div>

<!-- ... your existing code ... -->

</div>
</div>

<?php require('../templates/cp_template_footer_start.php'); ?>
</div>
</div>

<?php require('../templates/cp_template_footer_end.php'); ?>
<?php require('../templates/cp_template_html_end.php'); ?>
