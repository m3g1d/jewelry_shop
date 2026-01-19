<?php

require_once '../app/models/User.php';

class AuthController extends Controller
{

    public function index()
    {
        $this->login();
    }

    public function login()
    {
        // If logged in, redirect
        if (isset($_SESSION['user_id'])) {
            $this->redirect('shop');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = new User();
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Check Lockout
            if ($userModel->isLocked($email)) {
                $error = "Account locked due to too many failed attempts. Check your email.";
                $this->view('auth/login', ['error' => $error]);
                return;
            }

            $user = $userModel->findUserByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Success
                $userModel->resetFailedAttempts($email);
                $_SESSION['user_id'] = $user['user_id']; // Changed to match DB
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name']; // Helper for display

                if ($user['role'] == 'Admin' || $user['role'] == 'Warehouse') {
                    $this->redirect('admin/dashboard');
                } else {
                    $this->redirect('shop');
                }
            } else {
                // Fail
                if ($user) {
                    $isLocked = $userModel->incrementFailedAttempts($email);
                    if ($isLocked) {
                        $error = "Account has been locked.";
                    } else {
                        $error = "Invalid credentials.";
                    }
                } else {
                    $error = "Invalid credentials.";
                }

                $this->view('auth/login', ['error' => $error]);
            }
        } else {
            $this->view('auth/login');
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = new User();
            $firstName = trim($_POST['first_name']);
            $lastName = trim($_POST['last_name']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Basic Validation
            if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
                $this->view('auth/register', ['error' => 'All fields are required']);
                return;
            }

            if ($userModel->register($firstName, $lastName, $email, $password)) {
                $this->redirect('auth/login');
            } else {
                $debug = isset($_SESSION['debug_error']) ? ' Error: ' . $_SESSION['debug_error'] : '';
                unset($_SESSION['debug_error']); // clear it
                $this->view('auth/register', ['error' => 'Registration failed. Email might be taken or Database issue.' . $debug]);
            }
        } else {
            $this->view('auth/register');
        }
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('auth/login');
    }
}
