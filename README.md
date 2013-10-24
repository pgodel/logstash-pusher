logstash-pusher
===============

CLI tool to send messages to logstash through redis


Installation
------------

To install logstash-pusher, you need PHP 5.3.x.

The installation is done with Composer:

    # install composer
    curl -sS https://getcomposer.org/installer | php

    # install dependencies
    php composer.phar install


Usage
-----

	php logstash-pusher.php push "your message"  --tags='["tag1", "tag2"]' --fields='{"name": "value", "name2", "value2"}'