<?php
// Technotutor - front controller
session_start();
define('ROOT', __DIR__);
require ROOT.'/config/app.php';
require ROOT.'/config/seed_materials.php';
require ROOT.'/config/premium_materials.php';
require ROOT.'/includes/functions.php';
require ROOT.'/includes/database.php';
require ROOT.'/includes/auth.php';
require ROOT.'/includes/actions.php';

include ROOT.'/views/layout/header.php';
if ($p == 'login') {
    include ROOT.'/views/login.php';
} else {
    include ROOT.'/views/layout/nav.php';
    $routes = ['home','field','mat','quiz','cert','settings','competitions','plans','tutor','games','game'];
    if ($U['role']==='tutor') $routes = ['home','settings','tutor'];
    if ($adm) $routes[] = 'edit';
    include ROOT.'/views/'.(in_array($p, $routes) ? $p : 'home').'.php';
}
include ROOT.'/views/layout/footer.php';
