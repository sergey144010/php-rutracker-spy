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
            // Инициализация конфига
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
            // Проверяем подключение к базе данных
            if($this->db->isConnect()){
                // Если cookie есть, то
                if ($this->rutrackerClient->isCookie() && $this->rutrackerClient->isCookieValid()) {
                    Log::add("Cookie is valid");
                    $this->getThemeFiltr();
                    $this->mainProcess();
                } else {
                    // Если cookie нет, то логинимся
                    Log::add("Cookie is not valid");
                    Log::add("Run login");
                    $this->rutrackerClient->login();
                    // Если соединение не установлено, пишем в лог
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
            // Отслеживаемые темы
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
                // Открываем файл отслеживаемых тем
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
                    // Сохраняем настройки раздела
                    $this->themeRaw = $themeSpy;
                    $themeSpy = trim($this->themeRaw["url"]);
                };

                Log::add("Current theme - ".$themeSpy);
                #var_dump($themeRaw["setting"]);
                #continue;

                // Открываем раздел
                $this->rutrackerClient->urlOpenLogin($themeSpy);
                $this->rutrackerClient->getContent();
                $this->rutrackerClient->writeToFile($this->rutrackerClient->content);
                // Парсим раздел
                $themeInternetArray = Parser::contentGetThemeArrayMaxSize($this->rutrackerClient->content);
                $themeInternetTitle = Parser::getTitle($this->rutrackerClient->content);
                $themeInternetCountPage = Parser::getPagination($this->rutrackerClient->content);
                $this->countPage = $themeInternetCountPage;

                // Проверяем есть ли заданная тема из конфига в базе данных
                // Если нет, то создаем
                preg_match("/\?[\w|\d|=]*/i", $themeSpy, $matches);
                $tableId = substr($matches[0], 1);
                #list($tableLink, $tableId) = explode("?", $themeSpy);
                if(!$this->db->tableExist($tableId)){
                    $this->db->tableCreate($tableId);
                    $this->db->elementAddTheme(["tableId"=>$tableId, "name"=>$themeInternetTitle]);
                };

                #################################################

                if ($themeInternetArray) {

                    // Создаем объект класса для работы с базой данных определенный в конфиге
                    #$db = $this->dbObject(Config::$dbClass);
                    #$db->tableSet("theme_one_spy");
                    $this->db->tableSet($tableId);

                    foreach ($themeInternetArray as $key => $themeInternet) {

                        #if($key!=0){continue;};

                        // Пропускаем через фильтр
                        if(Config::$filtrManyFiltr == "on"){
                            // Переопределяем настройки фильтра из главного конфига
                            Config::$filtrSetting = $this->themeRaw["setting"];
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
                                // Совпадения найдены
                                Log::add("Find in DB");
                                // Сравниваем размеры элементов
                                $elementBaseSize = Parser::sizeMb(base64_decode($element['size']));
                                $elementInSize = Parser::sizeMb($themeInternet['size']);

                                $contrast = false;
                                if($elementBaseSize<$elementInSize){$contrast = 0;};
                                if($elementBaseSize>$elementInSize){$contrast = 1;};
                                if($elementBaseSize==$elementInSize){$contrast = 2;};

                                if($contrast == 0){
                                    Log::add("ElementBaseSize < ElementInSize");
                                    // Скачиваем торрент файл
                                    $this->rutrackerClient->getTorrentFile($themeInternet['torrentFile']);
                                    // Добавляем новый элемент в базу
                                    // в случае успешной загрузки
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
                                // Совпадения не найдены
                                Log::add("Not find in DB");

                                // Скачиваем торрент файл
                                $this->rutrackerClient->getTorrentFile($themeInternet['torrentFile']);
                                // Добавляем новый элемент в базу
                                // в случае успешной загрузки
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

                // Парсим страницы
                if(!$this->stopParsing){
                    // Смотрим конфиг
                    if(isset($this->themeRaw) && isset($this->themeRaw["entry"])){
                        // Сколько нужно страниц просмотреть
                        $entry = trim($this->themeRaw["entry"]);

                        // Проверяем есть ли страницы
                        if($this->countPage){
                            $this->countPage = (int) $this->countPage;

                            // Проверяем, что указал пользователь в конфиге
                            if($entry == "all"){
                                $countSeePage = $this->countPage;
                            }else{
                                if(preg_match("/^\d+$/", $entry)){
                                    $countSeePage = (int) $entry;
                                }else{
                                    $countSeePage = 0;
                                };
                            };

                            // Если пользователь указал больше страниц чем есть
                            if($countSeePage > $this->countPage){
                                $countSeePage = $this->countPage;
                            };

                            // Если вдруг установлено отрицательное значение
                            // то ничего не происходит
                            if($countSeePage > 0){
                                // Формируем массив для парсинга
                                $i=0;$start=0;
                                while($i < $countSeePage){
                                    $start = $start+50;
                                    $fileThemeArray[] = $themeSpy."&start=".$start;
                                    $i++;
                                };
                            };

                            // Если массив сформирован запускаем основной процесс
                            if(isset($fileThemeArray)){
                                $this->fileThemeArray = $fileThemeArray;
                                $this->stopParsing = true;
                                $this->mainProcess();
                                $this->stopParsing = false;
                            };
                        };
                    };
                };

            }


        }

    }

}