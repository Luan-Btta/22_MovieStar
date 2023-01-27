<?php

require_once("globals.php");
require_once("db.php");
require_once("models/Movie.php");
require_once("models/Message.php");
require_once("models/Review.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");
require_once("dao/ReviewDAO.php");

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$userData = $userDao->verifyToken();
$movieDao = new MovieDAO($conn, $BASE_URL);
$reviewDao = new ReviewDAO($conn, $BASE_URL);

$type = filter_input(INPUT_POST, "type");

if($type === "create"){
    $rating = filter_input(INPUT_POST, "rating");
    $review = filter_input(INPUT_POST, "review");
    $movies_id = filter_input(INPUT_POST, "movies_id");
    
    $reviewObj = new Review();
    $movieData = $movieDao->findById($movies_id);

    
    if($movieData){
        if(!empty($rating) && !empty($review)){
            $reviewObj->review = $review;
            $reviewObj->rating = $rating;
            $reviewObj->movies_id = $movies_id;
            $reviewObj->user_id = $userData->id;
            
            $reviewDao->create($reviewObj);

        }else{
            $message->setMessage("É necessário nota e comentário para prosseguir.", "error", "back");
        }
    }else{
        $message->setMessage("Informações inválidas.", "error");
    }



}else{
    $message->setMessage("Informações inválidas.", "error");
}