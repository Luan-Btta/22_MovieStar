<?php

require_once("globals.php");
require_once("db.php");
require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);

//VERIFCAR TIPO DO FORM
$type = filter_input(INPUT_POST, "type");

if($type == "register"){
    $email = filter_input(INPUT_POST, "email");
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    //VERIFICAR DADOS MÍNIMOS
    if($name && $lastname && $email && $password){
        //VERIFICAR SE AS SENHAS BATEM
        if($password === $confirmpassword){
            
            //VERIFICAR SE O EMAIL JÁ ESTÁ CADASTRADO NO SISTEMA
            if($userDao->findByEmail($email)===false){
                $user = new User();

                //CRIAÇÃO DE TOKEN
                $userToken = $user->generateToken();
                $finalPassoword = $user->generatePassword($password);
                // OU direto $finalPassoword = password_hash($password, PASSWORD_DEFAULT);

                $user->email = $email;
                $user->name = $name;
                $user->lastname = $lastname;
                $user->password = $finalPassoword;
                $user->confirmpassword = $confirmpassword;
                $user->token = $userToken;

                $auth = true;

                $userDao->create($user, $auth);

            }else{
                $message->setMessage("E-mail já cadastrado.", "error", "back");
            }

        }else{
            $message->setMessage("As senhas não coincidem.", "error", "back");
        }
    }else {
        //ENVIAR MENSAGEM DE ERRO, DADOS FALTANTES
        $message->setMessage("Por favor preeencha todos os campos.", "error", "back");
    }
    

}else if ($type == "login"){
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");

    //TENTA AUTENTICAR O USER, CASO CONTRÁRIO REDIRECIONA PARA NOVA TENTATIVA
    if(!$userDao->authenticateUser($email, $password)){
        $message->setMessage("Login incorreto, tente novamente.", "error", "back");
    }
}