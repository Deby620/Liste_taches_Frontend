<?php
class Database {
    private $host = "localhost"; 
    private $database_name = "liste_taches"; // nom de la base de données
    private $username = "root";  // utilisateur par défaut de XAMPP
    private $password = "";      // mot de passe par défaut de XAMPP
    public $conn; // connexion à la base de données

    public function getConnection() {
        $this->conn = null;
        //creation de la connexion a la base de donnees
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, 
                                 $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } 
        // en cas d'erreur de connexion, on capture l'exception et on affiche le message d'erreur
        catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
