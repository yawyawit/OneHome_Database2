<?php
require_once __DIR__ . '/includes/auth.php';
$_SESSION = [];
session_regenerate_id(true);
flash('You have been logged out.', 'info');
redirect('login.php');
