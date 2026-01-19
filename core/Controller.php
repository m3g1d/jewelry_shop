<?php

class Controller
{

    public function model($model)
    {
        if (file_exists('../app/models/' . $model . '.php')) {
            require_once '../app/models/' . $model . '.php';
            return new $model();
        } else {
            die("Model {$model} not found");
        }
    }

    public function view($view, $data = [])
    {
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            die("View {$view} not found");
        }
    }

    // Helper to redirect
    public function redirect($url)
    {
        header('Location: ' . BASE_URL . '/' . $url);
        exit();
    }
}
