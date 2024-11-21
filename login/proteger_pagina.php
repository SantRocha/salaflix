<?php
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$secretKey = "@adfSOV{hFrH@1469]f4fR?521R4";

function proteger_pagina() {
    global $secretKey;
    
    if (!isset($_COOKIE['token'])) {
        header('Location: ../index.php');
        exit();
    }

    $token = $_COOKIE['token'];

    try {
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

        // Verifique se o token ainda é válido
        if ($decoded->exp < time()) {
            header('Location: login.php');
            exit();
        }

        // Caso o token seja válido, acessa as informações do usuário
        return $decoded->data;

    } catch (Exception $e) {
        // Caso o token seja inválido ou tenha expirado
        header('Location: ./sala.php');
        exit();
    }
}

function proteger_pagina_funcionario_ou_adm() {
    $usuario = proteger_pagina();

    if ($usuario->funcao !== 'funcionario' && $usuario->funcao !== 'adm') {
        header('Location: ./sala.php');
        exit();
    }

    return $usuario; 
}

function proteger_pagina_adm() {
    $usuario = proteger_pagina();

    if ($usuario->funcao !== 'adm') {
        header('Location: ./sala.php');
        exit();
    }

    return $usuario;
}
?>