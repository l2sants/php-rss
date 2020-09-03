<?php
$db_news = new SQLite3('./db/news.db');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $news_id = $_POST['news_id'];
    $db_news->exec("DELETE FROM post WHERE id = '$news_id'");
}

header("Location: index.php");
exit();