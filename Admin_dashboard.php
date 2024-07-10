<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0; /* Bg ng page pwede baguhin depende sainyo */
            margin: 0; 
        }
        .sidebar {
            background-color: white;
            height: 100vh; /* Full height sidebar */
            position: fixed;
            top: 0;
            left: -220px; /* Start off-screen sa navbar to */
            width: 220px;
            padding-top: 20px;
            transition: all 0.3s ease;
            z-index: 1030; 
        }
        .sidebar.active {
            left: 0; /* Slide in when active sa navbar */
        }
        .sidebar .admin-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-bottom: 10px;
            overflow: hidden;
            margin-left: 55px;
        }
        .sidebar .admin-icon img {
            width: 100%;
            height: auto;
        }
        .sidebar .admin-text {
            font-size: 16px;
            margin-bottom: 20px;
            margin-left: 60px;
            color: black;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .sidebar ul li {
            margin-bottom: 30px;
            margin-left: 15px;
        }
        .sidebar ul li a {
            display: flex;
            color: black;
            text-decoration: none;
            padding: 10px;
            transition: background-color 0.3s ease;
            border-radius: 5px;
        }
        .sidebar ul li a .fa-fw {
            margin-right: 10px; /* Adjust margin sa icon and text */
        }
        .sidebar ul li a:hover {
            background-color: #FFC10B;
        }

        .content {
            padding: 20px;
            margin-left: 0; 
            transition: margin-left 0.3s ease; /* Smooth transition for content */
            z-index: 1020; 
            position: relative; 
        }
        .header {
            text-align: right;
            padding: 15px;
            z-index: 1040; 
            position: relative; 
        }
        .container {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        } 
        .product-container {
            background-color: #FFC10B;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .product-container h3 {
            margin-bottom: 20px;
            text-align: left; 
        }
        .product-container .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-right: -10px;
            margin-left: -10px;
        }
        .product-container .col-md-4 {
            flex: 25%;
            max-width: 25%;
            padding: 0 10px;
        }
        .product-container .product-card {
            background-color: #fff;
            padding: 0px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }
        .product-container .product-card .product-image img {
            max-width: 100%;
            height: auto; /* Maintain aspect ratio */
            border-radius: 50%;
            display: block; 
            margin: 0 auto; 
        }
        .product-container .product-card .product-info {
            text-align: center;
        }
        .product-container h4 {
            margin-top: 10px;
            font-size: 16px;
        }
        .product-container .price {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .product-container .ratings {
            color: #ffd700; /* Gold */
            margin-bottom: 5px;
        }
        .product-container .num-ratings {
            margin-left: 5px;
            color: #333;
        }
        .product-container .see-all {
            text-align: right;
            margin-top: 10px;
        }
        .product-container .see-all a {
            color: white;
            text-decoration: none;
        }
        .product-container .see-all a:hover {
            text-decoration: underline;
        }
        .ratings-container {
            background-color: #f0f0f0;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .ratings-container h3 {
            margin-bottom: 20px;
            text-align: center;
        }
        .ratings-container .review-card {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .ratings-container .review-card .stars {
            color: #ffd700;
            margin-bottom: 10px;
            display: flex;
            justify-content: right;
        }
        .ratings-container .review-card .review-text {
            margin-bottom: 10px;
            line-height: 1.6;
            text-align: left;
        }
        .ratings-container .review-card .reviewer {
            color: #555;
            text-align: left;
        }
        .ratings-container .see-more-reviews {
            text-align: center;
            margin-top: 20px;
        }
        .ratings-container .see-more-reviews a {
            color: #333;
            text-decoration: none;
        }
        .ratings-container .see-more-reviews a:hover {
            text-decoration: underline;
            text-align: right;
        }

        /* Media Queries */
        @media (max-width: 992px) {
            .sidebar {
                left: -280px; 
            }
            .sidebar.active {
                left: 0; /* Slide in when active */
            }
            .content {
                margin-left: 0; /* Content area adjusts */
            }
            .container {
                padding: 10px; /* Adjust padding for smaller screens */
            }
            .header {
                padding-right: 30px; 
            }
            .product-container h4 {
                font-size: 14px; /* Adjust font size for smaller screens */
            }
        }

        @media (max-width: 768px) {
            .product-container .col-md-4 {
                flex: 0 0 100%; /* One product per row on pag minimize */
                max-width: 100%;
            }
        }

        .full-image-container {
            margin-bottom: 20px; 
            overflow: hidden;
        }

        .full-image-container img {
            width: 100%; /* Ensure image fills its container */
            max-width: none; 
            height: auto; /* Maintain aspect ratio */
            border-radius: 10px; 
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="text-center">
            <div class="admin-icon">
                <img src="https://via.placeholder.com/60" alt="Admin Image">
            </div>
            <div class="admin-text">Admin</div>
        </div>
        <ul>
            <li><a href="Admin_dashboard.html"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a></li>
            <li><a href="Admin_transaction.html"><i class="fas fa-history fa-fw"></i> Transaction History</a></li>
            <li><a href="Admin_employee.html"><i class="fas fa-user-tie fa-fw"></i> Employees</a></li>
            <li><a href="#"><i class="fas fa-users fa-fw"></i> Customers</a></li>
            <li><a href="#"><i class="fas fa-th fa-fw"></i> Categories</a></li>
            <li><a href="#"><i class="fas fa-box-open fa-fw"></i> Products</a></li>
			<li><a href="#"><i class="fas fa-star fa-fw"></i> Ratings</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt fa-fw"></i> Sign Out</a></li>
			<li><a href="#"><i class="fas fa-question-circle fa-fw"></i>Help</a></li>
			</ul>
    </div>

    <!-- Content -->
    <div class="content" id="main-content">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
			<!-- Hamburger icon -->
                <button class="navbar-toggler" type="button" id="sidebarCollapseButton">
                    <i class="fas fa-bars"></i> 
                </button>
		
		<div class="header">
    		Welcome, Admin
		</div>
			
        <div class="full-image-container">
            <img src="Images/dashboard-image.jpg" class="d-block w-100" alt="Full Width Image">
        </div>
		
	<div class="product-container">
     <h3 style="text-align: center;">Popular Products</h3>
     <div class="row justify-content-center">
        <!-- Product 1 -->
        <div class="col-md-4">
            <div class="product-card">
                <div class="product-image text-center">
                    <img src="Images/octobits.png" alt="Takoyaki Image">
                </div>
                <div class="product-info">
                    <h4>Octo Bits</h4>
                    <p class="price">$100</p>
                    <div class="ratings">
                        <div class="stars">★★★★☆</div>
                        <div class="num-ratings">230 reviews</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Product 2 -->
        <div class="col-md-4">
            <div class="product-card">
                <div class="product-image text-center">
                    <img src="Images/octobits.png" alt="Sushi Image">
                </div>
                <div class="product-info">
                    <h4>Crab Bits</h4>
                    <p class="price">$12.99</p>
                    <div class="ratings">
                        <div class="stars">★★★★★</div>
                        <div class="num-ratings">450 reviews</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Product 3 -->
        <div class="col-md-4">
            <div class="product-card">
                <div class="product-image text-center">
                    <img src="Images/octobits.png" alt="Ramen Image">
                </div>
                <div class="product-info">
                    <h4>Cheese Bits</h4>
                    <p class="price">$8.99</p>
                    <div class="ratings">
                        <div class="stars">★★★☆☆</div>
                        <div class="num-ratings">120 reviews</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="see-all">
        <a href="#">See All</a>
    </div>
   </div>
        <!-- Ratings Container -->
        <div class="container ratings-container">
            <h3>Customer Ratings & Reviews</h3>
            <div class="row justify-content-center">
                <!-- Individual review cards -->
                <!-- Review 1 -->
                <div class="col-md-6">
                    <div class="review-card">
                        <div class="stars">★★★★☆</div>
                        <p class="review-text">This product is amazing. It exceeded my expectations! I would highly recommend it.</p>
                        <p class="reviewer">Reviewed by John Doe</p>
                    </div>
                </div>
                <!-- Review 2 -->
                <div class="col-md-6">
                    <div class="review-card">
                        <div class="stars">★★★★★</div>
                        <p class="review-text">I love this product. The quality is outstanding! Definitely worth the purchase.</p>
                        <p class="reviewer">Reviewed by Jane Smith</p>
                    </div>
                </div>
            </div>
            <div class="see-more-reviews">
                <a href="#">See More Reviews</a>
            </div>
        </div>

 
    <script src="js/bootstrap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        document.getElementById('sidebarCollapseButton').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('main-content').classList.toggle('active');
            adjustContentMargin();
        });
		
        // Function to adjust content margin-left based on sidebar state
        function adjustContentMargin() {
            const sidebarWidth = document.getElementById('sidebar').offsetWidth;
            const content = document.getElementById('main-content');
            
            if (content.classList.contains('active')) {
                content.style.marginLeft = sidebarWidth + 'px';
            } else {
                content.style.marginLeft = '0';
            }
        }
		
        adjustContentMargin();

        window.addEventListener('resize', adjustContentMargin);
    </script>
</body>
</html>
