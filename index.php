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
                <h1><strong>Liste des items   </strong><a href="insert.php" class="btn btn-success btn-lg"><span class="glyphicon glyphicon-plus"></span>Ajouter</a></h1>
                <table class="table table-striped table-bordered"> <!--.table-stripedclass adds zebra-stripes to a table, and .table-bordered class adds borders on all sides of the table and cells-->
                    <thead> <!--Le head de mon table. avec <tr> comme les lignes. Ici on a 1 ligne. -->
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix</th>
                            <th>Categorie</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require 'database.php';
                        $db = Database::connect(); /*$db une variable db, Database la class, et :: comme la variable est static, puis connect() pour connecter*/
                        $statement = $db->query('SELECT items.id, items.name, items.description, items.price, categories.name AS category FROM items INNER JOIN categories ON items.category = categories.id ORDER BY items.id DESC'); /*Pour recuperer les lignes et colonnes de notre bd pour notre page php.*/
                        while ($item = $statement->fetch())
                        {
                            echo '<tr>';
                                echo '<td>'. $item['name'] . '</td>'; /*On remplace nos items 1 par des valeurs de notre bd et on veut que ca concatene. Donc on met $item ['nom du champ de la bd']*/
                                echo '<td>'. $item['description'] . '</td>'; /* $item cad la table, puis entre crochet le nom de la colonne. */
                                echo '<td>'. number_format((float)$item['price'], 2, '.', ' '). '</td>'; /*le number_format a 4 elts: le float, le nombre de chiffre apres virgule, l'element qui marque la virgule, et le separateur de millier. Ici c le vide. */
                                echo '<td>'. $item['category'] . '</td>';
                                echo '<td width=300>';
                                    echo '<a class="btn btn-default" href="view.php?id='. $item['id'] . '"><span class="glyphicon glyphicon-eye-open"></span> Voir</a>';
                                    echo '<a class="btn btn-primary" href="update.php?id='. $item['id']. '"><span class="glyphicon glyphicon-pencil"></span> Modifier</a>';
                                    echo '<a class="btn btn-danger" href="delete.php?id='. $item['id']. '"><span class="glyphicon glyphicon-remove"></span> Supprimer</a>';
                                echo '</td>';
                            echo '</tr>';
                        }
                        Database::disconnect(); 
                        ?>
                    </tbody>
                </table>
            
            </div>
        
        </div>

    </body>
<html>
