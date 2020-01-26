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

        function __construct($user, $key, $method = 'GET', $version = 1, $authtype='Bearer')
        {
            $this->token    = $user;
            $this->user     = $user;
            $this->key      = $key;
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
        public function Me($callback, $options = null)
        {
            $this->Read('/me', $callback, $options);
        }
        public function Contacts($callback, $options = null)
        {
            $this->Read('/contacts', $callback, $options);
        }
        public function Messages($callback, $options = null)
        {
            $this->Read('/messages', $callback, $options);
        }
        public function Forms($callback, $options = null)
        {
            $this->Read('/forms', $callback, $options);
        }
        public function Books($callback, $options = null)
        {
            $this->Read('/forms', $callback, $options);
        }
        public function Qrs($callback, $options = null)
        {
            $this->Read('/qrs', $callback, $options);
        }
        public function Events($callback, $options = null)
        {
            $this->Read('/events', $callback, $options);
        }
        public function Resources($callback, $options = null)
        {
            $this->Read('/resources', $callback, $options);
        }


        private function Read($path, $callback, $params)
        {
            $method = 'GET';
            $this->Call($path, $callback, $params);
        }
        private function Save($path, $callback, $params)
        {
            $method = 'POST';
            $this->Call($path, $callback, $params);
        }
        private function Delete($path, $callback, $params)
        {
            $method = 'DELETE';
            $this->Call($path, $callback, $params);
        }
        private function Update($path, $callback, $params)
        {
            $method = 'PUT';
            $this->Call($path, $callback, $params);
        }
        private function Patch($path, $callback, $params)
        {
            $method = 'PATCH';
            $this->Call($path, $callback, $params);
        }
        private function Purge($path, $callback, $params)
        {
            $method = 'PURGE';
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
