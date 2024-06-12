<?php
$banco = new mysqli("localhost:3306", "root", "", "projeto");

function createOnDB($into, $value)
{
    global $banco;

    $q = "INSERT INTO $into VALUES $value";
    $banco->query($q);
}

function updateOnDB($data, $set, $where)
{
    global $banco;

    $q = "UPDATE $data SET $set WHERE $where";
    $banco->query($q);
}


function criarUsuario($usuario, $nome, $senha)
{
    $senha = password_hash($senha, PASSWORD_DEFAULT);
    createOnDB('usuarios', "(NULL, '$usuario', '$nome', '$senha')");
}

function atualizarUsuario($usuario, $nome = "", $senha = "")
{
    if ($nome != "" && $senha != "") {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $set = "nome='$nome', senha='$senhaHash'";
    } else if ($nome != "") {
        $set = "nome='$nome'";
    } else if ($senha != "") {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $set = "senha='$senhaHash'";
    }

    updateOnDB('usuarios', $set, "usuario='$usuario'");
}

function loginDB($login, $senha)
{
    global $banco;

    $pass = password_hash($senha, PASSWORD_DEFAULT);

    $q = "SELECT id FROM usuarios WHERE login= '$login' AND senha= '$pass' LIMIT 1";
    $resp = $banco->query($q);

    return $resp->fetch_row();
}
