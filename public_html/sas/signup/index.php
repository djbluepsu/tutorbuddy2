<?php
    session_start();
    /*if(isset($_SESSION['username'])) {
        header("location:../dashboard/");
    }*/
    require('../includes/dbc_connect.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
            $pageTitle = "Sign Up";
            //require_once("../includes/head.php");
            require('../includes/header.php');
        ?>
        <script type="text/javascript" src="./script.js"></script>
    </head>
    <body>
        <?php
            require ('../includes/nav.php');
            $showForm = true;
            $sasEmail = true;
            if(isset($_POST['submit'])) {
                $showForm = false;

                // creates variables to hold all the form inputs
                $fName = trim(htmlentities($_POST['firstname']));
                $lName = trim(htmlentities($_POST['lastname']));
                $email = trim(htmlentities($_POST['email']));
                $password = trim(htmlentities($_POST['password']));
                $grade = $_POST['grade'];

                $formValid = true;
                
                if(strlen($password) < 5) {
                    $formValid = false;
                }
                if (substr($email, -11) != "@sas.edu.sg") {
                    $formValid = false;
                    $sasEmail = false;
                }

                if($formValid) {
                    // check if the email exists already
                    $query = "SELECT id FROM users WHERE email = ?";
                    $stmt = $dbc->prepare($query);
                    $stmt->bind_param("s", $email);
                    /* $stmt->bind_result($returnedId);
                    $stmt->fetch(); */
                    $stmt->execute();
                    $stmt->store_result();

                    if($stmt->num_rows > 0) {
                        $emailTaken = true;
                        $showForm = true;
                    } else {
                        $emailTaken = false;
                    }

                    $stmt->free_result();
                    $stmt->close();

                    // hashes the password and inserts all the info into database
                    if(!($emailTaken)) {
                        $pwHash = md5($password);

                        $key = mt_rand(100000000,999999999);
                        $query = "INSERT into users (first_name, last_name, email, password, grade, activation_key) VALUES (?, ?, ?, ?, ?, ?)";

                        $stmt = $dbc->prepare($query);
                        $stmt->bind_param("ssssii", $fName, $lName, $email, $pwHash, $grade, $key);

                        $stmt->execute();
                        
                        // general closing stuff, and emails activation key
                        $stmt->free_result();

                        $subject = "Confirm your email address";
                        $message = 'Hi ' . $fName . ', 
                        You have signed up for Tutorbuddy. Please click <a href="http://sas.tutorbuddy.net/signup/activate.php">here</a> and log in with the following activation key to confirm your email address.' . "\r\n" . '<span style="font-weight: bold;">Your key is: ' . $key . '</span>';

                        $headers  = 'MIME-Version: 1.0' . "\r\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                        $headers .= 'From: Tutorbuddy <noreply@tutorbuddy.net>' . "\r\n";

                        mail($email, $subject, $message, $headers);
                        echo '<script type="text/javascript"> window.location.assign("./complete.php"); </script>';
                    }
                }
            }
            if($showForm || !$formValid):
        ?>
        <div class="container">
            <?php if(isset($_POST['submit']) && !$formValid): echo '<p class="error">There was an error with your data.</p>'; endif; ?>
            <form role="form" action="index.php" onsubmit="return validateForm()" method="post" id="registerform">
                <div class="form-group">
                    <div class="form-group">
                        <label for="firstname">First Name:</label>
                        <input type="text" class="form-control" name="firstname" required <?php if(isset($fName)): echo 'value="'.$fName.'"'; endif; ?>>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name:</label>
                        <input type="text" class="form-control" name="lastname" required <?php if(isset($lName)): echo 'value="'.$lName.'"'; endif; ?>> 
                    </div>
                    <div class="form-group">
                        <label for="grade">Grade:</label>
                        <input type="number" name="grade" class="form-control" id="grade" 
                            <?php 
                                if(isset($grade)){
                                    echo 'value="'.$grade.'"';
                                }
                                else {
                                    echo 'value="9"';
                                }
                            ?> 
                        min='9' max='12' step= "1">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" required id="emailinput" <?php if(isset($email)): echo 'value="'.$email.'"'; endif; ?>>
                    </div>
                    <?php if($emailTaken): ?>
                        <p class="error">Email already in use.</p>
                    <?php endif; ?>
                    <?php if (!$sasEmail): ?>
                        <p class="error">Use your SAS email.</p>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="pw1">Password:</label>
                        <input type="password" class="form-control" name="password" id="pw1" required>
                    </div>
                    <div class="form-group">
                        <label for="pw2">Confirm Password:</label>
                        <input type="password" class="form-control" name="confirmpassword" id="pw2" required>
                    </div>
                    <input type="submit" class="btn btn-success btn-small" name="submit" value="Create my account">
                </div>
            </form>
        </div>
        <?php endif; ?>
    </body>
    <?php $dbc->close(); ?>
</html>