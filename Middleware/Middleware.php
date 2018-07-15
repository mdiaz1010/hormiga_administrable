<?php

namespace HormigaMiddleware;

use ReflectionClass;
use ReflectionMethod;
use MysqliDb;
use HormigaMiddleware\ConstantsInterfase;

/**
 * Middleware
 */
class Middleware implements ConstantsInterfase
{

    public $log = null;
    public $climate = null;
    public $database = null;

    /**
     *
     */
    public function __construct($params = [])
    {

        $this->log = $params['log'];
        $this->climate = $params['climate'];

        $this->database = new MysqliDb([
            'host'      => $_ENV['DB_HOST'],
            'username'  => $_ENV['DB_USERNAME'],
            'password'  => $_ENV['DB_PASSWORD'],
            'db'        => $_ENV['DB_NAME'],
            'charset'   => 'utf8'
        ]);
        $this->climate->backgroundBlack()->white()->out($this->climate->arguments->get('log'));
        $this->climate->br();
    }

    public function help()
    {
        $not_allow = ['__construct', 'run', 'help'];

        $list = (new ReflectionClass($this))->getMethods(ReflectionMethod::IS_PUBLIC);
        $names = [];
        foreach ($list as $name) {
            if (in_array($name->getName(), $not_allow))
                continue;

            $names[] = [
                'Nombre' => trim(str_replace(['/', '*'], '', $name->getDocComment())),
                'Funcion' => $name->getName()
            ];

        }
        $this->climate->backgroundBlack()->white()->table($names);
    }

    public function generar_notas_aleatorias()
    {

    }

    public function generar_horario()
    {

    }

}