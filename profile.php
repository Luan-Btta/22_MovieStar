<?php
    require_once("templates/header.php");
    require_once("models/User.php");
    require_once("dao/UserDAO.php");
    require_once("dao/MovieDAO.php");

    $user =  new User();
    $userDao = new UserDAO($conn, $BASE_URL);
    $userData = $userDao->verifyToken(false);
    $movieDao = new MovieDAO($conn, $BASE_URL);

    $id = filter_input(INPUT_GET, "id");

    if(empty($id)){
       if(!empty($userData->id)){
            $id = $userData->id;
       }else{
            $message->setMessage("Usuário não encontrado!.", "error");
       }

    }else{
        $userData = $userDao->findById($id);

        if(!$userData){
            $message->setMessage("Usuário não encontrado!.", "error");
        }

    }

    $fullName = $user->getFullName($userData);

    if($userData->image == ""){
        $userData->image = "user.png";
    }

    //FILMES QUE O USUÁRIO ADICIONOU
    $userMovies = $movieDao->getMoviesByUserId($id);
    
?>

<div class="container-fluid" id="main-container">
    <div class="col-md-8 offset-md-2">
        <div class="row" id="profile-container">
            <div class="col-md-12" id="about-container">
                <h1 class="page-title"><?= $fullName ?></h1>
                <div id="profile-image-container"
                    style="background-image: url('<?= $BASE_URL ?>/img/users/<?= $userData->image ?>');">
                </div>
                <h3 class="about-title">Sobre:</h3>
                <?php if(!empty($userData->bio)): ?>
                <p class="profile-description"><?= $userData->bio ?></p>
                <?php else: ?>
                <p class="profile-description">Usuário ainda não adicionou informações aqui...</p>
                <?php endif; ?>
            </div>
            <div class="col-md-12" id="added-movies-container">
                <h3>Filmes que enviou:</h3>
                <div class="movies-container">
                    <?php if($userMovies): ?>
                    <?php foreach($userMovies as $movie): ?>
                    <?php require("templates/movie_card.php"); ?>
                    <?php endforeach ?>
                    <?php else: ?>
                    <p class="empty-list">Usuário ainda não adicionou filmes...</p>
                    <? endif ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include_once("templates/footer.php");
?>