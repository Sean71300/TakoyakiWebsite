<?php
  session_start();
?>
<html>
    <head>
        <title>
          Personnel Page
        </title>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/af468059ce.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
          .logo {
                display: inline-block;
                max-width: 50px;
                width: auto;
                height: auto;
                border-radius: 50%;
                margin-left: 2%;
              } 
          .footer{
            display: inline-block;
            max-width: 50px;
            width: auto;
            height: auto;
            border-radius: 50%;
          } 
          .footer1{
            display: inline-block;
            max-width: 35px;
            width: auto;
            height: auto;
          }
		  
		  .banner{
        background-image: linear-gradient(180deg, rgba(0, 0, 0, 0.80), rgb(0, 0, 0,0.75)), url('Images/banner_t.png');;
        background-repeat: no-repeat;
        background-position: 100% 25%;
        background-attachment:fixed;
        background-size: cover;
		  }
		  
		  #caption {
			color: hsl(0, 4%, 11%, 0.1);
			background-repeat:no-repeat;
			background-size: 0% 100%;
			background-clip:text;
			background-image: linear-gradient(90deg, #ffffff, #ffffff);
			animation-timeline: view();			

			animation: scroll-anim 1.5s ease 0s 1 normal forwards;
		}
		
		@keyframes scroll-anim{
		  0% {
			background-size: 0% 100%;
		  }
		  
		  60% {
			background-size: 0% 100%;
		  }
		  
		  100% {
			background-size: 100% 100%;
		  }
		}
		
		@keyframes fade {
			0% {
				opacity: 0;
			}
			
			50% {
				opacity: 0;
			}

			100% {
				opacity: 1;
			}
		}
		
		@keyframes fade-bottom {
			0% {
				opacity: 0;
				transform: translateY(50px);
			}
			
			70% {
				opacity: 0;
				transform: translateY(50px);
			}

			100% {
				opacity: 1;
				transform: translateY(0);
			}
		}
		
		.loading-screen {
		  position: fixed;
		  top: 0;
		  left: 0;
		  width: 100%;
		  height: 100%;
		  display: flex;
		  background-color:white;
		  justify-content: center;
		  align-items: center;
		  opacity:1;
		  transition: opacity 1s ease-in-out;
		  z-index: 999;
		}

		.loading-screen img {
		  animation: spin 1s linear forwards;
		}

		@keyframes spin {
		  from { transform: rotate(0deg); }
		  to { transform: rotate(1000deg); }
		}
    
        </style>
    </head>
    <body class="bg-body-tertiary">
	<script>
		window.onload = function() {
		  const loadingScreen = document.querySelector('.loading-screen');
		  loadingScreen.style.opacity = 0;
		  setTimeout(() => {
		    loadingScreen.remove();
			document.body.style.display = 'block';
		  }, 1000);
		}
	  </script>
	  <div class="loading-screen">
		<img src="Images/loading.png" alt="Loading...">
	  </div>
      <!-- Navbar -->
    <?php include "Navigation.php"?>      
	  <div class="forbanner">
        <div class="banner col-12 bg-black ">
          <div class="py-lg-5 py-3"></div>
          <div class="col-12 text-center text-light fs-1 fw-bolder" style="animation: fade 1s ease 0s 1 normal forwards;">
            MEET THE TEAM
            <span class="fw-light fs-4" id="caption">
              <br> The Minds Behind The Flavors
            </span>
          </div>
          <div class="py-lg-5 py-3 "></div>
        </div>
    </div>
      <div class="forWhyus" style="animation: fade-bottom 2s ease 0s 1 normal forwards;">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-sm-6 col-md-6 col-lg-5 p-5">
        <div class="bg-white rounded-5 p-5 shadow-sm">
          <div class="row mt-2">
            <div class="col-12 text-center" >
              <img src="Images/personA.png" class="img-fluid icons">
            </div>
            <div class="col-12 fs-4 my-2 text-left">
              <b>Jam <span style="color:#eb5757;">Asprilla</span><br></b>
			  <span style="font-size:20px;">Vendor</span>
            </div>
            <div class="col-12 fs-7 my-1 text-left">
              Brainstorming new flavor combinations and presentation ideas, Jam thrives in a fast-paced environment and keeps things fun for both customers and coworkers.
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-5 p-5">
        <div class="bg-white rounded-5 p-5 shadow-sm">
          <div class="row mt-2">
            <div class="col-2,11 text-center" >
              <img src="Images/personB.png" class="img-fluid icons">
            </div>
            <div class="col-12 fs-4 my-2 text-left">
              <b>Joe <span style="color:#eb5757;">Allana</span><br></b>
			  <span style="font-size:20px;">Vendor</span>
            </div>
            <div class="col-12 fs-7 my-1 text-left">
              Ensuring each takoyaki is perfectly cooked and presented. You can always count on Joe for consistent quality that keeps the takoyaki operation running smoothly.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

      <!-- Footer -->
    <?php include "Footer.php"?>      

    </body>
</html>