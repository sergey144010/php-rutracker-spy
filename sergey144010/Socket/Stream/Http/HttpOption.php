<?php

namespace sergey144010\Socket\Stream\Http {

    use sergey144010\Socket\Stream\Http\Method;
    use sergey144010\Socket\Stream\Http\Header;
    use sergey144010\Socket\Stream\Http\UserAgent;

    class HttpOption
    {
        /*
         * Опции контекста HTTP
         *
         * http://php.net/manual/ru/context.http.php
         *
         */

        public $method;
        public $header;
        public $userAgent;
        public $content;
        public $proxy;
        public $requestFullUri;
        public $followLocation;
        public $maxRedirects;
        public $protocolVersion;
        public $timeout;
        public $ignoreErrors;

        public $request;

        public function __construct()
        {
            $this->method = new Method();
            $this->header = new Header();
            $this->userAgent = new UserAgent();
        }

        public function setRequest($request)
        {
            $this->request = $request;
            return $this;
        }
        public function getRequest()
        {
            $this->make();
            return $this->request;
        }
        public function getOption()
        {
            return $this->getRequest();
        }

/*
        public function method($value)
        {
            $this->method = $value;
            return $this;
        }

        public function header($value)
        {
            $this->header = $value;
            return $this;
        }

        */
        public function userAgent($value)
        {
            $this->userAgent = $value;
            return $this;
        }

        public function content($value)
        {
            $this->content = $value;
            return $this;
        }

        public function proxy($value)
        {
            $this->proxy = $value;
            return $this;
        }

        public function requestFullUri($value)
        {
            $this->requestFullUri = $value;
            return $this;
        }

        public function followLocation($value)
        {
            $this->followLocation = $value;
            return $this;
        }

        public function maxRedirects($value)
        {
            $this->maxRedirects = $value;
            return $this;
        }

        public function protocolVersion($value)
        {
            $this->protocolVersion = $value;
            return $this;
        }

        public function timeout($value)
        {
            $this->timeout = $value;
            return $this;
        }

        public function ignoreErrors($value)
        {
            $this->ignoreErrors = $value;
            return $this;
        }

        public function make()
        {
            if($this->method->isExist()){
                $this->request['method'] = $this->method->getMethod();
            };
            if($this->header->isExist()){
                $this->request['header'] = $this->header->getHeader();
            };

            if(is_object($this->userAgent) && $this->userAgent->isExist()){
                $this->request['user_agent'] = $this->userAgent->getUserAgent();
            }elseif(is_string($this->userAgent)){
                $this->request['user_agent'] = $this->userAgent;
            };

            if($this->content){
                $this->request['content'] = $this->content;
            };
            if($this->proxy){
                $request['proxy'] = $this->proxy;
            };
            if($this->requestFullUri){
                $this->request['request_fulluri'] = $this->requestFullUri;
            };
            if($this->followLocation){
                $this->request['follow_location'] = $this->followLocation;
            };
            if($this->maxRedirects){
                $this->request['max_redirects'] = $this->maxRedirects;
            };
            if($this->protocolVersion){
                $this->request['protocol_version '] = $this->protocolVersion;
            };
            if($this->timeout){
                $this->request['timeout'] = $this->timeout;
            };
            if($this->ignoreErrors){
                $this->request['ignore_errors'] = $this->ignoreErrors;
            };
        }
    }
}