<?php
namespace sergey144010\RutrackerSpy {

    use sergey144010\RutrackerSpy\RutrackerClient;
    use sergey144010\RutrackerSpy\Parser;
    use sergey144010\RutrackerSpy\DbInterface;
    use sergey144010\RutrackerSpy\DbYii;
    use sergey144010\RutrackerSpy\Filtr;
    use sergey144010\RutrackerSpy\Configuration as Config;
    use sergey144010\RutrackerSpy\Logger as Log;

    class Main
    {
        /**
         * @var RutrackerClient $rutrackerClient
         */
        public $rutrackerClient;

        /**
         * @var DbYii $db
         */
        public $db;
        /**
         * @var Filtr $filtr
         */
        public $filtr;

        public $countPage;
        public $stopParsing;
        public $fileThemeArray;
        public $themeRaw;

        public function __construct()
        {
            // ������������� �������
            #new Config();

            $this->rutrackerClient = new RutrackerClient();
            $this->db = $this->dbObject(Config::$dbClass);
            $this->filtr = $this->filtrObject(Config::$filtrClass);;
        }

        public function dbObject($class)
        {
            return $this->dbClass(new $class);
        }

        public function dbClass(DbInterface $class)
        {
            return $class;
        }

        public function filtrObject($class)
        {
            return $this->filtrClass(new $class);
        }

        public function filtrClass(FiltrInterface $class)
        {
            return $class;
        }

        public function run()
        {
            // ��������� ����������� � ���� ������
            if($this->db->isConnect()){
                // ���� cookie ����, ��
                if ($this->rutrackerClient->isCookie() && $this->rutrackerClient->isCookieValid()) {
                    Log::add("Cookie is valid");
                    $this->getThemeFiltr();
                    $this->mainProcess();
                } else {
                    // ���� cookie ���, �� ���������
                    Log::add("Cookie is not valid");
                    Log::add("Run login");
                    $this->rutrackerClient->login();
                    // ���� ���������� �� �����������, ����� � ���
                    if(!$this->rutrackerClient->response){
                        Log::add("ERROR: Failed to open stream: HTTP request failed !");
                    }else{
                        $this->rutrackerClient->getHeader();
                        $this->rutrackerClient->getCookie();
                        $this->rutrackerClient->getBbData();
                        $this->rutrackerClient->saveCookie();
                        $this->rutrackerClient->indexOpenLogin();
                        $this->rutrackerClient->getContent();
                        $this->rutrackerClient->getToken();
                        if($this->rutrackerClient->token){
                            $this->getThemeFiltr();
                            $this->mainProcess();
                        }else{
                            Log::add("ERROR: Login is failed");
                        };
                    };
                };
            };
        }

        public function getThemeFiltr()
        {
            $fileThemeArray = false;
            // ������������� ����
            if(Config::$filtrManyFiltr == "on"){
                $dir = scandir(Config::$themeSpyDir);
                foreach ($dir as $file) {
                    if($file == "." || $file == ".."){continue;};
                    if(preg_match("/.filtr.php$/",$file)){

                        $file = Config::$themeSpyDir.DIRECTORY_SEPARATOR.$file;
                        $filtr = false;
                        require_once($file);
                        $fileThemeArray[] = $filtr;

                    };

                };
                if(!isset($fileThemeArray)){
                    $fileThemeArray = file(Config::$themeSpyDir.DIRECTORY_SEPARATOR.Config::$themeSpyFileName);
                };
                Log::add("File with themeSpy open");
            }else{
                // ��������� ���� ������������� ���
                $fileThemeArray = file(Config::$themeSpyDir.DIRECTORY_SEPARATOR.Config::$themeSpyFileName);
                Log::add("File with themeSpy open");
            };

            $this->fileThemeArray = $fileThemeArray;
        }

        public function mainProcess()
        {

            foreach ($this->fileThemeArray as $themeSpy) {

                if(is_string($themeSpy)){
                    $themeSpy = trim($themeSpy);
                };

                if(is_array($themeSpy)){
                    // ��������� ��������� �������
                    $this->themeRaw = $themeSpy;
                    // ������������� url
                    $themeSpy = trim($this->themeRaw["url"]);
                    // ����� ��������� �� ������� ���� ����
                    // � ������������� � � �������
                    if(isset($this->themeRaw["extraction"])){
                        Parser::setExtraction($this->themeRaw["extraction"]);
                    };
                };

                Log::add("Current theme - ".$themeSpy);
                #var_dump($themeRaw["setting"]);
                #continue;

                // ��������� ������
                $this->rutrackerClient->urlOpenLogin($themeSpy);
                $this->rutrackerClient->getContent();
                $this->rutrackerClient->writeToFile($this->rutrackerClient->content);
                // ������ ������
                #Log::add("Parser::contentGetThemeArrayMaxSize - start");
                try{
                    $themeInternetArray = Parser::contentGetThemeArrayMaxSize($this->rutrackerClient->content);
                }catch (\Exception $error){
                    Log::add($error->getMessage());
                    Log::add($error->getTraceAsString());
                    $themeInternetArray = false;
                };
                #Log::add("Parser::getTitle - start");
                $themeInternetTitle = Parser::getTitle($this->rutrackerClient->content);
                #Log::add("Parser::getPagination - start");
                $themeInternetCountPage = Parser::getPagination($this->rutrackerClient->content);
                $this->countPage = $themeInternetCountPage;

                // ��������� ���� �� �������� ���� �� ������� � ���� ������
                // ���� ���, �� �������
                preg_match("/\?[\w|\d|=]*/i", $themeSpy, $matches);
                $tableId = substr($matches[0], 1);
                #list($tableLink, $tableId) = explode("?", $themeSpy);
                if(!$this->db->tableExist($tableId)){
                    $this->db->tableCreate($tableId);
                    $this->db->elementAddTheme(["tableId"=>$tableId, "name"=>$themeInternetTitle]);
                };

                #################################################

                if ($themeInternetArray) {

                    foreach ($themeInternetArray as $key => $themeInternet) {

                        #if($key!=0){continue;};

                        // ���������� ����� ������
                        if(Config::$filtrManyFiltr == "on"){
                            // �������������� ��������� ������� �� �������� �������
                            Config::$filtrSetting = $this->themeRaw["setting"];
                        };
                        /**
                         * @var Filtr $rule
                         */
                        $rule = $this->filtr;
                        $ruleCheck = false;
                        if($rule->run($themeInternet)){
                            Log::add("Filtr Check successful");
                            $ruleCheck = true;
                        }else{
                            Log::add("Filtr Check was failed");
                            $ruleCheck = false;
                        };

                        if($ruleCheck){

                            $this->db->tableSet($tableId);
                            $element = $this->db->elementSearchByHash($themeInternet['nameRusHash']);
                            if ($element) {
                                // ���������� �������
                                Log::add("Find in DB");

                                if (
                                    isset(Config::$filtrSetting["size"]["downloadBigger"]) &&
                                    Config::$filtrSetting["size"]["downloadBigger"] == "on"
                                ){
                                    // ���������� ������� ���������
                                    $elementBaseSize = Parser::sizeMb(base64_decode($element['size']));
                                    $elementInSize = Parser::sizeMb($themeInternet['size']);

                                    $contrast = false;
                                    if ($elementBaseSize < $elementInSize) {
                                        $contrast = 0;
                                    };
                                    if ($elementBaseSize > $elementInSize) {
                                        $contrast = 1;
                                    };
                                    if ($elementBaseSize == $elementInSize) {
                                        $contrast = 2;
                                    };

                                    if ($contrast == 0) {
                                        Log::add("ElementBaseSize < ElementInSize");
                                        #Log::add('element[size] - '.$element['size']);
                                        Log::add($elementBaseSize . " < " . $elementInSize);
                                        // ��������� ������� ����
                                        $this->rutrackerClient->getTorrentFile($themeInternet['torrentFile']);
                                        // ��������� ����� ������� � ����
                                        // � ������ �������� ��������
                                        if ($this->rutrackerClient->torrentFileStatus) {
                                            $this->db->tableSet($tableId);
                                            $this->db->elementAdd($themeInternet);
                                        } else {
                                            Log::add("Element is not add in data base");
                                        };
                                    };

                                    if ($contrast == 1) {
                                        Log::add("ElementBaseSize > ElementInSize");
                                        Log::add("Continue");
                                        continue;
                                    };

                                    if ($contrast == 2) {
                                        Log::add("ElementBaseSize == ElementInSize");
                                        Log::add("Continue");
                                        continue;
                                    };
                                };

                            } else {
                                // ���������� �� �������
                                Log::add("Not find in DB");

                                // ��������� ������� ����
                                $this->rutrackerClient->getTorrentFile($themeInternet['torrentFile']);
                                // ��������� ����� ������� � ����
                                // � ������ �������� ��������
                                if($this->rutrackerClient->torrentFileStatus){
                                    $this->db->tableSet($tableId);
                                    $this->db->elementAdd($themeInternet);
                                }else{
                                    Log::add("Element is not add in data base");
                                };

                            };

                        };
                    };
                    Log::add("Finish");
                } else {
                    Log::add("Error: Parser False");
                };

                // ������ ��������
                if(!$this->stopParsing){
                    // ������� ������
                    if(isset($this->themeRaw) && isset($this->themeRaw["entry"])){
                        // ������� ����� ������� �����������
                        $entry = trim($this->themeRaw["entry"]);

                        // ��������� ���� �� ��������
                        if($this->countPage){
                            $this->countPage = (int) $this->countPage;

                            // ���������, ��� ������ ������������ � �������
                            if($entry == "all"){
                                $countSeePage = $this->countPage;
                            }else{
                                if(preg_match("/^\d+$/", $entry)){
                                    $countSeePage = (int) $entry;
                                }else{
                                    $countSeePage = 0;
                                };
                            };

                            // ���� ������������ ������ ������ ������� ��� ����
                            if($countSeePage > $this->countPage){
                                $countSeePage = $this->countPage;
                            };

                            // ���� ����� ����������� ������������� ��������
                            // �� ������ �� ����������
                            if($countSeePage > 0){
                                // ��������� ������ ��� ��������
                                $fileThemeArray = false;
                                $i=0;$start=0;
                                while($i < $countSeePage){
                                    $start = $start+50;
                                    $fileThemeArray[] = $themeSpy."&start=".$start;
                                    $i++;
                                };
                            };

                            // ���� ������ ����������� ��������� �������� �������
                            if(isset($fileThemeArray)){
                                $this->fileThemeArray = false;
                                $this->fileThemeArray = $fileThemeArray;
                                $this->stopParsing = true;
                                $this->mainProcess();
                                $this->stopParsing = false;
                                $this->fileThemeArray = false;
                            };
                        };
                    };
                };

            }


        }

    }

}