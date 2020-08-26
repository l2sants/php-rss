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
$xml = file_get_contents("https://www.sciencedaily.com/rss/computers_math/computer_programming.xml");
file_put_contents("./xml/computer_programming.xml", $xml);
$doc = simplexml_load_file("./computer_programming.xml");

$base = $doc->channel->item;

foreach ($base as $item) { ?>
    <div class="p-3 d-flex justify-content-center">
        <div class="card w-75">
            <div class="card-body">
                <h5 class="card-title"><?php echo $item->title ?> </h5>
                <p class="card-text"><?php echo $item->description?></p>
                <p class="text-monospace"><?php echo substr($item->pubDate, 5, 11) ?></p>
                <a href=<?php echo $item->link ?> class="btn btn-primary">Ir</a>
            </div>
        </div>
    </div>
<?php } ?>
</body>
</html>