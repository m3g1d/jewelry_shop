<?php
session_start();

require_once '../config/config.php';
require_once '../config/db.php';
require_once '../core/Controller.php';
require_once '../core/Router.php';


$app = new Router();
