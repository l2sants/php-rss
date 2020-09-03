<?php
$url_db = new SQLite3('./db/urls.db');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = $_POST['category_id'];
    $category_path = $_POST['category_path'];  
    $xml_path = $_POST['xml_path'];  
    $url_db->exec("DELETE FROM url_list WHERE id = '$category_id'");
    unlink($category_path);
    unlink($xml_path);
}

header("Location: index.php");
exit();