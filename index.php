<?php
$paginaApenasLogado = false;
include_once './parts/header.php';
    if(estaLogado()){
        header('location: ./menu.php');
        exit();
    }

    $sucessoLogin = true;
    if(!empty($_POST['login']) && !empty($_POST['senha'])){
        $sucessoLogin = loginDB($_POST['login'], $_POST['senha']);
        if($sucessoLogin){
            header('Location: ./menu.php');
            exit();
        }
    }
?>


<div class="container">
    <form method="post">
        <div class="form-group">
            <label for="login">Login</label>
            <input type="text" id="login" name="login" />
        </div>
        <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" />
        </div>
        <div class="form-group w-100">
            <div class="w-50 mx-auto" style="text-align: center">
                
                <button type="submit">Acessar</button>
                <br>
                <a href="cadastro.php"><h4>Não tem conta? Clique aqui e crie a sua</h4></a>
            </div>
        </div>
    </form>
</div>
