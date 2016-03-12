<?php
namespace sergey144010\RutrackerSpy;

interface DbInterface
{
    public function tableCreate($tableName);
    public function tableDel($tableName);
    public function tableSet($tableName);
    public function tableExist($tableName);
    public function elementAdd(array $array);
    public function elementDelById($id);
    public function elementDelByHash($hashName);
    public function elementSearchById($id);
    public function elementSearchByHash($nameRusHash);
}