<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['updated'])){
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
 
    // Update user profile
    $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
    $update_profile->execute([$name, $email, $user_id]);
 
    $old_pass = sha1(filter_var($_POST['old_pass'], FILTER_SANITIZE_STRING));
    $new_pass = sha1(filter_var($_POST['new_pass'], FILTER_SANITIZE_STRING));
    $cpass = sha1(filter_var($_POST['cpass'], FILTER_SANITIZE_STRING));
 
    // Fetch current password from database
    $select_password = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
    $select_password->execute([$user_id]);
    $result = $select_password->fetch(PDO::FETCH_ASSOC);
 
    if ($result) {
        $prev_pass = $result['password'];
 
        if ($old_pass == $prev_pass) {
            if ($new_pass == $cpass && $new_pass != sha1("")) {
                $update_password = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
                $update_password->execute([$new_pass, $user_id]);
                $message[] = 'Password updated successfully!';
            } else {
                $message[] = 'New password and confirm password do not match!';
            }
        } else {
            $message[] = 'Old password does not match!';
        }
    } else {
        $message[] = 'User not found!';
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
   <link rel="icon" type="image/x-icon" href="favicon.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<?php include 'components/header.php'; ?>


<section class="account-management py-5" style="background-color: #ffffff;">
    <div class="container">
        <!-- Profile Update Card -->
        <div class="card border-0 rounded-4 overflow-hidden mb-5" 
             style="background: rgba(255, 255, 255, 0.95);
                    backdrop-filter: blur(10px);
                    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);">
            <div class="card-body p-4 p-md-5">
                <!-- Header -->
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="p-3 rounded-circle" 
                         style="background: linear-gradient(135deg, rgba(13,110,253,0.1) 0%, rgba(13,110,253,0.05) 100%);">
                        <i class="fas fa-user-cog text-primary fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1" style="color: #2b3452;">Account Settings</h3>
                        <p class="text-muted small mb-0">Update your personal information</p>
                    </div>
                </div>

                <!-- Update Form -->
                <form action="" method="post">
                        <input type="hidden" name="prev_pass" value="<?= $fetch_profile["password"]; ?>">
                        <div class="row g-3">
                            <!-- Username -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           name="name" 
                                           class="form-control bg-light border-0" 
                                           id="name" 
                                           placeholder="Username"
                                           required 
                                           maxlength="20"
                                           value="<?= $fetch_profile["name"]; ?>"
                                           style="border-radius: 12px;">
                                    <label class="text-muted">Username</label>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" 
                                           name="email" 
                                           class="form-control bg-light border-0" 
                                           id="email" 
                                           placeholder="Email"
                                           required 
                                           maxlength="50"
                                           value="<?= $fetch_profile["email"]; ?>"
                                           style="border-radius: 12px;"
                                           oninput="this.value = this.value.replace(/\s/g, '')">
                                    <label class="text-muted">Email Address</label>
                                </div>
                            </div>

                            <!-- Old Password -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="password" 
                                           name="old_pass" 
                                           class="form-control bg-light border-0" 
                                           id="old_pass" 
                                           placeholder="Old Password"
                                           maxlength="20"
                                           style="border-radius: 12px;"
                                           oninput="this.value = this.value.replace(/\s/g, '')">
                                    <label class="text-muted">Old Password</label>
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" 
                                           name="new_pass" 
                                           class="form-control bg-light border-0" 
                                           id="new_pass" 
                                           placeholder="New Password"
                                           maxlength="20"
                                           style="border-radius: 12px;"
                                           oninput="this.value = this.value.replace(/\s/g, '')">
                                    <label class="text-muted">New Password</label>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" 
                                           name="cpass" 
                                           class="form-control bg-light border-0" 
                                           id="cpass" 
                                           placeholder="Confirm Password"
                                           maxlength="20"
                                           style="border-radius: 12px;"
                                           oninput="this.value = this.value.replace(/\s/g, '')">
                                    <label class="text-muted">Confirm New Password</label>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="col-12 mt-4">
                              <button type="submit" name="updated" 
                                        class="btn btn-primary py-3 px-5 rounded-3 fw-semibold float-end"
                                        style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                                    <i class="fas fa-check-circle me-2"></i>Update Profile
                                </button>
                            </div>
                        </div>
                </form>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="row g-4">
            <!-- Account Settings -->
            <div class="col-md-4">
                <div class="card h-100 border-0 rounded-4 text-center p-4" 
                     style="background: rgba(255, 255, 255, 0.95);
                            backdrop-filter: blur(10px);
                            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);">
                    <div class="card-body">
                        <div class="d-inline-block p-3 rounded-circle mb-3" 
                             style="background: linear-gradient(135deg, rgba(13,110,253,0.1) 0%, rgba(13,110,253,0.05) 100%);">
                            <i class="fas fa-cogs text-primary fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-2" style="color: #2b3452;">Account Settings</h4>
                        <p class="text-muted mb-0">Manage your personal information and security preferences.</p>
                    </div>
                </div>
            </div>

            <!-- Privacy & Security -->
            <div class="col-md-4">
                <div class="card h-100 border-0 rounded-4 text-center p-4" 
                     style="background: rgba(255, 255, 255, 0.95);
                            backdrop-filter: blur(10px);
                            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);">
                    <div class="card-body">
                        <div class="d-inline-block p-3 rounded-circle mb-3" 
                             style="background: linear-gradient(135deg, rgba(13,110,253,0.1) 0%, rgba(13,110,253,0.05) 100%);">
                            <i class="fas fa-lock text-primary fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-2" style="color: #2b3452;">Privacy & Security</h4>
                        <p class="text-muted mb-0">Change your password and update privacy settings.</p>
                    </div>
                </div>
            </div>

            <!-- Help & Support -->
            <div class="col-md-4">
                <div class="card h-100 border-0 rounded-4 text-center p-4" 
                     style="background: rgba(255, 255, 255, 0.95);
                            backdrop-filter: blur(10px);
                            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);">
                    <div class="card-body">
                        <div class="d-inline-block p-3 rounded-circle mb-3" 
                             style="background: linear-gradient(135deg, rgba(13,110,253,0.1) 0%, rgba(13,110,253,0.05) 100%);">
                            <i class="fas fa-question-circle text-primary fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-2" style="color: #2b3452;">Help & Support</h4>
                        <p class="text-muted mb-0">Get assistance with your account or find FAQs.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
