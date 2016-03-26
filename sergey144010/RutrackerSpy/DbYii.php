<?php

namespace sergey144010\RutrackerSpy;

use sergey144010\RutrackerSpy\Yii;
use sergey144010\RutrackerSpy\ActiveTable;
use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Logger as Log;


class DbYii implements DbInterface
{
    /**
     * @var Yii $yii
     */
    protected $yii;

    /**
     * @var ActiveTable $table
     */
    protected $table;

    public function __construct()
    {
        $this->yii = Yii::init();
    }

    public function tableCreate($tableName){

        if(!$this->tableExist("theme")){
            $this->tableCreateTheme();
        };

        // Создаем новую таблицу $tableName
        /**
         * @var \yii\db\Command $schema
         */
        $schema = $this->yii->db->createCommand()->createTable($tableName,[
            "id"=>"pk",
            "themeId"=>"varchar(50) CHARACTER SET cp1251 NOT NULL DEFAULT ''",
            "name"=>"varchar(500) CHARACTER SET cp1251 NOT NULL DEFAULT ''",
            "nameRusHash"=>"varchar(100) CHARACTER SET cp1251 NOT NULL DEFAULT ''",
            "size"=>"varchar(50) CHARACTER SET cp1251 NOT NULL DEFAULT ''",
            "href"=>"varchar(100) CHARACTER SET cp1251 NOT NULL DEFAULT ''",
            "torrentFile"=>"varchar(150) CHARACTER SET cp1251 NOT NULL DEFAULT ''",
        ],
            "ENGINE=InnoDB DEFAULT CHARSET=cp1251");
        $schema->execute();

        // Добавляем запись в таблицу Theme
        $this->tableSet('theme');
        $this->table->themeId = $tableName;
        $this->table->save();
        $this->table = null;

        // Пишем лог
        Log::add("Create new table in data base - ".$tableName);
    }
    public function tableDel($tableName){}

    public function tableSet($tableName){
        ActiveTable::setTable($tableName);
        $this->table = new ActiveTable();
        return $this;
    }

    public function tableExist($tableName=false)
    {
        if(!$tableName){
            #$this->tableSet($tableName);
            try{
                $array =  ActiveTable::find()->asArray()->limit("1")->all();
                if(is_array($array)){
                    return true;
                }else{
                    return false;
                }
            }catch (\Exception $e){
                return false;
            }
        }else{
            $this->tableSet($tableName);
            try{
                $array = ActiveTable::find()->asArray()->limit("1")->all();
                if(is_array($array)){
                    return true;
                }else{
                    return false;
                }
            }catch (\Exception $e){
                return false;
            }
        }
    }

    public function tableTake(){
        return ActiveTable::find()->asArray()->all();
    }

    public function tableCreateTheme()
    {
        #$clone = $this->yii->db->getTableSchema('theme');

        $schema = $this->yii->db->createCommand()->createTable('theme',[
            'id'=>'pk',
            'themeId'=>"varchar(50) CHARACTER SET cp1251 NOT NULL DEFAULT ''",
        ],
            "ENGINE=InnoDB DEFAULT CHARSET=cp1251");
        /**
         * @var \yii\db\Command $schema
         */
        $schema->execute();
        // Пишем лог
        Log::add("Create new table in data base - theme");
    }

    public function elementAdd(array $array){

        $theme = $array;
        $theme['name'] = base64_encode($theme['name']);
        $theme['size'] = base64_encode($theme['size']);

        // Пишем в базу
        $this->table->themeId = $theme['id'];
        $this->table->name = $theme['name'];
        $this->table->nameRusHash = $theme['nameRusHash'];
        $this->table->size = $theme['size'];
        $this->table->href = $theme['href'];
        $this->table->torrentFile = $theme['torrentFile'];
        $this->table->save();

        // Пишем лог
        Log::add("Add new element in data base - ".$theme['id']);

    }
    public function elementDelById($id){}
    public function elementDelByHash($hashName){}
    public function elementSearchById($id){}
    public function elementSearchByHash($nameRusHash){
        $obj = ActiveTable::findOne(['nameRusHash'=>$nameRusHash]);
        if(isset($obj)){
            return true;
        }else{
            return false;
        }
    }

    public function showTables()
    {
       return $this->yii->db->createCommand('SHOW TABLES')->queryAll();
    }

    public function isConnect()
    {
        try{
            $this->yii->db->createCommand('SHOW TABLES')->queryOne();
            return true;
        }catch (\Exception $e){
            Log::add("ERROR: (Db) : DATE BASE IS NOT CONNECTED");
            return false;
        }
    }

}