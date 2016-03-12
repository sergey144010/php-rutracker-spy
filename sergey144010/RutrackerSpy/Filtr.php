<?php
namespace sergey144010\RutrackerSpy;

use sergey144010\RutrackerSpy\Configuration as Config;

class Filtr
{
    public $string;
    public $rule;

    public function rule()
    {
        $this->rule = ["комедия", "семейный", "приключения"];
        return $this;
    }

    public function run($string)
    {
        if(Config::$filtrTurn == "on"){
            $this->string = $string;
            $this->rule();
            if($this->check()){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
        #return true;
    }

    public function check()
    {
        $find = false;
        foreach ($this->rule as $rule) {
            $pos = stripos($this->string, $rule);
            if ($pos !== false) {
                $find = true;
            };
        };
        if($find){
            return true;
        }else{
            return false;
        }
    }
}