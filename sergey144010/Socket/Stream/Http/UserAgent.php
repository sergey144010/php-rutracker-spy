<?php

namespace sergey144010\Socket\Stream\Http;

class UserAgent
{
    protected $value;

    public function getUserAgent()
    {
        $this->value = "User-Agent: " . $this->value . "\r\n";
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

    public function Mozilla_Firefox_44_x64()
    {
        $this->value = "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0";
        return $this->getUserAgent();
    }

    public function Mozilla_Firefox_36_x64()
    {
        $this->value = "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:36.0) Gecko/20100101 Firefox/36.0";
        return $this->getUserAgent();
    }

    public function Google_Chrome_40_x64()
    {
        $this->value = "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.115 Safari/537.36";
        return $this->getUserAgent();
    }

    public function Opera_12_x64()
    {
        $this->value = "Opera/9.80 (Windows NT 6.2; WOW64) Presto/2.12.388 Version/12.17";
        return $this->getUserAgent();
    }

    public function Apple_Safari_5_x64()
    {
        $this->value = "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2";
        return $this->getUserAgent();
    }

    public function Internet_Explorer_11_x64()
    {
        $this->value = "Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; ASU2JS; rv:11.0) like Gecko";
        return $this->getUserAgent();
    }

}