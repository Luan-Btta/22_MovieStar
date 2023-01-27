<?php

require_once("globals.php");
require_once("db.php");
require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$user = new User();
$userData = $userDao->verifyToken();

//VERIFCAR TIPO DO FORM
$type = filter_input(INPUT_POST, "type");

//ATUALIZAR USUÁRIO
if($type == "update"){

    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $image = filter_input(INPUT_POST, "image");
    $bio = filter_input(INPUT_POST, "bio");

    $userData->name = $name;
    $userData->lastname = $lastname;
    $userData->email = $email;
    $userData->image = $image;
    $userData->bio = $bio;

    //UPLOAD DA IMAGEM
    if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])){
        $image = $_FILES["image"];
        $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
        $imageJpg = ["image/jpeg", "image/jpg"];
        
        //CHECAGEM TIPO DE IMAGEM
        if(in_array($image["type"], $imageTypes)){
            if(in_array($image["type"], $imageJpg)){
                $imageFile = imagecreatefromjpeg($image["tmp_name"]);

            //SE FOR PNG
            }else{
                $imageFile = imagecreatefrompng($image["tmp_name"]);
            }

            $imageName = $user->imageGenerateName();

            imagejpeg($imageFile, "./img/users/". $imageName, 100);

            $userData->image = $imageName;

            $userDao->update($userData);

        //TIPO INVÁLIDO DE IMAGEM
        }else{
            $message->setMessage("Tipo inválido de imagem para o perfil, use PNG ou JPG.", "error", "back");
        }
    }

    

//ATUALIZAR SENHA DO USUARIO    
}else if($type=="changepassword"){
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");
    

    if($password === $confirmpassword){
        if(password_verify($password, $userData->password)){
            $message->setMessage("Alteração não realizada, nova senha igual a atual.", "error", "back");
        }else{
            $finalPassoword = $user->generatePassword($password);
            $userData->password = $finalPassoword;
            $userDao->changePassword($userData);
        }
    }else{
        $message->setMessage("As senhas não coincidem.", "error", "back");
    }

}else{
    $message->setMessage("Informações Inválidas.", "error");
}