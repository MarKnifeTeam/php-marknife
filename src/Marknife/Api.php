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
        public $method;
        /**
            * API Version
            * @var int
        */
        public $version;
        /**
            * Authentication Mode
            * @var int
        */
        public $authtype;

        function __construct($token, $method = 'GET', $version = 1, $authtype='Bearer')
        {
            $this->token    = $token;
            $this->method   = $method;
            $this->version  = $version;
            $this->authtype = $authtype;
        }
        public static function SayHello()
        {
            return 'Hello World!';
        }
        public function Help($callback, $options = null)
        {
            $this->Read('/help', $callback, $options);
        }

        public function Messages($callback, $options = null)
        {
            $this->Read('/messages', $callback, $options);
        }

        private function Read($path, $callback, $params)
        {
            $method = 'GET';
            $this->Call($path, $callback, $params);
        }

        private function Call($path, $callback, $params)
        {
            if(empty($this->token))
                return $callback('Marknife Invalid Token', null, null);
            if(empty($path))
                return $callback('Invalid Path', null, null);

            $headers = array();
            $headers['Authorization'] = sprintf('Authorization: %s %s', $this->authtype, $this->token);

            $curl = curl_init();
            $options = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_REFERER => 'Marknife PHP API Wrapper',
                CURLOPT_ENCODING => 'gzip, deflate'
                //CURLOPT_HTTPAUTH => CURLAUTH_BASIC
            );

            if(!empty($params) && $this->method != 'GET')
            {
                $headers['Content-Type'] = 'application/json';
                $payload = json_encode(array("params" => $params));
                $options["CURLOPT_POSTFIELDS"] = $payload;
            };

            $url = 'https://api.marknife.com/v' . $this->version . $path;
            switch ($this->method)
            {
                case "POST":
                    $options["CURLOPT_POST"] = 1;
                    break;
                case "PUT":
                    $options["CURLOPT_CUSTOMREQUEST"] = "PUT";
                    break;
                default:
                    if ($params)
                        $url = sprintf("%s?%s", $url, http_build_query($params));
            }

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt_array($curl, $options);

            $response   = curl_exec($curl);
            $err        = curl_errno($curl);
            curl_close($curl);
            if($err) return $callback($err, null, null);
            //$callback(null, json_decode($response, true), $params); print_r($data->{'company'});
            $callback(null, json_decode($response), $params);

        }

    }
