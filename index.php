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
</head>

<!--Index page viewable to Unregistered users-->

<body class="home">

  <nav class="navbar navbar-expand-lg navbar-light bg-custom justify-content-end front-nav">
    <a class="nav-item nav-link active" href="index.php">HOME</a>
    <a class="nav-item nav-link" href="about_us.php">ABOUT</a>
    <a class="nav-item nav-link" href="services.php">SERVICES</a>
    <a class="nav-item nav-link" href="contact.php">CONTACT</a>
    <a class="nav-item nav-link" href="sign_up.php">SIGN UP</a>
    <a class="nav-item nav-link" href="user_login.php">LOGIN</a>
    <a class="nav-item nav-link" href="admin_login.php">ADMIN</a>
  </nav>

  <div id="indexCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
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
        <img src="images/index_Carousel-1.png" class="d-block w-100" alt="Carousel image 1" />
      </div>
      <div class="carousel-item">
        <img src="images/index_Carousel-2.png" class="d-block w-100" alt="Carousel image 2" />
      </div>
      <div class="carousel-item">
        <img src="images/index_Carousel-3.png" class="d-block w-100" alt="Carousel image 3" />
      </div>
      <div class="carousel-item">
        <img src="images/index_Carousel-4.png" class="d-block w-100" alt="Carousel image 4" />
      </div>
      <div class="carousel-item">
        <img src="images/index_Carousel-5.png" class="d-block w-100" alt="Carousel image 5" />
      </div>
      <div class="carousel-item">
        <img src="images/index_Carousel-6.png" class="d-block w-100" alt="Carousel image 6" />
      </div>
      <div class="carousel-item">
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

  <!--Index page Tagline-->
  <div class="indexTagContainer">
    <div id="indexTaglineTopic">
      <p style="margin-top: 75px">
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

  <!--Index pagge content blocks containing Bootstrap Cards-->
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
          <a href="sign_up.php" style="text-decoration: none; color: #ffffff">Get Appointment</a>
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
        <a class="footerLink bi bi-link-45deg" href="index.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</a><br />
        <a class="footerLink bi bi-link-45deg" href="about_us.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;About</a><br />
        <a class="footerLink bi bi-link-45deg" href="services.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Services</a><br />
        <a class="footerLink bi bi-link-45deg" href="contact.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contact</a><br />
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