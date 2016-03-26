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
                            $this->mainProcess();
                        }else{
                            Log::add("ERROR: Login is failed");
                        };
                    };
                };
            };
        }

        public function mainProcess()
        {
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

            foreach ($fileThemeArray as $themeSpy) {

                if(is_string($themeSpy)){
                    $themeSpy = trim($themeSpy);
                };
                if(is_array($themeSpy)){
                    $themeRaw = $themeSpy;
                    $themeSpy = trim($themeRaw["url"]);
                };

                Log::add("Current theme - ".$themeSpy);
                #var_dump($themeRaw["setting"]);
                #continue;

                // ��������� ���� �� ������ ���� � ���� ������
                // ���� ���, �� �������
                list($tableLink, $tableId) = explode("?", $themeSpy);
                #$db = $this->dbObject(Config::$dbClass);
                #$db->tableSet($tableId);
                if(!$this->db->tableExist($tableId)){
                    $this->db->tableCreate($tableId);
                };

                #################################################

                $this->rutrackerClient->urlOpenLogin($themeSpy);
                $this->rutrackerClient->getContent();
                $this->rutrackerClient->writeToFile($this->rutrackerClient->content);
                $themeInternetArray = Parser::contentGetThemeArrayMaxSize($this->rutrackerClient->content);

                if ($themeInternetArray) {

                    // ������� ������ ������ ��� ������ � ����� ������ ������������ � �������
                    #$db = $this->dbObject(Config::$dbClass);
                    #$db->tableSet("theme_one_spy");
                    $this->db->tableSet($tableId);

                    foreach ($themeInternetArray as $key => $themeInternet) {

                        #if($key!=0){continue;};

                        // ���������� ����� ������
                        if(Config::$filtrManyFiltr == "on"){
                            // �������������� ��������� ������� �� �������� �������
                            Config::$filtrSetting = $themeRaw["setting"];
                        };
                        #$rule = $this->filtrObject(Config::$filtrClass);
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

                            $element = $this->db->elementSearchByHash($themeInternet['nameRusHash']);
                            if ($element) {
                                // ���������� �������
                                Log::add("Find in DB");
                                // ���������� ������� ���������
                                $elementBaseSize = Parser::sizeMb(base64_decode($element['size']));
                                $elementInSize = Parser::sizeMb($themeInternet['size']);

                                $contrast = false;
                                if($elementBaseSize<$elementInSize){$contrast = 0;};
                                if($elementBaseSize>$elementInSize){$contrast = 1;};
                                if($elementBaseSize==$elementInSize){$contrast = 2;};

                                if($contrast == 0){
                                    Log::add("ElementBaseSize < ElementInSize");
                                    // ��������� ������� ����
                                    $this->rutrackerClient->getTorrentFile($themeInternet['torrentFile']);
                                    // ��������� ����� ������� � ����
                                    // � ������ �������� ��������
                                    if($this->rutrackerClient->torrentFileStatus){
                                        $this->db->elementAdd($themeInternet);
                                    }else{
                                        Log::add("Element is not add in data base");
                                    };
                                };

                                if($contrast == 1){
                                    Log::add("ElementBaseSize > ElementInSize");
                                    Log::add("Continue");
                                    continue;
                                };

                                if($contrast == 2){
                                    Log::add("ElementBaseSize == ElementInSize");
                                    Log::add("Continue");
                                    continue;
                                };

                            } else {
                                // ���������� �� �������
                                Log::add("Not find in DB");

                                // ��������� ������� ����
                                $this->rutrackerClient->getTorrentFile($themeInternet['torrentFile']);
                                // ��������� ����� ������� � ����
                                // � ������ �������� ��������
                                if($this->rutrackerClient->torrentFileStatus){
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

            }


        }



    }

}