<?php

namespace sergey144010\RutrackerSpy;

use sergey144010\RutrackerSpy\Configuration as Config;


class MainFiltr implements FiltrInterface
{
    public $theme;

    public function run(array $theme)
    {
        if(Config::$filtrTurn == "on"){

            $this->theme = $theme;

            if($this->checkAllRule()){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    /*
     * »щет совпадени€ в строке
     *
     * @return false || true
     */
    public function check($string, array $array)
    {
        $find = false;
        foreach ($array as $rule) {
            $pos = stripos($string, $rule);
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

    /*
     * ѕереопредел€ть этот метод
     * в наследуемых классах
     *
     */
    public function checkAllRule()
    {
        return true;
    }

    /*

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

        */
    /*
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

       */

}