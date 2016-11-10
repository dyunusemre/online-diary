<?php
	session_start();
	
	if(array_key_exists("content",$_POST)){
			
		$dbHost = "localhost";
		$dbUsername = "cl25-admin-of4";
		$dbPassword = "9GF!tbUfR";
		$dbName = "cl25-admin-of4";


        $dbLink = mysqli_connect($dbHost,$dbUsername,$dbPassword,$dbName);

        if(mysqli_connect_error()){

            die("Database connection error");

        }

		$query = "UPDATE kullanicilar SET diary = '".$_POST['content']."' WHERE id = '".$_SESSION['id']."' ";
		
		$result = mysqli_query($dbLink,$query);
				
		
	}
	
	
	
	
?>