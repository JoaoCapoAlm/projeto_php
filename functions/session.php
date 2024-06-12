<?php
session_start();

function estaLogado(): bool
{
    return isset($_SESSION['user_id']);
}

function login($user_id): void
{
    $_SESSION['user_id'] = $user_id;
}

function logout(): void
{
    session_destroy();
}