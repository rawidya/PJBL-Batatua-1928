<?php
require_once __DIR__ . '/config.php';
start_session_if_needed();
session_unset();
session_destroy();
header('Location: Login-for-admin.php');
exit;


