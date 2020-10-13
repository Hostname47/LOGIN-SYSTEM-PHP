<?php

    /* $email and $password to get user inputs from $_POST superglobal */
    $email = $password = "";
    /* To store errors relevent to user inputs. (NOSUCHUSER will appear if the user credentials are wrong) */
    $emailErr = $passwordErr = $noSuchUser = "";

    session_start();

    /* If the user is already connected and the session variable is set then redirect the user directly to dashboard page */
    if(isset($_SESSION["user-id"])) {
        header("location: dashboard.php");
    } else {
        
    }

    // The code inside this if statement will be executed only if the user press sign in button
    if(isset($_POST["sign-in"])) {

        /* CHECK USER INPUTS */

        if(empty($_POST["email"])) {
            $emailErr = "Email should not be empty";
        } else {
            // Make sure that email is valid.
            $email = cleanData($_POST["email"]);
            // Sanitize it
            $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

            // Check if the input is a valid email
            if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                
            } else {
                $emailErr = "Invalid email address";
            }
        }

        if(empty($_POST["password"])) {
            $passwordErr = "Password should not be empty";
        } else {
            // Make sure that password is valid.
            $password = cleanData($_POST["password"]);
        }

        // Here if you can use associative array will be better
        if($emailErr != "" || $passwordErr != "") {
            
        } else {

            $conn = mysqli_connect("localhost", "hostname47", "truestory", "logindb");

            if(!$conn) {
                echo "Connection error: " . mysqli_connect_error($conn);
            }

            $sqlQuery = "SELECT id, username FROM Users WHERE email='".$email."' AND password='".md5($password)."'";
            $resultSet = mysqli_query($conn, $sqlQuery) or die("database error:". mysqli_error($conn));
            $isValidLogin = mysqli_num_rows($resultSet);

            // Here we check if we get a record of the user by checking the number of rows
            if($isValidLogin){

                // check wether the user check the remember me feature
                if(!empty($_POST["remember-me"])) {
                    // If so store the credentials into cookies
                    // Notice that password is encrypted for security 
                    setcookie("loginEmail", $email, time()+ (10 * 365 * 24 * 60 * 60));  
                    setcookie("loginPassword",	$password, time()+ (10 * 365 * 24 * 60 * 60));
                } else {
                    setcookie ("loginEmail",""); 
                    setcookie ("loginPassword","");
                }
                $userDetails = mysqli_fetch_assoc($resultSet);

                // Here we get the user id from the query by using the assoc-array to store it into session superglobal so that we can access it later in the dashboard page
                $_SESSION["user-id"] = $userDetails['id'];
                $_SESSION["user-name"] = $userDetails['username'];
                // Redirect the user to dashboard page
                header("location:dashboard.php");
            } else {
                $noSuchUser = "Invalid user credentials.";		 
            } 
        }
    }

    function cleanData($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="styles/sign-in.css">

    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet"> 
</head>
<body>
    <header>
        <div id="top-header-strip">
            <h2>LOGIN SYSTEM USING PHP</h2>
        </div>
    </header>

    <main>
        <div id="login-container">
            <div id="title-container">
                <h3 id="login-title">SIGN IN</h3>
            </div>
            <div id="login-without-title">
                <div>
                    <p style="text-align: center; font-size: 18px;">Sign in with</p>
                    <div id="social-logins-choice-container">
                        <a href="#" id="google-login">Google</a>
                        <a href="#" id="github-login">Github</a>
                    </div>
                </div>
                <hr>
                <p style="font-size: 18px">Or sign in with credentials</p>
                <span ></span>
                <form id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <!-- Top error message -->
                    <label for="" name="no-such-user" style="display: block; margin-bottom: 8px; color: rgb(250, 53, 53);"><?php echo $noSuchUser ?></label>

                    <!-- Email -->
                    <label for="email">Email</label> <span style="color: rgb(226, 52, 52); margin-left: 15px;"><?php echo $emailErr; ?></span>
                    <input placeholder="Email" type="text" name="email" id="email" value="<?php if(isset($_COOKIE["loginEmail"])) {echo $_COOKIE["loginEmail"];} else { echo $email;} ?>">

                    <!-- Password -->
                    <label for="password">Password</label> <span style="color: rgb(226, 52, 52); margin-left: 15px;"><?php echo $passwordErr; ?></span>
                    <input placeholder="Password" type="password" name="password" id="password" value="<?php if(isset($_COOKIE["loginPassword"])) {echo $_COOKIE["loginPassword"];} ?>">

                    <!-- Remember me -->
                    <input type="checkbox" name="remember-me" id="remember-me"><label id="remember-label" for="remember-me" <?php if(isset($_COOKIE["loginEmail"])) { ?> checked <?php } ?> >Remember me.</label>
                    
                    <input type="submit" name="sign-in" value="SIGN IN">
                </form>
                <div id="account-need-container">
                    <a href="sign-up.php" id="dont-have-account-button">Don't have an account ?</a>
                </div>
            </div>
        </div>
    </main>

    <footer>

    </footer>
</body>
</html>