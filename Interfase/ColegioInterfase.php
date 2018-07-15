<?php

namespace ColegioMiddleware;

require dirname(__DIR__) . '/vendor/autoload.php';


use League\CLImate\CLImate;
use Faker\Factory;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
#use Witchcraft\MagicMethods;
use Dotenv;
use League\Pipeline\Pipeline;
use MysqliDb;
use HormigaMiddleware\ConstantsInterfase;

class ColegioInterfase implements ConstantsInterfase
{
    public $database = null;
    public $climate = null;
    public $log = null;
    public $faker = null;
    public function __construct($params = [])
    {

        $this->log = $params['log'];
        $this->climate = $params['climate'];
        $this->faker = Factory::create();

        $this->database = new MysqliDb([
            'host'      => $_ENV['DB_HOST'],
            'username'  => $_ENV['DB_USERNAME'],
            'password'  => $_ENV['DB_PASSWORD'],
            'db'        => $_ENV['DB_NAME'],
            'charset'   => 'utf8'
        ]);
    }

    public function consultar_docentes()
    {

        $cols = Array ("id_persona", "nom_usuario", "clav_usuario");
        $db->where ("role_usuario",4);
        $users = $db->get ("maeusuarios", null, $cols);
        if ($db->count > 0)
            foreach ($users as $user) {
                $this->climate->out("Nombre: {$user['nom_usuario']}");
            }
    }

}

?>