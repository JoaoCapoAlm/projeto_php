<?php
// $banco = new mysqli("localhost:3307", "lucas", "", "projeto");
$banco = new mysqli("localhost:3307", "root", "", "projeto");


function createOnDB($into, $value)
{
    global $banco;

    $q = "INSERT INTO $into VALUES $value";
   echo $banco->query($q);
}

function updateOnDB($data, $set, $where)
{
    global $banco;

    $q = "UPDATE $data SET $set WHERE $where";
    $banco->query($q);
}


function criarUsuario($usuario, $nome, $senha, $cpf)
{
    $senha = password_hash($senha, PASSWORD_DEFAULT);
    createOnDB('usuarios', "(NULL, '$usuario', '$nome', '$senha', '$cpf')");
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

    $q = "SELECT id, senha FROM usuarios WHERE login = '$login' LIMIT 1";
    $resp = $banco->query($q);

    if ($resp && $resp->num_rows > 0) {
        $row = $resp->fetch_row();

        if (password_verify($senha, $row[1])) {
            sessionLogin($row[0]);
            return true;
        } else {
            echo "Usuário ou senha inválidos.";
            return false;
        }
    } else {
        echo "Usuário ou senha inválidos.";
        return false;
    }
}
