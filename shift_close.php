<?php
require('common/database.php');
require('common/config.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Information</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            overflow: hidden;
            margin: 0; /* Remove default margin */
        }

        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .content-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #333;
        }

        .shop-info {
            text-align: center;
        }

        h1 {
            font-size: 3em;
            margin-bottom: 15px;
            color:black;
        }

        p {
            font-size: 1.2em;
            margin-bottom: 20px;
            line-height: 1.5;
            color:black;
        }

        .closing-time {
            color: #e44d26;
            font-weight: bold;
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        .additional-info {
            color: #777;
            font-size: 1.2em;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <img class="background-image" src="<?php echo $base_url; ?>asset/images/closse.jpg" alt="Background Image">
    <div class="content-container">
        <div class="shop-info">
            <marquee direction="down" behavior="scroll" scrollamount="8">
                <h1>Rosey Mini Mark</h1>
                <h3>Have A Nice Day , Friend!<br/><br/>
                 Explore a wide range of products and enjoy us.</h3>
                <div class="closing-time">
                    <h2>
                    Shop Closing Time: 8:00 PM
                </h2>
                </div>
                <div class="additional-info">
                    <h3>
                    <p>Address: 123 Main Street, Pyay Street</p>
                    <p>Contact: (123) 456-7890</p>
                    <p>Email: rosey@shop.com</p>
                    </h3>
                </div>
            </marquee>
        </div>
    </div>
</body>

</html>