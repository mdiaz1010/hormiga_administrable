<?php

namespace HormigaMiddleware;

require dirname(__DIR__) . '/vendor/autoload.php';

use League\CLImate\CLImate;
use Faker\Factory;
use ReflectionClass;
use ReflectionMethod;

class ColegioMiddleware extends Middleware
{
    public $climate     = null;
    public $database    = null;
    public $ColegioInterfase = null;
    public function __construct($params = [])
    {
        parent::__construct($params);
        $this->ColegioInterfase = new ColegioInterfase($params);
        $this->faker = Factory::create();

    }
    public function run() {
        if (empty($this->climate->arguments->get('function')))
            return $this->climate->usage();

        if (!method_exists($this, $this->climate->arguments->get('function'))) {
            $this->climate->animation('404')->enterFrom('bottom');
            return $this->climate->br()->error('Función no reconocida');
        }

        call_user_func([$this, $this->climate->arguments->get('function')]);
    }

    public function consular_profesor()
    {
        $update = $this->ColegioInterfase->consultar_docentes();
    }
}

?>