<?php

namespace sergey144010\Socket\Stream\Http {

    class Method
    {
        protected $value;
        protected $data;

        public function options()
        {
            $this->value = "OPTIONS";
            return $this;
        }

        public function get($data = false)
        {
            $this->value = "GET";
            if ($data) {
                if (is_array($data)) {
                    $query = false;
                    foreach ($data as $param => $value) {
                        if (!$query) {
                            $query .= $param . "=" . $value;
                        } else {
                            $query .= "&" . $param . "=" . $value;
                        };
                    };
                    $this->data = $query;
                } else {
                    $this->data = $data;
                };
            };
            return $this;
        }

        public function head()
        {
            $this->value = "HEAD";
            return $this;
        }

        public function post($data = false)
        {
            $this->value = "POST";
            if ($data) {
                if (is_array($data)) {
                    $query = false;
                    foreach ($data as $param => $value) {
                        if (!$query) {
                            $query .= $param . "=" . $value;
                        } else {
                            $query .= "&" . $param . "=" . $value;
                        };
                    };
                    $this->data = $query;
                } else {
                    $this->data = $data;
                };
            };
            return $this;
        }

        public function put()
        {
            $this->value = "PUT";
            return $this;
        }

        public function delete()
        {
            $this->value = "DELETE";
            return $this;
        }

        public function trace()
        {
            $this->value = "TRACE";
            return $this;
        }

        public function connect()
        {
            $this->value = "CONNECT";
            return $this;
        }

        public function getMethod()
        {
            return $this->value;
        }

        public function getData()
        {
            return $this->data;
        }

        public function isExist()
        {
            if(isset($this->value)){
                return true;
            }else{
                return false;
            }
        }

        public function isDataExist()
        {
            if(isset($this->data)){
                return true;
            }else{
                return false;
            }
        }
    }
}