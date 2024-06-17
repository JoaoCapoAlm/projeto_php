<?php
class Usuario {
    public $id;
    public $login;
    public $nome;
    public $cpf;
    public $senha;
    public $saldo;
    public $USD;
    public $EUR;
    public $GBP;
    public $JPY;
    public $AUD;
    public $CAD;

    public function __construct($id, $login, $nome, $cpf, $senha, $saldo, $USD, $EUR,  $GBP, $JPY, $AUD, $CAD) {
        $this->id = $id;
        $this->login = $login;
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->senha = $senha;
        $this->saldo = $saldo;
        $this->USD = $USD;
        $this->EUR = $EUR;
        $this->GBP = $GBP;
        $this->JPY = $JPY;
        $this->AUD = $AUD;
        $this->CAD = $CAD;
    }
}
?>
