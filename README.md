php-rutracker-spy
==========================

Version 0.0.5

������� ��������
-------
��������� ������������� ������� ������ rutracker.
� ������������ � ������������� �������� ��������� ������� �����
� ��������� ������ � ���� ������.

��� ����?
-------
����� ������������ ������� ������ �� ���������� ����� ��������� �� ������������ �����
(� ����� ������ �� ����� torrent) � ��������� �������������� ���������� ����� ��������� � RuTracker.

��������
-------
��������� ��������� �� rutracker. ��������� ����.
��������� ��������. ������ �������� �������� ����.
���� ���� �������� ������ ��������� ������� ���� � ��������� ������ � ���� ������.
��� ��������� ������ ������������ ����������� ����.
���� ���� �������� ������ � ��� ��� ���� ����, �� ��������� ������ ��������.
� ������ ������� � ������� �������� ����� ����� ������ ������.

�����������
-------
��������� ������������� ������ ��� ������, ��������������� � yii2.
�� ��������� ������������ - MySql.
(PHP, sunra/php-simple-html-dom-parser)

���������
-------
����������� �����������.
```php
git clone https://github.com/sergey144010/php-rutracker-spy YOUR_PATH
```
������� � YOUR_PATH.
���������� ����������� ��������
```php
composer update
```

���������
-------

������� ������, �������� ������ create_config.php
```php
php create_config.php
```

� ��������� ����� ������� config.php ���������
��������� ��� ����������� � ���� ������
```php
    "db" =>
        [
            // ����� ��� ������ � ����� ������
            # ����� ������� ���� ����� � ������������ � DbInterface
            # � ���������� ��� �����
            "class"=>"sergey144010\RutrackerSpy\DbYii",
            // ������ ���� ������
            "type"=>"mysql",
            // ���� ���� ������
            "host"=>"localhost",
            // ��� ���� ������
            "name"=>"rutracker_spy",
            // ��� ������������ � ������ ��� ������� � ���� ������
            "user"=>"userName",
            "pass"=>"password",
        ],
```
������� ��������������� ���� ������ � MySql. � ����� ������ rutracker_spy.

��������� ��� ������������ � ������ ��� ����� �� Rutracker.
```php
    "client" =>
        [
            "user"=>"login",
            "pass"=>"password",
        ],
```

��������� ��������� ����� �������� ��� ���� �� ����������� ������� ���.

��� ������ ������� ����� �������� ���������� ������� ������� � ��������
```php
    "timer" =>
        [
            "time"=>"3600",
        ]
```

������� ����������� ���������� cookie, log, themeSpy, torrent, web/assets
�������� ������
```php
php create_dir.php
```
��� ���������� � ����� ������� �� �������.

� ���������� �������� ������������� ����.
�� ��������� ���� ������������� ���  - themeSpy/theme.txt
��������� ���, ������ ���������: ����� ������ = ����� ������
```php
http://rutracker.org/forum/viewforum.php?f=2200
http://rutracker.org/forum/viewforum.php?f=2093
```

��������� ������� ���
---------------------

���������� ������� � ����� ������� config.php
```php
    "filtr" =>
        [
            // on - �������, off - ��������
            "turn"=>"off",
        ],
```
��� ����������� ������� ��� ���� ����� �������.

��������� �������:
```php
    "filtr" =>
        [
            // ����� �������
            "class"=>"sergey144010\RutrackerSpy\Filtr",
            // ��������/��������� ������ - on/off
            "turn"=>"on",
            // ��� ������� ������� ���� ������
            // ��������/��������� - on/off
            "manyFiltr"=>"off",
            // ��������� ����������.
            // ���� �� ����� ����� �������� ������, ��������: "setting"=>""
            "setting"=>
                // ���� �����-���� ������ �� �����, ��
                // ����� ��� ������� ��� �������� ������ ��������.
                // ��������:
                // "genre"=>"", "quality"=>"", "size"=>"", "stopWord"=>"", "seed"=>"", "travelCard"=>""
                // ��� ���������� ���� ���������� ���������:
                // ��������� ���� ���� �������� ��� "�������" ��� "��������" ��� "�����������",
                // �� �� �������� "�����".
                // �������� ����� ����� CAMRip,
                // ������ �� 1Gb �� 3Gb,
                // ����������� ���������� ����� 1 ��.
                [
                    // ����
                    "genre"=>
                        [
                            // ���� ����� ������� ��� ����� ������
                            // �������� only ������������ ��� ��������� ������.
                            #"only"=>["�������", "��������", "�����������"],
                            //
                            // ���� ����� ������� ������ "�������", "��������", "�����������"
                            // �������� exept ������������ ��� ��������� ������.
                            #"exept"=>["�����"],
                            //
                            // ���� ����� ������� ������ "�������", "��������", "�����������"
                            // � ������� �� ������������ "�����"
                            // ���������� ��� ���������.
                            //
                            // � ������ ������ ���� � �������� ������������ [�������, �����]
                            // ����� ������ �� �����.

                            // ������ ������ ...
                            "only"=>["�������", "��������", "�����������"],
                            // ������ ��� ����� ...
                            "exept"=>["�����"],
                        ],

                    // ��������
                    "quality"=>
                        [
                            // ����� ������ �������� �� ��� ��� � �����, � ������ ������
                            // ��������� only ���� exept, ������������ "only" ��� �������
                            // ��� ������� �����������������, ������ �� ��������.
                            // ������ ������ ...
                            #"only"=>["HDRip", "DVDRip" ,"WEB-DLRip" , "BDRip"],
                            // ������ ��� ����� ...
                            "exept"=>["CAMRip"],
                        ],

                    // ������
                    "size"=>
                        [
                            // ��������:
                            // ���� ������ �� 1 GB - "min"=>"1Gb",
                            // ���������������� "max"=>"3Gb"
                            //
                            // ���� ������ �� 2 GB - "max"=>"2Gb",
                            // ���������������� "min"=>"1Gb"
                            //
                            // ���� ������ �� 1 GB �� 2 GB
                            // ������������ ��� ���������
                            "min"=>"1Gb",
                            "max"=>"3Gb",
                        ],

                    // �������� ����� ( ��� ������ �� ���������� ���� )
                    // ���� ������� �� ������
                    // ��������: ������� �� �������� �����
                    "stopWord"=>[""],

                    // ����
                    "seed"=>
                        [
                            // ���������� ����������� ���������� �����
                            "min"=>"1",
                        ],

                    // ���������
                    // ���� ������� ������ � ����� ������
                    // ��������: ������� �������� �����
                    "travelCard"=>[""],
                ],
        ],
```

���� �� ����� ��� ������� ������� ���������� ���� �������, �� �����
� ����� ������� config.php �������� �����
```php
"manyFiltr"=>"on",
```
����� ���������� ������� ���� ������� ��������� ���������:

������� �1.
```php
php create_theme_config.php
```
� ������ ������ � ���������� ������������� ���, �� ��������� themeSpy,
�������� ���� ���� ���� 1458574015.filtr.php �� ��������� ����������, ������� ���������� ���������

```php
$filtr =
[
    // URL �������
    // ��������:
    // http://rutracker.org/forum/viewforum.php?f=2200
    "url" => "",
    
    // ������� ��������� ����
    // "entry" => "0", - ������� ������ ������ �������� �������
    // "entry" => "1", - ������� ������ � ������ �������� �������
    // "entry" => "2", - ������� 1,2,3 �������� �������
    // "entry" => "all", - ������� ��� �������� �������
    "entry" => "0",

    // ��������� ����������.
    // ���� �� ����� ����� �������� ������, ��������: "setting"=>""
    "setting"=>
    // ���� �����-���� ������ �� �����, ��
    // ����� ��� ������� ��� �������� ������ ��������.
    // ��������:
    // "genre"=>"", "quality"=>"", "size"=>"", "stopWord"=>"", "seed"=>"", "travelCard"=>""
    // ��� ���������� ���� ���������� ���������:
    // ��������� ���� ���� �������� ��� "�������" ��� "��������" ��� "�����������",
    // �� �� �������� "�����".
    // �������� ����� ����� CAMRip,
    // ������ �� 1Gb �� 3Gb,
    // ����������� ���������� ����� 1 ��.
        [
            // ����
            "genre"=>
                [
                    // ���� ����� ������� ��� ����� ������
                    // �������� only ������������ ��� ��������� ������.
                    #"only"=>["�������", "��������", "�����������"],
                    //
                    // ���� ����� ������� ������ "�������", "��������", "�����������"
                    // �������� exept ������������ ��� ��������� ������.
                    #"exept"=>["�����"],
                    //
                    // ���� ����� ������� ������ "�������", "��������", "�����������"
                    // � ������� �� ������������ "�����"
                    // ���������� ��� ���������.
                    //
                    // � ������ ������ ���� � �������� ������������ [�������, �����]
                    // ����� ������ �� �����.

                    // ������ ������ ...
                    "only"=>["�������", "��������", "�����������"],
                    // ������ ��� ����� ...
                    "exept"=>["�����"],
                ],

                // ��������
                "quality"=>
                    [
                    // ����� ������ �������� �� ��� ��� � �����, � ������ ������
                    // ��������� only ���� exept, ������������ "only" ��� �������
                    // ��� ������� �����������������, ������ �� ��������.
                    // ������ ������ ...
                    #"only"=>["HDRip", "DVDRip" ,"WEB-DLRip" , "BDRip"],
                    // ������ ��� ����� ...
                        "exept"=>["CAMRip"],
                    ],

                // ������
                "size"=>
                    [
                        // ��������:
                        // ���� ������ �� 1 GB - "min"=>"1Gb",
                        // ���������������� "max"=>"3Gb"
                        //
                        // ���� ������ �� 2 GB - "max"=>"2Gb",
                        // ���������������� "min"=>"1Gb"
                        //
                        // ���� ������ �� 1 GB �� 2 GB
                        // ������������ ��� ���������
                        "min"=>"1Gb",
                        "max"=>"3Gb",
                    ],

                // �������� ����� ( ��� ������ �� ���������� ���� )
                // ���� ������� �� ������
                // ��������: ������� �� �������� �����
                "stopWord"=>[""],

                // ����
                "seed"=>
                    [
                         // ���������� ����������� ���������� �����
                        "min"=>"1",
                    ],

                // ���������
                // ���� ������� ������ � ����� ������
                // ��������: ������� �������� �����
                "travelCard"=>[""],
        ],
];
```

������� �2.
```php
php create_theme_config.php 5
```
� ������ ������ � ���������� ������������� ���, �� ��������� themeSpy,
�������� ���� ������ ���� 1458574015.filtr.php

������� �3.
```php
php create_theme_config.php 1 NameTopic
```
� ������ ������ � ���������� ������������� ���, �� ��������� themeSpy,
�������� ���� ���� NameTopic.filtr.php
����� ���� ���� �� ������ 1 �������� 11, �� �� ����� ����� ������ ���� ���� �
������ NameTopic.filtr.php. ������� �� ���������� ������ ��������.

Web
---
�����������, ��� ��������� �������� ����� ������� - web/index.php

�������������
-------

����������� ������
-------
```php
php run_once.php
```
��� � Windows ������
```php
run_once.cmd
```

������������ ������
-------
```php
php run_service.php
```
��� � Windows ������
```php
run_service.cmd
```

� ������ �������
-------
��� *nix
�������� � ������� run_service.php

��� Windows
��������� ��������� http://nssm.cc/usage
������������� ������
```php
nssm.exe install RuTrackerSpy "���� �� run_service.cmd"
```
��������:
```php
nssm.exe install RuTrackerSpy "C:\RutrackerSpy\run_service.cmd"
```

���������
-----------------
1. ���������� yii2 ���������.
2. ������� ����� ����� ������ � ����� ������ �� ������ yii2.
3. �������� ��� ��������� �� ������ yii2.
4. ��������� ����������� �������� ������� �������.


��� ����� ���������/��������
----------------------------

1. ���������� �� nameRusHash.