<div class="forNavigationbar sticky-top">
    <nav class="navbar navbar-expand-lg bg-custome ">
        <div class="container-fluid ">
        <a href="Admin_dashboard.php"><img src="Images/Logo.jpg" class="logo ms-4 ms-lg-5 rounded-circle" height=50></a>&nbsp;&nbsp;
        <a class="navbar-brand " href="Admin_dashboard.php"><b>Hentoki</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ps-5 mb-2 mb-lg-0 col d-flex justify-content-between">      
                <?php  
                if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                echo '<li class="nav-item">';
                echo '<a class="nav-link " href="Login.php">Login</a>';
                echo '</li>';
                }
                else{          
                echo  '<div>';
                echo  '<h5>';
                echo    "Welcome ".htmlspecialchars($_SESSION["full_name"]).'';   
                echo  '</h5>';
                echo  '</div>';
                }
                ?>                               
            </ul>   
                                
            </div>                    
        </div>                   
    </nav>
    </div>