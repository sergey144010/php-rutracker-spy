<?php
class Config
{
    public $config = "config.php";
    public $configNew = "config_new.php";
    public $configBase64 = "config_base64_encode.txt";

    public function encode()
    {
        // Получаем закодированную строку
        $current = file_get_contents($this->config);
        $current = base64_encode($current);
        file_put_contents($this->configBase64, $current);
    }
    public function create()
    {
        // Создаем файл конфига
        $current = file_get_contents($this->configBase64);
        $current = base64_decode($current);
        if(is_file($this->config))
        {
            file_put_contents($this->configNew, $current);
        }else{
            file_put_contents($this->config, $current);
        };
    }
}

$config = new Config();
#$config->encode();
$config->create();
echo "End create".PHP_EOL;