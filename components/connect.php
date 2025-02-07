<?php

$db_name = 'mysql:host=localhost; dbname=astro';
$user_name = '${{ secrets.DB_USERNAME }}';
$user_password = '${{ secrets.DB_PASSWORD }}';

$conn = new PDO($db_name, $user_name, $user_password);

?>
