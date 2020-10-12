<?php

    $email = $password = "";
    $emailErr = $passwordErr = $noSuchUser = "";

    if(isset($_POST["sign-in"])) {
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

        if($emailErr != "" || $passwordErr != "") {
            
        } else {
            $servername = "localhost";
            $usrname = "hostname47";
            $psswrd = "truestory";
            $dbname = "logindb";
    
            try {
                $conn = new PDO("mysql:host=$servername; dbname=$dbname;", $usrname, $psswrd);
    
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $stmt = $conn->prepare("SELECT * FROM Users WHERE email = :mail AND password = :password");
    
                $stmt->bindParam(":mail", $email);
                $stmt->bindParam(":password", $password);
    
                $stmt->execute();
    
                if($stmt->rowCount() == 0) {
                    $noSuchUser = "No such user with these credentials";
                } else {
                    session_start();
                    
                }
    
            } catch (PDOException $ex) {
                echo "PDO Error: " . $ex->getMessage();
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
                    <label for="" name="no-such-user" style="display: block; margin-bottom: 8px; color: rgb(250, 53, 53);"><?php echo $noSuchUser ?></label>
                    <label for="email">Email</label> <span style="color: rgb(226, 52, 52); margin-left: 15px;"><?php echo $emailErr; ?></span>
                    <input placeholder="Email" type="text" name="email" id="email">
                    <label for="password">Password</label> <span style="color: rgb(226, 52, 52); margin-left: 15px;"><?php echo $passwordErr; ?></span>
                    <input placeholder="Password" type="password" name="password" id="password">
                    <input type="checkbox" name="remember-me" id="remember-me"><label id="remember-label" for="remember-me">Remember me.</label>
                    
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