<?php

class Links_controller extends Controller {

    public function __construct() {
        parent::__construct();
        $this->Verificar_Session();
    }

    public function agregar() {
        $this->Verifica_POST();
        $data = $this->Verifica_JSON(file_get_contents("php://input"));
        $links = new Links(
                $id = null,
                $id_empresa = $data->id_empresa,
                $ramo = $data->ramo,
                $url = $data->url,
                $usuario = $data->usuario,
                $password = $data->password,
                $observacion = $data->observacion,
                $fecha_hora = fecha_hora,
                $id_usuario = Auth_controller::SessionId(),
        );
        $resultado = $links->create();
        echo $this->json($resultado['error']);
    }

    public function mostrar() {
        $links = Links::getAll();
        $links = FuncionesArray::groupArray($links, 'id_empresa');
        foreach ($links as $key => $value) {
            $empresas = EmpresasSeguros::select('id,nombre,ruc,logo')->where([['id', $value['id_empresa']]])->run();
            $links[$key]['id_empresa'] = $empresas[0];
        }
        echo $this->json($links);
    }

    public function editar($parametros) {
        
        $this->Verifica_POST();
        $data = $this->Verifica_JSON(file_get_contents("php://input"));
        $links = Links::getById($parametros[0]);
        $links->setId_empresa($data->id_empresa);
        $links->setRamo($data->ramo);
        $links->setUrl($data->url);
        $links->setUsuario($data->usuario);
        $links->setPassword($data->password);
        $links->setObservacion($data->observacion);
        $links->setFecha_hora(fecha_hora);
        $links->setId_usuario(Auth_controller::SessionId());
        $resultado = $links->update();
        echo $this->json($resultado['error']);
    }

}
