<?php
require '../config.php';
checkCandidateAuth();

// Fetch candidate profile details
$stmt = $pdo->prepare("
    SELECT c.*, d.document_path AS profile_photo 
    FROM candidates c
    LEFT JOIN documents d ON c.id = d.candidate_id AND d.type = 'profile_photo'
    WHERE c.id = ?
");
$stmt->execute([$_SESSION['candidate_id']]);
$candidate = $stmt->fetch();

$names = explode(' ', $candidate['name']);
$initials = '';
if(count($names) >= 2) {
    $initials = strtoupper($names[0][0].end($names)[0]);
} else {
    $initials = strtoupper(substr($candidate['name'], 0, 2));
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

    <title>Profile | SoT</title>

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

    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
       /* General layout for mobile responsiveness */
@media (max-width: 768px) {
    /* Profile card container adjustments */
    .card {
        width: 100%;
        margin: 0;
        padding: 20px;
    }

    .flex {
        display: flex;
        flex-wrap: wrap;
        gap: 20px; /* Space between elements */
    }

    .flex-shrink-0 {
        flex-shrink: 0;
    }

    /* Adjust the avatar (profile picture) size */
    .w-32 {
        width: 80px;
        height: 80px;
    }

    /* Change text size and layout for smaller screens */
    .text-3xl {
        font-size: 24px;
    }

    .text-sm {
        font-size: 12px;
    }

    .font-medium {
        font-size: 14px;
    }

    /* Make the profile details stack vertically */
    .space-y-4 {
        margin-top: 10px;
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr; /* Single column on mobile */
        gap: 10px;
    }

    .text-gray-600 {
        font-size: 14px;
    }

    /* Adjust buttons for mobile screens */
    .btn {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
    }

    .btn-blue {
        width: 100%;
        padding: 12px;
    }

    /* Adjust the document verification status box */
    .bg-yellow-100 {
        padding: 8px;
        font-size: 12px;
    }

    /* Ensure the 'Change Photo' button is aligned well */
    .w-full {
        width: 100%;
        padding: 12px;
        text-align: center;
    }
}


    </style>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="index.html" class="app-brand-link gap-2">
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
            <!-- Documents -->
            <li class="menu-item">
              <a href="upload_document.php" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-file-doc"></i>
                <div class="text-truncate">Upload Documents</div>
              </a>
            </li>
            <!-- Take Test -->
            <li class="menu-item">
              <a href="take_test.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div class="text-truncate">Attend Exam</div>
              </a>
            </li>
            <!-- View Offer -->
            <li class="menu-item">
              <a href="offer_letter.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-envelope"></i>
                <div class="text-truncate">Offer Letter</div>
              </a>
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
                        <?php if($candidate['profile_photo']): ?>
                            <img src="<?= $candidate['profile_photo'] ?>" alt class="w-px-40 h-px-40 rounded-circle object-cover" />
                        <?php else: ?>
                            <div class="w-px-40 h-px-40 rounded-circle bg-blue-500 flex items-center justify-center text-white text-xl font-bold border-2 border-blue-100">
                            <?= $initials ?>
                            </div>
                        <?php endif; ?>
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                                <?php if($candidate['profile_photo']): ?>
                                    <img src="<?= $candidate['profile_photo'] ?>" alt class="w-px-40 h-px-40 rounded-circle object-cover" />
                                <?php else: ?>
                                    <div class="w-px-40 h-px-40 rounded-circle bg-blue-500 flex items-center justify-center text-white text-xl font-bold border-2 border-blue-100">
                                    <?= $initials ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-0"><?= htmlspecialchars($candidate['name']) ?></h6>
                            <small class="text-body-secondary"><?= htmlspecialchars($candidate['email']) ?></small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="profile.php">
                        <i class="icon-base bx bx-user icon-md me-3"></i><span>My Profile</span>
                      </a>
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
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="card">
                            <div class="card-body">
                                <!-- Profile Header -->
                                <div class="flex items-center justify-between mb-8">
                                    <h1 class="text-3xl font-bold text-gray-800">My Profile</h1>
                                    <a href="upload_document.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                                        <?= $candidate['profile_photo'] ? 'Change Photo' : 'Upload Photo' ?>
                                    </a>
                                </div>

                                <!-- Profile Content -->
                                <div class="flex items-start gap-8">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0">
                                        <?php if($candidate['profile_photo']): ?>
                                            <img src="<?= $candidate['profile_photo'] ?>" 
                                                 class="w-32 h-32 rounded-full object-cover border-4 border-blue-100">
                                        <?php else: ?>
                                            <div class="w-32 h-32 rounded-full bg-blue-500 flex items-center justify-center 
                                                          text-white text-4xl font-bold border-4 border-blue-100">
                                                <?= $initials ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Details -->
                                    <div class="space-y-4 flex-1">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-gray-600 text-sm">Full Name</label>
                                                <p class="font-medium"><?= htmlspecialchars($candidate['name']) ?></p>
                                            </div>
                                            <div>
                                                <label class="text-gray-600 text-sm">Email</label>
                                                <p class="font-medium"><?= htmlspecialchars($candidate['email']) ?></p>
                                            </div>
                                            <div>
                                                <label class="text-gray-600 text-sm">Mobile Number</label>
                                                <p class="font-medium"><?= htmlspecialchars($candidate['phone']) ?></p>
                                            </div>
                                            <div>
                                                <label class="text-gray-600 text-sm">Date of Birth</label>
                                                <p class="font-medium"><?= date('M j, Y', strtotime($candidate['dob'])) ?></p>
                                            </div>
                                            <div>
                                                <label class="text-gray-600 text-sm">Registration Date</label>
                                                <p class="font-medium"><?= date('M j, Y', strtotime($candidate['created_at'])) ?></p>
                                            </div>
                                        </div>

                                        <!-- Verification Status -->
                                        <div class="mt-6">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-medium">Document Verification:</span>
                                                <?php if($candidate['verified']): ?>
                                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">Verified</span>
                                                <?php else: ?>
                                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm">Pending Verification</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl">
                <div
                  class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                  <div class="mb-2 mb-md-0">
                    &#169;
                    <script>
                      document.write(new Date().getFullYear());
                    </script>
                    made by
                    <a href="https://smartontechnologies.com" target="_blank" class="footer-link">Smarton Technologies</a>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

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
    <!-- Core JS -->

    <script src="../assets/vendor/libs/jquery/jquery.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap 5 with Popper.js -->

    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->

    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
