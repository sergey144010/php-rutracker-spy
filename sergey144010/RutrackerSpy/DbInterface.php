<?php
namespace sergey144010\RutrackerSpy;

interface DbInterface
{
    /**
     * Проверяет подключение к базе данных
     *
     * @return true || false
     */
    public function isConnect();

    public function tableCreate($tableName);
    public function tableDel($tableName);
    public function tableSet($tableName);

    /**
     * @param $tableName
     * @return true || false
     */
    public function tableExist($tableName=false);
    /**
     * @return array
     */
    public function tableTake();
    public function tableCreateTheme();
    public function elementAdd(array $array);
    public function elementAddTheme(array $array);
    public function elementDelById($id);
    public function elementDelByHash($hashName);
    public function elementSearchById($id);
    /**
     * @return true || false
     */
    public function elementSearchByHash($nameRusHash);
    /**
     * @return array
     */
    public function showTables();
}