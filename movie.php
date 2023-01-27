<?php
    require_once("templates/header.php");
    require_once("models/User.php");
    require_once("dao/UserDAO.php");
    require_once("dao/MovieDAO.php");
    require_once("dao/ReviewDAO.php");
    require_once("models/Movie.php");

    $id = filter_input(INPUT_GET, "id");
    $movie;
    $movieDao = new MovieDAO($conn, $BASE_URL);
    $review;
    $reviewDao = new ReviewDAO($conn, $BASE_URL);
    
    //VALIDA SE O FILME EXISTE
    if(empty($id)){
        $message->setMessage("O filme não foi encontrado.", "error");
    }else{
        $movie = $movieDao->findById($id);

        if(!$movie){
            $message->setMessage("O filme não foi encontrado.", "error");
        }
        
        if(empty($movie->image)){
            $movie->image = "movie_cover.jpg";
        }
    }

    //VALIDA SE O FILME É DO USUÁRIO E NÃO ENVIOU UM REVIEW DESSE FILME
    $userOwnsMovie = false;
    $alreadyReviewed = false;

    if(!empty($userData)){
        if($userData->id === $movie->user_id){
            $userOwnsMovie = true;
        }
        $alreadyReviewed = $reviewDao->hasAlreadyReviewed($id ,$userData->id);
    }

    //RESGATAR REVIEWS DO FILME
    $movieReviews = $reviewDao->getMovieReviews($movie->id);
?>

<div class="container-fluid" id="main-container">
    <div class="row">
        <div class="offset-md-1 col-md-6" id="movie-container">
            <h1 class="page-title"><?= $movie->title ?></h1>
            <p class="movie-details">
                <span>Duração: <?= $movie->length ?></span>
                <span class="pipe"></span>
                <span>Categoria: <?= $movie->category ?></span>
                <span class="pipe"></span>
                <span><i class="fas fa-star"></i> <?= $movie->rating ?></span>
            </p>
            <iframe src="<?= $movie->trailer ?>" width="560" height="315" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe>
            <p><?= $movie->description ?></p>
        </div>
        <div class="col-md-4">
            <div class="movie-image-container"
                style="background-image: url('<?= $BASE_URL ?>img/movies/<?= $movie->image ?>')">
            </div>
        </div>
        <div class="offset-md-1 col-md-10" id="reviews-container">
            <h3 id="reviews-title">Avaliações:</h3>
            <!-- VERIFICA SE USUÁRIO ESTÁ LOGADO PARA AVALIAR -->
            <?php if(!empty($userData) && !$userOwnsMovie && !$alreadyReviewed): ?>
            <div class="col-md-12" id="reviews-form-container">
                <h4>Envie sua avaliação:</h4>
                <p class="page-description">
                    Preencha a nota e descrição desde filme.
                </p>
                <form action="<?= $BASE_URL ?>review_process.php" method="POST" id="form-review">
                    <input type="hidden" name="type" value="create">
                    <input type="hidden" name="movies_id" value="<?= $movie->id ?>">
                    <div class="form-group">
                        <label for="rating">Nota do Filme:</label>
                        <select name="rating" id="rating" class="form-control">
                            <option value="">Selecione</option>
                            <option value="10">10</option>
                            <option value="9">9</option>
                            <option value="8">8</option>
                            <option value="7">7</option>
                            <option value="6">6</option>
                            <option value="5">5</option>
                            <option value="4">4</option>
                            <option value="3">3</option>
                            <option value="2">2</option>
                            <option value="1">1</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="review">Comentário:</label>
                        <textarea name="review" id="review" rows="3" placeholder="O que você achou do filme?"
                            class="form-control"></textarea>
                    </div>
                    <input type="submit" class="btn" id="card-btn" value="Enviar Comentário">
                </form>
            </div>
            <?php endif; ?>
            <?php if($movieReviews): ?>
                <?php foreach($movieReviews as $review): ?>
                <?php require("templates/user_review.php"); ?>
                <?php endforeach ?>
            <?php else: ?>
                <p class="empty-list">Nenhum comentário foi adicionado para este filme.</p>
            <? endif ?>
        </div>
    </div>
</div>

<?php
    include_once("templates/footer.php");
?>