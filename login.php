<?php include_once './parts/header.php'; ?>

<?php
    require_once './functions/session.php';
    include_once './functions/banco.php';

    if(estaLogado()){
        header('location: ./graficos.php');
        exit();
    }

    if(!empty($_GET['login']) && !empty($_GET['senha'])){


        var_dump(loginDB($_GET['login'], $_GET['senha']));

    }
?>


<div class="container">
    <form method="get">
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
            </div>
        </div>
    </form>
</div>
