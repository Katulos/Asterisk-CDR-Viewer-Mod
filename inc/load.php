<?php

declare(strict_types=1);

require_once __DIR__ . '/Config.class.php';
Config::setPath('inc/config/config.php');

// Пользовательский конфиг
if (isset($_REQUEST['config']) && preg_match('#^[A-Za-z0-9_-]+$#', $_REQUEST['config'])) {
    Config::setPath('inc/config/config-' . $_REQUEST['config'] . '.php');
}

// Авторизация
$cdr_user_name = getenv('REMOTE_USER');
if ((string) $cdr_user_name !== '') {
    $is_admin = preg_grep('#^' . preg_quote($cdr_user_name) . '$#', Config::get('system.admins'));
    if (count(Config::get('system.admins')) === 0) {
        $cdr_user_name = '';
    } elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout') {
        header('Status: 401 Unauthorized');
        header('WWW-Authenticate: Basic realm="CDR Viewer Mod"');
        exit;
    } elseif ($is_admin) {
        $cdr_user_name = '';
    }
}

// Загрузка плагинов
if (Config::exists('system.plugins') && Config::get('system.plugins')) {
    foreach (Config::get('system.plugins') as $p_val) {
        require_once "inc/plugins/$p_val.php";
    }
}

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/requests.php';
require_once __DIR__ . '/Sendfile.class.php';
