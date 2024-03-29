<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Ratchet Websocket Library: config file
 * @author Romain GALLIEN <romaingallien.rg@gmail.com>
 * @var array
 */
$config['ratchet_client'] = array(
    /* 'host' => '192.168.10.128', */    // Default host
    'host' => 'localhost',    // Default host
    'port' => 8282,         // Default port (be carrefull to set unused server port)
    'auth' => true,         // If authentication is mandatory
    'debug' => true         // Better to set as false in Production
);