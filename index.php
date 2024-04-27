<?php 
    if(isset($_POST['send_message'])) {
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $privateKey = "6LcFyukZAAAAAKFwfuniyp31iw7WAOP2L0c8eQh9";
        $response = file_get_contents($url."?secret=".$privateKey."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']);
        $data = json_decode($response);
        if (isset($data->success) AND $data->success==true) {
            $error = "";
            $successMsg = "";
            if ($_POST) {
                if ($_POST['email'] && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
                    $error .= "The email is invalid!<br>";
                }
                if (!$_POST['name']) {
                    $error .= "A name address is required!<br>";
                }
				if (!$_POST['email']) {
                    $error .= "An email address is required!<br>";
                }
                if (!$_POST['subject']) {
                    $error .= "A subject is required!<br>";
                }
                if (!$_POST['message']) {
                    $error .= "Content in the body is required!<br>";
                }
                if ($error != "") {
                    $error = '<div class="alert alert-danger" role="alert"><strong>There is an error with your form!</strong><br>' . $error . '</div>';
                } else {
                    $emailTo = 'info@dthlivetv.com';
					$name = $_POST['name'];
                    $subject = $_POST['subject']." [".$name."]";
                    //$message = "Name:".$name."------".$_POST['message'];
                    $message = $_POST['message'];
                    $headers = "From: ".$_POST['email'];
					//$headers = array("From: from@example.com",
					//"Reply-To: replyto@example.com",
					//"X-Mailer: PHP/" . PHP_VERSION
					//);
                    if (mail($emailTo, $subject, $message, $headers)) {
                        $successMsg = '<div class="alert alert-success" role="alert">The message has successfully been sent. We will contact you ASAP!</div>';
                    } else {
                        $error = '<div class="alert alert-danger" role="alert">There was a problem sending your message, please try again later!</div>';
                    }
                }
            }
        } else {
            $captchaFail = '<div class="alert alert-danger" role="alert"><strong>There is an error with your form!</strong><br>reCaptcha Verification Failed, Please Try Again.</div>';
        }
    }
?>



<!DOCTYPE html>

<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <title>ADplication</title>
    <meta name="description" content="">
    <meta name="author" content="ADplication Owner Sivesh-Kumar ">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/modernizr.js"></script>
    <script src="js/pace.min.js"></script>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5TH98G4');</script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const categoryDropdown = document.getElementById('category');
        const monthDropdown = document.getElementById('month');
        const subcategoryDropdown = document.getElementById('subcategory');
        const submitBtn = document.getElementById('submitBtn');
        function fetchSubcategoryData(selectedCategory, selectedMonth) {
            return fetch(`Category_Benchmarks_${selectedMonth}_2024.json`)
                .then(response => response.json())
                .then(data => {
                    return data[selectedCategory].map(item => item["Subcategory Name"]);
                })
                .catch(error => {
                    console.error('Error fetching subcategory data:', error);
                    return [];
                });
        }

        function populateSubcategoryDropdown(subcategories) {
            subcategoryDropdown.innerHTML = '';
            subcategories.forEach(subcategory => {
                const option = document.createElement('option');
                option.value = subcategory;
                option.text = subcategory;
                subcategoryDropdown.add(option);
            });
        }
        function displayDashboard(selectedCategory, selectedMonth, selectedSubcategory) {
            fetch(`Category_Benchmarks_${selectedMonth}_2024.json`)
                .then(response => response.json())
                .then(data => {
                    const selectedData = data[selectedCategory].find(item => item["Subcategory Name"] === selectedSubcategory);
                    const dashboardContainer = document.querySelector('.dashboard-container');
                    dashboardContainer.innerHTML = ''; 
                    const dashboardElement = document.createElement('div');
                    dashboardElement.classList.add('dashboard-element');
                    var formattedNum = selectedData.ROAS.toFixed(1);
                    dashboardElement.innerHTML = `
                        <h2>${selectedData["Subcategory Name"]}</h2>
                        <p>ROAS: ${formattedNum}</p>
                    `;
                    dashboardContainer.appendChild(dashboardElement);
                })
                .catch(error => console.error('Error fetching data:', error));
        }
        categoryDropdown.addEventListener('change', function() {
            const selectedCategory = categoryDropdown.value;
            const selectedMonth = monthDropdown.value;
            fetchSubcategoryData(selectedCategory, selectedMonth)
                .then(subcategories => {
                    populateSubcategoryDropdown(subcategories);
                });
        });
        monthDropdown.addEventListener('change', function() {
            const selectedCategory = categoryDropdown.value;
            const selectedMonth = monthDropdown.value;
            
            fetchSubcategoryData(selectedCategory, selectedMonth)
                .then(subcategories => {
                    populateSubcategoryDropdown(subcategories);
                });
        });
        
        submitBtn.addEventListener('click', function() {
            const selectedCategory = categoryDropdown.value;
            const selectedMonth = monthDropdown.value;
            const selectedSubcategory = subcategoryDropdown.value;
            displayDashboard(selectedCategory, selectedMonth, selectedSubcategory);
        });
    });
        
        
    </script>
    <style>
        .form-container div {
            display: inline-block;
            margin-right: 10px;
        }
        .bor {
            border: 1px solid #000;
            padding: 20px;
        }
        .plus {
            height: 24px;
            width: 24px;
            display: inline-block;
            background-color: black;
            color: white;
            font-size: 24px;
            line-height: 24px;
            text-align: center;
        }

        .plus::before {
            content: "+";
        }
        
    @media only screen and (max-width: 900px) {
        wrapper1{
            display: none;
        }
  }

    </style>
</head>
<body id="top">
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5TH98G4"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <section style="height: 200px;">
    <header class="s-header fixed-top">
        <div class="header-logo"><br>
            <a class="site-logo" href="index.html">
                <img src="images/avatars/Adplcation.png" alt="Homepage">
                <p style="color: green;">
                    <strong >ADplication</strong>
                </p>
            </a>
            
        </div>
        <nav class="header-nav">
            <a href="#0" class="header-nav__close" title="close"><span>Close</span></a>
            <div class="header-nav__content">
                <h3>Navigation</h3>
                <ul class="header-nav__list">
                    <li class="current"><a class="smoothscroll"  href="#home" title="home">Home</a></li>
                    <li><a class="smoothscroll"  href="#about" title="about">About</a></li>
                    <li><a class="smoothscroll"  href="#services" title="services">Services</a></li>
                    <li><a class="smoothscroll"  href="#works" title="works">Works</a></li>
                    <li><a class="smoothscroll"  href="#clients" title="clients">Clients</a></li>
                    <li><a class="smoothscroll"  href="#contact" title="contact">Contact</a></li>
                </ul>
                <p>OUR SUCCESSES Our track record shows that we increase our clients sales from 20-200+% year over year, while targeting a less than 20% Advertising<a href='#0'> Cost of Sale </a>(ACoS, the total spend on advertising divided by attributed sales) through our proprietary method of keyword search term research and maintenance of advertising performance
                </p>
                <ul class="header-nav__social">
                    <!-- <li>
                        <a href="#"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                    </li> -->
                    <li>
                        <a href="https://www.linkedin.com/company/ad-plication/about/?viewAsMember=true" target="_blank"><i style="color:green" class="fa fa-linkedin" aria-hidden="true"></i>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="#"><i class="fa fa-dribbble"></i></a>
                    </li> -->
                </ul>
            </div> <!-- end header-nav__content -->
        </nav>  <!-- end header-nav -->
        <a class="header-menu-toggle" href="#0">
            <span class="header-menu-text">Menu</span>
            <span class="header-menu-icon"></span>
        </a>
    </header> <!-- end s-header -->
    <!-- home
    ================================================== -->
</section>

    
    <section id="home" class="s-home target-section g" >
        <div class="container" style="margin-left: 39px;">
            <div class="row" style="display:flex;">
                <div class="col-md-8 home-content__main q" style="text-align: justify;">
                    <h1 id="Head" style="color: green;">Welcome to ADplication</h1>
                    <h3 id="Head2" style="color: green; margin-top: -4px;">
                        THE AGENCY ACCELERATING <br> YOUR JOURNEY FROM LISTING TO SELLING<br>
                    </h3>
                    <h4 style="color:black; margin-top: -4px; text-align: justify;">
                    Stop struggling and start thriving!<br> 
                    ADplication empowers your online business<br>
                    with the tools and expertise you need to succeed.
                    </h4>
                
                    <div>
                        <ul class="" style="color: white;">
                            <!-- <li>
                                <a href="#0"><i class="fa fa-facebook" aria-hidden="true"></i><span>Facebook</span></a>
                            </li>
                            <li>
                                <a href="#0"><i class="fa fa-twitter" aria-hidden="true"></i><span>Twiiter</span></a>
                            </li>
                            <li>
                                <a href="#0"><i class="fa fa-instagram" aria-hidden="true"></i><span>Instagram</span></a>
                            </li> -->
                            <li>
                                <a href="https://www.linkedin.com/company/ad-plication/about/?viewAsMember=true"><i style="padding-top: 2px;background-color: green;border-radius: 50%;width: 26px;height: 23px;padding-left: 6px;" class="fa fa-linkedin" aria-hidden="true"></i><span>Linkedin</span></a>
                            </li>
                            <!-- <li>
                                <a href="#0"><i class="fa fa-dribbble" aria-hidden="true"></i><span>Dribbble</span></a>
                            </li> -->
                        </ul> 
                    </div>
                    <div class="home-content__buttons">
                        <a href="#contact" style="color: green; border-color: green;" class="smoothscroll btn btn--stroke">
                            Start a Project
                        </a>
                        <a href="#about" class="smoothscroll btn btn--stroke" style="color: green; border-color: green;">
                            More About Us
                        </a>

                    </div>  
                </div>
                <div style="margin-top:240px;" class="col-md-4 l" >
                    <div id="wrapper " style="margin-left:60px ;">
                        <!-- <div class="gears" id="two-gears">s
                            <div class="gears-container">
                                <div class="gear-rotate"></div>
                                <div class="gear-rotate-left"></div>
                            </div>
                        </div>
                        <div class="gears">
                            <div class="gears-container">
                                <div class="gear-rotate"></div>
                                <div class="gear-rotate-left"></div>
                            </div>
                        </div>
                        <div class="gears">
                            <div class="gears-container">
                                <div class="gear-rotate"></div>
                                <div class="gear-rotate-left"></div>
                            </div>
                        </div> -->
                        <div class="wrapper1" style="margin-top: -150px;">
                            <img src="images/avatars/74pZ.gif" alt="Graph" width="400px" height="600px">
                        </div>
                    </div>
                </div>
        </div>
            <div class="home-content__scroll">
                <a href="#about" class="scroll-link smoothscroll">
                    <span style="color:green">Scroll Down</span>
                </a>
            </div>
            
            <div class="home-content__line"></div>

        </div> <!-- end home-content -->


    </section> <!-- end s-home -->
<hr style="background-color:GREY;">

    <!-- about
    ================================================== -->
    <section id='about' class="s-about">

        <div class="row section-header has-bottom-sep" data-aos="fade-up">
            <div class="col-full">
                <h3 class="subhead subhead--dark"></h3>
                <h1 class="display-1 display-1--light">We Are ADplication</h1>
            </div>
        </div> <!-- end section-header -->

        <div class="row about-desc" data-aos="fade-up">
            <div class="col-full">
                <p style="text-align: justify;">
                    ADplication is your expert partner for elevating your brand on E-Commerce (speciality Amazon). With over 9 years of experience, we use data to increase your sales and visibility. Our customized plans are built to make your brand shine in the competitive E-Commerce space. We've helped many brands grow, and we're ready to simplify the process for you. At ADplication, we take on the complexities of the platform, allowing you to focus on your core business. Let's work together to take your E-Commerce journey to the next level.
                </p>
            </div>
        </div> <!-- end about-desc -->
        
        <div class="row about-stats stats block-1-4 block-m-1-2 block-mob-full" data-aos="fade-up">
            <div class="col-block col-md-2 stats__col">
                <div><span class="stats__count" style="color:green;">2500</span><span class="display-1" style="color:green;">+</span></div>
                <h5>Campaign's Launch</h5>
            </div>
            <div class="col-block stats__col col-md-2">
                <div><span class="stats__count" style="color:green;">42</span><span class="display-1" style="color:green;"></span></div>
                <h5>Projects Completed</h5>
            </div>
            <div class="col-block stats__col col-md-2">
                <div><span class="stats__count" style="color:green;">7</span><span class="display-1" style="color:green;"></span></div>
                <h5>Happy Clients</h5> 
            </div>
            <div class="col-block stats__col">
                <img src="images/Amazon-Advisory.webp" alt="Amazon-Advisory" width="150px" height="auto" style="padding-bottom: 10px;">
            <h5>Awards Received
            </h5></div>
        </div> <!-- end about-stats -->
        
        <div class="about__line"></div>

    </section> <!-- end s-about -->

    <hr style="background-color:GREY;">
    <!-- services
    ================================================== -->
    <section id='services' class="s-services">

        <div class="row section-header has-bottom-sep" data-aos="fade-up">
            <div class="col-full">
                <h3 class="subhead">What We Do</h3>
                <h1 class="display-2">We’ve got everything you need to launch and grow your business</h1>
            </div>
        </div> <!-- end section-header -->

        <div class="row services-list block-1-2 block-tab-full">

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon">
                    <i class="icon-paint-brush"></i>
                </div>
                <div class="service-text">
                    <h3 class="h2">SEO </h3>
                    <p>Increase your visibility with our expert SEO. We tackle online search ranking challenges to ensure your products stand out.
                    </p>
                </div>
            </div>
            

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon">
                    <i class="icon-group"></i>
                </div>
                <div class="service-text">
                    <h3 class="h2">ADVERTISING MANAGEMENT</h3>
                    <p>Boost your growth with our effective and affordable PPC service.Expect higher profis, lower ACOS, and clear ad management.
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon">
                    <i class="icon-megaphone"></i>
                </div>  
                <div class="service-text">
                    <h3 class="h2">ACCOUNT MANAGEMENT</h3>
                    <p>Streamline your operations with our full account management, freeing up your time, reducing stress, and ensuring peace of mind.
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon">
                    <i class="icon-earth"></i>
                </div>
                <div class="service-text">
                    <h3 class="h2">LISTING CREATION</h3>
                    <p>Improve your sales with better listings.
                        We assist in creating them by analyzing recent trends and aligning with your brand theme for engaging and high-converting listings.
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon">
                    <i class="icon-cube"></i>
                </div>
                <div class="service-text">
                    <h3 class="h2">PRODUCT LAUNCH</h3>
                    <p>Launch your Procts on multiple E-Commerce platform with our help for higher sales and success. We use SEO, PPC, design, and Optimization for impactful launches.
                    </p>
                </div>
            </div>
    
            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon"><i class="icon-lego-block"></i></div>
                <div class="service-text">
                    <h3 class="h2">BRAND STORE DEVELOPMENT</h3>
                    <p>Build a stronger brand presence with compelling content and visuals. Our optimized brand stores aim to boost customer conversions.
                    </p>
                </div>
            </div>
            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon"><i class="icon-lego-block"></i></div>
                <div class="service-text">
                    <h3 class="h2">CATALOG TROUBLE SHOOTING</h3>
                    <p>Solve any cataloging issues with our experts support, making catalog management hassle-free
                    </p>
                </div>
            </div>
            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon"><i class="icon-lego-block"></i></div>
                <div class="service-text">
                    <h3 class="h2">A+ LISTING CREATION</h3>
                    <p>Attract and convert more customers with high-quality A+ content, designed and optimized for the best conversion rates.
                    </p>
                </div>
            </div>
            
            
            
        </div> <!-- end services-list -->
        <hr>
        <div class="row  services-list block-1-2 block-tab-full">
        <div class="col-block service-item" data-aos="fade-up">
            <div class="service-icon"><i class="icon-lego-block"></i></div>
            <div class="service-text dropdown-container">
                <div class="">
                    <h3 class="h2">Quick dashboard</h3>
                    <p>To check your category performance (ROAS) on Amazon.</p>
                    
                    <div class="form-container">
                        <div>
                            <label for="month">Select Month:</label>
                            <select id="month">
                                <option>--Select--</option>
                                <option value="Jan">January</option>
                                <option value="Feb">February</option>
                                <option value="Mar">March</option>
                                <option value="Apr">April</option>
                                <option value="May">May</option>
                                <option value="Jun">June</option>
                                <option value="Jul">July</option>
                                <option value="Aug">August</option>
                                <option value="Sep">September</option>
                                <option value="Oct">October</option>
                                <option value="Nov">November</option>
                                <option value="Dec">December</option>
                            </select>
                        </div>
                        <div>
                            <label for="category">Select Category:</label>
                            <select id="category">
                                <option>--Select--</option>
                                <option value="SP">SP</option>
                                <option value="SB">SB</option>
                                <option value="SBv">SBv</option>
                                <option value="SD">SD</option>
                            </select>
                        </div>
                        <div>
                            <label for="subcategory">Select Subcategory:</label>
                            <select id="subcategory">
                                <!-- Subcategory options will be populated dynamically -->
                            </select>
                        </div>
                        <div>
                            <button id="submitBtn">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-block service-item" data-aos="fade-up">
            
            <div class="service-text">
                <div class="dashboard-container display-1">
                    <!-- Dashboard elements will be displayed here -->
                </div>
            </div>
        </div>
        </div>
        <hr>
    </section> <!-- end services -->
    <hr style="background-color:GREY;">

    <!-- works
    ================================================== -->
    <section id='works' class="s-works">

        <div class="intro-wrap">
                
            <div class="row section-header has-bottom-sep light-sep" data-aos="fade-up">
                <div class="col-full">
                    <h3 class="subhead">Recent Works</h3>
                    <h1 class="display-2 display-2--light">We love what we do, check out some of our latest works</h1>
                </div>
            </div> <!-- end section-header -->

        </div> <!-- end intro-wrap -->

        <div class="row works-content">
            <div class="col-full masonry-wrap">
                <div class="masonry">
    
                    <div class="masonry__brick" data-aos="fade-up">
                        <div class="item-folio">
                                
                            <div class="item-folio__thumb">
                                <a href="images/clients/WhatsApp Image 2024-04-16 at 15.44.27_83531b49.jpg" class="thumb-link" title="Flipkart Marketing Dashboard" data-size="1050x700">
                                    <img src="images/clients/WhatsApp Image 2024-04-16 at 15.44.27_83531b49.jpg" 
                                         srcset="images/clients/WhatsApp Image 2024-04-16 at 15.44.27_83531b49.jpg 1x, images/clients/WhatsApp Image 2024-04-16 at 15.44.27_83531b49.jpg 2x" alt="Flipkart Dashboard">
                                </a>
                            </div>
    
                            <div class="item-folio__text">
                                <h3 class="item-folio__title">
                                    Flipkart
                                </h3>
                                <p class="item-folio__cat">
                                    Flipkart Marketing Dashboard
                                </p>
                            </div>
    
                            <a href="#" class="item-folio__project-link" title="Project link">
                                <i class="icon-link"></i>
                            </a>
    
                            <div class="item-folio__caption">
                                <p>Getting a glimpse of what we can offer to our client on the Flipkart Marketing dashboard.</p>
                            </div>
    
                        </div>
                    </div> <!-- end masonry__brick -->

                    <div class="masonry__brick" data-aos="fade-up">
                        <div class="item-folio">
                                
                            <div class="item-folio__thumb">
                                <a href="images/clients/WhatsApp Image 2024-04-16 at 15.45.52_441eb724.jpg" class="thumb-link" title="Google Ads Dashboard" data-size="1050x700">
                                    <img src="images/clients/WhatsApp Image 2024-04-16 at 15.45.52_441eb724.jpg" 
                                         srcset="images/clients/WhatsApp Image 2024-04-16 at 15.45.52_441eb724.jpg 1x, images/clients/WhatsApp Image 2024-04-16 at 15.45.52_441eb724.jpg 2x" alt="Google Ads Dashboard">
                                </a>
                            </div>
    
                            <div class="item-folio__text">
                                <h3 class="item-folio__title">
                                    Google Ads 
                                </h3>
                                <p class="item-folio__cat">
                                    Google Ads Dashboard
                                </p>
                            </div>
    
                            <a href="#" class="item-folio__project-link" title="Project link">
                                <i class="icon-link"></i>
                            </a>
    
                            <div class="item-folio__caption">
                                <p>Getting a glimpse of what we can offer our clients on Google ads and how we can perform holistic development of brand.</p>
                            </div>
    
                        </div>
                    </div> <!-- end masonry__brick -->
    
                    <div class="masonry__brick" data-aos="fade-up">
                        <div class="item-folio">
                                
                            <div class="item-folio__thumb">
                                <a href="images/clients/WhatsApp Image 2024-04-16 at 15.47.01_d12e8a78.jpg" class="thumb-link" title="Amazon-Dashboard" data-size="1050x700">
                                    <img src="images/clients/WhatsApp Image 2024-04-16 at 15.47.01_d12e8a78.jpg"
                                         srcset="images/clients/WhatsApp Image 2024-04-16 at 15.47.01_d12e8a78.jpg 1x, images/clients/WhatsApp Image 2024-04-16 at 15.47.01_d12e8a78.jpg 2x" alt="Amazon Dashboard">
                                </a>
                            </div>
    
                            <div class="item-folio__text">
                                <h3 class="item-folio__title">
                                    Amazon Ads
                                </h3>
                                <p class="item-folio__cat">
                                    Amazon Ads Dashboard
                                </p>
                            </div>
    
                            <a href="#" class="item-folio__project-link" title="Project link">
                                <i class="icon-link"></i>
                            </a>
    
                            <div class="item-folio__caption">
                                <p>Getting a glimpse of what we can offer to our clients on Amazon ads and how we can perform 360 development of brand through multiple channels.</p>
                            </div>
    
                        </div>
                    </div> <!-- end masonry__brick -->
    
                </div>
            </div> <!-- end col-full -->
        </div> <!-- end works-content -->

    </section> <!-- end s-works -->
    <hr style="background-color:GREY;">

    <!-- clients
    ================================================== -->
    <section id="clients" class="s-clients">

        <div class="row section-header" data-aos="fade-up">
            <div class="col-full">
                <h3 class="subhead">Our Clients</h3>
                <h1 class="display-2">ADplication has been honored to
                partner up with these clients</h1> 
            </div>
        </div> <!-- end section-header -->

        <div class="row clients-outer" data-aos="fade-up">
            <div class="col-full">
                <div class="clients">
                    
                    <a href="#0" title="Godrej" class="clients__slide" ><img src="images/clients/Godrej.jpeg" style="padding: 10px;" ></a>
                    <a href="#0" title="Wipro" class="clients__slide" ><img src="images/clients/OIP.jpeg" style="margin-top: 5px;"></a>
                    <a href="#0" title="HealthAid" class="clients__slide"><img src="images/clients/healthAid.png" style="margin-top: 38px;"></a>
                    <a href="#0" title="Himalaya" class="clients__slide"><img src="images/clients/himalaya-removebg-preview.png" ></a>
                    <a href="#0" title="Hyuman" class="clients__slide"><img src="images/clients/hyuman.jpeg" style="margin-top: 25px;"></a>
                    <a href="#0" title="Dr.reddy" class="clients__slide"><img src="images/clients/Dr.reddy.jpeg" style="margin-top: 48px;"></a>
                    <a href="#0" title="Absorbia" class="clients__slide"><img src="images/clients/Absorbia.png" style="margin-top: 30px;"></a>
                     
                </div> <!-- end clients -->
            </div> <!-- end col-full -->
        </div> <!-- end clients-outer -->

        <div class="row clients-testimonials" data-aos="fade-up" style="text-align: justify;">
            <div class="col-full">
                <div class="testimonials">

                    <div class="testimonials__slide">

                        <p>Having been through a number of digital marketing agencies I was sceptical about employing another, but I have to say Ad-Plication are a breath of fresh air.
                        Within 3 months we saw a complete turnaround in our Amazon and Flipkart marketing performance.
                        These guys really life and breath digital and think outside the box when it comes to campaigns and report on everything. Would highly recommend.

                    </p>
                        <div style="text-align: left;">
                        <img src="images/avatars/divya.jpeg" alt="Author image" class="testimonials__avatar"></div>
                        <div class="testimonials__info">
                            <span class="testimonials__name">Divya</span> 
                            <span class="testimonials__pos">Brand Owner, HealthAid</span>
                        </div>

                    </div>

                    <div class="testimonials__slide">
                        
                        <p>Straight talking and clear outcomes. Wouldn't go anywhere else for my digital marketing support. If your product wants an organic growth through Keyword targeting, these are your guys.
                        </p>

                        <img src="images/avatars/user-05.jpg" alt="Author image" class="testimonials__avatar">
                        <div class="testimonials__info">
                            <span class="testimonials__name">Anup</span> 
                            <span class="testimonials__pos">Brand Manager, dthlivetv.com</span>
                        </div>

                    </div>

                    <div class="testimonials__slide">
                        
                        <p>Being a small business it's important for us that we keep control of our marketing spend and get maximum return on investment. Ad-plication understands this need perfectly and has put together a successful social media and AMS campaign that has exceeded my expectations and met the brief perfectly. Ad-plication continues to manage my online marketing, and I don't hesitate to recommend them.</p>

                        <img src="images/avatars/bhavana.jpeg" alt="Author image" class="testimonials__avatar">
                        <div class="testimonials__info">
                            <span class="testimonials__name">Bhavana</span> 
                            <span class="testimonials__pos">Brand Owner, PahariRoots</span>
                        </div>

                    </div>

                </div><!-- end testimonials -->
                
            </div> <!-- end col-full -->
        </div> <!-- end client-testimonials -->

    </section> <!-- end s-clients -->
    <hr style="background-color:GREY;">

    <!-- contact
    ================================================== -->
    <section class="s-contact">

        <div class="contact__line"></div>

        <div class="row section-header" data-aos="fade-up">
            <div class="col-full">
                <h3 class="subhead">Contact Us</h3>
                <h1 class="display-2 display-2--light">Reach out for a new project or just say hello</h1>
            </div>
        </div>

        <div class="row contact-content border-color" data-aos="fade-up">
            <div class="contact-primary">
                <h3 class="h6">Send Us A Message</h3>
                <form name="contactForm" id="contactForm1" method="post" action="send_email.php" novalidate="novalidate">
                    <fieldset>
                        <div class="form-field">
                            <input name="contactName" type="text" id="contactName1" placeholder="Your Name" value="" minlength="2" required="" aria-required="true" class="full-width">
                        </div>
                        <div class="form-field">
                            <input name="contactEmail" type="email" id="contactEmail1" placeholder="Your Email" value="" required="" aria-required="true" class="full-width">
                        </div>
                        <div class="form-field">
                            <input name="contactSubject" type="text" id="contactSubject1" placeholder="Subject" value="" class="full-width">
                        </div>
                        <div class="form-field">
                            <textarea name="contactMessage" id="contactMessage1" placeholder="Your Message" rows="10" cols="50" required="" aria-required="true" class="full-width"></textarea>
                        </div>
                        <div class="form-field">
                            <button type="submit" class="full-width btn--primary">Submit</button>
                            <div class="submit-loader">
                                <div class="text-loader">Sending...</div>
                                <div class="s-loader">
                                    <div class="bounce1"></div>
                                    <div class="bounce2"></div>
                                    <div class="bounce3"></div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
        
                        <!-- contact-warning -->
                <div class="message-warning">
                    Something went wrong. Please try again.
                </div> 
                
                <!-- contact-success -->
                <div class="message-success">
                    Your message was sent, thank you!<br>
                </div>
            </div>
        
<!-- 
            <div class="contact-secondary">
                <div class="contact-info">

                    <h3 class="h6 hide-on-fullwidth">Contact Info</h3>

                    <div class="cinfo">
                        <h5>Where to Find Us</h5>
                        <p>
                            1600 Amphitheatre Parkway<br>
                            Mountain View, CA<br>
                            94043 US
                        </p>
                    </div>

                    <div class="cinfo">
                        <h5>Email Us At</h5>
                        <p>
                            contact@ADplication.com<br>
                            info@ADplication.com
                        </p>
                    </div>

                    <div class="cinfo">
                        <h5>Call Us At</h5>
                        <p>
                            Phone: (+63) 555 1212<br>
                            Mobile: (+63) 555 0100<br>
                            Fax: (+63) 555 0101
                        </p>
                    </div>

                    <ul class="contact-social">
                        <li>
                            <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-behance" aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-dribbble" aria-hidden="true"></i></a>
                        </li>
                    </ul>  end contact-social -->

                <!--</div> end contact-info -->
            </div> <!-- end contact-secondary -->

    </section> <!-- end s-contact -->
    <section id="contact" class="s-contact">
        <div class="contact__line"></div>

        <div class="row section-header" data-aos="fade-up">
            <div class="col-full">
                <h3 class="subhead">Contact Us</h3>
                <h1 class="display-2 display-2--light">Reach out for a new project or just say hello</h1>
            </div>
        </div>

        <div class="row contact-content" data-aos="fade-up">
            
            <div class="contact-primary">

                <h3 class="h6">Send Us A Message</h3>

                <form name="contactForm" id="contactForm" method="post" action="sendEmail.php" novalidate="novalidate">
                    <fieldset>
    
                    <div class="form-field">
                        <input name="contactName" type="text" id="contactName" placeholder="Your Name" value="" minlength="2" required="" aria-required="true" class="full-width">
                    </div>
                    <div class="form-field">
                        <input name="contactEmail" type="email" id="contactEmail" placeholder="Your Email" value="" required="" aria-required="true" class="full-width">
                    </div>
                    <div class="form-field">
                        <input name="contactSubject" type="text" id="contactSubject1" placeholder="Subject" value="" class="full-width">
                    </div>
                    <div class="form-field">
                        <textarea name="contactMessage" id="contactMessage" placeholder="Your Message" rows="10" cols="50" required="" aria-required="true" class="full-width"></textarea>
                    </div>
                    <div class="form-field">
                        <button class="full-width btn--primary">Submit</button>
                        <div class="submit-loader">
                            <div class="text-loader">Sending...</div>
                            <div class="s-loader">
                                <div class="bounce1"></div>
                                <div class="bounce2"></div>
                                <div class="bounce3"></div>
                            </div>
                        </div>
                    </div>
    
                    </fieldset>
                </form>

                <!-- contact-warning -->
                <div class="message-warning">
                    Something went wrong. Please try again.
                </div> 
            
                <!-- contact-success -->
                <div class="message-success">
                    Your message was sent, thank you!<br>
                </div>

            </div> <!-- end contact-primary -->
</div>
<form method="POST">

<!--<input type="hidden" name="_token" value="6LcFyukZAAAAAB3cHZ9miVLKtPReV9E3_7No_0G2">
<input type="hidden" name="to" value="info@dthlivetv.com">-->
<div class="form-group">
    <input type="text" name="name" class="form-control" placeholder="Name" required>
</div>
<div class="form-group">
    <input type="email" name="email" class="form-control" placeholder="Email" required>
</div>
<div class="form-group">
    <input type="text" name="subject" class="form-control" placeholder="Subject" required>
</div>
<div class="form-group">
    <textarea class="form-control" name="message" id="message" cols="30" rows="5" placeholder="Message" style="resize: vertical;" required></textarea>
</div>
<div class="g-recaptcha" data-sitekey="6LcFyukZAAAAAB3cHZ9miVLKtPReV9E3_7No_0G2"></div>
<div class="form-group">
    <button type="submit" name="send_message" class="btn-block colored-bordered-btn text-uppercase d-inline-block">SUBMIT NOW</button>
</div>

</form>
</section>
    <hr style="background-color:GREY;">
    <script>
        document.getElementById("contactForm1").addEventListener("submit", function(event) {
            event.preventDefault();
            var formData = new FormData(this);
    
            // Send form data via AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "send_email.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === "success") {
                            // Display success message
                            document.querySelector(".message-success").style.display = "block";
                            // Clear form fields
                            document.getElementById("contactForm1").reset();
                        } else {
                            // Display error message
                            document.querySelector(".message-warning").style.display = "block";
                        }
                    } else {
                        // Display error message
                        document.querySelector(".message-warning").style.display = "block";
                    }
                }
            };
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.send(JSON.stringify(Object.fromEntries(formData)));
        });
    </script>

    <!-- footer
    ================================================== -->
    <footer>

        <div class="row footer-main">

            <div class="col-six tab-full left footer-desc" style="text-align: justify;color: black;">

                <div class="">
                    <img src="images/avatars/Adplcation.png" alt="ad_plication_logo" width="70px" height="70px">
                </div>
                OUR SUCCESSES
                Our track record shows that we increase our clients' sales from 20-200+% year over year, while targeting a less than 20% Advertising Cost of Sale (ACoS, the total spend on advertising divided by attributed sales) through our proprietary method of keyword search term research and maintenance of advertising performance
            </div>
            

            <div class="col-six tab-full right footer-subscribe">

                <h4 style="color: green;">Get Notified</h4>
                <p></p>

                <div class="subscribe-form">
                    <form id="mc-form" class="group" novalidate="true">
                        <input type="email" value="" name="EMAIL" class="email" id="mc-email" placeholder="Email Address" required="">
                        <input type="submit" name="subscribe" value="Subscribe">
                        <label for="mc-email" class="subscribe-message"></label>
                    </form>
                </div>

            </div>

        </div> <!-- end footer-main -->

        <div class="row footer-bottom">

            <div class="col-twelve">
                <div class="copyright">
                    <span>© Copyright ADplication 2021</span>	
                </div>

                <div class="go-top">
                    <a class="smoothscroll" title="Back to Top" href="#top"><i class="icon-arrow-up" aria-hidden="true"></i></a>
                </div>
            </div>

        </div> <!-- end footer-bottom -->

    </footer> <!-- end footer -->

    <hr style="background-color:GREY;">
    <!-- photoswipe background
    ================================================== -->
    <div aria-hidden="true" class="pswp" role="dialog" tabindex="-1">

        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">

            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button> 
                    <button class="pswp__button pswp__button--share" title=
                    "Share"></button> 
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                     <button class="pswp__button pswp__button--zoom" title=
                    "Zoom in/out"></button>
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>
                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button> 
                <button class="pswp__button pswp__button--arrow--right" title=
                "Next (arrow right)"></button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>

        </div>

    </div> <!-- end photoSwipe background -->


    <!-- preloader
    ================================================== -->
    <div id="preloader">
        <div id="loader">
            <div class="line-scale-pulse-out">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>


    <!-- Java Script
    ================================================== -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>
    <script src="inc/sendEmail.php" ></script>
    
    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>  
</body>

</html>