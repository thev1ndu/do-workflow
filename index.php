<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="favicon.png">
  <title>AstroShop | Home</title>

  <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

  <?php include 'components/header.php'; ?>

  <div class="home-bg">

    <section class="home">

      <div class="swiper home-slider">


      </div>

    </section>

  </div>

  <section class="home-hero position-relative" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
    <div class="swiper  container py-5">
      <div class="swiper-wrapper align-items-center">
        <div class="swiper-slide row align-items-center">
          <div class="col-md-6 text-white">
            <span class="badge bg-warning mb-3">Upto 50% Off</span>
            <h1 class="display-4 fw-bold mb-4">Latest Products</h1>
            <p class=" mb-4">
              Discover Astro's latest collection and enjoy up to 50% off today!
            </p>
            <a href="shop.php" class="btn btn-light px-4 py-2 rounded-pill" style="transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
              Shop Now
            </a>
          </div>
          <div class="col-md-6">
            <img src="images/ecom.png" class="img-fluid" style="transform: perspective(1000px) rotate3d(0, 1, 0, 15deg);" alt="Hero">
          </div>
        </div>
      </div>
    </div>
  </section>

  
  <section class="py-4 py-lg-5" style="background-color: #f8f9fa;">
  <h1 class="display-6 fw-bold" style="color: #2b3452; text-align: center;">
            <span class="badge rounded-pill mb-2" style="background-color: rgba(13,110,253,0.1); color: #0d6efd; font-size: 24px;">
        LATEST PRODUCTS
      </span>
            </h1>
            <br>
  <div class="container">

    <div class="row g-3 g-lg-4">
      <?php
      $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 4"); 
      $select_products->execute();
      if($select_products->rowCount() > 0){
        while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="col-6 col-md-4 col-lg-3">
        <div class="card h-100 border-0" style="background: white; transition: all 0.25s ease-in-out;">
          <div class="position-relative">
            <a href="view.php?pid=<?= $fetch_product['id']; ?>" class="d-block" style="aspect-ratio: 1;">
              <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" 
                   class="card-img-top h-100 w-100" 
                   alt="<?= $fetch_product['name']; ?>"
                   style="object-fit: cover;">
            </a>
            
            <div class="position-absolute top-0 end-0 p-2 d-flex flex-column gap-2" 
                 style="opacity: 0; transition: all 0.2s ease-in-out;">
              <form method="post">
                <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
                <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
                
                <button type="submit" name="add_to_wishlist" 
                        class="btn btn-light shadow-sm rounded-circle d-flex align-items-center justify-content-center" 
                        style="width: 35px; height: 35px; backdrop-filter: blur(4px); background: rgba(255,255,255,0.9);">
                  <i class="fas fa-heart" style="color: #dc3545; font-size: 0.9rem;"></i>
                </button>
              </form>
              
              <a href="view.php?pid=<?= $fetch_product['id']; ?>" 
                 class="btn btn-light shadow-sm rounded-circle d-flex align-items-center justify-content-center" 
                 style="width: 35px; height: 35px; backdrop-filter: blur(4px); background: rgba(255,255,255,0.9);">
                <i class="fas fa-eye" style="color: #0d6efd; font-size: 0.9rem;"></i>
              </a>
            </div>
          </div>

          <div class="card-body p-3">
            <h5 class="card-title mb-1" style="font-size: 0.95rem; font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
              <?= $fetch_product['name']; ?>
            </h5>

            <p class="text-primary mb-2" style="font-weight: 600; font-size: 1.1rem;">
              $<?= number_format($fetch_product['price'], 2); ?>
            </p>

            <form method="post" class="d-flex gap-2 align-items-center">
              <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
              <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
              <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
              <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">

              <input type="number" 
                     name="qty" 
                     class="form-control form-control-sm px-2" 
                     style="width: 65px;" 
                     min="1" 
                     max="99" 
                     value="1" 
                     onkeypress="if(this.value.length == 2) return false;">

              <button type="submit" 
                      name="add_to_cart" 
                      class="btn btn-primary btn-sm flex-grow-1 d-flex align-items-center justify-content-center gap-2">
                <i class="fas fa-shopping-cart"></i>
                <span class="d-none d-sm-inline">Add</span>
              </button>
            </form>
          </div>
        </div>
      </div>
      <?php
        }
      } else {
        echo '<div class="col-12"><div class="alert alert-info text-center">No products added yet!</div></div>';
      }
      ?>
    </div>
  </div>

  <style>
    .card {
      box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }
    
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .card:hover .position-absolute {
      opacity: 1 !important;
    }
    
    @media (max-width: 575.98px) {
      .position-absolute {
        opacity: 1 !important;
      }
    }
    
    .form-control:focus {
      border-color: #0d6efd40;
      box-shadow: 0 0 0 0.25rem rgba(13,110,253,.15);
    }
    
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
      opacity: 1;
    }
  </style>
</section>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
       const productCards = document.querySelectorAll('.product-card');
       productCards.forEach(card => {
          card.addEventListener('mouseover', function() {
             this.style.transform = 'translateY(-10px)';
          });
          card.addEventListener('mouseout', function() {
             this.style.transform = 'translateY(0)';
          });
       });
    });
  </script>

  <?php include 'components/footer.php'; ?>

  <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

  <script src="js/script.js"></script>

  <script>
    var swiper = new Swiper(".home-slider", {
       loop:true,
       spaceBetween: 20,
       pagination: {
          el: ".swiper-pagination",
          clickable:true,
        },
    });
    
     var swiper = new Swiper(".category-slider", {
       loop:true,
       spaceBetween: 20,
       pagination: {
          el: ".swiper-pagination",
          clickable:true,
       },
       breakpoints: {
          0: {
             slidesPerView: 2,
           },
          650: {
            slidesPerView: 3,
          },
          768: {
            slidesPerView: 4,
          },
          1024: {
            slidesPerView: 5,
          },
       },
    });
    
    var swiper = new Swiper(".products-slider", {
       loop:true,
       spaceBetween: 20,
       pagination: {
          el: ".swiper-pagination",
          clickable:true,
       },
       breakpoints: {
          550: {
            slidesPerView: 2,
          },
          768: {
            slidesPerView: 2,
          },
          1024: {
            slidesPerView: 3,
          },
       },
    });
    
  </script>

</body>

</html>