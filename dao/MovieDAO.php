<?php

require_once("models/User.php");
require_once("models/Movie.php");
require_once("models/Message.php");
require_once("dao/ReviewDAO.php");

class MovieDAO implements MovieDAOInterface {

    private $conn;
    private $url;
    private $message;
    
    public function __construct(PDO $conn, $url){
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }

    public function buildMovie($data){
        $movie = new Movie();

        $movie->id = $data["id"];
        $movie->title = $data["title"];
        $movie->description = $data["description"];
        $movie->image = $data["image"];
        $movie->trailer = $data["trailer"];
        $movie->category = $data["category"];
        $movie->length = $data["length"];
        $movie->user_id = $data["user_id"];

        $reviewDao =  new ReviewDAO($this->conn, $this->url);
        $movie->rating = $reviewDao->getRatings($movie->id);

        return $movie;
    }

    public function findAll(){

    }

    public function getLatestMovies(){
        $movies = [];

        $stmt = $this->conn->query("SELECT * FROM movies ORDER BY id DESC");
        $stmt->execute();
        
        if($stmt->rowCount()>0){
            $moviesArray = $stmt->fetchAll();

            foreach($moviesArray as $movieIt){
                array_push($movies, $this->buildMovie($movieIt));
            }
        }
        

        return $movies;
    }
    
    public function getMoviesByCategory($category){
        $movies = [];

        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE category = :category ORDER BY id DESC");
        $stmt->bindParam(":category", $category);
        $stmt->execute();
        
        if($stmt->rowCount()>0){
            $moviesArray = $stmt->fetchAll();

            foreach($moviesArray as $movieIt){
                array_push($movies, $this->buildMovie($movieIt));
            }
        }
        

        return $movies;
    }
    
    public function getMoviesByUserId($user_id){
        $movies = [];

        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE user_id = :user_id ORDER BY id ASC");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        
        if($stmt->rowCount()>0){
            $moviesArray = $stmt->fetchAll();

            foreach($moviesArray as $movieIt){
                array_push($movies, $this->buildMovie($movieIt));
            }
        }else{
            $movies = false;
        }
        
        return $movies;
    }
    
    public function findById($id){
        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE id = :id ORDER BY id ASC");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        if($stmt->rowCount()>0){
            $movieData = $stmt->fetch();
            $movie = $this->buildMovie($movieData);
        }else{
            return false;
        }
        

        return $movie;
    }
    
    public function findByTitle($title){
        $movies = [];

        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE title LIKE :title ORDER BY title ASC");
        
        $stmt->bindValue(":title", "%$title%");
        $stmt->execute();
        
        if($stmt->rowCount()>0){
            $moviesArray = $stmt->fetchAll();

            foreach($moviesArray as $movieIt){
                array_push($movies, $this->buildMovie($movieIt));
            }
        }else{
            $movies = false;
        }
        
        return $movies;
    }
    
    public function create(Movie $movie){
        $stmt = $this->conn->prepare("INSERT INTO movies (title, description, image, trailer, category, length, user_id) VALUES (:title, :description, :image, :trailer, :category, :length, :user_id)");

        $stmt->bindParam(":title", $movie->title);
        $stmt->bindParam(":description", $movie->description);
        $stmt->bindParam(":image", $movie->image);
        $stmt->bindParam(":trailer", $movie->trailer);
        $stmt->bindParam(":category", $movie->category);
        $stmt->bindParam(":length", $movie->length);
        $stmt->bindParam(":user_id", $movie->user_id);
        
        $stmt->execute();

        $this->message->setMessage("Filme cadastrado com sucesso.", "success");
    }
    
    public function update(Movie $movie){
        $stmt = $this->conn->prepare("UPDATE movies SET title = :title, description = :description, image = :image, trailer = :trailer, category = :category, length = :length WHERE id = :id");

        $stmt->bindParam(":title", $movie->title);
        $stmt->bindParam(":description", $movie->description);
        $stmt->bindParam(":image", $movie->image);
        $stmt->bindParam(":trailer", $movie->trailer);
        $stmt->bindParam(":category", $movie->category);
        $stmt->bindParam(":length", $movie->length);
        $stmt->bindParam(":id", $movie->id);
        
        $stmt->execute();

        $this->message->setMessage("Filme atualizado com sucesso.", "success", "movie.php/?id=$movie->id");
    }
    
    public function destroy($id){
        $stmt = $this->conn->prepare("DELETE FROM movies WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        $this->message->setMessage("Filme removido com sucesso.", "success", "back");
    }
    
}