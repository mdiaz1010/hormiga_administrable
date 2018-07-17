<?php

namespace HormigaMiddleware;

require dirname(__DIR__) . '/vendor/autoload.php';

use League\CLImate\CLImate;
use League\Csv\Reader;
use Faker\Factory;
use ReflectionClass;
use ReflectionMethod;

class ColegioMiddleware extends Middleware
{

    public $csv         = null;
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

    public function generacion_registros_docentes()
    {
        $list_grado_seccion= $this->ColegioInterfase->consultar_grado_seccion();
        $i=0;
        $j=0;
        foreach($list_grado_seccion as $key => $grado_seccion):
            $list_aula     = $this->ColegioInterfase->consultar_aula($grado_seccion['id_grado'],$grado_seccion['id_seccion']);
            $list_bimestre  = $this->ColegioInterfase->consultar_bimestre();
            $cant_bimestre  = count($list_bimestre);

            foreach ($list_aula as $keys => $value) {
                $list_notas     = $this->ColegioInterfase->consultar_notas($value['id_curso']);
                $list_notas_id  = array_column($list_notas,'id');
                foreach ($list_notas_id as $clave => $notas) {
                    if($clave<$cant_bimestre)
                    {
                        $abreviacion = 'AA';
                        $j=1;
                    }else
                    {
                        $i++;
                        $cant_bimestre= $cant_bimestre + count($list_bimestre);
                    }
                    $list_rel_notas= array(

                                                'id_grado' =>$grado_seccion['id_grado'],
                                                'id_curso' =>$value['id_curso'],
                                                'id_nota' =>$notas,
                                                'id_profesor' =>$value['id_profesor'],
                                                'ano' =>date('Y'),
                                                'abreviacion' =>$abreviacion.$i,
                                                'descripcion' =>$abreviacion.$i,
                                                'peso' =>1,
                                                'usu_creacion' =>'mdiaz',
                                                'fec_creacion' =>date('Y-m-d H:i:s'),
                                                'estado' =>1,

                                    );


                    $this->ColegioInterfase->insertar_rel_notas_detalle($list_rel_notas);
                }

            }
        endforeach;


    }
    public function consultar_profesor()
    {

        $update = $this->ColegioInterfase->consultar_docentes();
    }
}

?>