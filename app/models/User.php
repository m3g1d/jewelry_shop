<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findUserByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register($first_name, $last_name, $email, $password)
    {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, email, password_hash) VALUES (:fname, :lname, :email, :pass)");
        $stmt->bindParam(':fname', $first_name);
        $stmt->bindParam(':lname', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pass', $password_hash);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            $_SESSION['debug_error'] = $e->getMessage();
            return false;
        }
    }

    public function incrementFailedAttempts($email)
    {
        $stmt = $this->db->prepare("UPDATE users SET failed_attempts = failed_attempts + 1 WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Check if we reached limit
        $user = $this->findUserByEmail($email);
        if ($user && $user['failed_attempts'] >= 3) {
            $this->lockAccount($email);
            return true; // Locked
        }
        return false;
    }

    public function resetFailedAttempts($email)
    {
        $stmt = $this->db->prepare("UPDATE users SET failed_attempts = 0, account_status = 'Active', block_date = NULL WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }

    private function lockAccount($email)
    {
        // Lock account
        $stmt = $this->db->prepare("UPDATE users SET account_status = 'Blocked', block_date = NOW() WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }

    public function isLocked($email)
    {
        $user = $this->findUserByEmail($email);
        if (!$user)
            return false;

        if ($user['account_status'] === 'Blocked') {
            return true;
        }
        return false;
    }
}
