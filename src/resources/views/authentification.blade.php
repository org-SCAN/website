

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/dbd5498a57.js" crossorigin="anonymous"></script>
    <style>

        body{
            background: #f1f1f1;
        }
        #container{
            width:400px;
            margin:0 auto;
            margin-top:10%;
        }
        /* Bordered form */
        form {
            width:100%;
            padding: 30px;
            border: 1px solid#0C2D48;
            background: linear-gradient(to top right, #000066 0%, #0099ff 100%);
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
        }

        #container h1{
            width: 38%;
            margin: 0 auto;
            padding-bottom: 10px;
        }

        /* Full-width inputs */
        input[type=text], input[type=password] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        /* Set a style for all buttons */
        input[type=submit] {
            background-color:rgb(248, 249, 250);
            border: 1px solid rgb(0, 0, 0);
            color: black;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        input[type=submit]:hover {
            background-color:rgb(173, 181, 189);
            color: black;
            border: 1px solid rgb(31, 27, 27);

        }


    </style>
    <title>Red Cross menu page</title>
</head>

<body>

<!-- Login fields  >
-->
<div id="container">
    <form action="verification.php" method="POST">
        <h1>Login</h1>

        <label><b>Username</b></label>
        <input type="text" placeholder="Write your username" name="username" required>

        <label><b>Password</b></label>
        <input type="password" placeholder="Write your password" name="password" required>

        <input type="submit" id='submit' value='Login' >

    </form>
</div>

<!-- Chose the language buttons  >
-->
<div class="panel-body">
    <nav class="navbar ">
        <div class="right-abs">
            <div class="fixed-bottom">
                <div class="btn-group" class="ribbon" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-secondary">FR</button>
                    <button type="button" class="btn btn-secondary">EN</button>
                    <button type="button" class="btn btn-secondary">ES</button>
                </div>
            </div>
        </div>
    </nav>
</div>


</body>
</html>
