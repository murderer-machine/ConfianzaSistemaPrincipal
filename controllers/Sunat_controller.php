<?php

class Sunat_controller extends Controller {

    public function __construct() {
        parent::__construct();
        //$this->Verificar_Session();
    }

    public function Datos2() {
        require(URLCOMPOSER_VENDOR . "jossmp/sunatphp/src/autoload.php");
        $cookie = array(
            'cookie' => array(
                'use' => true,
                'file' => __DIR__ . "/cookie.txt"
            )
        );
        $config = array(
            'representantes_legales' => true,
            'cantidad_trabajadores' => true,
            'establecimientos' => true,
            'cookie' => $cookie
        );

        $sunat = new \Sunat\ruc($config);

        $ruc = "20169004359";
        $dni = "44274795";

        $search1 = $sunat->consulta($ruc);
        $search2 = $sunat->consulta($dni);
    }

    public function Datos($nrodoc = '', $return = 'n') {
        if (empty($nrodoc)) {
            Controller::Verifica_POST();
            $resultado = $this->Verifica_JSON(file_get_contents("php://input"));
            $ruc_data = $resultado->nrodoc;
        } else {
            Controller::Verifica_GET();
            $ruc_data = $nrodoc;
        }

        require(URLCOMPOSER_VENDOR . "jossmp/sunatphp/src/autoload.php");
        $company = new \Sunat\Sunat();
        $ruc = $ruc_data;

        $search = $company->search($ruc);

        if (empty($search['success'])) {
            $resultado = (object) array();
            if ($return == 's') {
                return $resultado;
            } else {
                echo json_encode($resultado, JSON_PRETTY_PRINT);
            }
            exit;
        }
        if ($search['success'] == 1) {
            if ($search['result']['Direccion'] == '-' || $search['result']['Direccion'] == '') {
                $datos_obtenidos = array(
                    'Direccion_corregida' => '',
                    'id' => '',
                    'ubi_departamento' => '',
                    'ubi_provincia' => '',
                    'ubi_distrito' => ''
                );
            } else {
                $datos_obtenidos = Sunat_controller::separar_direccion($search['result']['Direccion']);
            }
            $resultado = array_merge($search, $datos_obtenidos);
            if ($return == 's') {
                return $resultado;
            } else {
                echo json_encode($resultado, JSON_PRETTY_PRINT);
            }
        }
    }

    public function separar_direccion($datos) {
        $direccion = trim($datos);
        $variable = explode("-", trim($direccion));
        $variable = array_reverse($variable);
        $conteo = count($variable) - 1;
        $deparmanento_separar = explode(" ", trim($variable[2]));
        $conto_depa = count($deparmanento_separar) - 1;
        $departamento = trim($deparmanento_separar[$conto_depa]);
        $provincia = trim($variable[$conteo - $conteo + 1]);
        $distrito = trim($variable[$conteo - $conteo]);

        $ubigeo = Ubigeo::select()->where([['ubi_departamento', $departamento], ['ubi_provincia', $provincia], ['ubi_distrito', $distrito]])->run();

        $juntar = '';
        for ($i = 0; $i <= $conto_depa - 1; $i++) {
            $juntar = $juntar . ' ' . $deparmanento_separar[$i];
        }
        $datos_final = array(
            'Direccion_corregida' => trim($juntar),
            'id' => $ubigeo[0]['id'],
            'ubi_departamento' => $departamento,
            'ubi_provincia' => $provincia,
            'ubi_distrito' => $distrito
        );
        return $datos_final;
    }

}
