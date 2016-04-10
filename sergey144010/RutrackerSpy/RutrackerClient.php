<?php
namespace sergey144010\RutrackerSpy;

use sergey144010\Socket\Stream\Http\HttpClient;
use Sunra\PhpSimple\HtmlDomParser;
use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Logger as Log;

class RutrackerClient extends HttpClient
{
    public $bbData;
    public $token;

    public $cookieFileSave;
    public $bbDataFileSave;

    public $torrentDir;
    public $torrentId;
    public $torrentFileStatus;

    public function __construct()
    {
        $this->cookieFileSave  = Config::$cookieDir.DIRECTORY_SEPARATOR.Config::$cookieFileName;
        $this->bbDataFileSave  = Config::$cookieDir.DIRECTORY_SEPARATOR.Config::$cookieFileBbData;
        $this->torrentDir  = Config::$torrentDir;
    }

    /*
     * Запись в файл
     *
     * @return $this
     */
    public function writeToFile($data ,$fileWrite = "log/writeToFile.txt", $mode="w")
    {

        $handle = fopen($fileWrite, $mode);
        fwrite($handle, $data);
        fclose($handle);
        return $this;
    }

    public function getCookie()
    {
        parent::getCookie();
        $this->getBbData();
        if($this->bbData){
            Log::add("Cookie get successful");
        }else{
            Log::add("Cookie not get");
        };
        return $this;
    }

    /*
     * @set $this->bbData
     *
     * @return $this
     */
    public function getBbData()
    {
        if($this->cookie){
            $cookieRaw = $this->cookie;
            list($cookiePart1, $cookiePart2, $cookiePart3, $cookiePart4, $cookiePart5) = explode(";", $cookieRaw);
            list($cookieBbDataName, $cookieBbDataVal) = explode("=", $cookiePart1);
            $cookieBbDataVal = trim($cookieBbDataVal);
            $this->bbData = $cookieBbDataVal;
        };
        return $this;
    }

    /*
     * Проверка: есть ли файл с куками и он не пустой
     *
     * @return true || false
     */
    public function isCookie()
    {
        if(is_file($this->bbDataFileSave)){
            $bbData = file_get_contents($this->bbDataFileSave);
            $bbData = trim($bbData);
            if(isset($bbData) && strlen($bbData)!==0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /*
     * Проверка: куки из файла еще действуют или нет
     *
     * if true :
     * @set $this->bbData
     * @set $this->token
     *
     * @return true || false
     */
    public function isCookieValid()
    {
        $this->getCookieFromFile();
        $this->indexOpenLogin();
        $this->getContent()->writeToFile($this->content, Config::$logDir.DIRECTORY_SEPARATOR."isCookieValid.txt");
        $this->getToken();
        if(isset($this->token)){
            if(strlen($this->token) > 5){
                $this->getHeader();
                $this->getCookie();
                $this->getBbData();
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /*
     * Берем куки из файла
     *
     * @set $this->bbData;
     * @return $this;
     */
    public function getCookieFromFile()
    {
        if(is_file($this->bbDataFileSave)){
            $bbData = file_get_contents($this->bbDataFileSave);
            $bbData = trim($bbData);
            if(isset($bbData)){
                $this->bbData = $bbData;
            }else{
                #throw new \ErrorException("bb_data is null");
                Log::add("bb_data is null");
            };
        }else{
            #throw new \ErrorException("file cookie not found");
            Log::add("File cookie not found");
        };
        return $this;
    }

    /*
     * Сохраняем куки в файл
     *
     * @return $this;
     */
    public function saveCookie()
    {
        file_put_contents($this->cookieFileSave, $this->cookie);
        file_put_contents($this->bbDataFileSave, $this->bbData);
        return $this;
    }

   /*
    * Берем токен из контента
    *
    * @set $this->token;
    * @return $this;
    */
    public function getToken()
    {
        if($this->content){
            $html = HtmlDomParser::str_get_html($this->content);
            $scriptList = $html->find('head > script');
            $srcArr = false;
            foreach ($scriptList as $key => $script) {
                if ($scriptSrc = $script->innertext) {

                    $scriptSrc = trim($scriptSrc);
                    $explode= explode(";", $scriptSrc);
                    if(isset($explode[1])){
                        $explode[1] = trim($explode[1]);
                        if(preg_match("/^window.BB/", $explode[1])){
                            preg_match("/\{.*\}/m", $explode[1], $matches);
                            $matches[0] = substr($matches[0], 1, -1);
                            $matches[0] = trim($matches[0]);
                            preg_match_all("/[^:]*:[^:]*,/m", $matches[0], $out);
                            $out[0][1] = trim($out[0][1]);
                            preg_match("/'.*'/", $out[0][1], $token);
                            $token = substr($token[0], 1, -1);
                            $this->token = $token;
                        };
                    };

                };
            };
        };
        if($this->token){
            Log::add("Token get successful");
        }else{
            Log::add("Token not get");
            $this->writeToFile($this->content, Config::$logDir.DIRECTORY_SEPARATOR."tokenNotGet.txt");
        };
        return $this;
    }


    ###########################################################

    public function indexOpen()
    {
        $this->open("http://rutracker.org/forum/index.php");
        $this->http->method->get();
        $this->http->header->userAgent("Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0");
        $this->http->header->accept("text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8");
        $this->http->header->acceptLanguage("ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3");
        $this->http->header->connection("keep-alive");
        $this->send();
        return $this;
    }

    public function indexOpenLogin()
    {
        $this->open("http://rutracker.org/forum/index.php");
        $this->http->method->get();
        $this->http->header->userAgent("Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0");
        $this->http->header->accept("text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8");
        $this->http->header->acceptLanguage("ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3");
        $this->http->header->referer("http://login.rutracker.org/forum/login.php");
        $this->http->header->cookie("bb_data=" . $this->bbData);
        $this->http->header->connection("keep-alive");
        $this->send();
        return $this;
    }

    public function urlOpenLogin($url)
    {
        $this->open($url);
        $this->http->method->get();
        $this->http->header->userAgent("Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0");
        $this->http->header->accept("text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8");
        $this->http->header->acceptLanguage("ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3");
        $this->http->header->referer("http://login.rutracker.org/forum/login.php");
        $this->http->header->cookie("bb_data=" . $this->bbData);
        $this->http->header->connection("keep-alive");
        $this->send();
        return $this;
    }

    public function login()
    {
        $this->open("http://login.rutracker.org/forum/login.php");
        $this->http->method->post(
            [
                'redirect' => 'index.php',
                'login_username' => Config::$clientUser,
                'login_password' => Config::$clientPass,
                'login' => '%C2%F5%EE%E4'
            ]
        );
        $this->http->header->userAgent("Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0");
        $this->http->header->accept("text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8");
        $this->http->header->acceptLanguage("ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3");
        $this->http->header->referer("http://login.rutracker.org/forum/login.php");
        #$this->http->header->referer("http://rutracker.org/forum/index.php");
        $this->http->header->connection("keep-alive");
        $this->http->header->contentType("application/x-www-form-urlencoded");
        $this->send();
        return $this;
    }

    public function logout()
    {
        $this->open("http://login.rutracker.org/forum/login.php");
        $this->http->method->post(
            [
                'form_token' => $this->token,
                'logout' => '1'
            ]
        );
        $this->http->header->userAgent("Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0");
        $this->http->header->accept("text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8");
        $this->http->header->acceptLanguage("ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3");
        $this->http->header->referer("http://rutracker.org/forum/index.php");
        $this->http->header->cookie("bb_data=" . $this->bbData);
        $this->http->header->connection("keep-alive");
        $this->http->header->contentType("application/x-www-form-urlencoded");
        $this->send();
        return $this;
    }

    public function downloadTorrentFile($torrentFile)
    {
        /**
         * Изменение разметки на Rutracker, замечено 05.04.2016
         */
        $torrentFile = "http://rutracker.org/forum/".$torrentFile;

        list($link, $torrentId) = explode("?", $torrentFile);

        Log::add("Download torrent - ".$torrentId);

        $this->torrentId = $torrentId;
        $this->open($torrentFile);
        $this->http->method->post(
            [
                'form_token' => $this->token
            ]
        );
        $this->http->header->userAgent("Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0");
        $this->http->header->accept("text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8");
        $this->http->header->acceptLanguage("ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3");
        $this->http->header->referer($this->target);
        $this->http->header->cookie("bb_data=" . $this->bbData);
        $this->http->header->connection("keep-alive");
        $this->http->header->contentType("application/x-www-form-urlencoded");

        try{
            $this->send();
        }catch (\Exception $error){
            Log::add("ERROR: (RutrackerClient.downloadTorrentFile.send()) : Download failed ");
            Log::add($torrentFile);
            Log::add($this->target);
        };

        try{
            $this->getContent();
        }catch (\Exception $error){
            Log::add("ERROR: (RutrackerClient.downloadTorrentFile.getContent()) : Download failed ");
        }

        try{
            $handle = fopen(Config::$torrentDirTemp.DIRECTORY_SEPARATOR.$torrentId.".torrent","w");
            fwrite($handle, $this->content);
            fclose($handle);
        }catch (\Exception $error){
            Log::add("ERROR: (RutrackerClient.downloadTorrentFile.fwrite()) : Download failed ");
        }

        return $this;
    }

    public function checkTorrentFile()
    {
        $torrentFile = Config::$torrentDirTemp.DIRECTORY_SEPARATOR.$this->torrentId.".torrent";
        $torrentId = $this->torrentId;
        #$torrentFile = "torrent/t=5150809.torrent";
        #$torrentId = "t=5150809";

        if(is_file($torrentFile)){
            $content = file_get_contents($torrentFile);
            if(isset($content) && strlen($content)!==0){

                $torrentIdStrlen = strlen($torrentId);
                $contentStrlen = strlen($content);
                #$positionStart = $contentStrlen-($torrentIdStrlen+1);
                $positionStart = $contentStrlen-300;
                #$checkId = substr($content, $positionStart, $torrentIdStrlen);
                $contentPart = substr($content, $positionStart);
                #var_dump($contentPart);
                $pos = stripos($contentPart, $torrentId);
                if ($pos !== false) {
                    $this->torrentFileStatus = true;
                    return true;
                }else{
                    Log::add("ERROR: (RutrackerClient.checkTorrentFile()) : fileTorrent is not right");
                    $this->torrentFileStatus = false;
                    return false;
                }

                #var_dump(substr($content, $positionStart));
                #var_dump($checkId);
                #var_dump($torrentId);
/*
                if($torrentId === $checkId){
                    return true;
                }else{
                    echo "ERROR: fileTorrent is not right".PHP_EOL;
                    return false;
                }
                */
            }else{
                Log::add("ERROR: (RutrackerClient.checkTorrentFile()) : fileTorrent empty");
                $this->torrentFileStatus = false;
                return false;
            }
        }else{
            Log::add("ERROR: (RutrackerClient.checkTorrentFile()) : fileTorrent not found");
            $this->torrentFileStatus = false;
            return false;
        }
    }

    public function moveTorrentFile()
    {
        $torrentFileOld = Config::$torrentDirTemp.DIRECTORY_SEPARATOR.$this->torrentId.".torrent";
        $torrentFileNew = Config::$torrentDir.DIRECTORY_SEPARATOR.$this->torrentId.".torrent";
        rename($torrentFileOld, $torrentFileNew);
        return $this;
    }

    public function getTorrentFile($torrentFile)
    {
        // Проверяем, что установил пользователь
        $attempt = trim(Config::$torrentAttempt);
        if($attempt){
            if(preg_match("/^\d+$/", $attempt)){
                $countAttempt = $attempt;
            }elseif($attempt == "forever"){
                $countAttempt = 0;
            }else{
                $countAttempt = 10;
            };

            $i = 1;
            do {
                $this->downloadTorrentFile($torrentFile);
                if($i == $countAttempt){$i = 1; break;};
                $i++;
            }while(!$this->checkTorrentFile());

            if($this->torrentFileStatus){
                $this->moveTorrentFile();
            };
        };

        return $this;
    }

}
