<?php
    require_once __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $m = new Marknife\Api(getenv('APIUSER'), getenv('APIKEY'));

    if($m->SayHello() != 'Hello World!')
    {
        die('SayHello'. "\n\n");
    }

    $m->Help(function($err, $data, $params)
    {
        if($err) echo $err;
        print_r("HELP: " . $data->company->name . "\n\n");
    });

    $m->Messages(function($err, $data, $params)
    {
        if($err) echo $err;

        print_r($data);
    });
