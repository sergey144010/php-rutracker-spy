<?php
$fileAutoloadArray =
    [
        'vendor/autoload.php',
        'sergey144010/BaseFunctionExtension/ArrayExt.php',
        'sergey144010/Socket/Stream/Http/HttpOption.php',
        'sergey144010/Socket/Stream/Http/Method.php',
        'sergey144010/Socket/Stream/Http/Header.php',
        'sergey144010/Socket/Stream/Http/UserAgent.php',
        'sergey144010/Socket/Stream/Response.php',
        'sergey144010/Socket/Stream/Http/HttpClient.php',
        'sergey144010/RutrackerSpy/Event.php',
        'sergey144010/RutrackerSpy/Configuration.php',
        'sergey144010/RutrackerSpy/Logger.php',
        'sergey144010/RutrackerSpy/Main.php',
        'sergey144010/RutrackerSpy/Filtr.php',
        'sergey144010/RutrackerSpy/DbInterface.php',
        'sergey144010/RutrackerSpy/Db.php',
        'sergey144010/RutrackerSpy/Parser.php',
        'sergey144010/RutrackerSpy/RutrackerClient.php',
    ];

foreach ($fileAutoloadArray as $file) {
    require_once($file);
};
