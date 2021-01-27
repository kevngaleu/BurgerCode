S<?php
    require 'database.php';
    if (!empty($_GET['id']))
    {
        $id = checkInput($_GET['id']);
    }

    function checkInput($data) /*Pour proteger l'infos de notre super global qui vient de l'exterieur*/
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    if(!empty($_POST)) /* Comment recuperer mon id avec la variable POST. */
    {
        $id = checkInput($_POST['id']);
        $db = Database::connect();
        $statement = $db->prepare("DELETE FROM items WHERE id = ?");
        $statement->execute(array($id));
        Database::disconnect();
        header("Location: index.php"); /*Quand tu auras valide oui. Retourner sur la page index.php.*/
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
                <h1><strong>Supprimer un item  </strong></h1>
                <br>
                <form class="form" role="form" action="delete.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $id ?>"> <!--notre ligne pour recuperer l'id.-->
                    <p class="alert alert-warning">Etes vous sur(e) de vouloir supprimer cet element?</p>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-warning"> Oui</button>
                        <a class="btn btn-default" href="index.php"> Non</a>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>