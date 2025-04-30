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

// Handle document upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = '../uploads/documents/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    foreach ($_FILES as $field => $file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $type = $field; // Preserve original field name
            $originalFilename = basename($file['name']);
            $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
            $filename = uniqid('doc_').'.'.$extension;
            $targetPath = $uploadDir.$filename;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // Check existing document
                $stmt = $pdo->prepare("SELECT id FROM documents WHERE candidate_id = ? AND type = ?");
                $stmt->execute([$_SESSION['candidate_id'], $type]);
                
                if ($stmt->rowCount() > 0) {
                    $_SESSION['error'] = "Document already exists. Please contact admin to update.";
                } else {
                    $stmt = $pdo->prepare("INSERT INTO documents (candidate_id, type, document_path, original_name) VALUES (?, ?, ?, ?)");
                    $stmt->execute([
                        $_SESSION['candidate_id'],
                        $type,
                        $targetPath,
                        $originalFilename
                    ]);
                    $_SESSION['success'] = "Documents uploaded successfully!";
                }
            }
        }
    }
    header("Location: upload_document.php");
    exit();
}

// Get existing documents with filenames
$stmt = $pdo->prepare("SELECT type, original_name FROM documents WHERE candidate_id = ?");
$stmt->execute([$_SESSION['candidate_id']]);
$existingDocs = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
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

    <title>Upload Documents | SoT</title>

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
            <li class="menu-item">
              <a href="dashboard.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate">Dashboard</div>
              </a>
            </li>
            <!-- Documents -->
            <li class="menu-item active">
              <a href="javascript:void(0);" class="menu-link">
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
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="card">
                    <h5 class="card-header">Upload Documents</h5>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                        <!-- Profile Photo -->
                        <div class="mb-4">
                            <p class="formFile">Profile Photo ( Only Image )</p>
                            <div>
                                <?php if (isset($existingDocs['profile_photo'])): ?>
                                <div>
                                    ✓ Uploaded
                                    <span class="form-label"><?= htmlspecialchars($existingDocs['profile_photo']) ?></span>
                                </div>
                                <?php else: ?>
                                <label class="file-input-label">
                                    <input class="form-control" type="file" name="profile_photo" accept="image/*">
                                </label>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Aadhar Card -->
                        <div class="mb-4">
                            <p class="formFile">Aadhar Card ( PDF/Image )</p>
                            <div>
                                <?php if (isset($existingDocs['aadhar'])): ?>
                                <div>
                                    ✓ Uploaded
                                    <span><?= htmlspecialchars($existingDocs['aadhar']) ?></span>
                                </div>
                                <?php else: ?>
                                <label class="file-input-label">
                                    <input class="form-control" type="file" name="aadhar" accept="application/pdf,image/*">
                                </label>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- PAN Card -->
                        <div class="mb-4">
                            <p class="formFile">PAN Card ( PDF/Image )</p>
                            <div>
                                <?php if (isset($existingDocs['pan'])): ?>
                                <div>
                                    ✓ Uploaded
                                    <span class="form-label"><?= htmlspecialchars($existingDocs['pan']) ?></span>
                                </div>
                                <?php else: ?>
                                <label class="file-input-label">
                                    <input class="form-control" type="file" name="pan" accept="application/pdf,image/*">
                                </label>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Resume -->
                        <div class="mb-4">
                            <p class="formFile">Resume ( PDF )</p>
                            <div>
                                <?php if (isset($existingDocs['resume'])): ?>
                                <div>
                                    ✓ Uploaded
                                    <span><?= htmlspecialchars($existingDocs['resume']) ?></span>
                                </div>
                                <?php else: ?>
                                <label class="file-input-label">
                                    <input class="form-control" type="file" name="resume" accept="application/pdf">
                                </label>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Education Certificates -->
                        <div class="mb-4">
                            <p class="formFile">Education Certificates ( PDF/Image )</p>
                            <div>
                                <?php if (isset($existingDocs['education'])): ?>
                                <div>
                                    ✓ Uploaded
                                    <span><?= htmlspecialchars($existingDocs['education']) ?></span>
                                </div>
                                <?php else: ?>
                                <label class="file-input-label">
                                    <input class="form-control" type="file" name="education" accept="application/pdf,image/*">
                                </label>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Experience Letter -->
                        <div class="mb-4">
                            <p class="formFile">Experience Letter ( PDF/Image )</p>
                            <div>
                                <?php if (isset($existingDocs['experience'])): ?>
                                <div>
                                    ✓ Uploaded
                                    <span><?= htmlspecialchars($existingDocs['experience']) ?></span>
                                </div>
                                <?php else: ?>
                                <label class="file-input-label">
                                    <input class="form-control" type="file" name="experience" accept="application/pdf,image/*">
                                </label>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Relieving Letter -->
                        <div class="mb-4">
                            <p class="formFile">Relieving Letter ( PDF/Image )</p>
                            <div>
                                <?php if (isset($existingDocs['relieving'])): ?>
                                <div>
                                    ✓ Uploaded
                                    <span><?= htmlspecialchars($existingDocs['relieving']) ?></span>
                                </div>
                                <?php else: ?>
                                <label class="file-input-label">
                                    <input class="form-control" type="file" name="relieving" accept="application/pdf,image/*">
                                </label>
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <div class="mt-8">
                            <button type="submit" 
                                    class="btn btn-primary">
                                Upload Documents
                            </button>
                        </div>
                      </form>
                      <div class="mt-12 bg-yellow-100 p-4 rounded-lg">
                        <p class="text-yellow-800">
                            ℹ To update any already uploaded document, please contact the hr team.
                        </p>
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
    
    <!--uploading files-->
    <script>
        // File name preview script
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function(e) {
                const fileName = this.files[0]?.name || 'No file selected';
                const fileNameDisplay = this.closest('label').querySelector('.file-name');
                if (fileNameDisplay) {
                    fileNameDisplay.textContent = fileName;
                }
            });
        });

        // Prevent form resubmission on refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
