<?php  

# check if username, password, name submitted
if(isset($_POST['username']) &&
   isset($_POST['password']) &&
   isset($_POST['name'])){

   # database connection file
   include 'dbconn.php';
   
   # get data from POST request and store them in var
   $name = $_POST['name'];
   $password = $_POST['password'];
   $username = $_POST['username'];

   # making URL data format
   $data = 'name='.$name.'&username='.$username;

   #simple form Validation
   if (empty($name)) {
   	  # error message
   	  $em = "Name is required";

   	  # redirect to 'signup.php' and passing error message
   	  header("Location: signup.php?error=$em");
   	  exit;
   }else if(empty($username)){
      # error message
   	  $em = "Username is required";

   	  /*
    	redirect to 'signup.php' and 
    	passing error message and data
      */
   	  header("Location: signup.php?error=$em&$data");
   	  exit;
   }else if(empty($password)){
   	  # error message
   	  $em = "Password is required";

   	  /*
    	redirect to 'signup.php' and 
    	passing error message and data
      */
   	  header("Location: signup.php?error=$em&$data");
   	  exit;
   }else {
   	  # checking the database if the username is taken
   	  $sql = "SELECT username 
   	          FROM users
   	          WHERE username=?";
      $stmt = $conn->prepare($sql);
      $stmt->execute([$username]);

      if($stmt->rowCount() > 0){
      	$em = "The username ($username) is taken";
      	header("Location: signup.php?error=$em&$data");
   	    exit;
      }

      	// password hashing
      	$password = password_hash($password, PASSWORD_DEFAULT);

      	

      	# success message
      	$sm = "Account created successfully";

      	# redirect to 'index.php' and passing success message
      	header("Location: login.php?success=$sm");
     	exit;
      }

   }
}else {
	header("Location: signup.php");
   	exit;
}