<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $update_profile_name = $conn->prepare("UPDATE `admins` SET name = ? WHERE id = ?");
    $update_profile_name->execute([$name, $admin_id]);

    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $prev_pass = $_POST['prev_pass'];
    $old_pass = sha1($_POST['old_pass']);
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
    $confirm_pass = sha1($_POST['confirm_pass']);
    $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

    if ($old_pass == $empty_pass) {
        $message[] = 'Please enter the old password!';
    } elseif ($old_pass != $prev_pass) {
        $message[] = 'Old password not matched!';
    } elseif ($new_pass != $confirm_pass) {
        $message[] = 'Confirm password does not match!';
    } else {
        if ($new_pass != $empty_pass) {
            $update_admin_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE id = ?");
            $update_admin_pass->execute([$confirm_pass, $admin_id]);
            $message[] = 'Password updated successfully!';
        } else {
            $message[] = 'Please enter a new password!';
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
    <title>AstroShop | Account</title>

    <!-- Bootstrap CDN for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom styles (optional) -->
    <style>
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .message {
            color: #dc3545;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }

        .box {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">

    <form action="" method="post">
        <h3>UPDATE PROFILE</h3>

        

        <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">

        <div class="mb-3">
            <label for="name" class="form-label">Username</label>
            <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" required class="form-control" placeholder="Enter your username" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>

        <div class="mb-3">
            <label for="old_pass" class="form-label">Old Password</label>
            <input type="password" name="old_pass" placeholder="Enter old password" class="form-control" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>

        <div class="mb-3">
            <label for="new_pass" class="form-label">New Password</label>
            <input type="password" name="new_pass" placeholder="Enter new password" class="form-control" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>

        <div class="mb-3">
            <label for="confirm_pass" class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_pass" placeholder="Confirm new password" class="form-control" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>

        <button type="submit" class="btn btn-primary" name="submit">Update Now</button>
    </form>

</section>

<!-- Bootstrap JS for responsiveness -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
