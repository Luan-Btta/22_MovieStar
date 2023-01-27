<?php

require_once("models/Review.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

class ReviewDAO implements ReviewDAOInterface {
    private $conn;
    private $url;
    private $messege;

    public function __construct(PDO $conn, $url){
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }
    
    public function buildReview($data){
        $reviewObj = new Review();
        $reviewObj->id = $data["id"];
        $reviewObj->rating = $data["rating"];
        $reviewObj->review = $data["review"];
        $reviewObj->user_id = $data["user_id"];
        $reviewObj->movies_id = $data["movies_id"];

        return $reviewObj;
    }

    public function create(Review $review){
        $stmt = $this->conn->prepare("INSERT INTO reviews (rating, review, user_id, movies_id) VALUES (:rating, :review, :user_id, :movies_id)");

        $stmt->bindParam(":rating", $review->rating);
        $stmt->bindParam(":review", $review->review);
        $stmt->bindParam(":user_id", $review->user_id);
        $stmt->bindParam(":movies_id", $review->movies_id);

        $stmt->execute();

        $this->message->setMessage("Comentário adicionado com sucesso.", "success", "back");
    }
    
    public function getMovieReviews($id){
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movies_id = :movies_id");
        
        $stmt->bindParam(":movies_id", $id);
        $stmt->execute();
        $reviews = [];

        if($stmt->rowCount()>0){
            $reviewData = $stmt->fetchAll();
            $userDao = new UserDAO($this->conn, $this->url);

            foreach ($reviewData as $reviewIt){
                $userData = $userDao->findById($reviewIt["user_id"]);
                $reviewObj = $this->buildReview($reviewIt);
                $reviewObj->user = $userData;

                array_push($reviews, $reviewObj);
            }
            
            return $reviews;

        }else{
            return false;

        }        
    }

    public function getMoviesReview($id){

    }
    
    public function hasAlreadyReviewed($id, $user_id){
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movies_id = :movies_id AND user_id = :user_id");
        
        $stmt->bindParam(":movies_id", $id);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        if($stmt->rowCount()>0){
            return true;

        }else{
            return false;

        }        
    }
    
    public function getRatings($id){
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movies_id = :movies_id");
        
        $stmt->bindParam(":movies_id", $id);
        $stmt->execute();
        $totalRating = 0;

        if($stmt->rowCount()>0){
            $reviews = $stmt->fetchAll();
            foreach ($reviews as $reviewIt){
                $totalRating += $reviewIt["rating"];
            }
            return number_format($totalRating / count($reviews),1);
        }else{
            return "Sem Avaliações";

        }        
    }
    
}