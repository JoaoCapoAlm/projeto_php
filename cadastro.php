<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Cadastro</title>
</head>

<body>
  <div class="cadastro">
    <h3>Realize o seu cadastro</h3>
    <form action="" method="post">
      <label for="nome">Digite o seu nome: </label><br>
      <input type="text" name="nome" id="nome" required><br>
      <label for="sobrenome">Digite o seu sobrenome: </label><br>
      <input type="text" name="sobrenome" id="sobrenome" required><br>
      <label for="cpf">Informe o seu CPF: </label><br>
      <input type="text" name="cpf" id="cpf" required><br>
      <label for="senha">Crie a sua senha: </label><br>
      <input type="password" name="senha" id="senha" required><br>
      <label for="confirmaSenha">Confirme a sua senha: </label><br>
      <input type="password" name="confirmaSenha" id="confirmaSenha" required><br>
      <input type="submit" value="Enviar"><br>
    </form>
  </div>
</body>

</html>