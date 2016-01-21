<?php

/*
 * This file is part of `lemon/event` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

date_default_timezone_set('UTC');

// Prevent session cookies
ini_set('session.use_cookies', 0);

// Enable Composer autoloader
/** @var \Composer\Autoload\ClassLoader $autoloader */
$autoloader = require dirname(__DIR__) . '/vendor/autoload.php';

// Register test classes
$autoloader->addPsr4('Lemon\\Event\\Tests\\', __DIR__);
