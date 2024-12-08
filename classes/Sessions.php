<?php
class Session {
    private $conn;
    private $table_name = "sessions";

    public $id;
    public $name;
    public $coach_id;
    public $date_time;
    public $duration;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire toutes les séances
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Créer une séance
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, coach_id=:coach_id, date_time=:date_time, duration=:duration";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":coach_id", $this->coach_id);
        $stmt->bindParam(":date_time", $this->date_time);
        $stmt->bindParam(":duration", $this->duration);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Mettre à jour une séance
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET name=:name, coach_id=:coach_id, date_time=:date_time, duration=:duration WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":coach_id", $this->coach_id);
        $stmt->bindParam(":date_time", $this->date_time);
        $stmt->bindParam(":duration", $this->duration);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Supprimer une séance
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
