<?php
    require_once("templates/header.php");
    require_once("models/User.php");
    require_once("dao/UserDAO.php");
    require_once("dao/MovieDAO.php");

    $userDao = new UserDAO($conn, $BASE_URL);
    $userData = $userDao->verifyToken(true);
    $movieDao = new MovieDAO($conn, $BASE_URL);

    $id = filter_input(INPUT_GET, "id");
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
?>

<div class="container-fluid" id="main-container">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-5" id="form-editmovie">
                <h1><?= $movie->title ?></h1>
                <p class="page-description">Altere os dados do filme no formulário abaixo:</p>
                <form id="edit-movie-form" method="POST" action="<?= $BASE_URL ?>movie_process.php" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="update">
                    <input type="hidden" name="id" value="<?= $movie->id ?>">
                    <div class="form-group">
                        <label for="title">Título:</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= $movie->title ?>"
                            placeholder="Digite o título do filme">
                    </div>
                    <div class="form-group">
                        <label for="image">Imagem: </label>
                        <input type="file" class="form-control-file" id="image" name="image">
                    </div>
                    <div class="form-group">
                        <label for="length">Duração: </label>
                        <input type="text" class="form-control" id="length" name="length" value="<?= $movie->length ?>"
                            placeholder="Digite a duração do filme">
                    </div>
                    <div class="form-group">
                        <label for="category">Categoria:</label>
                        <select name="category" id="category" name="category" class="form-control">
                            <option value="Ação" <?= $movie->category === "Ação" ? "selected": "" ?>>Ação</option>
                            <option value="Romance" <?= $movie->category === "Romance" ? "selected": "" ?>>Romance
                            </option>
                            <option value="Comédia" <?= $movie->category === "Comédia" ? "selected": "" ?>>Comédia
                            </option>
                            <option value="Comédia Romântica"
                                <?= $movie->category === "Comédia Romântica" ? "selected": "" ?>>Comédia Romântica
                            </option>
                            <option value="Fantasia / Ficção"
                                <?= $movie->category === "Fantasia / Ficção" ? "selected": "" ?>>Fantasia / Ficção
                            </option>
                            <option value="Documentário" <?= $movie->category === "Documentário" ? "selected": "" ?>>
                                Documentário</option>
                            <option value="Terror" <?= $movie->category === "Terror" ? "selected": "" ?>>Terror</option>
                            <option value="Drama" <?= $movie->category === "Drama" ? "selected": "" ?>>Drama</option>
                            <option value="Suspense" <?= $movie->category === "Suspense" ? "selected": "" ?>>Suspense
                            </option>
                            <option value="Aventura" <?= $movie->category === "Aventura" ? "selected": "" ?>>Aventura
                            </option>
                            <option value="Anime" <?= $movie->category === "Anime" ? "selected": "" ?>>Anime</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="trailer">Trailer:</label>
                        <input type="text" class="form-control" id="trailer" name="trailer"
                            value="<?= $movie->trailer ?>" placeholder="Insira o link do trailer">
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição:</label>
                        <textarea name="description" id="description" rows="5" class="form-control"
                            placeholder="Descreva sucintamente o filme e sua experiência ao assistir..."><?= $movie->description ?></textarea>
                    </div>
                    <input type="submit" class="btn" id="card-btn" value="Atualizar">
                </form>
            </div>
            <div class="col-md-6" id="midias-editmovie">
                <div class="row">
                    <div class="movie-image-container"
                        style="background-image: url('<?= $BASE_URL ?>img/movies/<?= $movie->image ?>')">
                    </div>
                </div>
                <div class="row">
                    <iframe src="<?= $movie->trailer ?>" width="540" height="295" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php
    include_once("templates/footer.php");
?>