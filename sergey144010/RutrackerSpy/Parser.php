<?
namespace sergey144010\RutrackerSpy;

use Sunra\PhpSimple\HtmlDomParser;
use sergey144010\BaseFunctionExtension\ArrayExt;

class Parser
{
    public static function contentGetThemeArrayMaxSize($content)
    {
        $fileName = $content;
        #$fileName = "log/log.txt";
        $dom = HtmlDomParser::str_get_html($fileName);
        #$dom = HtmlDomParser::file_get_html( $fileName );
        #$topicLink = $dom->find('a[class=torTopic]');
        $themeListRaw = $dom->find('tr[class=hl-tr]');

        foreach ($themeListRaw as $theme) {

            $themeCurrent = HtmlDomParser::str_get_html($theme);

            // Первый разбор
            $themeCurrentElementA1 = $themeCurrent->find('a[class=torTopic]');

            foreach ($themeCurrentElementA1 as $elementA) {
                $themeCurrentHref = $elementA->getAttribute('href');
                $themeCurrentId = $elementA->getAttribute('id');
                $themeCurrentName = trim($elementA->plaintext);
            };

            // Второй разбор
            $themeCurrentElementA2 = $themeCurrent->find('div[class=small] > a');
            foreach ($themeCurrentElementA2 as $elementA) {
                $themeCurrentSize = trim($elementA->plaintext);
                $themeCurrentTorrentFile = $elementA->getAttribute('href');
            };

            if(
                isset($themeCurrentHref) &&
                isset($themeCurrentId) &&
                isset($themeCurrentName) &&
                isset($themeCurrentSize) &&
                isset($themeCurrentTorrentFile)
            ){
                list($themeCurrentRusName, $topicOtherPart) = explode("/", $themeCurrentName);
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
                        'torrentFile' => $themeCurrentTorrentFile
                    ];
            };
        };

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
    }

    public static function sizeMb($sizeInput)
    {
        list($size, $dimension) = explode('&nbsp;', $sizeInput);
        if ($dimension == 'GB') {
            $size = $size * 1024;
        };
        return $size;
    }
}