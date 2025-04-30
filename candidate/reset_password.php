<?php
session_start();
require '../config.php';
include '../alerts.php';

if (!isset($_SESSION['temp_password_user'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header('Location: reset_password.php');
        exit();
    }
    
    try {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            UPDATE candidates 
            SET password = ?, is_temp_password = 0 
            WHERE id = ?
        ");
        $stmt->execute([$hashed_password, $_SESSION['temp_password_user']]);
        
        unset($_SESSION['temp_password_user']);
        $_SESSION['success'] = "Password reset successfully! Please login with your new password";
        header('Location: ../index.php');
        exit();
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating password: " . $e->getMessage();
        header('Location: reset_password.php');
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

    <title>Change Password | SoT</title>

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
          <!-- Change Password -->
          <div class="card px-sm-6 px-0">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="index.html" class="app-brand-link gap-2">
                    <img src="../assets/img/favicon/dark-logo.png" alt="dark-logo" width="200"/>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reset Password 🔒</h4>
              <div class="<?= $is_admin ? 'ml-64' : '' ?> p-8">
                  <form method="POST" class="mb-6">
                    <div class="mb-6">
                      <label class="form-label">New Password</label>
                      <input
                        type="password"
                        class="form-control"
                        name="new_password"
                        placeholder="Enter your new password"
                        autofocus />
                    </div>
                    <div class="mb-6">
                      <label class="form-label">Confirm Password</label>
                      <input
                        type="password"
                        class="form-control"
                        name="confirm_password"
                        placeholder="Enter password again"
                        autofocus />
                    </div>
                    <button type="submit" class="btn btn-primary d-grid w-100">Reset Password</button>
                  </form>
            </div>
          </div>
          <!-- /Change Password -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    <script src="../assets/js/main.js"></script>
  </body>
</html>