<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Techbase</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include 'header.php'; ?>

    <section class="dashboard">
        <section class="users">

            <h1 class="title">Account</h1>

            <div class="box-container">
                <div class="account-box">
                    <p>Jméno: <span>
                            <?php echo $_SESSION['user_name']; ?>
                        </span></p>
                    <p>Email: <span>
                            <?php echo $_SESSION['user_email']; ?>
                        </span></p>
                    <a href="logout.php" class="delete-btn">logout</a>
                    <div><a href="register.php">register</a></div>
                </div>
            </div>
        </section>
    </section>
    <script src="js/admin_script.js"></script>
    <?php include 'footer.php'; ?>
</body>

</html>