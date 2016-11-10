<?php
session_start();

    if(!$_SESSION['id'] && !$_COOKIE['id']){

        header("location: index.php");

    }else{

     $dbHost = "localhost";
    $dbUsername = "username";
    $dbPassword = "password";
    $dbName = "dbname";

        $dbLink = mysqli_connect($dbHost,$dbUsername,$dbPassword,$dbName);

        if(mysqli_connect_error()){

            die("Database connection error");

        }

        $query = "SELECT diary FROM kullanicilar WHERE id ='".$_SESSION['id']."' ";

        $result = mysqli_query($dbLink,$query);

        if($result){

           $rowArr = mysqli_fetch_array($result);

            $diary_message = $rowArr['diary'];

        }

    }

    if(array_key_exists('logout',$_POST)){

        $_SESSION['logout'] = 0;
        header("location: index.php");

    }






?>
<html>
<head>
    <title>Secret Diary</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css" integrity="sha384-MIwDKRSSImVFAZCVLtU0LMDdON6KVCrZHyVQQj6e8wIEJkW4tvwqXrbMIya1vriY" crossorigin="anonymous">
    <style type ="text/css">

        body{

            background: url(bg.jpg) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;

        }

        .container{

            margin-top:50px;


        }
        .btn{

            margin-top:9px;

        }
        .navbar-brand{

            margin-top: 7px;
            font-weight: bold;
            font-size: 150%;

        }
        #diary-message{
            height: 600px;

        }

    </style>
</head>
<body>

        <nav class="size navbar navbar-light bg-faded">
            <a class="navbar-brand" href="#">SECRET DIARY</a>
            <form class="form-inline pull-xs-right" method="post">
                <button name ="logout" class="btn btn-outline-success" type="submit" value="1">LOGOUT</button>
            </form>
        </nav>

        <div class = "container">
            <form method="post">
                <div class = "form group row">
                            <textarea id  ="diary-message" name = "thougts"class = "form-control" placeholder = "Start your private diary"><?php
                                    echo $diary_message;
                                ?></textarea>
                </div>
            </form>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js" integrity="sha384-ux8v3A6CPtOTqOzMKiuo3d/DomGaaClxFYdCu2HPMBEkf6x2xiDyJ7gkXU0MWwaD" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.3/js/tether.min.js"></script>
        <script type ="text/javascript">

            $("#diary-message").bind('input propertychange',function () {

                $.ajax({

                    method:"POST",
                    url:"ajax.php",
                    data:{content: $("#diary-message").val()}

                });


            })



        </script>


</body>
</html>