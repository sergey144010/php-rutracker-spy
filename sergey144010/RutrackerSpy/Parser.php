<?
namespace sergey144010\RutrackerSpy;

use Sunra\PhpSimple\HtmlDomParser;
use sergey144010\BaseFunctionExtension\ArrayExt;

class Parser
{
    protected static $extraction;

    public static function setExtraction($in_extraction)
    {
        self::$extraction = $in_extraction;
    }

    public static function is_extraction()
    {
        if(isset(self::$extraction)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $content
     * @return false|string
     */
    public static function getTitle($content)
    {
        $title = false;
        $fileName = $content;
        $dom = HtmlDomParser::str_get_html($fileName);
        $array = $dom->find('h1[class=maintitle]');
        foreach ($array as $titleCurrent) {
            $title = trim($titleCurrent->plaintext);
        };

        return $title;
    }

    public static function getPagination($content)
    {
        $count = false;
        $fileName = $content;
        #$fileName = "log/writeToFile.txt";
        $dom = HtmlDomParser::str_get_html($fileName);
        #$dom = HtmlDomParser::file_get_html( $fileName );
        $array = $dom->find('div[id=pagination] > p > b');
        foreach ($array as $b) {
            $count[] = trim($b->plaintext);
        };
        if($count){
            $count = $count[1];
        };

        return $count;
    }

    /*
     * @return array || false
     */
    public static function contentGetThemeArrayMaxSize($content)
    {
        $fileName = $content;
        #$fileName = "log/writeToFile.txt";
        $dom = HtmlDomParser::str_get_html($fileName);
        #$dom = HtmlDomParser::file_get_html( $fileName );
        #$topicLink = $dom->find('a[class=torTopic]');
        $themeListRaw = $dom->find('tr[class=hl-tr]');

        foreach ($themeListRaw as $theme) {

            $themeCurrent = HtmlDomParser::str_get_html($theme);

            //
            // Берем
            // ссылку на топик,
            // id топика
            // полное название топика
            //
            $themeCurrentElementA1 = $themeCurrent->find('a[class=torTopic]');

            foreach ($themeCurrentElementA1 as $elementA) {
                $themeCurrentHref = $elementA->getAttribute('href');
                $themeCurrentId = $elementA->getAttribute('id');
                $themeCurrentName = trim($elementA->plaintext);
            };

            //
            // Берем
            // размер торрента,
            // ссылку на торрент файл
            //
            $themeCurrentElementA2 = $themeCurrent->find('div[class=small] > a');
            foreach ($themeCurrentElementA2 as $elementA) {
                $themeCurrentSize = trim($elementA->plaintext);
                $themeCurrentTorrentFile = $elementA->getAttribute('href');
            };

            //
            // Берем
            // Автора топика
            // здесь и латинница и кириллица
            //
            $topicAuthorArray = $themeCurrent->find('div[class=topicAuthor] > a[class=topicAuthor]');
            foreach ($topicAuthorArray as $topicAuthor) {
                $themeCurrentTopicAuthor = trim($topicAuthor->plaintext);
            };

            //
            // Берем
            // количество сидов и личей
            // в темах объявлений их нет - NULL
            //
            $seedArray = $themeCurrent->find('div > span[class=seedmed]');
            foreach ($seedArray as $seed) {
                $themeCurrentSeed = trim($seed->plaintext);
            };
            $leechArray = $themeCurrent->find('div > span[class=leechmed]');
            foreach ($leechArray as $leech) {
                $themeCurrentLeech = trim($leech->plaintext);
            };

            if(
                // Параметры текущего топика
                isset($themeCurrentHref) &&
                isset($themeCurrentId) &&
                isset($themeCurrentName) &&
                isset($themeCurrentSize) &&
                isset($themeCurrentTorrentFile) &&
                isset($themeCurrentTopicAuthor) &&
                isset($themeCurrentSeed) &&
                isset($themeCurrentLeech)
            ){

                #########################################

                // Поиск одинаковых значений в базе данных идёт по колонке
                // 'nameRusHash' => $themeCurrentRusNameHash,
                // соответственно нужно разобрать $themeCurrentName
                // и выделить $themeCurrentRusName так чтобы,
                // можно было осуществить поиск по $themeCurrentRusName.
                // Соответственно здесь можно подключить из конфига
                // шаблон регулярного выражения для конкретного раздела.
                // Например есть тема
                // " (Pop / Hip Hop) [CD] Chagrin D'Amour - Chagrin D'Amour - 2001, FLAC (image+.cue), lossless "
                // Создать фильтр для темы и в нём задать шаблон, например такой, чтобы выделить название
                // "/\].*\(/i"

                if(self::is_extraction()){
                    if(is_callable(self::$extraction)){
                        #echo "is_callable";
                        $preg_match = self::$extraction;
                        $name = $preg_match($themeCurrentName);
                        if($name){
                            if(is_string($name)){
                                #echo "is_string";
                                $themeCurrentRusName = $name;
                            }else{
                                throw new \Exception ("ERROR: Parser.contentGetThemeArrayMaxSize().Extraction : Name is not a string");
                            };
                        }else{
                            $themeCurrentRusName = false;
                            continue;
                        };
                    }else{
                        throw new \Exception ("ERROR: Parser.contentGetThemeArrayMaxSize().Extraction : Extraction is not callable");
                    };
                }else{
                    // Если парсер названия не задан, то применяем стандартный
                    $themeCurrentNameArray = explode("/", $themeCurrentName, 2);
                    if(isset($themeCurrentNameArray[1])){
                        $themeCurrentRusName = trim($themeCurrentNameArray[0]);
                    }else{
                        $themeCurrentRusName = trim($themeCurrentNameArray[0]);
                    };

                };

                #########################################

                $themeCurrentRusNameHash = hash('md5', $themeCurrentRusName);

                /*
                 * У двух массивов ниже одинаковые ключи
                 */
                $themeArrayRusNameHash[] = $themeCurrentRusNameHash;
                $themeArray[] =
                    [
                        'id' => $themeCurrentId,
                        'name' => $themeCurrentName,
                        'nameRusHash' => $themeCurrentRusNameHash,
                        'size' => $themeCurrentSize,
                        'href' => $themeCurrentHref,
                        'torrentFile' => $themeCurrentTorrentFile,
                        'topicAuthor' => $themeCurrentTopicAuthor,
                        'seed' => $themeCurrentSeed,
                        'leech' => $themeCurrentLeech
                    ];
            };
        };

        if(isset($themeArray)){
            return $themeArray;
        };

        return false;

/*
        if(isset($themeArrayRusNameHash)){

            // Смотрим одинаковые темы
            $themeRepeat = ArrayExt::arrayValRepeat($themeArrayRusNameHash);
            if ($themeRepeat) {
                foreach ($themeRepeat as $themeRepeatName => $themeRepeatArrayKey) {
                    $sizeArray = false;
                    foreach ($themeRepeatArrayKey as $key) {
                        list($size, $dimension) = explode('&nbsp;', $themeArray[$key]['size']);
                        if ($dimension == 'GB') {
                            $size = $size * 1024;
                        };
                        $sizeArray[$key] = $size;
                    }
                    #print_r($sizeArray);
                    // Определяем у кого максимальный размер
                    $sizeMax = max($sizeArray);
                    $sizeMaxKey = array_search($sizeMax, $sizeArray);
                    $themeRepeatNameSizeMaxKeyPreparation[$themeRepeatName] = $sizeMaxKey;
                };
                // Меняем местами ключи и значения
                foreach ($themeRepeatNameSizeMaxKeyPreparation as $key => $val) {
                    $themeRepeatNameSizeMaxKey[$val] = $key;
                }
            };

            // Убираем одинаковые темы и оставляем темы с максимальным размером файла
            foreach ($themeArrayRusNameHash as $key => $val) {

                $repeat = false;
                $key_save = false;
                foreach ($themeRepeatNameSizeMaxKey as $key2 => $val2) {
                    if ($val == $val2) {
                        $repeat = true;
                        $key_save = $key2;
                    };
                };

                if ($repeat == true and $key_save == $key) {
                    $themeArrayRusNameHashUnique[$key] = $val;
                };

                if ($repeat == false) {
                    $themeArrayRusNameHashUnique[$key] = $val;
                };
            };

            foreach ($themeArrayRusNameHashUnique as $key => $val) {
                $themeArrayUniqueMax[$key] = $themeArray[$key];
            };
        };

        if(isset($themeArrayUniqueMax)){
            return $themeArrayUniqueMax;
        }else{
            return false;
        }

        */

    }

    public static function sizeMb($sizeInput)
    {
        // Если разделитель - "&nbsp;"
        $pos = false;
        $pos = stripos($sizeInput, "&nbsp;");
        if ($pos !== false) {
            list($size, $dimension) = explode('&nbsp;', $sizeInput);
            if ($dimension == 'GB') {
                $size = $size * 1024;
            };
            return $size;
        };

        // Если разделитель - " "
        $pos = false;
        $pos = stripos($sizeInput, " ");
        if ($pos !== false) {
            list($size, $dimension) = explode(' ', $sizeInput);
            if ($dimension == 'GB' || $dimension == 'gb' || $dimension == 'Gb' || $dimension == 'gB') {
                $size = $size * 1024;
            };
            return $size;
        };

        // Если нет разделителя, т.е. 1.2Gb или 5GB
        if(preg_match("/^\d+.?\d*gb$/i", $sizeInput, $matches)){
            $sizeGb = substr($matches[0], 0, -2);
            $sizeMb = $sizeGb * 1024;
            return $sizeMb;
        };

        // Предполагаем что исходный размер в мегабайтах
        return $sizeInput;
    }
}