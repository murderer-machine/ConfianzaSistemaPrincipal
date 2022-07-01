<?php

class Links extends Model {

    protected static $table = "t_links";
    public $id;
    public $id_empresa;
    public $ramo;
    public $url;
    public $usuario;
    public $password;
    public $observacion;
    public $fecha_hora;
    public $id_usuario;

    function __construct($id = '', $id_empresa = '', $ramo = '', $url = '', $usuario = '', $password = '', $observacion = '', $fecha_hora = '', $id_usuario = '') {
        $this->id = $id;
        $this->id_empresa = $id_empresa;
        $this->ramo = $ramo;
        $this->url = $url;
        $this->usuario = $usuario;
        $this->password = $password;
        $this->observacion = $observacion;
        $this->fecha_hora = $fecha_hora;
        $this->id_usuario = $id_usuario;
    }

    public function getId() {
        return $this->id;
    }

    public function getId_empresa() {
        return $this->id_empresa;
    }

    public function getRamo() {
        return $this->ramo;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getObservacion() {
        return $this->observacion;
    }

    public function getFecha_hora() {
        return $this->fecha_hora;
    }

    public function getId_usuario() {
        return $this->id_usuario;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setId_empresa($id_empresa): void {
        $this->id_empresa = $id_empresa;
    }

    public function setRamo($ramo): void {
        $this->ramo = $ramo;
    }

    public function setUrl($url): void {
        $this->url = $url;
    }

    public function setUsuario($usuario): void {
        $this->usuario = $usuario;
    }

    public function setPassword($password): void {
        $this->password = $password;
    }

    public function setObservacion($observacion): void {
        $this->observacion = $observacion;
    }

    public function setFecha_hora($fecha_hora): void {
        $this->fecha_hora = $fecha_hora;
    }

    public function setId_usuario($id_usuario): void {
        $this->id_usuario = $id_usuario;
    }

    public function getMyVars() {
        return get_object_vars($this);
    }

}
