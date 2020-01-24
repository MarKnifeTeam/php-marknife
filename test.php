<?php
    require_once __DIR__ . '/vendor/autoload.php';

    $m = new Marknife\Api('');

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
        print_r($data. "\n\n");
    });
