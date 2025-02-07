<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>AstroShop | Orders</title>

      <!-- font awesome cdn link  -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="icon" type="image/x-icon" href="favicon.png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   
<?php include 'components/header.php'; ?>

<section class="orders-section py-5" style="background: linear-gradient(135deg, #f9f9f9 0%, #f1f2f6 100%);">
   <div class="container">
      <br>
   <div class="text-center mb-5">
      <span class="badge rounded-pill mb-2" style="background-color: rgba(13,110,253,0.1); color: #0d6efd; font-size: 0.9rem;">
        AstroShop / Orders
      </span>
      <h2 class="fw-bold mb-4" style="color: #2b3452;">Order History</h2>
    </div>

    <?php if($user_id == ''): ?>
    <!-- Not Logged In State -->
    <div class="card border-0 rounded-4 p-5 text-center" style="background-color: white; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
      <div class="mb-4">
        <i class="fas fa-user-lock text-primary" style="font-size: 3rem;"></i>
      </div>
      <h4 class="mb-3" style="color: #2b3452;">Please Login to View Orders</h4>
      <p class="text-muted mb-4">Track your order history, manage returns, and more by logging in to your account.</p>
      <a href="login.php" class="btn btn-primary px-4 mx-auto py-2 rounded-pill">
        Login
      </a>
    </div>

    <?php else: ?>
      <?php
        $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
        $select_orders->execute([$user_id]);
        if($select_orders->rowCount() > 0):
      ?>
      <!-- Orders Table -->
      <div class="card border-0 rounded-4 overflow-hidden" style="box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
        <div class="table-responsive">
          <table class="table table-hover mb-0" style="min-width: 1000px;">
            <thead style="background-color: #f8f9fa;">
              <tr>
                <th class="px-4 py-3" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Order ID</th>
                <th class="px-4 py-3" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Date</th>
                <th class="px-4 py-3" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Items</th>
                <th class="px-4 py-3" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Total</th>
                <th class="px-4 py-3" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Status</th>
                <th class="px-4 py-3" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Details</th>
              </tr>
            </thead>
            <tbody>
              <?php while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)): ?>
              <tr>
                <td class="px-4 py-3">
                  <span class="fw-medium" style="color: #2b3452;">#<?= $fetch_orders['id']; ?></span>
                </td>
                <td class="px-4 py-3">
                  <div style="color: #6c757d; font-size: 0.9rem;">
                    <?= date('M d, Y', strtotime($fetch_orders['placed_on'])); ?>
                  </div>
                </td>
                <td class="px-4 py-3">
                  
                  
                  
                 <div style="color: #0d6efd;">
          <ul style="margin: 0; padding-left: 1rem; list-style-type: none;">
            <?php 
            // Assuming 'total_products' is a comma-separated string of products
            $products = explode(',', $fetch_orders['total_products']); 
            foreach ($products as $product): ?>
            <li>
              <span class="badge rounded-pill" style="background-color: rgba(13,110,253,0.1); color: #0d6efd;">
              <?= htmlspecialchars(trim($product)); ?>
              </span>
                </li>
            <?php endforeach; ?>
          </ul>
        </div> 
                </td>
                <td class="px-4 py-3">
                  <span class="fw-medium" style="color: #2b3452;">$<?= number_format($fetch_orders['total_price'], 2); ?></span>
                </td>
                <td class="px-4 py-3">
                  <?php if($fetch_orders['payment_status'] == 'pending'): ?>
                    <span class="badge bg-warning">Pending</span>
                  <?php else: ?>
                    <span class="badge bg-success">Completed</span>
                  <?php endif; ?>
                </td>
                <td class="px-4 py-3">
                  <button type="button" class="btn btn-link p-0 text-primary" data-bs-toggle="modal" data-bs-target="#orderModal<?= $fetch_orders['id']; ?>">
                    View Details
                  </button>
                </td>
              </tr>

              <!-- Order Details Modal -->
              <div class="modal fade" id="orderModal<?= $fetch_orders['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content rounded-4 border-0" style="box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <div class="modal-header border-0">
                      <h5 class="modal-title fw-bold" style="color: #2b3452;">Order #<?= $fetch_orders['id']; ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between py-2" style="border-bottom: 1px solid #dee2e6;">
                          <span class="text-muted">Customer</span>
                          <span class="fw-medium" style="color: #2b3452;"><?= $fetch_orders['name']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-2" style="border-bottom: 1px solid #dee2e6;">
                          <span class="text-muted">Email</span>
                          <span style="color: #2b3452;"><?= $fetch_orders['email']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-2" style="border-bottom: 1px solid #dee2e6;">
                          <span class="text-muted">Phone</span>
                          <span style="color: #2b3452;"><?= $fetch_orders['number']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-2" style="border-bottom: 1px solid #dee2e6;">
                          <span class="text-muted">Address</span>
                          <span style="color: #2b3452;"><?= $fetch_orders['address']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-2" style="border-bottom: 1px solid #dee2e6;">
                          <span class="text-muted">Payment Method</span>
                          <span class="text-capitalize" style="color: #2b3452;"><?= $fetch_orders['method']; ?></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>

      <?php else: ?>
      <!-- No Orders State -->
      <div class="card border-0 rounded-4 p-5 text-center" style="background-color: white; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
        <div class="mb-4">
          <i class="fas fa-shopping-bag text-primary" style="font-size: 3rem;"></i>
        </div>
        <h4 class="mb-3" style="color: #2b3452;">No Orders Yet</h4>
        <p class="text-muted mb-4">Start shopping to see your orders here!</p>
        <a href="shop.php" class="btn btn-primary px-4 py-2 mx-auto rounded-pill">
          Browse Products
        </a>
      </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>

  <style>
    .table th, .table td {
      white-space: nowrap;
      vertical-align: middle;
    }
    
    .modal-backdrop.show {
      opacity: 0.7;
    }
    
    @media (max-width: 767.98px) {
      .orders-section {
        padding-left: 1rem;
        padding-right: 1rem;
      }
    }
  </style>
</section>















<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>