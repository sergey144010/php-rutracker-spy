<?php

namespace sergey144010\Socket\Stream\Http {

    use sergey144010\Socket\Stream\Http\UserAgent;

    class Header
    {
        protected $value;

        /**
         * @var \sergey144010\Socket\Stream\Http\UserAgent
         */
        public $userAgent;

        public function __construct()
        {
            $this->userAgent = new UserAgent();
        }

        public function host($value)
        {
            $this->value .= "Host: " . $value . "\r\n";
            return $this;
        }

        public function userAgent($value)
        {
            $this->value .= "User-Agent: " . $value . "\r\n";
            return $this;
        }

        public function accept($value)
        {
            $this->value .= "Accept: " . $value . "\r\n";
            return $this;
        }

        public function acceptLanguage($value)
        {
            $this->value .= "Accept-Language: " . $value . "\r\n";
            return $this;
        }

        public function acceptEncoding($value)
        {
            $this->value .= "Accept-Encoding: " . $value . "\r\n";
            return $this;
        }

        public function referer($value)
        {
            $this->value .= "Referer: " . $value . "\r\n";
            return $this;
        }

        public function cookie($value)
        {
            $this->value .= "Cookie: " . $value . "\r\n";
            return $this;
        }

        public function connection($value)
        {
            $this->value .= "Connection: " . $value . "\r\n";
            return $this;
        }

        public function contentType($value)
        {
            $this->value .= "Content-Type: " . $value . "\r\n";
            return $this;
        }

        public function contentLength($data)
        {
            $count = strlen($data);
            $this->value .= "Content-Length: " . $count . "\r\n";
            return $this;
        }

        public function getHeader()
        {
            return $this->value;
        }

        public function isExist()
        {
            if(isset($this->value)){
                return true;
            }else{
                return false;
            }
        }

    }
}