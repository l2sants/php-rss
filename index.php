<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>php RSS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#">Space time</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./computer_programming.php">Computer programming</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./space_time.php">Space time</a>
            </li>
        </ul>
    </div>
    </nav>
    <div class="d-flex justify-content-center">
        <h1 class="display-3">Favoritos</h1>
    </div>
    <?php  
    $db = new SQLite3('news.db');
    $res = $db->query("SELECT * FROM post");
    
    while ($row = $res->fetchArray()) { ?>
        <div class="p-3 d-flex justify-content-center">
            <div class="card w-75">
                    <div class="d-flex justify-content-end">
                        <form action="deleta.php" method="post">
                            <input type="hidden" name="news_id" value=<?php echo $row['id'] ?>>
                            <button type="submit" class="btn btn-danger">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </form>
                    </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['title']; ?> </h5>
                    <p class="card-text"><?php echo $row['desc']; ?></p>
                    <p class="text-monospace"><?php echo substr($row['dtpub'], 5, 11); ?></p>
                    <a href=<?php echo $row['link']; ?> class="btn btn-primary">Ir</a>
                </div>
            </div>
        </div>
    <?php } ?>
</body>
</html>


