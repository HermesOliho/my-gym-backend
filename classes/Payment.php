<?php
class Payment {
    private $conn;
    private $table_name = "payments";

    public $id;
    public $member_id;
    public $subscription_id;
    public $amount;
    public $payment_date;
    public $payment_method;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire tous les paiements
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Créer un paiement
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET member_id=:member_id, subscription_id=:subscription_id, amount=:amount, payment_date=:payment_date, payment_method=:payment_method";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":member_id", $this->member_id);
        $stmt->bindParam(":subscription_id", $this->subscription_id);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":payment_date", $this->payment_date);
        $stmt->bindParam(":payment_method", $this->payment_method);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Mettre à jour un paiement
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET member_id=:member_id, subscription_id=:subscription_id, amount=:amount, payment_date=:payment_date, payment_method=:payment_method 
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":member_id", $this->member_id);
        $stmt->bindParam(":subscription_id", $this->subscription_id);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":payment_date", $this->payment_date);
        $stmt->bindParam(":payment_method", $this->payment_method);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Supprimer un paiement
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
