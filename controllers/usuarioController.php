<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//AQUI TENGO TODO COMPLETO PARA QUE SIRVA CUANDO NOS LOGUEAMOS LA PARTE QUE SE CREA LA NUEVA CONTRASEÑA Y TODO ESO////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Incluir las clases necesarias
require_once '../config/database.php';
require_once '../data/usuarioDB.php';
$database = new Database();
$usuariobd = new UsuarioDB($database);
function redirigirConMensaje($url, $success, $mensaje){
    
    //almacena el resultado en la sesion
    $_SESSION['success'] = $success;
    $_SESSION['mensaje'] = $mensaje;

    //realiza la redirección
    header("location: $url");
    exit();
}

//registro usuario
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['registro'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $resultado = $usuariobd->registrarUsuario($email, $password);

    redirigirConMensaje('../admin/login.php', $resultado['success'], $resultado['mensaje']);
}

//Inicio de sesión
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $resultado = $usuariobd->verificarCredenciales($email, $password);
    $_SESSION['logueado'] = $resultado['success'];

    if($resultado['success']){
        $_SESSION['usuario'] = $resultado['usuario'];
        $_SESSION['user_id'] = $resultado['usuario']['id']; // Añadido: Guardar el ID del usuario
        $ruta = '../reservas.php';
    }else{
        $ruta = '../login.php';
    }
    redirigirConMensaje($ruta, $resultado['success'], $resultado['mensaje']);
}

//Recuperación de contraseña
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['recuperar'])){
    $email = $_POST['email'];

    $resultado = $usuariobd->recuperarPassword($email);
    redirigirConMensaje('../admin/login.php', $resultado['success'], $resultado['mensaje']);
}