#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Command/LogstashPusherCommand.php';

use ServerGrove\LogstashPusher\Command\LogstashPusherCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new LogstashPusherCommand);
$application->run();