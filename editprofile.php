<?php
    require_once("templates/header.php");
    require_once("models/User.php");
    require_once("dao/UserDAO.php");

    $userDao = new UserDAO($conn, $BASE_URL);
    $user = new User();
    $userData = $userDao->verifyToken(true);
    $fullName = $user->getFullName($userData);

    if($userData->image == ""){
        $userData->image = "user.png";
    }

?>

<div class="container-fluid" id="main-container">
    <div class="col-md-12" id="edit-profile">
        <form action="user_process.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" value="update" name="type">
            <div class="row">
                <div class="col-md-4">
                    <h1><?= $fullName ?></h1>
                    <p class="page-description">Altere seus dados no formulário abaixo:</p>
                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" class="form-control" name="name" value="<?= $userData->name ?>">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Sobrenome:</label>
                        <input type="text" class="form-control" name="lastname" value="<?= $userData->lastname ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" readonly class="form-control" id="disabled" name="email" value="<?= $userData->email ?>">
                    </div>
                    <input type="submit" class="btn" id="card-btn" value="Alterar">
                </div>
                <div class="col-md-4">
                    <div id="profile-image-container"
                        style="background-image: url('<?= $BASE_URL ?>/img/users/<?= $userData->image ?>');">
                    </div>
                    <div class="form-group">
                        <label for="image">Foto:</label>
                        <input type="file" class="form-control-file" name="image">
                    </div>
                    <div class="form-group">
                        <label for="bio">Sobre Você:</label>
                        <textarea name="bio" id="bio" rows="5" class="form-control"
                            placeholder="Conte quem você é, o que faz e onde trabalha..."><?= $userData->bio ?></textarea>
                    </div>
                </div>
            </div>
        </form>
        <div class="row" id="change-password-container">
            <div class="col-md-4">
                <h2>Alterar senha:</h2>
                <p class="page-description">Digite a nova senha e confirme, para alterar:</p>
                <form action="user_process.php" method="POST">
                    <input type="hidden" name="type" value="changepassword">
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Crie uma senha">
                    </div>
                    <div class="form-group">
                        <label for="confirmpassword">Confirmação:</label>
                        <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="Confirme a senha">
                    </div>
                    <input type="submit" class="btn" id="card-btn" value="Alterar Senha">
                </form>
            </div>
        </div>
    </div>
</div>

<?php
    include_once("templates/footer.php");
?>