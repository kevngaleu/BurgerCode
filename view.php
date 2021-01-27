<?php

    require 'database.php'; /* connexion a la database */
    if (!empty($_GET['id']))
    {
        $id = checkInput($_GET['id']);
    }

    $db = Database::connect(); /*function static de la database*/
    $statement = $db->prepare('SELECT items.id, items.name, items.description, items.price, items.image, categories.name AS category FROM items INNER JOIN categories ON items.category = categories.id WHERE items.id = ?');

    $statement->execute(array($id)); /* on a juste besoin du $id. */
    $item = $statement->fetch(); /*On a recupere notre item. Tout est stocke ici */
    Database::disconnect(); /*tjrs disconnect de notre db.*/

    function checkInput($data) /*Pour proteger l'infos de notre super global qui vient de l'exterieur*/
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Burger Code</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Holtwood+One+SC&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/style.css">
    </head>
    
    <body>
        <h1 class="text-logo"><span class="glyphicon glyphicon-cutlery"></span> Burger Code <span class="glyphicon glyphicon-cutlery"></span></h1>
        <div class="container admin" style="background: #fff; padding:50px; border-radius:10px">
            <div class="row">
                <div class="col-sm-6">
                    <h1><strong>Voir un item  </strong></h1>
                    <br>
                    <form>
                        <div class="form-group">
                            <label>Nom:</label><?php echo ' '. $item['name']; ?>
                        </div>
                        <div class="form-group">
                            <label>Description:</label><?php echo ' '. $item['description']; ?>
                        </div>
                        <div class="form-group">
                            <label>Prix:</label><?php echo ' '. number_format((float)$item['price'], 2, '.', ' '). ' €'; ?> 
                        </div>
                        <div class="form-group">
                            <label>Categorie:</label><?php echo ' '. $item['category']; ?>
                        </div>
                        <div class="form-group">
                            <label>Image:</label><?php echo ' '. $item['image']; ?>
                        </div>
                    </form>
                    <br>
                    <div class="form-actions">
                        <a class="btn btn-primary" href="index.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
                    </div>
                </div>
                <div class="col-sm-6 site" site>
                    <div class="thumbnail">
                        <img src="<?php echo '../images/'.$item['image'];?>" alt="...">
                        <div class="price"><?php echo number_format((float)$item['price'], 2, '.', ' '). ' €'; ?></div>
                        <div class="caption">
                            <h4><?php echo $item['name']; ?></h4>
                            <p><?php echo $item['description']; ?><?php echo ' '. $item['description']; ?></p>
                            <a class="btn btn-order" href="#"role="button"><span class="glyphicon glyphicon-shopping-cart"></span>Commander</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
<html>
