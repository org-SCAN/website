

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/dbd5498a57.js" crossorigin="anonymous"></script>
    <style>

        #content {
            position:relative;
        }

        .right-abs {
            position:absolute;
            top: 12px;
            right:15px ;
        }

        a { color: inherit; }



    </style>
    <title>Red Cross menu page</title>
</head>

<body>





<!-- Menu to chose the pages the we are going to access >
-->
<nav class="navbar navbar-expand-lg navbar-dark bg-light">
    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="nav justify-content-center nav-tabs  mr-auto; ">
            <li class="nav-item active">
                <a class="nav-link" href="home.blade.php"><i class="fas fa-home"></i>
                    Home
                    <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="analyse_data.blade.php"><i class="fas fa-server"></i>
                    Analyse data
                    </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="modify_data.blade.php"><i class="fas fa-mouse-pointer"></i>
                    Modify data
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle"  id="navbarDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-cog"></i>
                    Configurations
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="change_password.blade.php"><i class="fas fa-lock"></i>
                        Change Password
                    </a>
                    <a class="dropdown-item" href="edit_users.blade.php"><i class="fas fa-users"></i>
                        Edit users
                    </a>
                    <a class="dropdown-item" href="change_fileds.blade.php"><i class="fas fa-database"></i>
                        Change the fields
                    </a>
                    <a class="dropdown-item" href="new_user.blade.php"><i class="fas fa-user-plus"></i>
                        Create a new account
                    </a>
                </div>
            </li>
        </ul>

    </div>
</nav>



<!-- The page that we are curently on
       >
-->
<div class="container-xxl">
    Analyse data

</div>



<!-- Chose the language buttons >
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
