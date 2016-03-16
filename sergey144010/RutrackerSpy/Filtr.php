<?php
namespace sergey144010\RutrackerSpy;

use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Parser;
use sergey144010\RutrackerSpy\Logger as Log;

class Filtr extends MainFiltr
{

    public function genre()
    {
        if(!empty(Config::$filtrSetting["genre"])){

            // ������ ������ ...
            if(!empty(Config::$filtrSetting["genre"]["only"])){
                // ���� ����� ���� - ������
                if($this->check($this->theme['name'], Config::$filtrSetting["genre"]["only"])){
                    return true;
                };
                #Log::add("Filter GENRE failed ".$this->theme['name']);
                Log::add("Filter GENRE failed");
                return false;
            };

            // �� ����� ...
            if(!empty(Config::$filtrSetting["genre"]["exept"])){
                // ���� ����� ���� - �� ������
                if(!$this->check($this->theme['name'], Config::$filtrSetting["genre"]["exept"])){
                    return true;
                };
                #Log::add("Filter GENRE failed ".$this->theme['name']);
                Log::add("Filter GENRE failed");
                return false;
            };

        };
        // ���� ���� �� ���������� ������� true
        return true;
    }

    public function quality()
    {
        if(!empty(Config::$filtrSetting["quality"])){

            // ������ ������ ...
            if(!empty(Config::$filtrSetting["quality"]["only"])){
                // ���� ����� ... - ������
                if($this->check($this->theme['name'], Config::$filtrSetting["quality"]["only"])){
                    return true;
                };
                #Log::add("Filter QUALITY failed ".$this->theme['name']);
                Log::add("Filter QUALITY failed");
                return false;
            };

            // �� ����� ...
            if(!empty(Config::$filtrSetting["quality"]["exept"])){
                // ���� ����� CAMRip - �� ���������
                if(!$this->check($this->theme['name'], Config::$filtrSetting["quality"]["exept"])){
                    return true;
                };
                #Log::add("Filter QUALITY failed ".$this->theme['name']);
                Log::add("Filter QUALITY failed");
                return false;
            };

        };
        return true;
    }

    public function size()
    {
        if(!empty(Config::$filtrSetting["size"])){

            if(!empty(Config::$filtrSetting["size"]["min"]) && !empty(Config::$filtrSetting["size"]["max"])){

                $size = Parser::sizeMb($this->theme['size']);
                $min = Parser::sizeMb(Config::$filtrSetting["size"]["min"]);
                $max = Parser::sizeMb(Config::$filtrSetting["size"]["max"]);

                // ���� ������ �������� �� 1Gb �� 2Gb - ������
                if(
                    $min <= $size &&
                    $max >= $size
                ){
                    return true;
                }
                #Log::add("Filter SIZE failed ".$this->theme['size']." - ".$size." - ".$min." - ".$max);
                Log::add("Filter SIZE failed");
                return false;

            };

            if(!empty(Config::$filtrSetting["size"]["min"]) && empty(Config::$filtrSetting["size"]["max"])){

                $size = Parser::sizeMb($this->theme['size']);
                $min = Parser::sizeMb(Config::$filtrSetting["size"]["min"]);

                // ���� ������ �������� �� 1Gb - ������
                if(
                    $min <= $size
                ){
                    return true;
                }
                #Log::add("Filter SIZE failed ".$this->theme['size']);
                Log::add("Filter SIZE failed");
                return false;

            };

            if(empty(Config::$filtrSetting["size"]["min"]) && !empty(Config::$filtrSetting["size"]["max"])){

                $size = Parser::sizeMb($this->theme['size']);
                $max = Parser::sizeMb(Config::$filtrSetting["size"]["max"]);

                // ���� ������ �������� �� 2Gb - ������
                if(
                    $max >= $size
                ){
                    return true;
                }
                #Log::add("Filter SIZE failed ".$this->theme['size']);
                Log::add("Filter SIZE failed");
                return false;

            };

        };
        return true;
    }

    public function stopWord()
    {
        if(!empty(Config::$filtrSetting["stopWord"])){

            $array = Config::$filtrSetting["stopWord"];

            // ���� ����� �������� ����� - �� ���������
            if(!$this->check($this->theme['name'], $array)){
                return true;
            };
            #Log::add("Filter STOPWORD failed ".$this->theme['name']);
            Log::add("Filter STOPWORD failed");
            return false;

        };
        return true;
    }

    public function seed()
    {
        if(!empty(Config::$filtrSetting["seed"])){

            if(!empty(Config::$filtrSetting["seed"]["min"])){
                // ���� ����� ������ ��� ... - �� ���������
                if(Config::$filtrSetting["seed"]["min"] < $this->theme['seed']){
                    return true;
                };
                #Log::add("Filter SEED failed ".$this->theme['seed']);
                Log::add("Filter SEED failed");
                return false;
            };

        };
        return true;
    }

    public function travelCard()
    {
        if(!empty(Config::$filtrSetting["travelCard"])){
            // ���� ����� ���� ����� - ������� � ����� ������
            if($this->check($this->theme['name'], Config::$filtrSetting["travelCard"])){
                return true;
            };
            #Log::add("Filter TRAVELCARD failed".$this->theme['name']);
            return false;
        }
        return false;
    }

    // ����� �������� ������ �� ������ ������

    /*
     * �������������� ����� �������� checkAllRule()
     *
     * ���� ��� ������� ���������� true, �� �������� ��������
     *
     */
    public function checkAllRule()
    {
        if($this->travelCard()){
            return true;
        };

        if(
            $this->quality() &&
            $this->genre() &&
            $this->size() &&
            $this->stopWord() &&
            $this->seed()
        ){
            return true;
        }else{
            return false;
        }
    }

}