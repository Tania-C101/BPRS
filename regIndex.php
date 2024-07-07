<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Home - Locks&Curls</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
  <link rel="stylesheet" href="styles.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <style>
    .dropdown-menu {
      position: absolute;
      top: 100%;
      left: 0;
      transform: translateY(0);
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
      border: none;
    }

    .dropdown-menu a.dropdown-items {
      padding: 10px 20px;
      font-size: 14px;
      color: #333;
    }

    .dropdown-menu a.dropdown-items:hover {
      background-color: #f8f9fa;
      color: white;
    }

    .dropdown-menu a.dropdown-items.active {
      background-color: #a600fa;
      color: white;
    }
  </style>
</head>

<!--Index page viewable to Registered users-->

<body class="home">

  <nav class="navbar navbar-expand-lg navbar-light bg-custom justify-content-end front-nav">
    <a class="nav-item nav-link active" href="regIndex.php">HOME</a>
    <a class="nav-item nav-link" href="regAbout_Us.php">ABOUT</a>
    <a class="nav-item nav-link" href="regServices.php">SERVICES</a>
    <a class="nav-item nav-link" href="regContact.php">CONTACT</a>
    <a class="nav-item nav-link" href="appointment.php">APPOINTMENT</a>
    <a class="nav-item nav-link" href="booking_history.php">BOOKING HISTORY</a>
    <a class="nav-item nav-link" href="invoice_history.php">INVOICE HISTORY</a>
    <div class="dropdown">
      <a class="nav-item nav-link dropdown-toggle" data-bs-toggle="dropdown">PROFILE SETTINGS</a>
      <ul class="dropdown-menu">
        <li class="dropdown-tab">
          <a class="dropdown-item dropdown-link" href="edit_profile.php">Edit Profile</a>
        </li>
        <li class="dropdown-tab">
          <a class="dropdown-item dropdown-link" href="change_password.php" id="change-password-link">Change
            Password</a>
        </li>
      </ul>
    </div>
    <a class="nav-item nav-link" href="index.php" onclick="return confirm('Are you sure you want to logout?');"
      style="color: white;">LOGOUT</a>
  </nav>

  <!--Carousel component of the Index page-->
  <div id="indexCarousel" class="carousel slide" data-bs-ride="carousel">
    <!--Creates carousel container; Applies carousel & slide classes in BT-->
    <div class="carousel-indicators">
      <!--Bottom indicators for the carousel-->
      <!--Each button represents a slide-->
      <button type="button" data-bs-target="#indexCarousel" data-bs-slide-to="0" class="active" aria-current="true"
        aria-label="Slide1"></button>
      <button type="button" data-bs-target="#indexCarousel" data-bs-slide-to="1" aria-label="Slide2"></button>
      <button type="button" data-bs-target="#indexCarousel" data-bs-slide-to="2" aria-label="Slide3"></button>
      <button type="button" data-bs-target="#indexCarousel" data-bs-slide-to="3" aria-label="Slide4"></button>
      <button type="button" data-bs-target="#indexCarousel" data-bs-slide-to="4" aria-label="Slide5"></button>
      <button type="button" data-bs-target="#indexCarousel" data-bs-slide-to="5" aria-label="Slide6"></button>
      <button type="button" data-bs-target="#indexCarousel" data-bs-slide-to="6" aria-label="Slide7"></button>
      <button type="button" data-bs-target="#indexCarousel" data-bs-slide-to="7" aria-label="Slide8"></button>
    </div>

    <!--All carousel items are placed here-->
    <div class="carousel-inner">
      <div class="carousel-item active">
        <!--Carousel item represents a slide-->
        <img src="images/index_Carousel-1.png" class="d-block w-100" alt="Carousel image 1" />
      </div>
      <div class="carousel-item">
        <!--Carousel item represents a slide-->
        <img src="images/index_Carousel-2.png" class="d-block w-100" alt="Carousel image 2" />
      </div>
      <div class="carousel-item">
        <!--Carousel item represents a slide-->
        <img src="images/index_Carousel-3.png" class="d-block w-100" alt="Carousel image 3" />
      </div>
      <div class="carousel-item">
        <!--Carousel item represents a slide-->
        <img src="images/index_Carousel-4.png" class="d-block w-100" alt="Carousel image 4" />
      </div>
      <div class="carousel-item">
        <!--Carousel item represents a slide-->
        <img src="images/index_Carousel-5.png" class="d-block w-100" alt="Carousel image 5" />
      </div>
      <div class="carousel-item">
        <!--Carousel item represents a slide-->
        <img src="images/index_Carousel-6.png" class="d-block w-100" alt="Carousel image 6" />
      </div>
      <div class="carousel-item">
        <!--Carousel item represents a slide-->
        <img src="images/index_Carousel-7.png" class="d-block w-100" alt="Carousel image 7" />
      </div>
    </div>

    <!--Side button navigation-->
    <button class="carousel-control-prev" type="button" data-bs-target="#indexCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#indexCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
  <br />

  <!--Index page Tagline-->
  <div class="indexTagContainer">
    <div id="indexTaglineTopic">
      <p>
        <center>Discover Locks & Curls!</center>
      </p>
    </div>
    <div id="indexTaglineContent">
      <p>
        <center>
          Your ultimate destination for all-in-one beauty services!
        </center>
      </p>
    </div>
  </div>

  <!--Index page content blocks containing Bootstrap Cards-->
  <div class="indexCardContainer">
    <div id="indexCard1" class="card indexCardSettings">
      <center>
        <img src="images/indexCard1.png" class="card-img-top" style="width: 17rem" alt="Hair styling services" />
      </center>
      <br />
      <div class="card-body">
        <h5 class="card-title">
          <center>Hair Services</center>
        </h5>
      </div>
    </div>

    <div id="indexCard2" class="card indexCardSettings">
      <center>
        <img src="images/indexCard2.png" class="card-img-top" style="width: 17rem" alt="Nail services" />
      </center>
      <br />
      <div class="card-body">
        <h5 class="card-title">
          <center>Nail Treatments</center>
        </h5>
      </div>
    </div>

    <div id="indexCard3" class="card indexCardSettings">
      <center>
        <img src="images/indexCard3.jpg" class="card-img-top" style="width: 17rem" alt="Makeup services" />
      </center>
      <br />
      <div class="card-body">
        <h5 class="card-title">
          <center>Makeup & Facials</center>
        </h5>
      </div>
    </div>

    <div id="indexCard4" class="card indexCardSettings">
      <center>
        <img src="images/indexCard4.jpg" class="card-img-top" style="width: 17rem" alt="Waxing services" />
      </center>
      <br />
      <div class="card-body">
        <h5 class="card-title">
          <center>Waxing Services</center>
        </h5>
      </div>
    </div>
  </div>

  <!--Index page - 'Salon intro pitch' section-->
  <div id="indexPitchContainer1">
    <p id="indexPitch">
      At Locks & Curls, our goal is to make you shine like a star!<br />
      We create flattering, contemporary looks for our guests, specializing in
      versatile styles for everyday life.<br />
      Whether you want something fashion-forward, timeless, or just for a
      special event, Locks & Curls has your answer believe that you are your
      best accessory. This is why we offer a full-service experience including
      hair styling, nail treatments, makeup services and waxing services.
    </p>
  </div>
  <br />
  <br />
  <br />
  <div id="indexPitchContainer2">
    <br />
    <div id="indexLeft">
      <p id="indexPitch2">Why Choose us?</p>
      <i>Here's why you should choose us:</i><br />
      With premium products and skilled stylists, we guarantee vibrant look
      for you that turns heads.<br />Relax in our tranquil environment and
      become part of our inclusive community. Choose Locks&Curls for an
      empowering salon experience that leaves you feeling confident and ready
      to conquer the world.<br /><br />
      <h5>Book your appointment today and let your looks do the talking!</h5>
    </div>
    <div id="indexRight">
      <div>
        <button type="button" name="getAppointmentbtn" id="getAppointmentbtn" value="Get Appointment">
          <a href="appointment.php" style="text-decoration: none; color: #ffffff">Get Appointment</a>
        </button>
        <br />
        <br />
      </div>
    </div>
  </div>
  <footer>
    <div class="footerContainer">
      <div class="sec1">
        <p style="font-size: 24px">Contact Us</p>
        <p class="bi bi-geo-alt-fill ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No. 05, Park Road, Colombo</p>
        <p class="bi bi-envelope-fill">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;locksncurls2024@gmail.com</p>
        <p class="bi bi-telephone-fill">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0112 878 774</p>
      </div>
      <div class="sec2">
        <p style="font-size: 24px">Useful Links</p>
        <a class="footerLink bi bi-link-45deg" href="regIndex.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</a><br />
        <a class="footerLink bi bi-link-45deg" href="regAbout_us.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;About</a><br />
        <a class="footerLink bi bi-link-45deg" href="regServices.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Services</a><br />
        <a class="footerLink bi bi-link-45deg" href="regContact.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contact</a><br />
      </div>
      <div class="sec3">
        <p style="font-size: 24px">About Us</p>
        <p style="line-height: 30px;">
          Locks & Curls Beauty Parlor offers expert hair, skin, and nail
          services in a relaxing environment. Our skilled team uses premium,
          eco-friendly products to ensure you look and feel your best. Visit
          us for personalized care and a rejuvenating experience.
        </p>
      </div>
    </div>
  </footer>
</body>

</html>