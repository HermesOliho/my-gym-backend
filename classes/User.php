<?php
class User
{
    /** @var PDO $conn */
    private $conn;
    private $table_name = "users";

    public $id;
    public string $name;
    public string $contact;
    public string $get_query;
    public string $password;
    public string $role;
    public $subscription_type;
    public $start_date;
    public $end_date;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->get_query = "SELECT 
                                u.id AS id, 
                                u.name AS name, 
                                u.contact AS contact, 
                                s.start_date AS start_date, 
                                s.end_date AS end_date, 
                                st.name AS subscription, 
                                st.id AS subscription_id, 
                                st.price AS price 
                            FROM users u, subscriptions s, subscription_types st 
                            WHERE s.subscription_type_id = st.id AND s.user_id = u.id";
    }

    // Lire tous les utilisateurs
    public function read()
    {
        $stmt = $this->conn->prepare($this->get_query . " ORDER BY u.id DESC;");
        $stmt->execute();
        return $stmt;
    }

    // Lire un utilisateur
    public function readOne(int $id)
    {
        $stmt = $this->conn->prepare($this->get_query . " AND u.id = :id;");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt;
    }

    // Créer un utilisateur
    public function create()
    {
        // Create a simple user
        $query = "INSERT INTO users SET name=:name, contact=:contact";
        // if (is_string($this->password)) {
        //     $query .= ", password=:password";
        // }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":contact", $this->contact);
        // if (is_string($this->password)) {
        //     $stmt->bindParam(":password", $this->password);
        // }
        if ($stmt->execute()) {
            $id = $this->conn->lastInsertId();
            $stmt->closeCursor();
            $query = "INSERT INTO subscriptions(subscription_type_id, start_date, end_date, user_id)
                      VALUES(:type_id, :start_date, :end_date, :user_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":type_id", $this->subscription_type);
            $stmt->bindParam(":start_date", $this->start_date);
            $stmt->bindParam(":end_date", $this->end_date);
            $stmt->bindParam(":user_id", $id);
            if ($stmt->execute()) {
                return true;
            }
        }
        return false;
    }

    public function update()
    {
        $query = "UPDATE users 
              SET name = :name, contact = :contact, role = :role 
              WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":contact", $this->contact);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            // Mise à jour de l'abonnement si les détails sont fournis
            if ($this->subscription_type && $this->start_date && $this->end_date) {
                $query = "UPDATE subscriptions 
                      SET subscription_type_id = :type_id, start_date = :start_date, end_date = :end_date 
                      WHERE user_id = :user_id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":type_id", $this->subscription_type);
                $stmt->bindParam(":start_date", $this->start_date);
                $stmt->bindParam(":end_date", $this->end_date);
                $stmt->bindParam(":user_id", $this->id);

                if ($stmt->execute()) {
                    return true;
                }
            } else {
                // Si pas d'abonnement fourni, considérez l'utilisateur mis à jour
                return true;
            }
        }

        return false;
    }

    public function delete()
    {
        // Suppression de l'utilisateur et des abonnements liés en cascade
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
