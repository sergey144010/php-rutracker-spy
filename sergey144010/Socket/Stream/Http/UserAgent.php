<?php

namespace sergey144010\Socket\Stream\Http;

class UserAgent
{
    protected $value;

    public function getUserAgent()
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

    public function firefox64()
    {
        $this->value = "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0";
        return $this;
    }
}