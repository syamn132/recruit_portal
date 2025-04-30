<?php
require '../config.php';
include '../alerts.php';
checkCandidateAuth();

if (!isset($_SESSION['candidate_logged_in']) || !$_SESSION['candidate_logged_in']) {
    header('Location: ../index.php');
    exit();
}

// Get candidate status with offer acceptance
$stmt = $pdo->prepare("
    SELECT 
        c.verified,
        (SELECT COUNT(*) FROM test_results WHERE candidate_id = ?) AS test_taken,
        COALESCE(o.accepted, 0) AS offer_accepted,
        (SELECT COUNT(*) FROM offer_letters WHERE candidate_id = ?) AS has_offer
    FROM candidates c
    LEFT JOIN offer_letters o ON c.id = o.candidate_id
    WHERE c.id = ?
");
$stmt->execute([
    $_SESSION['candidate_id'],
    $_SESSION['candidate_id'],
    $_SESSION['candidate_id']
]);
$status = $stmt->fetch();

$names = explode(' ', $candidate['name']);
$initials = '';
if(count($names) >= 2) {
    $initials = strtoupper($names[0][0].end($names)[0]);
} else {
    $initials = strtoupper(substr($candidate['name'], 0, 2));
}

// Get offer letter details
$stmt = $pdo->prepare("
    SELECT o.* 
    FROM offer_letters o
    WHERE candidate_id = ?
");
$stmt->execute([$_SESSION['candidate_id']]);
$offer = $stmt->fetch();

// Handle offer acceptance
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accept_offer'])) {
    $stmt = $pdo->prepare("UPDATE offer_letters SET accepted = 1 WHERE id = ?");
    $stmt->execute([$offer['id']]);
    $_SESSION['success'] = "Offer accepted successfully!";
    header("Location: offer_letter.php");
    exit();
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

    <title>Offer Letter | SoT</title>

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
        /* Your regular CSS goes here */
        
        /* Add the media queries for mobile responsiveness */
        @media (max-width: 768px) {
            .card {
                width: 100%;
                margin: 0;
                padding: 10px;
            }

            .grid {
                grid-template-columns: 1fr;  /* Stack the columns */
            }

            .pdf-viewer {
                height: 300px;  /* Adjust the height of the PDF viewer */
            }

            .acceptance-section {
                padding-left: 0;
                border-left: none;
            }

            /* Adjust buttons for mobile screens */
            .btn {
                width: 100%;
                padding: 12px;
                margin-top: 10px;
            }

            .btn-red {
                font-size: 14px;
                padding: 10px;
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
            <li class="menu-item active">
              <a href="javascript:void(0);" class="menu-link">
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
                            <img src="<?= $candidate['profile_photo'] ?>" alt class="w-px-40 h-px-40 object-cover rounded-circle" />
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
                                    <img src="<?= $candidate['profile_photo'] ?>" alt class="w-px-40 h-px-40 object-cover rounded-circle" />
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
                            <!-- Responsive Flex Layout -->
                            <div class="flex flex-wrap justify-between mb-8">
                                <h1 class="text-3xl font-bold text-gray-800 w-full md:w-auto">Offer Letter</h1>
                                <?php if($offer && $offer['accepted']): ?>
                                    <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full">✅ Accepted</span>
                                <?php endif; ?>
                            </div>
            
                            <?php include '../alerts.php'; ?>
            
                            <?php if($offer): ?>
                                <!-- PDF Viewer Container -->
                                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                                    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                                        <div>
                                            <h2 class="text-xl font-semibold"><?= htmlspecialchars($_SESSION['candidate_name']) ?>'s Offer Letter</h2>
                                            <p class="text-gray-600">Issued on <?= date('F j, Y', strtotime($offer['joining_date'])) ?></p>
                                        </div>
                                        <a href="<?= $offer['file_path'] ?>" 
                                           download 
                                           class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 mt-4 md:mt-0">
                                            Download PDF
                                        </a>
                                    </div>
            
                                    <!-- PDF Preview -->
                                    <div class="border rounded-lg overflow-hidden">
                                        <iframe src="<?= $offer['file_path'] ?>#toolbar=0" 
                                                class="w-full h-[300px] md:h-[600px]" 
                                                frameborder="0">
                                            <p>Your browser does not support PDF preview. <a href="<?= $offer['file_path'] ?>">Download instead</a></p>
                                        </iframe>
                                    </div>
            
                                    <!-- Offer Details -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 p-4 bg-gray-50 rounded-lg">
                                        <div>
                                            <h3 class="font-semibold mb-2">Key Details</h3>
                                            <dl class="space-y-2">
                                                <div>
                                                    <dt class="text-gray-600">Total CTC</dt>
                                                    <dd class="font-medium">₹<?= number_format($offer['ctc'], 2) ?></dd>
                                                </div>
                                                <div>
                                                    <dt class="text-gray-600">Monthly In-hand</dt>
                                                    <dd class="font-medium">₹<?= number_format($offer['monthly_inhand'], 2) ?></dd>
                                                </div>
                                                <div>
                                                    <dt class="text-gray-600">Joining Date</dt>
                                                    <dd class="font-medium"><?= date('M j, Y', strtotime($offer['joining_date'])) ?></dd>
                                                </div>
                                            </dl>
                                        </div>
            
                                        <!-- Acceptance Section -->
                                        <?php if(!$offer['accepted']): ?>
                                        <div class="border-l pl-6 md:pl-4">
                                            <h3 class="font-semibold mb-4">Acceptance Section</h3>
                                            <form method="POST">
                                                <div class="mb-4">
                                                    <label class="flex items-center">
                                                        <input type="checkbox" required 
                                                               class="form-checkbox h-4 w-4 text-blue-600">
                                                        <span class="ml-2 text-gray-700">I accept the terms and conditions of this offer</span>
                                                    </label>
                                                </div>
                                                <button type="submit" name="accept_offer" 
                                                        class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600">
                                                    Accept Offer
                                                </button>
                                            </form>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="bg-white p-8 rounded-xl shadow-lg text-center">
                                    <p class="text-gray-600 text-xl">No offer letter available yet.</p>
                                    <p class="mt-4">Check back later or contact the HR team for updates.</p>
                                </div>
                            <?php endif; ?>
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
