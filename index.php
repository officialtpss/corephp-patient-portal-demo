<?php 
	/** Include header.php */
	include('layout/header.php'); 

	require 'db.php'; 
	
	$error = false;
	$error_mssg = '';

	/** check if form was submitted */
	if(isset($_POST['submit'])){
		
		if ( !isset($_POST['username'], $_POST['password']) ) {

			$error = true;
			$error_mssg = 'Username and password fields are required!';

		}else{

			/** check if username & password record exists */
			$result = _check_login($_POST);

			if($result['success'] === false){
				$error = true;
				$error_mssg = $result['message'];
			}else{

				session_regenerate_id();
				$_SESSION['loggedin'] = TRUE;
				$_SESSION['user_id'] = $result['data']['user_id'];
				$_SESSION['username'] = $result['data']['username'];
				
			}
		}

	}

	/** check if user is logged in then redirect to patients */
	if (isset($_SESSION['loggedin'])) {
		header('Location: patients.php');
		exit;
	}

?>

<div class="login">
	<h1>CRUD matrix Login</h1>
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

		<label for="username">
			<i class="fas fa-user"></i>
		</label>

		<input type="text" name="username" placeholder="Username" id="username" required <?php if(isset($_POST)){ ?> value="<?php echo $_POST['username']; ?>"<?php }?>>
		<?php if($error === true){ ?> 
			<span class="errors"><?php echo $error_mssg; ?></span>
		<?php } ?>

		<label for="password">
			<i class="fas fa-lock"></i>
		</label>

		<input type="password" name="password" placeholder="Password" id="password" required>

		<input type="submit" name="submit" value="Login">
	</form>
</div>

<?php include('layout/footer.php'); ?>