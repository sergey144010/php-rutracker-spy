<?php
class Config
{
    public function encode()
    {
        // Получаем закодированную строку
        $current = file_get_contents("config.php");
        $current = base64_encode($current);
        file_put_contents("config_base64_encode.txt", $current);
    }
    public function create()
    {
        // Создаем файл конфига
        $current = file_get_contents("config_base64_encode.txt");
        $current = base64_decode($current);
        if(is_file("config.php"))
        {
            file_put_contents("config_new.php", $current);
        }else{
            file_put_contents("config.php", $current);
        };
    }
}

$config = new Config();
#$config->encode();
$config->create();
echo "End create".PHP_EOL;