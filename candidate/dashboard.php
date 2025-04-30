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

    <title>Dashboard | SoT</title>

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
            <li class="menu-item active">
              <a href="javascript:void(0);" class="menu-link">
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
                            <img src="<?= $candidate['profile_photo'] ?>" alt class="w-px-40 h-px-40 rounded-circle object-cover"/>
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
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <div class="col-xxl-8 mb-6 order-0">
                  <div class="card">
                    <div class="d-flex align-items-start row">
                      <div class="col-sm-7">
                        <div class="card-body">
                            <?php if ($status['test_taken']) { 
                                if ($status['verified']) { 
                                    if ($status['has_offer']) { 
                                            if ($status['offer_accepted']) {
                                                echo '<h5 class="card-title text-primary mb-3">Congratulations ' . htmlspecialchars($candidate['name']) . '! 🎉</h5>
                                                <p class="mb-6">
                                                    You have accepted the offer. Be ready to join our team!
                                                </p>
                                                <a href="profile.php" class="btn btn-sm btn-outline-primary">View Profile</a>';
                                            } else {
                                                echo '<h5 class="card-title text-primary mb-3">Congratulations ' . htmlspecialchars($candidate['name']) . '! 🎉</h5>
                                                <p class="mb-6">
                                                    You have received an offer. Please review and accept the offer.
                                                </p>
                                                <a href="offer_letter.php" class="btn btn-sm btn-outline-primary">Review Offer</a>';
                                            }
                                        } else {
                                            echo '<h5 class="card-title text-primary mb-3">Congratulations ' . htmlspecialchars($candidate['name']) . '! 🎉</h5>
                                            <p class="mb-6">
                                                Your documents are verified now. Test and Interview results are being calculated. You will receive an update soon.
                                            </p>
                                            <a href="profile.php" class="btn btn-sm btn-outline-primary">View Profile</a>';
                                        }
                                    } else {
                                        echo '<h5 class="card-title text-primary mb-3">Hello ' . htmlspecialchars($candidate['name']) . '! 🎉</h5>
                                        <p class="mb-6">
                                            You have completed the test. Please upload your documents for verification.
                                        </p>
                                        <a href="upload_document.php" class="btn btn-sm btn-outline-primary">Upload Documents</a>';
                                    }
                                } else {
                                    echo '<h5 class="card-title text-primary mb-3">Hello ' . htmlspecialchars($candidate['name']) . '! 🎉</h5>
                                    <p class="mb-6">
                                        You have not completed the test yet. Please complete the test first.
                                    </p>
                                    <a href="take_test.php" class="btn btn-sm btn-outline-primary">Complete Test</a>';
                                }
                            ?>
                        </div>
                      </div>
                      <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-6">
                          <img
                            src="../assets/img/illustrations/man-with-laptop.png"
                            height="175"
                            alt="View Badge User" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="col-xxl-4 col-lg-12 col-md-4 order-1">
                  <div class="row">
                    <div class="col-lg-6 col-md-12 col-6 mb-6">
                      <div class="card h-100">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/test-icon.png"
                                alt="chart success"
                                class="rounded" />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt3"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                                <i class="icon-base bx bx-dots-vertical-rounded text-body-secondary"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                <a class="dropdown-item" href="profile.php">View More</a>
                              </div>
                            </div>
                          </div>
                          <p class="mb-1">Online Test</p>
                          <button class="btn btn-success mb-3 d-none d-lg-inline-block" type="button" onclick="location.href='take_test.php'">
                              Take Test
                            </button>
                            <button class="btn btn-success mb-3 d-inline-block d-lg-none" type="button" onclick="location.href='take_test.php'">
                               Attempt
                            </button>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-6">
                      <div class="card h-100">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/job-offer.png"
                                alt="chart success"
                                class="rounded" />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt3"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                                <i class="icon-base bx bx-dots-vertical-rounded text-body-secondary"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                <a class="dropdown-item" href="profile.php">View More</a>
                              </div>
                            </div>
                          </div>
                          <p class="mb-1">Offer Letter</p>
                          <button class="btn btn-info mb-3 d-none d-lg-inline-block" type="button" onclick="location.href='offer_letter.php'">
                              Check
                            </button>
                            <button class="btn btn-info mb-3 d-inline-block d-lg-none" type="button" onclick="location.href='offer_letter.php'">
                              Check
                            </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class=" col-12 col-xxl-8 order-3 order-xxl-2 mb-6">
                  <div class="card">
                    <h5 class="card-header">Overall Status</h5>
                    <div class="table-responsive text-nowrap">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>Tasks</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                          <tr>
                            <td>
                              <i class="icon-base bx bx-dock-top icon-md text-success me-4"></i> <span>Online Test</span>
                            </td>
                            <?= $status['test_taken'] ? '<td><span class="badge bg-label-success me-1">Completed</span></td>' : '<td><span class="badge bg-label-warning me-1">Pending</span></td>' ?>
                          </tr>
                          <tr>
                            <td>
                              <i class="icon-base bx bx-file icon-md text-danger me-4"></i> <span>Documents</span>
                            </td>
                            <?= $status['verified'] ? '<td><span class="badge bg-label-success me-1">Uploaded</span></td>' : '<td><span class="badge bg-label-warning me-1">Pending</span></td>' ?>
                          </tr>
                          <tr>
                            <td>
                              <i class="icon-base bx bx-user-check icon-md text-info me-4"></i> <span>Verification</span>
                            </td>
                            <?= $status['verified'] ? '<td><span class="badge bg-label-success me-1">Completed</span></td>' : '<td><span class="badge bg-label-warning me-1">Pending</span></td>' ?>
                          </tr>
                          <tr>
                            <td>
                              <i class="icon-base bx bxs-file-pdf icon-md text-warning me-4"></i> <span>Offer Letter</span>
                            </td>
                            <?= $status['has_offer'] ? '<td><span class="badge bg-label-success me-1">Completed</span></td>' : '<td><span class="badge bg-label-warning me-1">Pending</span></td>' ?>
                          </tr>
                          <tr>
                            <td>
                              <i class="icon-base bx bx-stats icon-md text-secondary me-4"></i> <span>Offer Status</span>
                            </td>
                            <?php
                            if ($status['has_offer']) {
                                if ($status['offer_accepted']) {
                                    echo '<td><span class="badge bg-label-success me-1">Accepted</span></td>';
                                } else {
                                    echo '<td><span class="badge bg-label-info me-1">Received</span></td>';
                                }
                            } else {
                                echo '<td><span class="badge bg-label-warning me-1">Pending</span></td>';
                            }
                            ?>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                
                <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-2">
                  <div class="row">
                    <div class="col-6 mb-6">
                      <div class="card h-100">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <div class="avatar flex-shrink-0">
                              <img src="../assets/img/icons/unicons/upload-offer.png" class="rounded" />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt4"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                                <i class="icon-base bx bx-dots-vertical-rounded text-body-secondary"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                <a class="dropdown-item" href="profile.php">Check Status</a>
                              </div>
                            </div>
                          </div>
                          <p class="mb-1">Upload Documents</p>
                          <button class="btn btn-danger mb-3 d-none d-lg-inline-block" type="button" onclick="location.href='upload_document.php'">
                              Upload Doc
                            </button>
                            <button class="btn btn-danger mb-3 d-inline-block d-lg-none" type="button" onclick="location.href='upload_document.php'">
                              Upload
                            </button>
                        </div>
                      </div>
                    </div>
                    <div class="col-6 mb-6">
                      <div class="card h-100">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <div class="avatar flex-shrink-0">
                              <img src="../assets/img/icons/unicons/reset-password.png" class="rounded" />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt1"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                                <i class="icon-base bx bx-dots-vertical-rounded text-body-secondary"></i>
                              </button>
                              <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                <a class="dropdown-item" href="profile.php">View Profile</a>
                              </div>
                            </div>
                          </div>
                          <p class="mb-1">Change Password</p>
                          <button class="btn btn-primary mb-3 d-none d-lg-inline-block" type="button" onclick="location.href='change_password.php'">
                              Change Now
                            </button>
                            <button class="btn btn-primary mb-3 d-inline-block d-lg-none" type="button" onclick="location.href='change_password.php'">
                              Change
                            </button>
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

    <div class="buy-now">
      <a
        href="logout.php"
        class="btn btn-danger btn-buy-now"
        >Log Out</a
      >
    </div>

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
