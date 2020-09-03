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
        <a class="navbar-brand" href="/">PHP-rss</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <?php  
                include "validate.php";
        
                $err_msg = "";
                $erro_link_msg = "";
                $url_db = new SQLite3("./db/urls.db");
                $db_news = new SQLite3('./db/news.db');
                $db_news->exec('CREATE TABLE IF NOT EXISTS post(id INTEGER PRIMARY KEY, title TEXT UNIQUE, desc TEXT UNIQUE, link TEXT UNIQUE, dtpub DATE UNIQUE)');
                $url_db->exec('CREATE TABLE IF NOT EXISTS url_list(id INTEGER PRIMARY KEY, url TEXT UNIQUE)');
                
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $link = $_POST['url-rss'];
                    url($link);
                    $err_msg = url($link);
                
                    if(!$err_msg) {
                        try {
                            if(!$url_db->exec("INSERT INTO url_list(url)VALUES('$link')")) {
                                throw new Exception("Não é possivel adicionar o um link duas vezes");
                            }
                        } catch(Exception $e) {
                            $erro_link_msg = $e->GetMessage();
                        } 
                    }
                }

                $urls = $url_db->query("SELECT * FROM url_list");
                while ($row = $urls->fetchArray()) {
                    if($row['url']) {
                        $file_name = explode("/", $row['url'])[5];
                        $xml = file_get_contents($row['url']);
                        file_put_contents("./xml/" . $file_name, $xml); 
                        
                        $category_name = explode(".", explode("/", $row['url'])[5])[0];
                        $category_path = "./pages/" . $category_name . ".php";
                        $xml_path = "./xml/" . $file_name;
                        $page = fopen($category_path, "w");

                        $content_template = '<?php

                        include "./templates/header.html";
                        
                        include "../save_news.php";
                        echo $alert_msg;
                        include "../show_news_list.php";
                
                        show_news("'.$file_name.'");
                        
                        include "./templates/footer.html";';
                        
                        fwrite($page, $content_template);
                        fclose($page);
                    }
                    
                    ?>
                    <!-- Lista as categorias de noticias -->
                    <li class="nav-item pr-4">
                        <div class="form-inline my-2 my-lg-0">
                        <a class="nav-link" href="<?php echo $category_path; ?>"><?php echo $category_name; ?></a>
                            <form action="./delete_category.php" method="post">
                                <input type="hidden" name="category_id" value=<?php echo $row['id'] ?>>
                                <input type="hidden" name="category_path" value=<?php echo $category_path ?>>
                                <input type="hidden" name="xml_path" value=<?php echo $xml_path ?>>
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </form>
                        </div>
                    </li>
            <?php } ?>
                <li class="nav-item">
                    <form class="form-inline my-2 my-lg-0" action="./index.php" method="POST">
                        <input class="form-control mr-sm-2" type="text" name="url-rss" placeholder="Adicione o link do RSS ">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">+</button>
                    </form>
                    <?php if($err_msg) {?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><?php echo $err_msg; ?> </strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                    <?php if($erro_link_msg) {?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><?php echo $erro_link_msg; ?> </strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                </li>
            </ul>
        </div>
        </nav>
        <!-- Lista todos favoritos -->
        <div class="d-flex justify-content-center">
            <h1 class="display-3">Favoritos</h1>
        </div>
        <?php
            $res = $db_news->query("SELECT * FROM post");
        
            while ($row = $res->fetchArray()) {?>
            <div class="p-3 d-flex justify-content-center">
                <div class="card w-75">
                    <div class="d-flex justify-content-end">
                        <form action="./delete_news.php" method="POST">
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
    <?php   } ?>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script>
        $('.alert')
        </script>
    </body>
    </html>