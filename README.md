PHP CLASS MAP AUTOLOADER [en]
=============================
Script for generating autoloader based on the map of classes in your project.
Support namespace, trait.

REQUIREMENTS
------------
PHP >= 5.3


USAGE
-------------
Just download [autoload.phar](https://github.com/dmkuznetsov/php-autoloader/raw/master/bin/autoload.phar)
and run next command in terminal:

`php autoload.phar --file=/path/to/file_for_autoload.php --dir=/path/to/dir/where/php/files`

Script will create file "/path/to/file_for_autoload.php" with autoloader. Just include this in your project:

```php
<?php
include '/path/to/file_for_autoload.php';
```

By default, script will generate relative paths. If you need absolute paths - use next command:

`php autoload.phar --file=/path/to/file_for_autoload.php --dir=/path/to/dir/where/php/file --absolute-path`



PHP CLASS MAP AUTOLOADER [ru]
=============================
Скрипт для генерации автозагрузчика на основании карты классов вашего проекта.
Поддерживает неймспейсы и трейты.

ТРЕБУЕТСЯ
---------
PHP >= 5.3


КАК ПОЛЬЗОВАТЬСЯ
----------------
Скачайте [autoload.phar](https://github.com/dmkuznetsov/php-autoloader/raw/master/bin/autoload.phar)
и выполните команду в консоли:

`php autoload.phar --file=/path/to/file_for_autoload.php --dir=/path/to/dir/where/php/file`

Скрипт создаст файл "/path/to/file_for_autoload.php" (если это возможно) с автозагрузчиком. Просто подключите его в вашем проекте:

```php
<?php
include '/path/to/file_for_autoload.php';
```

По-умолчанию, скрипт записывает относительные пути. Если вам нужно, чтобы были сгенерированы абсолютные пути - используйте команду:

`php autoload.phar --file=/path/to/file_for_autoload.php --dir=/path/to/dir/where/php/file --absolute-path`

