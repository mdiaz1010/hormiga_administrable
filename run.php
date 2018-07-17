<?php
require __DIR__ . '/vendor/autoload.php';
use League\CLImate\CLImate;
use HormigaMiddleware\CoLegioMiddleware;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Whoops\Handler\Handler;
use Whoops\Exception\Formatter;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();


$climate = new CLImate();

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler);

$climate->arguments->add(
    [
        'help' => [
            'longPrefix'  => 'help',
            'description' => 'Prints a usage statement',
            'noValue'     => true,
        ],
        'verbose' => [
            'prefix'      => 'v',
            'description' => 'Verbose output',
            'noValue'     => true,
        ],
        'verbose2' => [
            'prefix'      => 'vv',
            'description' => 'Verbose output level 2',
            'noValue'     => true,
        ],
        'timeout' => [
            'prefix' => 't',
            'longPrefix' => 'timeout',
            'description' => 'Tiempo máximo de espera',
            'castTo' => 'int',
            'defaultValue' => 30
        ],
        'offset' => [
            'prefix'      => 'o',
            'description' => 'Desde',
        ],
        'limit' => [
            'prefix'      => 'l',
            'longPrefix'  => 'limit',
            'description' => 'Limite',
            'defaultValue' => 2000
        ],
        'input' => [
            'prefix'      => 'i',
            'description' => 'Nombre del archivo CSV',
        ],
        'log' => [
            'prefix'      => 'log',
            'description' => 'Nombre del archivo LOG',
            'defaultValue' => './logs/' . uniqid(time() . '_') . '.log'
        ],
        'middleware' => [
            'description' => 'Nombre del Middleware',
        ],
        'function' => [
            'description' => 'Nombre de la función a ejecutar',
        ],
    ]
);

$climate->arguments->parse();

if (!($response = $climate->arguments->get('middleware')))
{
        $input = $climate->input('Seleccionar Middleware: ');
        $input->accept(['Colegio', 'Blog'], true);
        $response = $input->prompt();
}

$log = new Logger('log');
$log->pushHandler(new StreamHandler($climate->arguments->get('log'), Logger::WARNING));
$response = sprintf('\HormigaMiddleware\%sMiddleware', $response);
$params = [
        'climate'   => $climate,
        'log'       => $log,
          ];

(new $response($params))->run();
?>