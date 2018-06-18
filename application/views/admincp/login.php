<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Amincp | Login</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=PATH_URL?>assets/dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="<?=PATH_URL?>assets/index2.html"><b>Admin</b>LTE</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <div class="alert alert-danger alert-dismissible" style="display: none" id="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <div id="error"></div>
                </div>
                <form action="<?=$form_action?>" method="post" name="formLogin">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Username</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fa fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Username..." name="username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Password</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fa fa-key"></i></span>
                            </div>
                            <input type="password" name="password" class="form-control" placeholder="Password...">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="<?=PATH_URL?>assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?=PATH_URL?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            $('form[name="formLogin"]').submit(function(event){
                event.preventDefault();
                $('#alert').css('display','none');
                $.post(this.action, {
                    username: $('input[name="username"]').val(),
                    password: $('input[name="password"]').val()
                }, function(json){
                    if(json.status == 1) {
                        location = json.redirect_url;
                    } else {
                        var html = '';
                        if (typeof json.message == 'object') {
                            json.message.forEach(function (error) {
                                html += '<span>' + error + '</span><br>';
                            });
                        } else {
                            html += json.message;
                        }
                        $('#error').html(html);
                        $('#alert').css('display','block');
                    }
                }, 'json');
            });
        });
    </script>
</body>

</html>