<?php 

namespace Model;

class Usuario extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_new;
    public $password_old;
    public $token;
    public $confirmado;

    public function __construct($args =[]){
        $this->id = $args['id'] ?? NULL;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_new = $args['password_new'] ?? '';
        $this->password_old = $args['password_old'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    // Validar Formulario de Cuenta Nueva
    public function validarCuentaNueva(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre de usuario es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El correo es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        if(strlen($this->password) < 8){
            self::$alertas['error'][] = 'La contraseña debe tener minimo 8 caracteres';
        }
        if($this->password !== $this->password2){
            self::$alertas['error'][] = 'Las contraseñas tienen que coincidir';
        }

        return self::$alertas;
    }

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El correo es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        return self::$alertas;
    }

    public function validarPass(){
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        if(strlen($this->password) < 8){
            self::$alertas['error'][] = 'La contraseña debe tener minimo 8 caracteres';
        }

        return self::$alertas;        
    }

    public function validarPerfil(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre es un campo obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El email es un campo obligatorio';
        }

        return self::$alertas;
    }

    public function passwordHash(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function passwordNuevo(){
        if(!$this->password_new){
            self::$alertas['error'][] = 'El campo de contraseña nueva no puede ir vacio';
        }
        if(strlen($this->password_new) < 8){
            self::$alertas['error'][] = 'La nueva contraseña debe tener un minimo de 8 caracteres';
        }
        if(!$this->password_old){
            self::$alertas['error'][] = 'El campo de contraseña actual no puede ir vacio';
        }

        return self::$alertas;
    }

    public function comprobarPassword(){
        return password_verify($this->password_old, $this->password);
    }

    public function crearToken(){
        $this->token = uniqid();
    }

}