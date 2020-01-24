<?php
    /**
        * Marknife Rest API
        * @version 1.0
        * @package Marknife
        * @link https://www.marknife.com
        * @author Jose Bernalte <yo@josebernalte.com>
        * @copyright Copyright (c) 2019, Marknife
    */
    namespace Marknife;

    class Api
    {
        /**
            * API token
            * @var string
        */
        public $token;
        /**
            * API Call Method
            * @var string
        */
        public $method     = 'GET';
        /**
            * API Version
            * @var int
        */
        public $version     = 1;
        /**
            * Authentication Mode
            * @var int
        */
        public $authtype   ='Bearer';


        function __construct($token)
        {
            $this->token    = $token;
            //$this->method   = $method;
            //$this->version  = $version;
            //$this->authtype = $authtype;
        }

        public static function SayHello()
        {
            return 'Hello World!';
        }
    }
