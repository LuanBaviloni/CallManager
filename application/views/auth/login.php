<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>CallManager</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="all,follow">
        <!-- Bootstrap CSS-->
        <link rel="stylesheet" href="<?= base_url('assets/login/vendor/bootstrap/css/bootstrap.min.css'); ?>">
        <!-- Font Awesome CSS-->
        <link rel="stylesheet" href="<?= base_url('assets/login/vendor/font-awesome/css/font-awesome.min.css'); ?>">
        <!-- Custom Font Icons CSS-->
        <link rel="stylesheet" href="<?= base_url('assets/login/css/font.css'); ?>">
        <!-- Google fonts - Muli-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,700">
        <!-- theme stylesheet-->
        <link rel="stylesheet" href="<?= base_url('assets/login/css/style.sea.css'); ?>" id="theme-stylesheet">
        <!-- Custom stylesheet - for your changes-->
        <link rel="stylesheet" href="<?= base_url('assets/login/css/custom.css'); ?>">
        <!-- Favicon-->
        <link rel="shortcut icon" href="<?= base_url('assets/login/img/favicon.ico'); ?>">
        <!-- Tweaks for older IEs--><!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
    </head>
    <body>
        <div class="login-page">
            <div class="container d-flex align-items-center">
                <div class="form-holder has-shadow">
                    <div class="row">
                        <!-- Logo & Information Panel-->
                        <div class="col-lg-6">
                            <div class="info d-flex align-items-center">
                                <div class="content">
                                    <div class="logo">
                                        <h1 style="color: #eee">CALL<span style="color: #255">MANAGER</span></h1>
                                    </div>
                                    <p>Um jeito rápido, fácil e prático de gerenciar seu CallCenter.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Form Panel    -->
                        <div class="col-lg-6 bg-white">
                            <div class="form d-flex align-items-center">
                                <div class="content">
                                    <?php echo form_open("auth/login"); ?>
                                    <div class="form-group">                                        
                                        <input id="login-username" type="text" name="identity" required="" class="input-material">
                                        <label for="login-username" class="label-material">Email</label>
                                    </div>
                                    <div class="form-group">
                                        <input id="login-password" type="password" name="password" required="" class="input-material">
                                        <label for="login-password" class="label-material">Senha</label>
                                    </div><input id="login" onclick="" class="btn btn-primary" type="submit" value="Entrar"/>
                                    
                                    <?php echo form_close(); ?>
                                    <a href="#" class="forgot-pass">Esqueceu sua senha?</a><br><small>Não possui uma conta? </small><a href="#" class="signup">Cadastre-se</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="copyrights text-center">
                <p>Desenvolvido por <a href="mailto:w.luanbaviloni@gmail.com" class="external">Luan Baviloni</a></p>
            </div>
        </div>
        <!-- Javascript files-->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
        <script src="<?= base_url('assets/login/vendor/bootstrap/js/bootstrap.min.js'); ?>"></script>
        <script src="<?= base_url('assets/login/vendor/jquery.cookie/jquery.cookie.js'); ?>"></script>
        <script src="<?= base_url('assets/login/vendor/chart.js/Chart.min.js'); ?>"></script>
        <script src="<?= base_url('assets/login/js/front.js'); ?>"></script>
    </body>
</html>
