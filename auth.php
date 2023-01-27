<?php
    include_once("templates/header.php");
?>

<div class="container-fluid" id="main-container">
    <div class="col-md-12">
        <div class="row" id="auth-row">
            <div class="col-md-4" id="login-container">
                <h2>Entrar</h2>
                <form action="auth_process.php" method="POST">
                    <input type="hidden" value="login" name="type">
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" name="email" id="email" class="form-control"
                            placeholder="Digite seu e-mail">
                    </div>
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Digite sua senha">
                    </div>
                    <input type="submit" class="btn" id="card-btn" value="Entrar">
                </form>
            </div>
            <div class="col-md-4" id="register-container">
                <h2>Criar conta</h2>
                <form action="auth_process.php" method="POST">
                    <input type="hidden" value="register" name="type">
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" name="email" id="email" class="form-control"
                            placeholder="Digite seu e-mail">
                    </div>
                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="Digite seu nome">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Sobrenome:</label>
                        <input type="text" name="lastname" id="lastname" class="form-control"
                            placeholder="Digite seu sobrenome">
                    </div>
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Crie uma senha">
                    </div>
                    <div class="form-group">
                        <label for="confirmpassword"> Confirmação de Senha:</label>
                        <input type="password" name="confirmpassword" id="confirmpassword" class="form-control"
                            placeholder="Confirme sua senha">
                    </div>
                    <input type="submit" class="btn" id="card-btn" value="Registrar">
                </form>
            </div>
        </div>
    </div>
</div>

<?php
    include_once("templates/footer.php");
?>