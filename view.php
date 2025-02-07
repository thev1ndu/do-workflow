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
   <title>AstroShop | Quick View</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="icon" type="image/x-icon" href="favicon.png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
   
<?php include 'components/header.php'; ?>

<section class="quick-view py-5" style="margin-top: -80px; z-index: 1;">
   <div class="container">
      <!-- Section Header -->
      <div class="row justify-content-center mb-4">
         <div class="col-lg-8 text-center">
            <span class="badge rounded-pill mb-3" style="background-color: rgba(13,110,253,0.1); color: #0d6efd; font-size: 0.9rem;">
               Product Details
            </span>
            <h1 class="display-6 fw-bold" style="color: #2b3452;">
            <span class="badge rounded-pill mb-2" style="background-color: rgba(13,110,253,0.1); color: #0d6efd; font-size: 0.9rem;">
        Product Information
      </span>
            </h1>
         </div>
      </div>

      <?php
         $pid = $_GET['pid'];
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?"); 
         $select_products->execute([$pid]);
         if($select_products->rowCount() > 0){
            while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post">
         <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">

         <div class="card border-0 rounded-4" style="background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <div class="card-body p-4 p-lg-5">
               <div class="row g-4 align-items-center">
                  <!-- Image Section -->
                  <div class="col-lg-6">
                     <div class="image-container">
                        <div class="main-image mb-4">
                           <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" 
                                class="img-fluid rounded-4 w-100" 
                                style="box-shadow: 0 5px 15px rgba(0,0,0,0.08);" 
                                alt="Main Product Image">
                        </div>
                        <div class="sub-images d-flex gap-3 justify-content-center">
                           <?php foreach(['image_01', 'image_02', 'image_03'] as $image): ?>
                              <div class="sub-image-wrapper" 
                                   style="cursor: pointer; transition: all 0.3s ease;">
                                 <img src="uploaded_img/<?= $fetch_product[$image]; ?>" 
                                      class="rounded-3" 
                                      style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #dee2e6;" 
                                      alt="Product thumbnail">
                              </div>
                           <?php endforeach; ?>
                        </div>
                     </div>
                  </div>

                  <!-- Product Details -->
                  <div class="col-lg-6">
                     <div class="content">
                        <h2 class="fw-bold mb-4" style="color: #2b3452;">
                           <?= $fetch_product['name']; ?>
                        </h2>
                        
                        <div class="d-flex align-items-center gap-4 mb-4">
                           <div class="p-3 rounded-4" style="background: rgba(13,110,253,0.1);">
                              <span class="h3 fw-bold mb-0" style="color: #0d6efd;">
                                 $<?= $fetch_product['price']; ?>
                              </span>
                           </div>
                           
                           <div class="form-floating" style="width: 150px;">
                              <input type="number" id="qty" name="qty" 
                                     class="form-control" 
                                     style="border-radius: 12px; border: 1px solid #dee2e6;"
                                     min="1" max="99" value="1" required>
                              <label for="qty" class="text-muted">Quantity</label>
                           </div>
                        </div>

                        <p class="lead mb-4" style="color: #a4b5cf;">
                           <?= $fetch_product['details']; ?>
                        </p>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3">
                           <button type="submit" name="add_to_cart" 
                                   class="btn btn-primary px-5 py-3 rounded-pill" 
                                   style="transition: all 0.3s ease;">
                              <i class="bi bi-cart3 me-2"></i>
                              Add to Cart
                           </button>
                           
                           <button type="submit" name="add_to_wishlist" 
                                   class="btn btn-light px-5 py-3 rounded-pill" 
                                   style="transition: all 0.3s ease;">
                              <i class="bi bi-heart me-2"></i>
                              Add to Wishlist
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </form>
      <?php
            }
         } else {
            echo '<div class="card border-0 rounded-4 p-5 text-center" style="background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                     <p class="lead mb-0" style="color: #dc3545;">No products found!</p>
                  </div>';
         }
      ?>
   </div>
</section>

<style>
   .form-control:focus {
      border-color: #0d6efd40;
      box-shadow: 0 0 0 0.25rem rgba(13,110,253,.15);
   }
   
   .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(13,110,253,0.2);
   }
   
   .sub-image-wrapper:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
   }
   
   @media (max-width: 991.98px) {
      .quick-view {
         margin-top: -40px;
      }
   }
</style>

<!-- Add Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Add Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
















<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>