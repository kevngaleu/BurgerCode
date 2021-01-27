<?php
    require 'database.php';
    /* Un peu de php pour recuperer l'id de mon item lorsque je cliquerai sur le bouton bleu "modifier" en admin. */
    if(!empty($_GET['id'])) /* !empty coz cet id sera recuperer lorsque l'id aura deja ete en bd lors de l'input. */
    {
        $id = checkInput($_GET['id']); 
    }



    $nameError = $descriptionError = $priceError = $categoryError = $imageError = $name = $description = $price = $category = $image = "";
    if (!empty($_POST)) /*Si mes valeurs ne sont pas vides, alors recupere les valeurs en question grace a la super GLOBAL $_POST ['name_of_value']. Puis je securise mes datas des hackeurs avec ma function que je cree que j'appelle checkInput*/
    {
        $name              = checkInput ($_POST ['name']);
        $description       = checkInput($_POST ['description']);
        $price             = checkInput($_POST ['price']);
        $category          = checkInput($_POST ['category']);
        $image             = checkInput($_FILES ['image']['name']); /*On utilise plutot la super global $_FILES pour recuperer des input de type file. */
        $imagePath         = "../images/" . basename($image);
        $imageExtension    = pathinfo($imagePath,PATHINFO_EXTENSION); /*chemin total vers mon image et une variable pour l'extension*/
        $isSuccess         = true;
        
        if (empty($name))
        {
            $nameError = 'Ce champ ne peut pas etre vide';
            $isSuccess = false; /*vu que il y a erreur.*/
        }
        if (empty($description))
        {
            $descriptionError = 'Ce champ ne peut pas etre vide';
            $isSuccess = false; /*vu que il y a erreur.*/
        }
        if (empty($price))
        {
            $priceError = 'Ce champ ne peut pas etre vide';
            $isSuccess = false; /*vu que il y a erreur.*/
        }
        if (empty($category))
        {
            $categoryError = 'Ce champ ne peut pas etre vide';
            $isSuccess = false; /*vu que il y a erreur.*/
        }
        if (empty($image))
        {
            $isImageUpdated = false; /*Je suppose que l'admin ne veux pas updater l'image. Donc le champ restera vide. Sinon je passe au else. */
        }
        else
        {   
            $isImageUpdated = true; /*Dans ce cas je veux updated l'image a ete update. */
            $isUploadSuccess = true; /* Ici je vais verifier un certain nombre de choses sur l'upload. */
            
            /*1ere verification de mon upload sur l'extension des fichiers. Si les fichiers uploades ont une extension != de celles ci*/
            if($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif" )
            {
                $imageError = "Authorized files are: .jpg, .jpeg, .png, .gif"; /*Alors envoie message les fichiers authorises sont ...*/
                $isUploadSuccess = false; /* et $isUploadSuccess devient false. */
            }
            /*2eme verification de mon upload, Sur le chemin de mon image. Pour checker si cette image existe deja. exple b7.png*/
            if (file_exists($imagePath))
            {
                $imageError = "Oups! Files already exists!";
                $isUploadSuccess =  false;
            }
            /* 3eme verification de notre upload. Sur la taille des fichiers images upload sinon notre le site va charger lentement. J'utilise la super Global $-FILES ['nom_image']['size']*/
            if ($_FILES['image']['size'] > 500000)
            {
                $imageError = "Ayayai! Your file is too big!";
                $isUploadSuccess = false;
            }
            /*cette fois une fois nos etapes de checking precedent passees. Je vais move l'image upload vers un autre point non temporaire. Un endroit qui correspond au chemin defini plus tot cad le $imagePath ..*/
            if ($isUploadSuccess)
            {
                if(!move_uploaded_files($_FILES["image"]["top_name"], $imagePath))
                {
                    $imageError = "There has been an error while uploading.";
                    $isUploadSuccess = false;
                }
            }
        }
        /* On a deja verifie nos conditions en haut. Now on si isSuccess et upload success. */
        if (($isSuccess && $isImageUpdated && $isUploadSuccess) || ($isSuccess && !$isImageUpdated)) /*Si ls donnees sont bien entrees, si l'image a ete updated, et si l'upload a ete un succes. Ou si les donnees sont correctes et l'image n'a pas ete upadted Alors execute le code en bas.  */
        {
            $db = Database::connect();
            if($isImageUpdated)
            {
                $statement = $db->prepare("UPDATE items set name = ?, description = ?, price = ?, category = ?, image = ? WHERE id = ?");
                $statement->execute(array($name, $description, $price, $category, $image, $id)); /* on rajoute le id pour savoir quel item a modifier l'image. */
            }
            else
            {
                $statement = $db->prepare("UPDATE items set name = ?, description = ?, price = ?, category = ? WHERE id = ?");
                $statement->execute(array($name, $description, $price, $category, $id)); /* pas besoin de image coz l'image ne sera pas modifie.*/
            }
            Database::disconnect();
            header("location: index.php"); /*cela veut dire change moi l'adresse et mets moi index.php. */
        }
        else if($isImageUpdated && !isUploadSuccess)
        /* Pour que le nom d'un fichier inadapte ne s'affiche pas sur ma boite d'upload. */
        {
        $db = Database::connect();
        $statement   = $db->prepare("SELECT * FROM items WHERE id = ?");
        $statement->execute(array($id)); /* Cad la vvaleur que j'ai recupere a la ligne 6. */
        $item        = $statement->fetch(); /*Je recupere la ligne de l'item. */
        $image       = $item['image'];
        Database::disconnect();            
        }
        
    }
    else
    {
        $db = Database::connect();
        $statement   = $db->prepare("SELECT * FROM items WHERE id = ?");
        $statement->execute(array($id)); /* Cad la vvaleur que j'ai recupere a la ligne 6. */
        $item        = $statement->fetch(); /*Je recupere la ligne de l'item. */
        $name        = $item['name'];
        $description = $item['description'];
        $price       = $item['price'];
        $category    = $item['category'];
        $image       = $item['image'];
        Database::disconnect();            
    }

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
                    <br>
                    <h1><strong>Modifier un item  </strong></h1>
                    <!--Puis je precise dans la propriete action le lien de la page et l'id que je vais recuperer. -->
                    <form class="form" role="form" action="<?php echo 'update.php?id=' . $id; ?>" method="post" enctype="multipart/form-data"> 
                        <div class="form-group">
                            <label for="name">Nom :</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Entrez nom" value="<?php echo $name; ?>">

                            <span class="help-inline"><?php echo $nameError; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="description">Description :</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?php echo $description; ?>">

                            <span class="help-inline"><?php echo $descriptionError; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="price">Prix (en €):</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Prix" value="<?php echo $price; ?>">

                            <span class="help-inline"><?php echo $priceError; ?></span>

                        </div>
                        <div class="form-group">
                            <label for="category">Categorie:</label>
                            <select class="form-control" id="category" name="category">
                                <?php /*J'ouvre ma balise php */
                                    $db = Database::connect();  /*Je la connecte a ma bd */
                                    foreach($db->query('SELECT * FROM categories') as $row) /* J'ecris l'instruction de la boucle while or foreach en lancant un requete pour ts les elts de categories*/
                                    {
                                        if($row['id'] == $categpry)
                                            echo '<option selected="selected" value="'. $row['id'] . '">' .  $row['name'] . '</option>';   /* Affiche les noms de ces elts qui ont pour option value leur id */
                                            echo '<option value="'. $row['id'] . '">' .  $row['name'] . '</option>'; 
                                    }
                                    Database::disconnect(); /*J'ai termine donc je disconnect. */
                                ?>
                            </select>
                            <span class="help-inline"><?php echo $categoryError; ?></span>
                        </div>
                        <div class="form-group">
                            <label>image:</label>
                            <p><?php echo $image; ?></p>
                            <label for="image">Selectionner une image:</label>
                            <input type="file" id="image" name="image">
                            <span class="help-inline"><?php echo $imageError; ?></span>
                        </div>
                        <br>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Modifier</button>
                            <a class="btn btn-primary" href="index.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
                        </div>
                    </form>
                </div>
                <!-- Colonne pour l'apercu image. -->
                <div class="col-sm-6">
                    <div class="thumbnail">
                        <img src="<?php echo '../images/'.$image ;?>" alt="...">
                        <div class="price"><?php echo number_format((float)$price, 2, '.', ' '). ' €'; ?></div>
                        <div class="caption">
                            <h4><?php echo $name; ?></h4>
                            <p><?php echo $description; ?><?php echo ' '. $description; ?></p>
                            <a class="btn btn-order" href="#"role="button"><span class="glyphicon glyphicon-shopping-cart"></span>Commander</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>