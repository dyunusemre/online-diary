<?php
    session_start();

    if($_SESSION['logout'] == 0){

        $_SESSION['logout'] = 1;
        $_SESSION['id'] = "";
        setcookie("id","",time()-60*60);

    }
	if($SESSION['id'] != "" ){
		
		header:("location: diary.php");
		
	}

    $error = "";
    $message = "";
    $dbHost = "localhost";
    $dbUsername = "username";
    $dbPassword = "password";
    $dbName = "dbname";

    if(array_key_exists("sign-up-button",$_POST)){

        $dbLink = mysqli_connect($dbHost,$dbUsername,$dbPassword,$dbName);

        if(mysqli_connect_error()){

            die("Database connection error");

        }

        if(!$_POST['sign-up-email']){

            $error .="<li> An Email Required </li>";

        }
        if(!$_POST['sign-up-password']){

            $error .="<li>A Password Required</li>";

        }
        if(strlen($_POST['sign-up-password']) < 6){

            $error .="<li>Password at lest 6 character</li>";

        }
        if($_POST['sign-up-password'] != $_POST['control-password']){

            $error .="<li>Passwords doesn't macth</li>";

        }

        if($error == ""){

            $query = "SELECT id FROM kullanicilar WHERE email = '".mysqli_real_escape_string($dbLink,$_POST['sign-up-email'])."' ";

            $result = mysqli_query($dbLink,$query);

            if(mysqli_num_rows($result) > 0 ){

                $error .= "<li>The email already taken</li>";

            }else{

                $query = "INSERT INTO kullanicilar (email,password) VALUES ('".mysqli_real_escape_string($dbLink,$_POST['sign-up-email'])."',
                '".mysqli_real_escape_string($dbLink,$_POST['sign-up-password'])."')";

                $result = mysqli_query($dbLink,$query);

                if($result){

                    $hash = md5(md5(mysqli_insert_id($dbLink)).$_POST['sign-up-password']); // password hash

                    $query = "UPDATE kullanicilar SET password = '".$hash."' WHERE email = '".$_POST['sign-up-email']."' ";
                    mysqli_query($dbLink,$query);

                    $message .= "You have signed up succesfully";

                }else{

                    $error .="<li>A problem while sign up</li>";

                }

            }

        }


    }
    if(array_key_exists("login-button",$_POST)){

        $dbLink = mysqli_connect($dbHost,$dbUsername,$dbPassword,$dbName);

        if(mysqli_connect_error()){

            die("Database connection error");

        }

        if(!$_POST['login-email']){

            $error .="<li>Enter Your Email to Login </li>";

        }
        if(!$_POST['login-password']){

            $error .="<li>Enter Your Password to Login</li>";

        }
        if($error == ""){

            $query = "SELECT id,email,password FROM kullanicilar WHERE email = '".mysqli_real_escape_string($dbLink,$_POST['login-email'])."' ";

            $result = mysqli_query($dbLink,$query);

            $logArr = mysqli_fetch_array($result);

            if(array_key_exists('email',$logArr)){


                $hash = md5(md5($logArr['id']).$_POST['login-password']);

                if($logArr['email'] == $_POST['login-email'] && $logArr['password'] == $hash ){

                    $message .= "You have logged in succesfully";

                    $_SESSION['id'] = $logArr['id'];



                    if($_POST['stay-login'] == 1){

                        setcookie("id",$logArr['id'], time() + 60*60);

                    }

                    header("location: diary.php");


                }else{

                    $error .= "<li>Your email or password is wrong</li>";

                }

            }else{

                $error .= "<li>You're email not found please Sign UP !</li>";

            }
        }

    }

?>

<html>
<head>
    <title>Secret Diary</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css" integrity="sha384-MIwDKRSSImVFAZCVLtU0LMDdON6KVCrZHyVQQj6e8wIEJkW4tvwqXrbMIya1vriY" crossorigin="anonymous">

    <style type = "text/css" media="screen">


        body{

            background: url(bg.jpg) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;

        }
        .container{

            color:#FFFFFF;
            text-align: center;
            margin-top:250px;

        }
        .container h1{

            font-weight: bold;
            font-size: 400%;

        }
        .sub-text{

            font-weight: bold;
            font-size: 120%;


        }
        .container .sign-up-text{

            font-weight: normal !important;

        }
        #sign-up{

            display:none;

        }
    </style>

</head>
<body>
    <div class = "container">

        <h1>Secret Diary</h1>
        <div class ="row">
            <p class = "error">

                <?php

                    if($error) {

                        echo "<div class=\"alert alert-danger col-xs-4 offset-xs-4\" style='text-align: left;' role=\"alert\">
                            <p>There are following problems: </p><ul>".$error."</ul></div>";

                    }
                    if($message){

                        echo "<div class=\"alert alert-success col-xs-4 offset-xs-4\" style='text-align: left;' role=\"alert\">
                            ".$message."</div>";

                    }
                ?>
            </p>
        </div>
        <p class = "sub-text">Store your thoughts permanently and securely.</p>
        <p class = "sign-up-text">Interests ?<span id="login-ref"><i><b><u>Login</u></b></i></span> Or <span id="sign-up-ref"><i><b><u>Sign up</u></b></i></span>  now</p>



        <div class="">
            <form method="post" id ="sign-up-form" action = "">
                <div id="sign-up">
                    <div class="form-group row">
                        <div class="col-xs-6 offset-xs-3">
                            <input name = "sign-up-email" id="sign-up-email" class="form-control" type="email" value="" placeholder="Email Adress">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-6 offset-xs-3">
                            <input name = "sign-up-password" id="sign-up-password" class="form-control" type="password" value="" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-6 offset-xs-3">
                            <input name = "control-password" id="control-password" class="form-control" type="password" value="" placeholder="Re-Password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button name="sign-up-button" type="submit" class="btn btn-success">Sign Up</button>
                        </div>
                    </div>

                </div>

            </form>
            <form method="post" id = "login-form" action = "">

                <div id="login">
                    <div class="form-group row">
                        <div class="col-xs-6 offset-xs-3">
                            <input name = "login-email" id="login-email" class="form-control" type="email" value="" placeholder="Your Email Adress">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-6 offset-xs-3">
                            <input name = "login-password" id="login-password" class="form-control" type="password" value="" placeholder="Your Password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input name ="stay-login" class="form-check-input" type="checkbox" value ="1"> Stay logged in
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button id = "login-button" name="login-button" type="submit" class="btn btn-success">Login</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js" integrity="sha384-ux8v3A6CPtOTqOzMKiuo3d/DomGaaClxFYdCu2HPMBEkf6x2xiDyJ7gkXU0MWwaD" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.3/js/tether.min.js"></script>
    <script type ="text/javascript">

        $("#sign-up-ref").on('click',function () {

            $("#sign-up").css("display","block");
            $("#login").css("display","none");

        })
        $("#login-ref").on('click',function () {

            $("#login").css("display","block");
            $("#sign-up").css("display","none");

        })


    </script>

</body>
</html>