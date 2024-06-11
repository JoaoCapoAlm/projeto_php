<?php
  session_start();

  function estaLogado(){
    return isset($_SESSION['user_id']);
  }

  function login($user_id){
    $_SESSION['user_id'] = $user_id;
  }

  function logout(){
    session_destroy();
  }



?>