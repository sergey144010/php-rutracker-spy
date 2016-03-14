<?php
namespace sergey144010\RutrackerSpy;

use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Parser;
use sergey144010\RutrackerSpy\Logger as Log;

class Filtr extends MainFiltr
{

    public function genre()
    {
        $can = ["�������", "��������", "�����������"];

        // ���� ����� ���� - ������
        if($this->check($this->theme['name'], $can)){
            return true;
        };
        #Log::add("Filter GENRE failed".$this->theme['name']);
        return false;
    }

    public function quality()
    {
        // ������ ������
        $can = ["HDRip", "DVDRip" ,"WEB-DLRip" , "BDRip"];
        // ������ �� �����
        $not = ["CAMRip"];

        // ���� ����� CAMRip - �� ���������
        if(!$this->check($this->theme['name'], $not)){
            return true;
        };
        #Log::add("Filter QUALITY failed".$this->theme['name']);
        return false;
    }

    public function size()
    {
        $size = Parser::sizeMb($this->theme['size']);
        $range = ["1 GB", "2 GB"];

        // ���� ������ �������� �� 1Gb �� 2Gb - ������
        if(
            Parser::sizeMb($range[0]) <= $size &&
            Parser::sizeMb($range[1]) >= $size
        ){
            return true;
        }
        #Log::add("Filter SIZE failed".$this->theme['size']);
        return false;
    }

    public function stopWord()
    {
        $not = [""];

        // ���� ����� �������� ����� - �� ���������
        if(!$this->check($this->theme['name'], $not)){
            return true;
        };
        #Log::add("Filter STOPWORD failed".$this->theme['name']);
        return false;
    }

    public function travelCard()
    {
        $can = [""];

        // ���� ����� ���� ����� - ������� � ����� ������
        if($this->check($this->theme['name'], $can)){
            return true;
        };
        #Log::add("Filter TRAVELCARD failed".$this->theme['name']);
        return false;
    }

    /*
     * �������������� ����� �������� checkAllRule()
     *
     * ���� ��� ������� ���������� true, �� �������� ��������
     *
     */
    public function checkAllRule()
    {
        if($this->travelCard()){
            return true;
        };

        if(
            $this->quality() &&
            $this->genre() &&
            $this->size() &&
            $this->stopWord()
        ){
            return true;
        }else{
            return false;
        }
    }

}