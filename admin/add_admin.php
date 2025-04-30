<?php
require '../config.php';
include '../alerts.php';
checkAdminAuth();

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Invalid security token. Please refresh the page.";
        header("Location: add_admin.php");
        exit();
    }

    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Input validation
    $errors = [];
    if (empty($name) || empty($username) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $username)) {
        $errors[] = "Username must be 4-20 characters (letters, numbers, underscores).";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
        header("Location: add_admin.php");
        exit();
    }

    // Check for existing username/email
    try {
        $stmt = $pdo->prepare("SELECT id, username FROM admins WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            $_SESSION['error'] = ($existing['username'] === $username) 
                ? "Username already exists." 
                : "Email already exists.";
            header("Location: add_admin.php");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Admin check error: " . $e->getMessage());
        $_SESSION['error'] = "System error. Please try again.";
        header("Location: add_admin.php");
        exit();
    }

    // Create admin
    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admins (name, username, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $username, $email, $hashedPassword]);
        
        $_SESSION['success'] = "Admin created successfully!";
        header("Location: admins.php"); // Redirect to admin list on success
        exit();
    } catch (PDOException $e) {
        error_log("Admin creation error: " . $e->getMessage());
        $_SESSION['error'] = "System error. Please try again.";
        header("Location: add_admin.php");
        exit();
    }
}
?>

<!doctype html>

<html
  lang="en"
  class="layout-menu-fixed layout-compact"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Add Admin | SoT</title>

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

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="javascript:void(0);" class="app-brand-link gap-2">
                <img src="../assets/img/favicon/dark-logo.png" alt="dark-logo" width="200"/>
            </a>
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
            </a>
          </div>

          <div class="menu-divider mt-0"></div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item">
              <a href="dashboard.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate">Dashboard</div>
              </a>
            </li>
            <!-- Candidates -->
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base bx bx-user"></i>
                <div class="text-truncate">Candidates</div>
              </a>
              <ul class="menu-sub">
                  <li class="menu-item">
                  <a href="add_candidate.php" class="menu-link">
                    <div class="text-truncate">Add Candidate</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="manage_candidates.php" class="menu-link">
                    <div class="text-truncate">Edit / Delete</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="reset_password.php" class="menu-link">
                    <div class="text-truncate">Reset Password</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="candidates.php" class="menu-link">
                    <div class="text-truncate">Candidates List</div>
                  </a>
                </li>
              </ul>
            </li>
            
            <!-- Exam Settings -->
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div class="text-truncate">Exam Settings</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="questions.php" class="menu-link">
                    <div class="text-truncate">Exam Questions</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="test_results.php" class="menu-link">
                    <div class="text-truncate">Exam Results</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="questions.php" class="menu-link">
                    <div class="text-truncate">Add / Modify Questions</div>
                  </a>
                </li>
              </ul>
            </li>
            
            <!-- Documents -->
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bxs-file-doc"></i>
                <div class="text-truncate">Documents</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="documents.php" class="menu-link">
                    <div class="text-truncate">Check Documents</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="manage_candidates.php" class="menu-link">
                    <div class="text-truncate">Mark Verified</div>
                  </a>
                </li>
              </ul>
            </li>
            
            <!-- Offer Letter -->
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-envelope"></i>
                <div class="text-truncate">Offer Letter</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="upload_offer.php" class="menu-link">
                    <div class="text-truncate">Upload Offer</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="offer_status.php" class="menu-link">
                    <div class="text-truncate">Check Offer Status</div>
                  </a>
                </li>
              </ul>
            </li>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
            id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                <i class="icon-base bx bx-menu icon-md"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center me-auto d-none d-md-block">
                <div class="nav-item d-flex align-items-center">
                  <span class="w-px-22 h-px-22"><i class="icon-base bx bx-search icon-md"></i></span>
                  <input
                    type="text"
                    class="form-control border-0 shadow-none ps-1 ps-sm-2"
                    placeholder="Search..."
                    aria-label="Search..." />
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                <!-- Place this tag where you want the button to render. -->

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a
                    class="nav-link dropdown-toggle hide-arrow p-0"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="../assets/img/favicon/logo.png" alt class="w-px-40 h-px-40 object-cover rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="../assets/img/favicon/logo.png" alt class="w-px-40 h-px-40 object-cover rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-0">HR</h6>
                            <small class="text-body-secondary">HR</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="change_password.php">
                        <i class="icon-base bx bx-cog icon-md me-3"></i><span>Change Password</span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="logout.php">
                        <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="col-xxl">
                  <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="mb-0">Add New Admin</h5>
                    </div>
                    <div class="card-body">
                      <form method="POST" id="adminForm">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">  
                        
                        <div class="row mb-6">
                          <label class="col-sm-2 col-form-label">Full Name</label>
                          <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                              <span class="input-group-text"
                                ><i class="icon-base bx bx-user"></i
                              ></span>
                              <input
                                type="text"
                                name="name"
                                class="form-control"
                                minlength="2"
                                maxlength="50"
                                pattern="[\p{L} ]+"
                                placeholder="Enter full name"
                                aria-label="Enter full name"
                                aria-describedby="basic-icon-default-fullname2" 
                                required/>
                            </div>
                            <div class="form-text">Use only Letters and Spaces</div>
                          </div>
                        </div>
                        
                        <div class="row mb-6">
                          <label class="col-sm-2 col-form-label">Username</label>
                          <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                              <span class="input-group-text"
                                ><i class="icon-base bx bx-user"></i
                              ></span>
                              <input
                                type="text"
                                name="username"
                                class="form-control"
                                minlength="4"
                                maxlength="20"
                                pattern="[A-Za-z0-9_]+"
                                placeholder="Enter username"
                                aria-label="Enter username"
                                aria-describedby="basic-icon-default-username" 
                                required/>
                            </div>
                            <div class="form-text">Use only Letters and numbers</div>
                          </div>
                        </div>
                        
                        <div class="row mb-6">
                          <label class="col-sm-2 col-form-label">Email</label>
                          <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                              <span class="input-group-text"><i class="icon-base bx bx-envelope"></i></span>
                              <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="name.doe"
                                aria-label="name.doe"
                                required/>
                              <span class="input-group-text">@gmail.com</span>
                            </div>
                            <div class="form-text">You can use letters, numbers & periods</div>
                          </div>
                        </div>
                        
                        <div class="row mb-6">
                          <label class="col-sm-2 col-form-label">Password</label>
                          <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                              <span class="input-group-text"
                                ><i class="icon-base bx bx-key"></i
                              ></span>
                              <input
                                type="password"
                                name="password"
                                required minlength="8"
                                pattern="^(?=.*[A-Za-z])(?=.*\d).{8,}$"
                                class="form-control"
                                placeholder="At least 8 characters with a letter and number"
                                aria-label="At least 8 characters with a letter and number"
                            </div>
                          </div>
                        </div>
                        
                        <div class="row justify-conent-end mt-6">
                          <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Add Admin</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
            </div>
            <!-- / Content -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <div class="buy-now">
      <a
        href="logout.php"
        class="btn btn-danger btn-buy-now"
        >Logout</a
      >
    </div>

    <!-- Core JS -->

    <script src="../assets/vendor/libs/jquery/jquery.js"></script>

    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>

    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->

    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
