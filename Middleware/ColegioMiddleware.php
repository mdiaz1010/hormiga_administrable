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

    public function consultar_docentes()
    {
        $list_grado_seccion= $this->ColegioInterfase->consultar_grado_seccion();
        foreach($list_grado_seccion as $key => $grado_seccion):
            $list_aula     = $this->ColegioInterfase->consultar_aula($grado_seccion['id_grado'],$grado_seccion['id_seccion']);
        endforeach;
        $list_notas     = $this->ColegioInterfase->consultar_notas();
        $list_bimestre  = $this->ColegioInterfase->consultar_bimestre();

    }
    public function consultar_profesor()
    {

        $update = $this->ColegioInterfase->consultar_docentes();
    }
}

?>