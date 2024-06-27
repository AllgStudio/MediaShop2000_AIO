<?php
/**
 * USing PDO to connect to the database, so require Php version 5.1 or higher
 * 
 * Il file api/user.php è il file che gestisce le richieste relative agli utenti.
 * Per esempio:
 *    ottenere la lista di tutti gli utenti
 *    ottenere un utente specifico
 *    creare un nuovo utente
 *    aggiornare un utente esistente
 *    eliminare un utente
 *      
 */
class UserManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUser($id) {
        $query = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getUsers() {
        $query = "SELECT * FROM user";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createUser($name, $email, $password) {
        $query = "INSERT INTO user (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateUser($id, $name, $email, $password) {
        $query = "UPDATE user SET name = :name, email = :email, password = :password WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    }

    public function deleteUser($id) {
        
        $query = "DELETE FROM user WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function updateRole($id, $role) {
        $query = "UPDATE user SET role = :role WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
    }
}