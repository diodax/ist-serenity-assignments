<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Homework 01 - SQL Injection</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <?php
            $mysqli = new mysqli("localhost", "dl8006", "yellowshall", "dl8006");

            // define variables and set to empty values
            $username1 = "";            
            $password1 = "";
            $username2 = "";
            $password2 = "";

            if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['form1'])) {
                // Part 1: No Sanitization 
                $username1 = $_POST["username1"];
                $password1 = $_POST["password1"];

                $result1 = $mysqli->query("SELECT * FROM users WHERE username = '{$username1}' AND password = '{$password1}'");
                $row1 = $result1->fetch_assoc();
                $row_cnt1 = $result1->num_rows;
            }

            if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['form2'])) {
                // Part 2: Prepared Statement
                $username2 = $_POST["username2"];
                $password2 = $_POST["password2"];

                $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
                $stmt->bind_param("ss", $username2, $password2);
                $stmt->execute();
                $result2 = $stmt->get_result();

                $row2 = $result2->fetch_assoc();
                $row_cnt2 = $result2->num_rows;
                $stmt->close();
            }
        ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title">SQL Injection (HW 01)</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Part 1: No Sanitization</h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-inline" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <input type="hidden" name="form1" value="yes" />
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="username1">Username</label>
                                                <input type="text" class="form-control" id="username1" name="username1" placeholder="Username">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="password1">Password</label>
                                                <input type="password" class="form-control" id="password1" name="password1" placeholder="Password">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php 
                                                    if (isset($result1) && $row_cnt1 > 0) {
                                                        // Login sucessfull
                                                        $parsed_username = htmlentities($row1['username']);
                                                        echo "<div class='alert alert-success' role='alert'>Welcome back, {$parsed_username}!</div>";
                                                    } else if (isset($result1) && $row_cnt1 == 0) {
                                                        // Login failed
                                                        echo '<div class="alert alert-danger" role="alert">Sorry, the username / password is incorrect.</div>';
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="alert alert-info" role="alert">
                                            Test Data:
                                            <br /><br />
                                            Username: diodax
                                            <br />
                                            Password: 1234
                                            <br /><br />
                                            Username: ' OR 1=1; -- (add a space in the end)
                                            <br />
                                            Password: (anything)
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" id="submitBtn1" class="btn btn-default">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Part 2: Prepared Statements</h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-inline" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <input type="hidden" name="form2" value="yes" />
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="username2">Username</label>
                                        <input type="text" class="form-control" id="username2" name="username2" placeholder="Username">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="password2">Password</label>
                                        <input type="password" class="form-control" id="password2" name="password2" placeholder="Password">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <?php 
                                            if (isset($result2) && $row_cnt2 > 0) {
                                                // Login sucessfull
                                                $parsed_username = htmlentities($row2['username']);
                                                echo "<div class='alert alert-success' role='alert'>Welcome back, {$parsed_username}!</div>";
                                            } else if (isset($result2) && $row_cnt2 == 0) {
                                                // Login failed
                                                echo '<div class="alert alert-danger" role="alert">Sorry, the username / password is incorrect.</div>';
                                            }
                                        ?>
                                    </div>
                                </div>

                                <button type="submit" id="submitBtn2" class="btn btn-default">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<style>
    .title {
        margin-bottom: 5px;
        font-size: 30px;
        line-height: 1.5;
    }
    .row .form-group {
        margin-bottom: 15px;
    }
</style>