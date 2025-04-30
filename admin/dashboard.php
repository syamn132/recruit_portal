<?php
require '../config.php';
include '../alerts.php';
checkAdminAuth();
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
            <li class="menu-item active">
              <a href="javascript:void(0);" class="menu-link">
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
                      <img src="../assets/img/favicon/logo.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="../assets/img/favicon/logo.png" alt class="w-px-40 h-auto rounded-circle" />
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
              <div class="row">
                <div class="col-xxl-8 mb-6 order-0">
                  <div class="card">
                    <div class="d-flex align-items-start row">
                      <div class="col-sm-7">
                        <div class="card-body">
                          <h5 class="card-title text-primary mb-3">Congratulations Admin! 🎉</h5>
                          <p class="mb-6">
                            You have total of <?php 
                                            $stmt = $pdo->query("SELECT COUNT(*) FROM candidates");
                                            echo $stmt->fetchColumn();
                                            ?> Candidates as of Today.<br />Check Candidates details.
                          </p>

                          <a href="candidates.php" class="btn btn-sm btn-outline-primary">View Candidates</a>
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
                                src="../assets/img/icons/unicons/create-employee.png"
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
                                <a class="dropdown-item" href="candidates.php">View More</a>
                                <a class="dropdown-item" href="candidates.php">Edit Candidate</a>
                              </div>
                            </div>
                          </div>
                          <p class="mb-1">Add Candidate</p>
                          <button class="btn btn-success mb-3 d-none d-lg-inline-block" type="button" onclick="location.href='add_candidate.php'">
                              Add New
                            </button>
                            <button class="btn btn-success mb-3 d-inline-block d-lg-none" type="button" onclick="location.href='add_candidate.php'">
                              Add
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
                                src="../assets/img/icons/unicons/remove-employee.png"
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
                                <a class="dropdown-item" href="candidates.php">View More</a>
                                <a class="dropdown-item" href="candidates.php">Edit Candidate</a>
                              </div>
                            </div>
                          </div>
                          <p class="mb-1">Remove Candidate</p>
                          <button class="btn btn-info mb-3 d-none d-lg-inline-block" type="button" onclick="location.href='remove_candidate.php'">
                              Remove
                            </button>
                            <button class="btn btn-info mb-3 d-inline-block d-lg-none" type="button" onclick="location.href='remove_candidate.php'">
                              Remove
                            </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Total Revenue -->
                <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6 total-revenue">
                  <div class="card">
                    <div class="row row-bordered g-0">
                      <div class="col-lg-8">
                        <div class="card-header d-flex align-items-center justify-content-between">
                          <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Total Candidates</h5>
                          </div>
                          <div class="dropdown">
                            <button
                              class="btn p-0"
                              type="button"
                              id="totalRevenue"
                              data-bs-toggle="dropdown"
                              aria-haspopup="true"
                              aria-expanded="false">
                              <i class="icon-base bx bx-dots-vertical-rounded icon-lg text-body-secondary"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalRevenue">
                              <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                              <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                              <a class="dropdown-item" href="javascript:void(0);">Share</a>
                            </div>
                          </div>
                        </div>
                        <div id="totalRevenueChart" class="px-3"></div>
                      </div>
                      <div class="col-lg-4">
                        <div class="card-body px-xl-9 py-12 d-flex align-items-center flex-column">
                          <div class="text-center mb-6">
                            <div class="btn-group">
                              <button type="button" class="btn btn-outline-primary">
                                <script>
                                  document.write(new Date().getFullYear() - 1);
                                </script>
                              </button>
                              <button
                                type="button"
                                class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                              </button>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">2021</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">2020</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">2019</a></li>
                              </ul>
                            </div>
                          </div>

                          <div id="growthChart"></div>
                          <div class="text-center fw-medium my-6">62% Company Growth</div>

                          <div class="d-flex gap-11 justify-content-between">
                            <div class="d-flex">
                              <div class="avatar me-2">
                                <span class="avatar-initial rounded-2 bg-label-primary"
                                  ><i class="icon-base bx bx-dollar icon-lg text-primary"></i
                                ></span>
                              </div>
                              <div class="d-flex flex-column">
                                <small>
                                  <script>
                                    document.write(new Date().getFullYear() - 1);
                                  </script>
                                </small>
                                <h6 class="mb-0">$32.5k</h6>
                              </div>
                            </div>
                            <div class="d-flex">
                              <div class="avatar me-2">
                                <span class="avatar-initial rounded-2 bg-label-info"
                                  ><i class="icon-base bx bx-wallet icon-lg text-info"></i
                                ></span>
                              </div>
                              <div class="d-flex flex-column">
                                <small>
                                  <script>
                                    document.write(new Date().getFullYear() - 2);
                                  </script>
                                </small>
                                <h6 class="mb-0">$41.2k</h6>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Total Revenue -->
                <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2 profile-report">
                  <div class="row">
                    <div class="col-6 mb-6">
                      <div class="card h-100">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <div class="avatar flex-shrink-0">
                              <img src="../assets/img/icons/unicons/upload-offer.png" alt="paypal" class="rounded" />
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
                                <a class="dropdown-item" href="offer_status.php">Check Status</a>
                              </div>
                            </div>
                          </div>
                          <p class="mb-1">Upload Offer Letter</p>
                          <button class="btn btn-danger mb-3 d-none d-lg-inline-block" type="button" onclick="location.href='upload_offer.php'">
                              Upload New
                            </button>
                            <button class="btn btn-danger mb-3 d-inline-block d-lg-none" type="button" onclick="location.href='upload_offer.php'">
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
                              <img src="../assets/img/icons/unicons/reset-password.png" alt="Credit Card" class="rounded" />
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
                                <a class="dropdown-item" href="candidates.php">View All</a>
                              </div>
                            </div>
                          </div>
                          <p class="mb-1">Reset Password</p>
                          <button class="btn btn-primary mb-3 d-none d-lg-inline-block" type="button" onclick="location.href='reset_password.php'">
                              Reset Now
                            </button>
                            <button class="btn btn-primary mb-3 d-inline-block d-lg-none" type="button" onclick="location.href='reset_password.php'">
                              Reset
                            </button>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 mb-6 profile-report">
                      <div class="card h-100">
                        <div class="card-body">
                          <div
                            class="d-flex justify-content-between align-items-center flex-sm-row flex-column gap-10 flex-wrap">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                              <div class="card-title mb-6">
                                <h5 class="text-nowrap mb-1">Total Candidates</h5>
                                <span class="badge bg-label-warning">YEAR <script>document.write(new Date().getFullYear());</script></span>
                              </div>
                              <div class="mt-sm-auto d-none d-lg-inline-block">
                                <h4 class="mb-0"><?php 
                                                $stmt = $pdo->query("SELECT COUNT(*) FROM candidates");
                                                echo $stmt->fetchColumn();
                                                ?> Candidates</h4>
                              </div>
                            </div>
                            <div id="profileReportChart"></div>
                            <div class="mt-sm-auto">
                                <h4 class="mb-0 d-inline-block d-lg-none"><?php 
                                                $stmt = $pdo->query("SELECT COUNT(*) FROM candidates");
                                                echo $stmt->fetchColumn();
                                                ?> Candidates</h4>
                            </div>
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

    <div class="buy-now">
      <a
        href="logout.php"
        class="btn btn-danger btn-buy-now"
        >Logout</a
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
