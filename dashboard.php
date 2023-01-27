<?php
    require_once("templates/header.php");
    require_once("models/User.php");
    require_once("dao/UserDAO.php");
    require_once("dao/MovieDAO.php");

    $userDao = new UserDAO($conn, $BASE_URL);
    $user = new User();
    $movieDao = new MovieDAO($conn,  $BASE_URL);
    $userData = $userDao->verifyToken(true);
    $userMovies = $movieDao->getMoviesByUserId($userData->id);
?>

<div class="container-fluid" id="main-container">
    <h2 class="section-title">Dashboard</h2>
    <p class="section-description">Adicione ou Atualize os filmes eviados por você</p>
    <div class="col-mds-12" id="add-movie-container">
        <a href="newmovie.php" class="btn" id="card-btn"><i class="fas fa-plus"></i> Adicionar Filme</a>
    </div>
    <div class="col-mds-12" id="movies-dashboard">
        <table class="table">
            <thead>
                <th scope="col">#</th>
                <th scope="col">Título</th>
                <th scope="col">Nota</th>
                <th scope="col" class="actions-column">Ações</th>
            </thead>
            <tbody>
                <?php if($userMovies): ?>
                <?php foreach ($userMovies as $movie):?>
                <tr>
                    <td><?= $movie->id ?></td>
                    <td><a href="movie.php/?id=<?= $movie->id ?>" class="table-movie-title"><?= $movie->title ?></a>
                    </td>
                    <td><i class="fas fa-star"></i> <?= $movie->rating ?></td>
                    <td class="actions-column">
                        <a href="editmovie.php/?id=<?= $movie->id ?>" class="btn" id="edit-btn">
                            <i class="far fa-edit"></i> Editar
                        </a>
                        <form action="movie_process.php" method="POST">
                            <input type="hidden" name="type" value="delete">
                            <input type="hidden" name="movie_id" value="<?= $movie->id ?>">
                            <button type="submit" class="btn" id="delete-btn">
                                <i class="fas fa-times"></i> Deletar
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach ?>
                <?php else: ?>
                <tr scope="row">
                    <td colspan="4" class="empty-list">
                        Nenhum filme foi adicionado...
                    </td>
                </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>
<?php
    include_once("templates/footer.php");
?>