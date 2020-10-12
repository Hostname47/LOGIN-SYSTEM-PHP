<?php

    $username = $gender = $email = $password = "";
    $usernameErr = $genderErr = $emailErr = $passwordErr = $noSuchUser = "";

    if(isset($_POST["sign-up"])) {

        if(empty($_POST["username"])) {
            $usernameErr = "A username required";
        } else {
            $username = cleanData($_POST["username"]);
            $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);

            if(!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
                $usernameErr = "Invalid username. username should contain only letter and numbers(or underscores) !";
            }
        }

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

        if(empty($_POST["gender"])) {
            $genderErr = "Gender should not be empty";
        } else {
            // Make sure that email is valid.
            $gender = cleanData($_POST["gender"]);
            // Sanitize it
            $gender = filter_var($_POST["gender"], FILTER_SANITIZE_STRING);

            echo $gender;
            // Check if the input is a valid email
            if(!preg_match("/^[a-zA-Z]+$/", $gender)) {
                $genderErr = "Invalid gender format";
            }
        }

        if(empty($_POST["password"])) {
            $passwordErr = "Password should not be empty";
        } else {
            // Make sure that password is valid.
            $password = cleanData($_POST["password"]);
        }

        if($emailErr != "" || $passwordErr != "" || $genderErr != "") {
            
        } else {
            $servername = "localhost";
            $usrname = "hostname47";
            $psswrd = "truestory";
            $dbname = "logindb";
    
            try {
                $conn = new PDO("mysql:host=$servername; dbname=$dbname;", $usrname, $psswrd);
    
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                // I didn't check the existance of an already existed email because email should be unique and username also shoould be unique
                $stmt = $conn->prepare("INSERT INTO Users (username, email, gender, password) VALUES (:username, :email, :gender, :password)");
                
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":gender", $gender);
                $stmt->bindParam(":password", $password);

                $stmt->execute();

                header("location: index.php");
    
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
                <h3 id="login-title">SIGN UP - CREATE AN ACCOUNT</h3>
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
                <p style="font-size: 18px">Fill in your credentials</p>
                <span ></span>
                <form id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <label for="" name="no-such-user" style="display: block; margin-bottom: 8px; color: rgb(250, 53, 53);"><?php echo $noSuchUser ?></label>
                    <label for="username">Username</label> <span style="color: rgb(226, 52, 52); margin-left: 15px;"><?php echo $usernameErr; ?></span>
                    <input placeholder="Username" type="text" name="username" id="username" value="<?php echo $username; ?>">
                    <label for="email">Email</label> <span style="color: rgb(226, 52, 52); margin-left: 15px;"><?php echo $emailErr; ?></span>
                    <input placeholder="Email" type="text" name="email" id="email" value="<?php echo $email; ?>">
                    <label for="password">Password</label> <span style="color: rgb(226, 52, 52); margin-left: 15px;"><?php echo $passwordErr; ?></span>
                    <input placeholder="Password" type="password" name="password" id="password">
                    <label for="gender">Gender</label> <span style="color: rgb(226, 52, 52); margin-left: 15px;"><?php echo $genderErr; ?></span>
                    <select name="gender" id="gender" style="display: block; margin-top: 8px; margin-bottom: 8px; padding: 6px;" selected="FEMALE">
                        <option value="">SELECT GENDER</option>
                        <option value="male" <?php if($gender == "male"){?> selected="selected" <?php } ?>>MALE</option>
                        <option value="female" <?php if($gender == "female"){?> selected="selected" <?php } ?>>FEMALE</option>
                        <option value="other" <?php if($gender == "other"){?> selected="selected" <?php } ?>>OTHER</option>
                    </select>

                    <input type="checkbox" name="remember-me" id="remember-me"><label id="remember-label" for="remember-me">Remember me.</label>
                    
                    <input type="submit" name="sign-up" value="SIGN UP">
                </form>
            </div>
        </div>
    </main>

    <footer>

    </footer>
</body>
</html>