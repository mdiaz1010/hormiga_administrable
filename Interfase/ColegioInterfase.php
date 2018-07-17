<?php

namespace HormigaMiddleware;

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


    public function consultar_grado_seccion()
    {
        $db=$this->database;
        $cols = Array ("id_grado,id_seccion");
        $db->orderBy("id_grado","asc");
        $db->orderBy("id_seccion","asc");
        $db->where ("estado",1);
        $users =  $db->setQueryOption('DISTINCT')->get ("relaula", null, $cols);
        if ($db->count > 0)
            return $users;
    }
    public function consultar_aula($grado,$seccion)
    {
        $db=$this->database;
        $cols = Array ("id_curso,id_profesor");
        $db->orderBy("id_curso","asc");
        $db->orderBy("id_profesor","asc");
        $db->where ("estado",1);
        $db->where ("id_grado",$grado);
        $db->where ("id_seccion",$seccion);
        $users =  $db->setQueryOption('DISTINCT')->get ("relaula", null, $cols);
        if ($db->count > 0)
            return $users;

            #var_dump(array_values(array_unique($grado))); die();
    }

    public function consultar_notas($curso)
    {
        $db=$this->database;
        $cols = Array ("ma.id,ma.nom_notas,ma.des_notas");
        $db->where ("rl.estado",1);
        $db->where ("rl.ano",date('Y'));
        $db->where ("rl.id_curso",$curso);
        $db->join("rel_curso_nota rl", "ma.id=rl.id_nota", "INNER");
        return $db->get ("maenotas ma", null, $cols);
    }

    public function consultar_bimestre()
    {
        $db=$this->database;
        $cols = Array ("id,nom_bimestre");
        $db->where ("ano",date('Y'));
        return $db->get ("maebimestre ma", null, $cols);
    }

    public function insertar_rel_notas_detalle($list_detalle)
    {
        $message_ok = self::IS_OK . " Notas en el grado ".$list_detalle['id_grado']." , curso ".$list_detalle['id_curso'];
        $db=$this->database;
        $rel_notas_detalle = $db->insert ('rel_notas_detalle', $list_detalle);
        if ($rel_notas_detalle)
            $this->climate->info($message_ok);
        else
            $this->climate->info(self::IS_KO.' '.$db->getLastError());
    }
}

?>