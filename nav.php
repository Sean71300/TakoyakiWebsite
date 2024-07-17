<div class="forNavigationbar sticky-top">
    <nav class="navbar navbar-expand-lg bg-custome ">
        <div class="container-fluid ">
        <a href="Admin_dashboard.php"><img src="Images/Logo.jpg" class="logo ms-4 ms-lg-5 rounded-circle" height=50></a>&nbsp;&nbsp;
        <a class="navbar-brand " href="Admin_dashboard.php"><b>Hentoki</b></a>&nbsp;|&nbsp;   
                <?php  
                if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                echo '<li class="nav-item">';
                echo '<a class="nav-link " href="Login.php">Login</a>';
                echo '</li>';
                }
                else{          
                echo  '<h5>';  
                echo  '<a class="navbar-brand " href="#" style="padding-top: 10.5px; padding-left: 15px;">';
                echo  '<b>';
                echo    "Welcome ".htmlspecialchars($_SESSION["full_name"]).'';  
                echo  '</b>'; 
                echo  '</a>';
                echo  '</h5>';  
                }
                ?>                     
        </div>                   
    </nav>
    </div>