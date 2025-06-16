<?php

class AdminModel {
    private $conn;
    private $table_name = "admin";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function checkLogin($username, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = ? AND password = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$username, $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE admin_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, password=:password, TenAdmin=:TenAdmin";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $username = htmlspecialchars(strip_tags($data['username']));
        $password = md5($data['password']); // Hash the password
        $tenAdmin = htmlspecialchars(strip_tags($data['TenAdmin']));

        // Bind values
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":TenAdmin", $tenAdmin);

        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " SET ";
        $params = [];
        
        if (isset($data['username'])) {
            $query .= "username=:username, ";
            $params[':username'] = htmlspecialchars(strip_tags($data['username']));
        }
        
        if (isset($data['password'])) {
            $query .= "password=:password, ";
            $params[':password'] = md5($data['password']);
        }
        
        if (isset($data['TenAdmin'])) {
            $query .= "TenAdmin=:TenAdmin, ";
            $params[':TenAdmin'] = htmlspecialchars(strip_tags($data['TenAdmin']));
        }

        $query = rtrim($query, ", ");
        $query .= " WHERE admin_id=:admin_id";
        $params[':admin_id'] = $id;

        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }
}
