<?php

switch ($extension) {
    case 'jpg':
    case 'jpeg':
            $sourceImage = imagecreatefromjpeg($inputFile);
        break;
    case 'png':
             $sourceImage = imagecreatefrompng($inputFile);
        break;
    case 'gif':
            $sourceImage = imagecreatefromgif($inputFile);
        break;
    default:
        // Unsupported image type
        die('Unsupported image type');
}

$sourceWidth = imagesx($sourceImage);
$sourceHeight = imagesy($sourceImage);


$newWidth  = $new_width ;
$newHeight = $new_height ;

// // Calculate crop position
// $cropX = ($sourceWidth - $cropWidth) / 2;
// $cropY = ($sourceHeight - $cropHeight) / 2;


$resizedImage = imagecreatetruecolor($newWidth, $newHeight);

imagecopyresampled($resizedImage,$sourceImage,0,0,0,0,$newWidth,$newHeight,$sourceWidth,$sourceHeight);

//output the resized image to a new file

$outputFile = $full_path_img;

switch ($extension) {
    case 'jpg':
    case 'jpeg':
        imagejpeg($resizedImage,$outputFile);
        break;
    case 'png':
        imagepng($resizedImage,$outputFile);
        break;
    case 'gif':
        imagegif($resizedImage,$outputFile);
        break;
    default:
        // Unsupported image type
        die('Unsupported image type');
}


// Free up resources
imagedestroy($sourceImage);
imagedestroy($resizedImage);

?>
