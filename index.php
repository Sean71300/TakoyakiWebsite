

<html>
    <head>
        <title>
            Home Page
        </title>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/af468059ce.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="customCodes/custom.css">
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
          .bannerfont{
            font-size: 80px;
          }          
          .bannerimg{
            max-width: 400px;
            width: auto;
            height: auto;  
            animation: fade-right 2s ease 0s 1 normal forwards;			
          }
          .icons{
            max-width: 125px;
            width: auto;
            height: auto;          
          }
          .Bcol{
            background-color: #eb5757;
          }
          .Bcol:hover{
            background-color: #ed7b7a;
          }
         
		#caption {
			
			color: hsl(0, 4%, 11%, 0.1);
			background-repeat:no-repeat;
			background-size: 0% 100%;
			background-clip:text;
			background-image: linear-gradient(90deg, #000000, #000000);
			animation-timeline: view();
			
			animation: scroll-anim 3s ease 0s 1 normal forwards;
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
		
		@keyframes fade-left {
			0% {
				opacity: 0;
				transform: translateX(-50px);
			}

			100% {
				opacity: 1;
				transform: translateX(0);
			}
		}
		
		@keyframes fade-right {
			0% {
				opacity: 0;
				transform: translateX(50px);
			}

			100% {
				opacity: 1;
				transform: translateX(0);
			}
		}
		
		@keyframes btn-fade-left {
			0% {
				opacity: 0;
				transform: translateX(-50px);
			}
			
			90% {
				opacity: 0;
				transform: translateX(-100px);
			}

			100% {
				opacity: 1;
				transform: translateX(0);
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
		
		@keyframes fade {
			0% {
				opacity: 0;
			}
			
			70% {
				opacity: 0;
			}

			100% {
				opacity: 1;
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
    <?php
      session_start();
      include_once 'setup.php';
    ?>
	
    <!-- Loading Functionality -->

    <!-- NavBar -->
      <?php include "Navigation.php"?> 
      <div class="forOpener">
        <div class="container-fluid">
          <div class="bg-warning row py-lg-5 py-3">
            <div class="col-sm-0 col-md-1 col-lg-1  "></div>
            <div class="pt-lg-3 pt-0 pt-0 col-sm-12 col-md-11 col-lg-6  text-start text-light fw-bolder bannerfont" style="animation: fade-left 2s ease 0s 1 normal forwards;">
              Are you starving ?
              <br>
              <span class="fs-6 fw-light" id="caption">Within a few clicks, find meals that will satisfy you.</span>
              <br>
              <a href="About.php">
                <button type="button" class="btn Bcol rounded-5 text-light mt-4" style="animation: btn-fade-left 3s ease 0s 1 normal forwards;"><b>About Us</b></button>
              </a>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-5 text-center"><img src="Images/banner2.png" class="bannerimg "></div>
            <div class="col-sm-0 col-md-0 col-lg-0  "></div>
          </div>      
        </div>        
      </div>
      <div class="forMenu">        
        <div class="container-fluid mt-5">
          <div class="row pt-5" style="animation: fade-bottom 2.9s ease 0s 1 normal forwards;">
			<div class="text-center col-12 fs-3 fw-bolder">Most Popular Takoyaki<br></div>
            <div class="text-center col-12"><i>"A taste of authenticity"</i><br></div>
          </div>
          <div class="row pt-5 inline-block">
            <div class="col-1 text-center"></div>
            <div class="col-10">  
              <div class="row">
                <div class="col-6 col-lg-4" style="animation: fade-bottom 3s ease 0s 1 normal forwards;">
                  <div class="col-12 "><img src="Images/assorted-barkada.png" class="products rounded-top-5 img-fluid">
                    <div class="row mb-3">
                      <div class="col-6 fs-3 fw-bolder">Assorted Barkada</div>
                      <div class="col-6 text-end fs-3 fw-bolder text-warning">*</div>
                    </div>                    
                  </div>                         
                </div>
                <div class="col-6 col-lg-4" style="animation: fade-bottom 3s ease 0s 1 normal forwards;">                  
                  <div class="col-12 "><img src="Images/bacon bits.png" class="products rounded-top-5  img-fluid">
                    <div class="row mb-3">
                      <div class="col-6 fs-3 fw-bolder">Bacon Bits</div>
                      <div class="col-6 text-end fs-3 fw-bolder text-warning">*</div>
                    </div>                    
                  </div>              
                </div>
                <div class="col-6 col-lg-4" style="animation: fade-bottom 3s ease 0s 1 normal forwards;">                  
                  <div class="col-12"><img src="Images/octobits.png" class="products rounded-top-5  img-fluid">
                    <div class="row mb-3">
                      <div class="col-6 fs-3 fw-bolder">Octobits</div>
                      <div class="col-6 text-end fs-3 fw-bolder text-warning">*</div>
                    </div>                    
                  </div>              
                </div> 
                <div class="col-6 col-lg-4" style="animation: fade-bottom 3.5s ease 0s 1 normal forwards;">                  
                  <div class="col-12"><img src="Images/crab bits.png" class="products rounded-top-5  img-fluid">
                    <div class="row mb-3">
                      <div class="col-6 fs-3 fw-bolder">Crab Bits</div>
                      <div class="col-6 text-end fs-3 fw-bolder text-warning">*</div>
                    </div>                    
                  </div>              
                </div> 
                <div class="col-6 col-lg-4" style="animation: fade-bottom 3.5s ease 0s 1 normal forwards;">                  
                  <div class="col-12"><img src="Images/cheese bits.png" class="products rounded-top-5  img-fluid">
                    <div class="row mb-0">
                      <div class="col-6 fs-3 fw-bolder">Cheese Bits</div>
                      <div class="col-6 text-end fs-3 fw-bolder text-warning">*</div>
                    </div>                    
                  </div>              
                </div> 
                <div class="col-6 col-lg-4" style="animation: fade-bottom 3.5s ease 0s 1 normal forwards;">                  
                  <div class="col-12"><img src="Images/cheesy-barkada.png" class="products rounded-top-5  img-fluid">
                    <div class="row mb-0">
                      <div class="col-6 fs-3 fw-bolder">Cheesy Barkada</div>
                      <div class="col-6 text-end fs-3 fw-bolder text-warning">*</div>
                    </div>                    
                  </div>              
                </div>                   
              </div>               
            </div>
            <div class="col-1 text-center"></div>                  
          </div>
          <div class="text-center">
            <a href="Menu.php">
              <button type="button" class="btn Bcol rounded-5 text-light mt-4" style="animation: fade 4s ease 0s 1 normal forwards;"><b>See More</b></button>
            </a>
          </div>

        </div>
        
      </div> 
           
      <div class="forWhyus" style="animation: fade 3s ease 0s 1 normal forwards;">
        <div class="container-fluid">
          <div class="row">
            <div class="text-center col-12 fw-bolder fs-2 my-5">Why choose our signature takoyakis?</div>           
          </div>
          <div class="row" style="padding-left:40px;padding-right:40px;">
            <div class="col-sm-12 col-md-4 col-lg-4  p-2  ">
              <div class="bg-white rounded-5 text-center p-2 border border-secondary">
                <div class="row mt-5">                     
                  <div class="col-12">
                    <img src="Images/restaurant.png" class="img-fluid icons">
                  </div>  
                  <div class="col-12 fs-3 fw-bolder my-4">
                    Quality Food
                  </div>  
                  <div class="col-12 fs-4 my-4">
                    Made with fresh and quality ingredients, our takoyaki are crispy on the outside and fluffy on the inside.
                  </div>                     
                </div>                                               
              </div>
            </div> 
            <div class="col-sm-12 col-md-4 col-lg-4  p-2  ">
              <div class="bg-white rounded-5 text-center p-2 border border-secondary">
                <div class="row mt-5">                     
                  <div class="col-12">
                    <img src="Images/knife-and-fork.png" class="img-fluid icons">
                  </div>  
                  <div class="col-12 fs-3 fw-bolder my-4">
                    Authentic Flavors
                  </div>  
                  <div class="col-12 fs-4 my-4">
                    We use a traditional recipe in creating our takoyaki, delivering the true taste of Japan through our product.
                  </div>                     
                </div>                                               
              </div>
            </div> 
            <div class="col-sm-12 col-md-4 col-lg-4  p-2  ">
              <div class="bg-white rounded-5 text-center p-2 border border-secondary">
                <div class="row mt-5">                     
                  <div class="col-12">
                    <img src="Images/food-delivery.png" class="img-fluid icons">
                  </div>  
                  <div class="col-12 fs-3 fw-bolder my-4">
                    Fast Service
                  </div>  
                  <div class="col-12 fs-4 my-4">
                    Get your takoyaki quickly and easily. Our fast service means you won't have to wait long to enjoy our delicious takoyaki.
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