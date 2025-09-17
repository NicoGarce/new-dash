<?php
/**
 * Database Configuration
 * Ready for database integration when needed
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'dashboard_db';
    private $username = 'root';
    private $password = '';
    private $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}

// Example usage when database is ready:
/*
$database = new Database();
$db = $database->getConnection();

// Example queries for live data:
$query = "SELECT * FROM enrollment WHERE campus = ? AND year = ?";
$stmt = $db->prepare($query);
$stmt->execute([$campus, $year]);
$enrollment_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
*/
?>
