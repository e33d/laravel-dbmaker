## DBMaker integration for Laravel Framework
This integration allows the use of <b>DBMaker</b> php function with Laravel framework instead of PDO.<br>
It emulates PDO class used by Laravel.

### # How to install
> `composer require dbmaker/laravel-odbc` To add source in your project

### # Usage Instructions
It's very simple to configure:

**1) Add database to database.php file**
```PHP
'dbmaker' => [
    'driver' => 'odbc',
    'dsn' => 'odbc:DSN=DBNAME',
    'database' => 'DBNAME',
    'host' => 'localhost',
    'username' => 'username',
    'password' => 'password',
    'options' => [
            'dbidcap' => 1
    ]
]
```

**2) set default database to dbmaker**
```PHP
'default' =>  'dbmaker',
```


**3) install php_odbc for dbmaker**

We suggest using the libary we build

1. Download URL  https://github.com/dbmaker-go/php_ext/releases
2. rename pdo_odbc.ini to 20-pdo_odbc.ini and move to /etc/php.d/
3. copy pdo_odbc.so to /usr/lib64/php/modules/
4. ```php -m``` Check if the installation was successful
5. restart php service


**4) testing**

```
# php artisan make:command MyCommand
```

```
# vi app/Console/Commands/MyCommand.php
```

find 
```
protected $signature = 'command:name';
```

change to
```
protected $signature = 'my:command';
```

and add test code
```
public function handle()
{
    $data= \DB::table('TA1')->get('C1');
	print_R($data);
}
```

```
# vi app/Console/Kernel.php
```

```
protected $commands = [
    // ...
    Commands\MyCommand::class,  //add this
];
```

run
```
# php artisan my:command
```


laravel DB Usage

Consult the <a href="http://laravel.com/docs" rel="nofollow">Laravel framework documentation</a>
