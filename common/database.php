<?php
$host='localhost';
$username='root';
$password='';
$database='sg_pos_db';
$port=3309;
$mysqli=new mysqli($host, $username , $password, $database, $port);
if($mysqli->connect_error){
    die("connection error:". $mysqli->connect_error);
}

?>