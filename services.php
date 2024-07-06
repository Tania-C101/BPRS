<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Services - Locks&Curls</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
  <link rel="stylesheet" href="styles.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
    rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf060NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-custom justify-content-end front-nav">
    <a class="nav-item nav-link" href="index.php">HOME</a>
    <a class="nav-item nav-link" href="about_us.php">ABOUT</a>
    <a class="nav-item nav-link active" href="services.php">SERVICES</a>
    <a class="nav-item nav-link" href="contact.php">CONTACT</a>
    <a class="nav-item nav-link" href="sign_up.php">SIGN UP</a>
    <a class="nav-item nav-link" href="user_login.php">LOGIN</a>
    <a class="nav-item nav-link" href="admin_login.php">ADMIN</a>
  </nav>

  <div class="container-image" style="text-align: center; width: 100%">
    <img src="images/services.png" style="width: 100%" alt="Contact page background image" />
    <div class="text" style="color: #ffffff; font-size: 60px">Our Services</div>
  </div>

  <div id="servicesContainer">
    <center>
      <h4>Our services include, but not limited to ....</h4>
    </center>
    <br>
    <center>
      <h5>Hair Services</h5>
    </center>
    <div id="hairServices" class="servicesTable" style="display:flex; flex-wrap:wrap"></div>
    <center>
      <h5>Waxing Services</h5>
    </center>
    <div id="waxingServices" class="servicesTable" style="display:flex; flex-wrap:wrap"></div>
    <center>
      <h5>Facial Services</h5>
    </center>
    <div id="facialServices" class="servicesTable" style="display:flex; flex-wrap:wrap"></div>
    <center>
      <h5>Nail Services</h5>
    </center>
    <div id="nailServices" class="servicesTable" style="display:flex; flex-wrap:wrap"></div>
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

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      async function fetchServices() {
        try {
          const response = await fetch('get_services.php');
          const services = await response.json();
          displayServices(services);
        } catch (error) {
          console.error('Error fetching services:', error);
        }
      }

      async function getServiceImage(imageId) {
        try {
          const response = await fetch(`get_image.php?image_id=${imageId}`);
          const data = await response.json();
          return data.image_url;
        } catch (error) {
          console.error('Error fetching image:', error);
          return '';
        }
      }

      async function createServiceCard(service) {
        const serviceCard = document.createElement('div');
        serviceCard.classList.add('service-card', 'p-3', 'mb-3', 'bg-white', 'rounded', 'shadow-sm');

        const imageUrl = await getServiceImage(service.Image_ID);

        serviceCard.innerHTML = `
          <img src="${imageUrl}" class="img-fluid mb-3" alt="${service.Service_Name}">
          <h5 class="card-title">${service.Service_Name}</h5>
        `;

        return serviceCard;
      }

      async function displayServices(services) {
        const hairServicesContainer = document.getElementById('hairServices');
        const waxingServicesContainer = document.getElementById('waxingServices');
        const facialServicesContainer = document.getElementById('facialServices');
        const nailServicesContainer = document.getElementById('nailServices');

        hairServicesContainer.innerHTML = '';
        waxingServicesContainer.innerHTML = '';
        facialServicesContainer.innerHTML = '';
        nailServicesContainer.innerHTML = '';

        for (const service of services) {
          let serviceContainer;

          switch (service.Service_Category) {
            case 'Hair Services':
              serviceContainer = hairServicesContainer;
              break;
            case 'Waxing Services':
              serviceContainer = waxingServicesContainer;
              break;
            case 'Facial Services':
              serviceContainer = facialServicesContainer;
              break;
            case 'Nail Services':
              serviceContainer = nailServicesContainer;
              break;
            default:
              console.warn(`Unknown category: ${service.Service_Category}`);
              continue;
          }

          const serviceCard = await createServiceCard(service);
          serviceContainer.appendChild(serviceCard);
        }
      }

      fetchServices();
      setInterval(fetchServices, 100000);
    });
  </script>
</body>

</html>