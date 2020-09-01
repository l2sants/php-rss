<?php
$alert_msg = null;
if($_SERVER['REQUEST_METHOD'] == 'POST') {   
    $index = $_POST['index']-1;
    $file_name = $_POST['file_name'];
    
    $doc = simplexml_load_file("../xml/" . $file_name);
    $base = $doc->channel->item;
    $t = str_replace("'", "`", $base[$index]->title);
    $d = str_replace("'", "`", $base[$index]->description);
    $p = str_replace("'", "`", $base[$index]->pubDate);
    $l = str_replace("'", "`", $base[$index]->link);
   
    $category = "./pages/" . explode(".", $file_name)[0] . ".php";
    $db = new SQLite3('../news.db');
    $db->exec('CREATE TABLE IF NOT EXISTS post(id INTEGER PRIMARY KEY, title TEXT UNIQUE, desc TEXT UNIQUE, link TEXT UNIQUE, dtpub DATE UNIQUE)');
    try {
        $alert_msg = "";
        if(!$db->exec("INSERT INTO post(title, desc, link, dtpub)VALUES('$t', '$d', '$l', '$p')")) {
            throw new Exception('Não é possivel salvar uma noticia duas vezes');
        }else {
            $alert_msg = '<div class="alert alert-success" role="alert">
                            noticias salva!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                          </div>';
        }
    } 
    catch(Exception $e) {
        $alert_msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>'. $e->GetMessage() . '</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                     </div>';
    }
}
