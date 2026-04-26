<?php 

namespace Model;

class Proyecto extends ActiveRecord{
    public static $tabla = 'proyectos';
    public static $columnasDB = ['id', 'proyecto', 'url', 'propietarioId'];

    public $id;
    public $proyecto;
    public $url;
    public $propietarioId;

    public function __construct($args = []){
        $this->id = $args['id'] ?? NULL;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propietarioId = $args['propietarioId'] ?? '';
    }

    public function validarProyecto(){
        if(!$this->proyecto){
            self::$alertas['error'][] = 'El nombre del Proyecto es obligatorio';
        }

        return self::$alertas;
    }
    
    public function crearUrl(){
        $this->url = md5(uniqid());
    }
}