<?php 

session_start();

if (isset($_SESSION['username'])) {

	if (isset($_POST['message']) &&
        isset($_POST['to_id'])) {
	
	include '../dbconn.php';

	$message = $_POST['message'];
	$to_id = $_POST['to_id'];
	$from_id = $_SESSION['user_id'];

	$sql = "INSERT INTO 
	       chats (from_id, to_id, message) 
	       VALUES (?, ?, ?)";
	$stmt = $conn->prepare($sql);
	$res  = $stmt->execute([$from_id, $to_id, $message]);
    
    // if the message inserted
    if ($res) {

	// checks if this is the first convo between users
       $sql2 = "SELECT * FROM conversations
               WHERE (user_1=? AND user_2=?)
               OR    (user_2=? AND user_1=?)";
       $stmt2 = $conn->prepare($sql2);
	   $stmt2->execute([$from_id, $to_id, $from_id, $to_id]);

	    // this is just the timezone
		define('TIMEZONE', 'Asia/Brunei');
		date_default_timezone_set(TIMEZONE);

		$time = date("h:i:s a");

		if ($stmt2->rowCount() == 0 ) {
			// insert statement into convo table
			$sql3 = "INSERT INTO 
			         conversations(user_1, user_2)
			         VALUES (?,?)";
			$stmt3 = $conn->prepare($sql3); 
			$stmt3->execute([$from_id, $to_id]);
		}
		?>

		<p>
		    <?=$message?>  
		    <small><?=$time?></small>      	
		</p>

    <?php 
     }
  }
}else {
	header("Location: login.php");
	exit;
}