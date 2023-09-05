<?php 
  session_start();

  if (isset($_SESSION['username'])) {
  	include 'dbconn.php';
    include 'chatHelper/getUser.php';
  	include 'chatHelper/getConvo.php';
    include 'chatHelper/getLatestChat.php';

  	$user = getUser($_SESSION['username'], $conn);

  	$conversations = getConvo($user['user_id'], $conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chat Home</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="p-2 w-400 rounded shadow">
    	<div>
    		<div class="d-flex mb-3 p-3 bg-light justify-content-between align-items-center">
    			<div class="d-flex align-items-center">
    			    
                    <h3><?=$user['name']?></h3> 
    			</div>
    			<a href="logout.php">Logout</a>
    		</div>

    		<div class="input-group mb-3">
    			<input type="text" placeholder="Search.." id="searchText" class="form-control">
    			<button class="btn btn-primary"  id="serachBtn">
    			        <i class="fa fa-search"></i>	
    			</button>       
    		</div>
    		<ul id="chatList"
    		    class="list-group mvh-50 overflow-auto">
    			<?php if (!empty($conversations)) { ?>
    			    <?php 

    			    foreach ($conversations as $conversation){ ?>
	    			<li class="list-group-item">
	    				<a href="chat.php?user=<?=$conversation['username']?>"
	    				   class="d-flex justify-content-between align-items-center p-2">
	    					<div zclass="d-flex align-items-center">
	    					    <h3 class="fs-xs m-2">
	    					    	<?=$conversation['name']?><br>
                      <small>
                        <?php 
                          echo latestChat($_SESSION['user_id'], $conversation['user_id'], $conn);
                        ?>
                      </small>
	    					    </h3>            	
	    					</div>
	    				</a>
	    			</li>
    			    <?php } ?>
    			<?php }else{ ?>
    				<div class="alert alert-info 
    				            text-center">
					   <i class="fa fa-comments d-block fs-big"></i>
                       No messages yet, start a convo
					</div>
    			<?php } ?>
    		</ul>
    	</div>
    </div>
	  

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
	$(document).ready(function(){
      
      // continuation of search function from search.php
       $("#searchText").on("input", function(){
       	 var searchText = $(this).val();
         if(searchText == "") return;
         $.post('ajax/search.php', 
         	     {
         	     	key: searchText
         	     },
         	   function(data, status){
                  $("#chatList").html(data);
         	   });
       });

       $("#serachBtn").on("click", function(){
       	 var searchText = $("#searchText").val();
         if(searchText == "") return;
         $.post('ajax/search.php', 
         	     {
         	     	key: searchText
         	     },
         	   function(data, status){
                  $("#chatList").html(data);
         	   });
       });

    });
</script>
</body>
</html>
<?php
  }else{
  	header("Location: login.php");
   	exit;
  }
 ?>