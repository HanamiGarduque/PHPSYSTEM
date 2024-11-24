<?php
class Database {
    private $host = "localhost"; // Database host
    private $db_name = "blib_librarysystem"; // Database name
    private $username = "root"; // Database username
    private $password = ""; // Database password
    private $conn;

    // Function to establish a database connection
    public function getConnect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );

            // Set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $exception) {
            die("Database connection error: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
?>
