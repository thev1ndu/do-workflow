<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

// Initialize $message as an empty array
$message = [];

if(isset($_POST['submit'])){
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
   $select_admin->execute([$name]);

   if($select_admin->rowCount() > 0){
      $message[] = 'Username already exists!';
   } else {
      if($pass != $cpass){
         $message[] = 'Confirm password does not match!';
      } else {
         $insert_admin = $conn->prepare("INSERT INTO `admins`(name, password) VALUES(?, ?)");
         $insert_admin->execute([$name, $cpass]);
         $message[] = 'New admin registered successfully!';
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>AstroShop | Register Admin</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

   <style>
      .form-container {
         background-color: #f9f9f9;
         padding: 30px;
         margin: 20px auto;
         border-radius: 8px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         width: 50%;
      }

      .form-container h3 {
         font-size: 24px;
         text-align: center;
         margin-bottom: 20px;
      }

      .form-container .box {
         width: 100%;
         padding: 10px;
         margin: 10px 0;
         border-radius: 5px;
         border: 1px solid #ddd;
      }

      .form-container .btn {
         background-color: blue;
         color: white;
         width: 100%;
         padding: 12px;
         border-radius: 5px;
         border: none;
         cursor: pointer;
      }

      .form-container .btn:hover {
         background-color: darkblue;
      }

      .message {
         text-align: center;
         color: red;
         font-size: 16px;
      }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>ADD ADMIN</h3>



      <input type="text" name="name" required placeholder="Enter admin username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter admin password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirm admin password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Register Now" class="btn" name="submit">
   </form>

</section>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="../js/admin_script.js"></script>

</body>
</html>
