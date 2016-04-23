<?php

namespace sergey144010\RutrackerSpy;

use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Logger as Log;
use \ZipArchive;

class Report
{
    #public $partDir = "../../";
    public $partDir = "../";

    public $config;
    public $log;
    public $cookie;
    public $themeDir;
    public $theme;

    /*
     * @var ZipArchive $zip
     */
    public $zip;

    public function getConfig()
    {
        // Берем файл конфига и удаляем из него логины и пароли
        $configFileName = $this->partDir."config.php";
        if (is_file($configFileName)) {
            require($configFileName);
            if (isset($config)) {
                $this->config = $config;
            }else{
                throw new \Exception ('Require config file failed');
            };
        } else {
            throw new \Exception ('File config not found');
        };
    }

    public function delLoginPass()
    {
        $this->config['db']['host'] = false;
        $this->config['db']['user'] = false;
        $this->config['db']['pass'] = false;
        $this->config['client']['user'] = false;
        $this->config['client']['pass'] = false;
    }

    public function prepareFile()
    {
        $this->log['log'] =
            $this->partDir.$this->config['log']['dir'].
            DIRECTORY_SEPARATOR.
            $this->config['log']['fileName'];

        $this->log['isCookieValid'] = $this->partDir.$this->config['log']['dir'].DIRECTORY_SEPARATOR.'isCookieValid.txt';
        $this->log['tokenNotGet'] = $this->partDir.$this->config['log']['dir'].DIRECTORY_SEPARATOR.'tokenNotGet.txt';
        $this->log['writeToFile'] = $this->partDir.$this->config['log']['dir'].DIRECTORY_SEPARATOR.'writeToFile.txt';

        $this->cookie['cookie'] = $this->partDir.$this->config['cookie']['dir'].DIRECTORY_SEPARATOR.$this->config['cookie']['fileName'];
        $this->cookie['bbData'] = $this->partDir.$this->config['cookie']['dir'].DIRECTORY_SEPARATOR.$this->config['cookie']['fileBbData'];

        $this->themeDir = $this->partDir.$this->config['themeSpy']['dir'];
    }

    public function prepareTheme()
    {
        if(is_dir($this->themeDir)){
            $dir = scandir($this->themeDir);
            foreach ($dir as $file) {
                if($file == '.' || $file == '..'){continue;};
                if($file == 'theme.txt'){
                    $this->theme[] = $this->themeDir.DIRECTORY_SEPARATOR.$file;
                    continue;
                };
                $strlen = strlen($file);
                $ext = $strlen-10;
                $extension = substr($file, $ext);
                if($extension == '.filtr.php'){
                    $this->theme[] = $this->themeDir.DIRECTORY_SEPARATOR.$file;
                };
            }

        };
    }

    public function zip()
    {
        $this->zip = new ZipArchive;
        $res = $this->zip->open($this->partDir.$this->config['log']['dir'].DIRECTORY_SEPARATOR.'report.zip', \ZipArchive::CREATE);
        if ($res === TRUE) {
            $this->runIterateArray();

            $configWrite = serialize($this->config);
            $this->zip->addFromString('config.php', $configWrite);

            $this->zip->close();
            #echo 'ok';
        } else {
            throw new \Exception ('Failed open ZipArchive');
        }
    }

    public function iterateArray($array)
    {
        foreach ($array as $key => $val) {
            if(is_string($val)){
                if(is_file($val)){
                    $this->zip->addFile($val);
                };
            };
            if(is_array($val)){
                $this->iterateArray($val);
            };
        }
    }

    public function runIterateArray()
    {
        #$arrayLog = [$this->log, $this->cookie];
        $arrayLog = [$this->log, $this->theme];
        $this->iterateArray($arrayLog);
    }

    public function run()
    {
        try{
            $this->getConfig();
            $this->delLoginPass();
            $this->prepareFile();
            $this->prepareTheme();
            $this->zip();
        }catch (\Exception $error){
            echo $error->getMessage().PHP_EOL;
            echo $error->getLine().PHP_EOL;
            echo $error->getFile().PHP_EOL;
            echo $error->getTraceAsString().PHP_EOL;
        }
    }
}
