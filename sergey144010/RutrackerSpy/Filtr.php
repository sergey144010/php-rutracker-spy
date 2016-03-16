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

            // Качать только ...
            if(!empty(Config::$filtrSetting["genre"]["only"])){
                // Если нашли жанр - качаем
                if($this->check($this->theme['name'], Config::$filtrSetting["genre"]["only"])){
                    return true;
                };
                #Log::add("Filter GENRE failed ".$this->theme['name']);
                Log::add("Filter GENRE failed");
                return false;
            };

            // Всё кроме ...
            if(!empty(Config::$filtrSetting["genre"]["exept"])){
                // Если нашли жанр - не качать
                if(!$this->check($this->theme['name'], Config::$filtrSetting["genre"]["exept"])){
                    return true;
                };
                #Log::add("Filter GENRE failed ".$this->theme['name']);
                Log::add("Filter GENRE failed");
                return false;
            };

        };
        // Если жанр не установлен вернуть true
        return true;
    }

    public function quality()
    {
        if(!empty(Config::$filtrSetting["quality"])){

            // Качать только ...
            if(!empty(Config::$filtrSetting["quality"]["only"])){
                // Если нашли ... - качаем
                if($this->check($this->theme['name'], Config::$filtrSetting["quality"]["only"])){
                    return true;
                };
                #Log::add("Filter QUALITY failed ".$this->theme['name']);
                Log::add("Filter QUALITY failed");
                return false;
            };

            // Всё кроме ...
            if(!empty(Config::$filtrSetting["quality"]["exept"])){
                // Если нашли CAMRip - не скачивать
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

                // Если размер торрента от 1Gb до 2Gb - качаем
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

                // Если размер торрента от 1Gb - качаем
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

                // Если размер торрента до 2Gb - качаем
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

            // Если нашли стоповое слово - не скачивать
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
                // Если сидов меньше чем ... - не скачивать
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
            // Если нашли спец слово - скачать в любом случае
            if($this->check($this->theme['name'], Config::$filtrSetting["travelCard"])){
                return true;
            };
            #Log::add("Filter TRAVELCARD failed".$this->theme['name']);
            return false;
        }
        return false;
    }

    // Можно добавить фильтр по автору топика

    /*
     * Переопределяем метод родителя checkAllRule()
     *
     * Если все правила возвращают true, то проверка пройдена
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