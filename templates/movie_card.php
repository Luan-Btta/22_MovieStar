<?php
    if(empty($movie->image)){
        $movie->image = "movie_cover.jpg";
    }
?>
<div class="card" id="movie-card">
    <div class="card-img-top" style="background-image: url('<?= $BASE_URL ?>img/movies/<?= $movie->image ?>')">
    </div>
    <div class="card-body">
        <p class="card-rating">
            <i class="fa fa-star"></i>
            <span class="rating"> <?= $movie->rating ?></span>
        </p>
        <h5 class="card-title">
            <a href="<?= $BASE_URL ?>movie.php/?id=<?= $movie->id ?>" class="link-title"><?= $movie->title ?></a>
        </h5>
        <a href="" class="btn btn-primary" id="rating-btn">Avaliar</a>
        <a href="<?= $BASE_URL ?>movie.php/?id=<?= $movie->id ?>" class="btn" id="card-btn">Conhecer</a>
    </div>
</div>