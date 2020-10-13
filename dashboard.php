<?php

    /*if(!isset($_SESSION["user-id"])) {
        header("location: index.php");
    }*/
    session_start();

    if(!isset($_SESSION["user-id"])) {
        header("location: index.php");
    }

    if(isset($_POST["logout"])) {
        unset($_SESSION["user-id"]);
        header("location:index.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>WELCOME <?php echo $_SESSION["user-name"] ?></h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <input type="submit" name="logout" id="logout">
    </form>
</body>
</html>