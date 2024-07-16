<div class="forNavigationbar sticky-top">
    <nav class="navbar navbar-expand-lg bg-body-tertiary ">
        <div class="container-fluid ">
        <a href="index.php"><img src="Images/Logo.jpg" class="logo ms-4 ms-lg-5 "></a>
        <a class="navbar-brand " href="index.php"><b>Hentoki</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ps-5 mb-2 mb-lg-0 col d-flex justify-content-between">                  
                <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="About.php">About</a>
                </li>
                <li class="nav-item">
                <a class="nav-link"  href="Menu.php">Menu</a>
                </li>
                <li class="nav-item">
                <a class="nav-link"  href="Pages.php">Personnel</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="Contact.php">Contact</a>
                </li>                  
                <?php  
                if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                echo '<li class="nav-item">';
                echo '<a class="nav-link " href="Login.php">Login</a>';
                echo '</li>';
                }
                else{          
                echo  '<div class="dropdown">';
                echo  '<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">';
                echo    "Welcome ".htmlspecialchars($_SESSION["full_name"]).'';   
                echo  '</button>';
                echo  '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                echo    '<li><a class="dropdown-item" href="posted-rate.php">Rate</a></li>';
                echo    '<li><a class="dropdown-item" href="edit.php">Account Settings</a></li>';
                echo    '<li><a class="dropdown-item" href="logout.php">Sign Out</a></li>';
                echo  '</ul>';
                echo  '</div>';
                
                }
                ?>                               
            </ul>   
                                
            </div>                    
        </div>                   
    </nav>
    </div>