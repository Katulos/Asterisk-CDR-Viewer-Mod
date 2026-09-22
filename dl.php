<?php

require_once __DIR__ . '/inc/load.php';

# Доступ запрещен
if ((string) $cdr_user_name !== '') {
    header('HTTP/1.1 403 Forbidden');
    exit;
}
if (isset($_REQUEST['f'])) {
    $fname = base64_decode($_REQUEST['f']);
    $fname = preg_replace('#\.\.#', '', $fname);
    $file = Config::get('system.monitor_dir') . '/' . $fname;
    $send = new Sendfile();
    $send->send($file);
    exit;
}

if (isset($_REQUEST['csv'])) {
    $fname = base64_decode($_REQUEST['csv']);
    $fname = preg_replace('#\.\.#', '', $fname);
    $file = Config::get('system.tmp_dir') . '/' . $fname;
    $send = new Sendfile();
    $send->contentType('text/csv');
    $send->send($file);
    exit;
}

header('HTTP/1.1 404 Not Found');
