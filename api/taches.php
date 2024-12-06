<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $stmt = $db->prepare("SELECT * FROM taches");
        $stmt->execute();
        $taches = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($taches);
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        $stmt = $db->prepare("INSERT INTO taches (nom) VALUES (?)");
        $stmt->execute([$data->nom]);
        echo json_encode(["message" => "Tache creee"]);
        break;
        
        case 'PUT':
            $data = json_decode(file_get_contents("php://input"));
            $stmt = $db->prepare("UPDATE taches SET terminee = :terminee WHERE id = :id");
            $result = $stmt->execute([
                ':terminee' => $data->terminee,
                ':id' => $data->id
            ]);
            if ($result) {
                // Vérifier la mise à jour
                $verify = $db->query("SELECT terminee FROM taches WHERE id = " . $data->id);
                $updated = $verify->fetch(PDO::FETCH_ASSOC);
                echo json_encode([
                    "message" => "Tache modifiee",
                    "success" => true,
                    "new_status" => $updated['terminee']
                ]);
            } else {
                echo json_encode(["message" => "Erreur", "success" => false]);
            }
            break;
        
                
        
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        $stmt = $db->prepare("DELETE FROM taches WHERE id = ?");
        $stmt->execute([$data->id]);
        echo json_encode(["message" => "Tache supprimee"]);
        break;
}
?>
