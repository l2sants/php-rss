<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>feed RSS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/">rss</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="./space_time.php">Space time</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="./computer_programming.php">Computer programming</a>
        </li>

        </ul>
    </div>
    </nav>

<?php

$xml = file_get_contents("https://www.sciencedaily.com/rss/space_time.xml");
file_put_contents("./xml/space_time.xml", $xml);
$doc = simplexml_load_file("./xml/space_time.xml");

$base = $doc->channel->item;

$idx = 0;
$erro_msg = "";
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $index = $_POST['index']-1;
    $doc = simplexml_load_file("./xml/space_time.xml");
    $base = $doc->channel->item;
    $t = str_replace("'", "`", $base[$index]->title);
    $d = str_replace("'", "`", $base[$index]->description);
    $p = str_replace("'", "`", $base[$index]->pubDate);
    $l = str_replace("'", "`", $base[$index]->link);
    
    
    $db = new SQLite3('news.db');
    $db->exec('CREATE TABLE IF NOT EXISTS post(id INTEGER PRIMARY KEY, title TEXT UNIQUE, desc TEXT UNIQUE, link TEXT UNIQUE, dtpub DATE UNIQUE)');
    try {
        if(!$db->exec("INSERT INTO post(title, desc, link, dtpub)VALUES('$t', '$d', '$l', '$p')")) {
            throw new Exception('Não é possivel salvar uma noticia duas vezes');
        }
    } 
    catch(Exception $e) {
        $erro_msg = $e->GetMessage();
    }
}?>

<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Erro: </strong><?php echo $erro_msg; ?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php foreach ($base as $item ) { ?>
    <div class="p-3 d-flex justify-content-center">
        <div class="card w-75">
            <div class="card-body">
                <h5 class="card-title"><?php echo $idx++; ?> </h5>
                <h4 class="card-title"><?php echo $item->title ?> </h4>
                <p class="card-text"><?php echo $item->description?></p>
                <p class="text-monospace"><?php echo substr($item->pubDate, 5, 11) ?></p>
                <a href=<?php echo $item->link ?> class="card-link">
                    <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-link-45deg" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.715 6.542L3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.001 1.001 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
                        <path d="M5.712 6.96l.167-.167a1.99 1.99 0 0 1 .896-.518 1.99 1.99 0 0 1 .518-.896l.167-.167A3.004 3.004 0 0 0 6 5.499c-.22.46-.316.963-.288 1.46z"/>
                        <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 0 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 0 0-4.243-4.243L6.586 4.672z"/>
                        <path d="M10 9.5a2.99 2.99 0 0 0 .288-1.46l-.167.167a1.99 1.99 0 0 1-.896.518 1.99 1.99 0 0 1-.518.896l-.167.167A3.004 3.004 0 0 0 10 9.501z"/>
                    </svg>
                </a>
                <div class=" d-flex justify-content-center">
                    <form action="/space_time.php" method="POST" class="pt-4">
                        <input type="hidden" name="index" value=<?php echo $idx ?>>
                        <input type="submit" class="btn btn-primary" value="SAVE">
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script>
   $('.alert')
</script>
</body>
</html>


