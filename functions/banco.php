<?php
// $banco = new mysqli("localhost:3307", "lucas", "", "projeto");
// $banco = new mysqli("localhost:3307", "root", "", "projeto");
$banco = new mysqli("localhost:3307", "Levoratech", "342711Lu#3427", "levoratech");



function createOnDB($into, $value)
{
    global $banco;

    $q = "INSERT INTO $into VALUES $value";
    if (!$banco->query($q)) {
        throw new Exception($banco->error);
    }
}

function updateOnDB($data, $set, $where)
{
    global $banco;

    $q = "UPDATE $data SET $set WHERE $where";
    if (!$banco->query($q)) {
        throw new Exception($banco->error);
    }
}


function criarUsuario($usuario, $nome, $senha, $cpf, $saldo = 1000, $usd = 0, $eur = 0)
{
    $senha = password_hash($senha, PASSWORD_DEFAULT);
    createOnDB('usuarios', "(NULL, '$usuario', '$nome', '$cpf', '$senha', $saldo, $usd, $eur,0,0,0,0)");
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

function usuarioExiste($login)
{
    global $banco;

    $q = "SELECT id FROM usuarios WHERE login = '$login' LIMIT 1";
    $resp = $banco->query($q);

    return $resp && $resp->num_rows > 0;

}
?>