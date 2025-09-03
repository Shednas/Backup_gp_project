<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $username = 'root';
        $password = 'student';
        $database = 'university_website';
        $host = 'mysql';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}

// Helper functions
function db() {
    return Database::getInstance()->getConnection();
}

function query($sql, $params = []) {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function fetchAll($sql, $params = []) {
    return query($sql, $params)->fetchAll();
}

function fetchOne($sql, $params = []) {
    return query($sql, $params)->fetch();
}
?>