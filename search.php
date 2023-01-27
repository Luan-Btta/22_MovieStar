<?php
    require_once("templates/header.php");
    require_once("dao/MovieDAO.php");

    $movieDao = new MovieDAO($conn, $BASE_URL);
    $moviesSearch;

    $q = filter_input(INPUT_GET, "q");
    
    $moviesSearch = $movieDao->findByTitle($q);
?>

<div class="container-fluid" id="main-container">
    <h2 class="section-title">Você está buscando: <?= $q ?></h2>
    <p class="section-description">Resultados relacionados a busca.</p>
    <div class="movies-container">
        <?php if(!$moviesSearch): ?>
            <p class="empty-list">Nenhum filme encontrado, <a href="<?= $BASE_URL ?>index.php" class="back-link">voltar</a></p>
        <?php else: ?>
            <?php foreach($moviesSearch as $movie): ?>
            <?php require("templates/movie_card.php"); ?>
            <?php endforeach; ?>        
        <?php endif ?>
    </div>
</div>

<?php
    include_once("templates/footer.php");
?>