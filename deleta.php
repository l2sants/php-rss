<?php
$db = new SQLite3('./news.db');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $news_id = $_POST['news_id'];
    $db->exec("DELETE FROM post WHERE id = '$news_id'");
}

header("Location: index.php");
exit();