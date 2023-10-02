<?php 
class Clientes extends Controller{
    public function __construct() {
        session_start();
        if (empty($_SESSION['activo'])) {
            header('location: '.base_url);
        }
        parent::__construct();
    }
    public function index()
    {
        //$data ['cajas']= $this->model->getCajas();
        $this->views->getView($this, "index"); //,$data);
    }
    public function listar()
    {
        $data= $this->model->getClientes();
        for ($i=0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado']= '<span class="badge badge-success">Activo</span>';
                $data[$i]['acciones']= '<div>
                <button class="btn btn-warning" type="button" onclick="btnEditarCli('.$data[$i]['id'].');"><i class="fas fa-edit"></i></button>
                <button class="btn btn-danger" type="button" onclick="btnEliminarCli('.$data[$i]['id'].');"><i class="fas fa-trash-alt"></i></button>
                <div/>';
            }else{
                $data[$i]['estado']= '<span class="badge badge-danger">Inactivo</span>';
                $data[$i]['acciones']= '<div>
                <button class="btn btn-success" type="button" onclick="btnReingresarCli('.$data[$i]['id'].');">Reingresar<i class="fas fa-share"></i></button>
                <div/>';
            }
            
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar()
    {
        $dni= $_POST['dni'];
        $nombre= $_POST['nombre'];
        $direccion= $_POST['direccion'];
        //$confirmar= $_POST['confirmar'];
        $telefono= $_POST['telefono'];
        $id= $_POST['id'];
        //$hash= hash("SHA256", $direccion);
        if (empty($dni) || empty($nombre) || empty($telefono) || empty($direccion)) {
            $msg= "Todos los campos son obligatorios";
        }else {
            if ($id == "") {
                    $data= $this->model->registrarCliente($dni, $nombre, $telefono, $direccion);
                    if ($data == "ok") {
                        $msg= "si";
                    }else if($data == "Existe"){
                        $msg= "El dni ya existe";
                    }else{
                        $msg= "Error al registrar el cliente";
                    }
            }else{
                $data= $this->model->modificarCliente($dni, $nombre, $telefono, $direccion, $id);
                    if ($data == "modificado") {
                        $msg= "modificado";
                    }else{
                        $msg= "Error al modificar el cliente";
                    }
            }
            
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function editar(int $id)
    {
        $data= $this->model->editarCli($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function eliminar(int $id)
    {
        $data= $this->model->accionrCli(0, $id);
        if ($data == 1) {
            $msg= "ok";
        }else{
            $msg="Error al eliminiar al cliente";
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function reingresar(int $id)
    {
        $data= $this->model->accionrCli(1, $id);
        if ($data == 1) {
            $msg= "ok";
        }else{
            $msg="Error al reingresar el cliente";
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
?>