<?php

namespace sergey144010\RutrackerSpy;

use sergey144010\RutrackerSpy\Configuration as Config;


class ConfigurationCheck
{
    public static function isFile($configFileName)
    {
        if(!is_file($configFileName)){
            return false;
        }else{
            return true;
        };
        return false;
    }

    public static function check($configFileName)
    {
        $isFileConfig = false;
        if(!is_file($configFileName)){
            $isFileConfig = false;
        }else{
            $isFileConfig = true;
        };

        if($isFileConfig){

            require($configFileName);

            if(isset($config)){

                if(!$config['db']){return false;};
                if(!$config['db']['class']){return false;};
                if(!$config['db']['type']){return false;};
                if(!$config['db']['host']){return false;};
                if(!$config['db']['name']){return false;};
                if(!$config['db']['user']){return false;};
                if(!$config['db']['pass']){return false;};

                if(!$config['client']){return false;};
                if(!$config['client']['user']){return false;};
                if(!$config['client']['pass']){return false;};

                if(!$config['log']){return false;};
                if(!$config['log']['turn']){return false;};
                if(!$config['log']['dir']){return false;};
                if(!$config['log']['fileName']){return false;};

                if(!$config['cookie']){return false;};
                if(!$config['cookie']['dir']){return false;};
                if(!$config['cookie']['fileName']){return false;};
                if(!$config['cookie']['fileBbData']){return false;};

                if(!$config['torrent']){return false;};
                if(!$config['torrent']['dir']){return false;};
                if(!$config['torrent']['attempt']){return false;};

                if(!$config['themeSpy']){return false;};
                if(!$config['themeSpy']['dir']){return false;};
                if(!$config['themeSpy']['fileName']){return false;};

                if(!$config['timer']){return false;};
                if(!$config['timer']['time']){return false;};

                if(!$config['filtr']){return false;};
                if(!$config['filtr']['class']){return false;};
                if(!$config['filtr']['turn']){return false;};
                if(!$config['filtr']['manyFiltr']){return false;};
                #if(!$config['filtr']['setting']){return false;};

                return true;
            }else{
                return false;
            };

        }else{
            return false;
        };
        return false;
    }
}