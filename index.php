<?php
/**
 * Sport+ — Single Entry Point
 * Every URL goes through here (via .htaccess rewrite)
 */

require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/routes/web.php';