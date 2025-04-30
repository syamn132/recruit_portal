<?php
session_start();
require '../config.php';
include '../alerts.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'])) {
        // Initial identity verification
        $stmt = $pdo->prepare("
            SELECT id FROM candidates 
            WHERE email = ? 
            AND phone = ? 
            AND name = ? 
            AND dob = ?
        ");
        
        $stmt->execute([
            $_POST['email'],
            $_POST['mobile'],
            $_POST['username'],
            $_POST['dob']
        ]);
        
        if ($user = $stmt->fetch()) {
            $_SESSION['reset_user_id'] = $user['id'];
        } else {
            $_SESSION['error'] = "Invalid credentials!";
        }
        header("Location: forgot_password.php");
        exit();
        
    } elseif (isset($_POST['new_password']) && isset($_SESSION['reset_user_id'])) {
        // Password reset handling
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if ($new_password !== $confirm_password) {
            $_SESSION['error'] = "Passwords do not match!";
            header("Location: forgot_password.php");
            exit();
        }
        
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE candidates SET password = ? WHERE id = ?");
        $stmt->execute([$hashed_password, $_SESSION['reset_user_id']]);
        
        unset($_SESSION['reset_user_id']);
        $_SESSION['success'] = "Password reset successfully!";
        session_write_close();
        header("Location: ../index.php");
        exit();
    }
}
?>

<!doctype html>

<html
  lang="en"
  class="layout-wide customizer-hide"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Forgot Password | SoT</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/logo.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="../assets/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="../assets/vendor/css/core.css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- endbuild -->

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Forgot Password -->
          <div class="card px-sm-6 px-0">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="index.html" class="app-brand-link gap-2">
                    <img src="../assets/img/favicon/dark-logo.png" alt="dark-logo" width="200"/>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-1">Forgot Password 🔒</h4>
              
              <?php if (!isset($_SESSION['reset_user_id'])): ?>
              <form method="POST" class="mb-6">
                <div class="mb-6">
                  <label class="form-label">Username</label>
                  <input
                    type="text"
                    class="form-control"
                    name="username"
                    placeholder="Enter your username"
                    autofocus />
                </div>
                <div class="mb-6">
                  <label class="form-label">Email</label>
                  <input
                    type="email"
                    class="form-control"
                    name="email"
                    placeholder="Enter your email"
                    autofocus />
                </div>
                <div class="mb-6">
                  <label class="form-label">Mobile Number</label>
                  <input
                    type="tel"
                    class="form-control"
                    name="mobile"
                    pattern="[0-9]{10}"
                    placeholder="Enter your mobile number"
                    autofocus />
                </div>
                <div class="mb-6">
                  <label class="form-label">Date of Birth</label>
                  <input
                    type="date"
                    class="form-control"
                    name="dob"
                    placeholder="Enter your date of birth"
                    autofocus />
                </div>
                <button type="submit" class="btn btn-primary d-grid w-100">Verify Identity</button>
              </form>
              <?php else: ?>
            <!-- Password Reset Form -->
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" 
                           class="form-control" placeholder="Enter New Password" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" 
                           class="form-control" placeholder="Enter New Passord" required>
                </div>
                
                <button type="submit" class="btn btn-primary d-grid w-100">Set New Password</button>
            </form>
            <?php endif; ?>
              <div class="text-center">
                <a href="../index.php" class="d-flex justify-content-center">
                  <i class="icon-base bx bx-chevron-left me-1"></i>
                  Back to login
                </a>
              </div>
            </div>
          </div>
          <!-- /Forgot Password -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    <script src="../assets/js/main.js"></script>
  </body>
</html>
