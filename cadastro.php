<?php 
include "./parts/header.php";
?>
  <div class="cadastro">
    <h3>Realize o seu cadastro</h3>
    <form action="" method="post">
      <label for="nome">Digite o seu nome: </label><br>
      <input type="text" name="nome" id="nome" required><br>
      <label for="cpf">Informe o seu CPF: </label><br>
      <input type="text" name="cpf" id="cpf" required><br>
      <label for="cadLogin">Qual será o seu login? </label><br>
      <input type="text" name="cadLogin" id="cadLogin"><br>
      <label for="senha">Crie a sua senha: </label><br>
      <input type="password" name="senha" id="senha" required><br>
      <label for="confirmaSenha">Confirme a sua senha: </label><br>
      <input type="password" name="confirmaSenha" id="confirmaSenha" required><br>
      <input type="submit" value="Enviar"><br>
      <a href="index.php">Voltar para o login</a>
      <?php

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];
        $cadLogin = $_POST['cadLogin'];
        $senha = $_POST['senha'];
        $confirmaSenha = $_POST['confirmaSenha'];

        if (!empty($senha) && !empty($confirmaSenha)) {
          if ($senha != $confirmaSenha) {
            echo "<p style='color:red;'><b>As senhas informadas não são iguais.<b><p>";
          } else {
            if (usuarioExiste($cadLogin)) {
              echo "<p style='color:red;'><b>Este login já está em uso. Por favor, escolha outro.<b><p>";
            } else {
              criarUsuario($cadLogin, $nome, $senha, $cpf);
              header('location: ./menu.php');
              exit();
            }
          }
        }

      }
      ?>
    </form>
  </div>
  <?php 
include "./parts/footer.php";
?>
