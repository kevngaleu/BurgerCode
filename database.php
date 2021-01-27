<?php
class Database
{
    private static $dbHost = "localhost";
    private static $dbName = "burger_code";
    private static $dbUser = "root";
    private static $dbUserPassword = "";
    
    private static $connection = null;
    
    public static function connect()
    {
        /*Je cree une variable connexion pour la connexion a ma db. Avec gestion des erreurs pour ne pas divulger des details de connexion*/
        try
        {
        self::$connection = new PDO ("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName,self::$dbUser,self::$dbUserPassword, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION)); /* array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION) permet de detecter des erreurs precisement*/
        
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
        return self::$connection;
    }
    public static function disconnect()
    {
        self::$connection = null;
    }
    
}

/* code de la connexion a la database*/
Database::connect();



?>