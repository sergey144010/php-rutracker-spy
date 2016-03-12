<?php
namespace sergey144010\RutrackerSpy;
/*
    Класс прослойка для подключения классов работы с базами данных.
    Через данный класс идет работа с базой данных в приложении.
*/
use sergey144010\RutrackerSpy\DbInterface;
use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Logger as Log;

class Db implements DbInterface
{
    public $dbName;
    public $dbTable;
    protected $dbh;
    protected $user;
    protected $pass;
    protected $dbHost;
    protected $dbType;

    public function __construct()
    {
        $this->dbName = Config::$dbName;
        $this->user = Config::$dbUser;
        $this->pass = Config::$dbPass;
        $this->dbType = Config::$dbType;
        $this->dbHost = Config::$dbHost;
        $this->connect();
    }

    public function connect()
    {
        try{
            $this->dbh = new \PDO($this->dbType.':host='.$this->dbHost.';dbname='.$this->dbName, $this->user, $this->pass);
        }catch(\Exception $error){
            Log::add("ERROR: (Db) : DATE BASE IS NOT CONNECTED");
            #Log::add("ERROR: (Db) : DATE BASE IS NOT CONNECTED".PHP_EOL.$error->getTraceAsString());
        };
        return $this;
    }

    public function isConnect()
    {
        if(isset($this->dbh)){
            return true;
        }else{
            return false;
        }
    }

    public function tableTake()
    {
        $array = $this->dbh->query('SELECT * from `'.$this->dbTable.'`');
        return $array;
    }

    public function tableExist($tableName=false)
    {
        if(!$tableName){
            $obj = $this->dbh->query('SELECT * from `'.$this->dbTable.'`');
            if(is_object($obj)){
                return true;
            }else{
                return false;
            }
        }else{
            $obj = $this->dbh->query('SELECT * from `'.$tableName.'`');
            if(is_object($obj)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function tableCreate($tableName)
    {
        if(!$this->tableExist("theme")){
            $this->tableCreateTheme();
        };

        $this->dbh->query("
CREATE TABLE IF NOT EXISTS `".$tableName."` (
  `id` int(10) NOT NULL,
  `themeId` varchar(50) CHARACTER SET cp1251 NOT NULL DEFAULT '',
  `name` varchar(500) CHARACTER SET cp1251 NOT NULL DEFAULT '',
  `nameRusHash` varchar(100) CHARACTER SET cp1251 NOT NULL DEFAULT '',
  `size` varchar(50) CHARACTER SET cp1251 NOT NULL DEFAULT '',
  `href` varchar(100) CHARACTER SET cp1251 NOT NULL DEFAULT '',
  `torrentFile` varchar(150) CHARACTER SET cp1250 NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;
        ");

        $this->dbh->query("
ALTER TABLE `".$tableName."`
  ADD PRIMARY KEY (`id`);
        ");

        $this->dbh->query("
ALTER TABLE `".$tableName."`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
        ");

        $this->dbh->query("INSERT INTO `".$this->dbName."`.`theme`
(`id`, `themeId`)
VALUES
(
NULL,
'".$tableName."');
");

        Log::add("Create new table in data base - ".$tableName);
    }

    public function tableCreateTheme()
    {
        $this->dbh->query("
CREATE TABLE IF NOT EXISTS `theme` (
  `id` int(10) NOT NULL,
  `themeId` varchar(50) CHARACTER SET cp1251 NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;
        ");

        $this->dbh->query("
ALTER TABLE `theme`
  ADD PRIMARY KEY (`id`);
        ");

        $this->dbh->query("
ALTER TABLE `theme`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
        ");

        Log::add("Create new table in data base - Theme");
    }

    public function tableDel($tableName=false){
        if(!$tableName){
            $this->dbh->query('DROP TABLE '.$this->dbTable);
        }else{
            $this->dbh->query('DROP TABLE '.$tableName);
        };
    }

    public function tableSet($dbTable){
        $this->dbTable = $dbTable;
        return $this;
    }
    public function elementAdd(array $theme){

        $theme['name'] = base64_encode($theme['name']);
        $theme['size'] = base64_encode($theme['size']);

        $this->dbh->query("INSERT INTO `".$this->dbName."`.`".$this->dbTable."`
(`id`, `themeId`, `name`, `nameRusHash`, `size`, `href`, `torrentFile`)
VALUES
(
NULL,
'".$theme['id']."',
'".$theme['name']."',
'".$theme['nameRusHash']."',
'".$theme['size']."',
'".$theme['href']."',
'".$theme['torrentFile']."');
");

        Log::add("Add new element in data base - ".$theme['id']);
    }
    public function elementDelById($id){
        $this->dbh->query("DELETE FROM `".$this->dbName."`.`".$this->dbTable."` WHERE `".$this->dbTable."`.`id` = ".$id);
    }

    public function elementDelByHash($hashName){}
    public function elementSearchById($id){}

    /*
     * Возвращает последний найденный элемент
     *
     * @return last find element || false
     * */
    public function elementSearchByHash($nameRusHash)
    {
        $elementS = $this->dbh->query("SELECT * FROM `".$this->dbTable."` WHERE `nameRusHash` = '".$nameRusHash."' ");
        foreach($elementS as $element) {};
        if(!isset($element)){
            return false;
        }else{
            #return ['last' => $element, 'all' => $elementS];
            return $element;
        }
    }

    /*
     * Возвращает все найденные элементы
     *
     * @return all find element || false
     * */
    public function elementSearchByHashAll($nameRusHash)
    {
        $elementS =  $this->dbh->query("SELECT * FROM `".$this->dbTable."` WHERE `nameRusHash` = '".$nameRusHash."' ");
        return $elementS;
    }
}