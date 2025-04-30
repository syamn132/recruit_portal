<?php
require '../config.php';
include '../alerts.php';
checkAdminAuth();

// Handle admin deletion
if (isset($_GET['delete'])) {
    $admin_id = $_GET['delete'];
    $response = ['success' => false];
    
    try {
        // Prevent deleting current admin
        if ($admin_id == $_SESSION['admin_id']) {
            $response['error'] = "You cannot delete your own account!";
        } else {
            $stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
            if ($stmt->execute([$admin_id])) {
                $response['success'] = true;
                $response['message'] = "Admin deleted successfully!";
            }
        }
    } catch (PDOException $e) {
        $response['error'] = "Error deleting admin: " . $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Get all admins
$admins = $pdo->query("SELECT id, name, email, created_at FROM admins")->fetchAll();
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

    <title>Manage Admins | SoT</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

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
    
    <style>
      th {
        cursor: pointer; /* Change cursor to hand when hovering over table headers */
      }
    
      th.sorted-asc::after {
        content: ' ↑'; /* Up arrow for ascending order */
      }
    
      th.sorted-desc::after {
        content: ' ↓'; /* Down arrow for descending order */
      }
    </style>
    
    <script>
      let currentSortColumn = -1;
      let currentSortDirection = 'asc';
    
      function sortTable(columnIndex) {
        let table = document.querySelector('table');
        let rows = Array.from(table.rows).slice(1); // Get rows excluding the header row
        let isNumericColumn = columnIndex === 2 || columnIndex === 3 || columnIndex === 5; // Numeric columns: Mobile, DOB, and Salary
    
        // Sort the rows based on the selected column
        rows.sort((rowA, rowB) => {
          let cellA = rowA.cells[columnIndex].textContent.trim();
          let cellB = rowB.cells[columnIndex].textContent.trim();
    
          // Convert to numeric if it's a numeric column
          if (isNumericColumn) {
            cellA = parseFloat(cellA.replace(/[^0-9.-]+/g, "")); // Remove any non-numeric characters (e.g., '$' in salary)
            cellB = parseFloat(cellB.replace(/[^0-9.-]+/g, ""));
          }
    
          // Compare in ascending or descending order based on the current sort direction
          if (currentSortDirection === 'asc') {
            return cellA > cellB ? 1 : cellA < cellB ? -1 : 0;
          } else {
            return cellA < cellB ? 1 : cellA > cellB ? -1 : 0;
          }
        });
    
        // Re-attach sorted rows to the table body
        rows.forEach(row => table.tBodies[0].appendChild(row));
    
        // Toggle the sort direction for the next click
        currentSortDirection = (currentSortDirection === 'asc') ? 'desc' : 'asc';
    
        // Optional: Add or remove a sorting indicator (e.g., arrows)
        updateSortIndicators(columnIndex);
      }
    
      function updateSortIndicators(columnIndex) {
        let headers = document.querySelectorAll('th');
        headers.forEach((header, index) => {
          if (index === columnIndex) {
            header.classList.add(currentSortDirection === 'asc' ? 'sorted-asc' : 'sorted-desc');
          } else {
            header.classList.remove('sorted-asc', 'sorted-desc');
          }
        });
      }
    </script>
    
    
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="index.html" class="app-brand-link">
              <span class="app-brand-logo demo">
                <span class="text-primary">
                  <svg
                    width="25"
                    viewBox="0 0 25 42"
                    version="1.1"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <defs>
                      <path
                        d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                        id="path-1"></path>
                      <path
                        d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                        id="path-3"></path>
                      <path
                        d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                        id="path-4"></path>
                      <path
                        d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                        id="path-5"></path>
                    </defs>
                    <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                      <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                        <g id="Icon" transform="translate(27.000000, 15.000000)">
                          <g id="Mask" transform="translate(0.000000, 8.000000)">
                            <mask id="mask-2" fill="white">
                              <use xlink:href="#path-1"></use>
                            </mask>
                            <use fill="currentColor" xlink:href="#path-1"></use>
                            <g id="Path-3" mask="url(#mask-2)">
                              <use fill="currentColor" xlink:href="#path-3"></use>
                              <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                            </g>
                            <g id="Path-4" mask="url(#mask-2)">
                              <use fill="currentColor" xlink:href="#path-4"></use>
                              <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                            </g>
                          </g>
                          <g
                            id="Triangle"
                            transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                            <use fill="currentColor" xlink:href="#path-5"></use>
                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                          </g>
                        </g>
                      </g>
                    </g>
                  </svg>
                </span>
              </span>
              <span class="app-brand-text demo menu-text fw-bold ms-2">Admin - SoT</span>
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
          <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
                  <!-- Table for managing candidates -->
                  <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="card-title mb-0">Admins List</h5>
                      <!-- Search Bar -->
                      <div class="d-flex align-items-center">
                        <input 
                          type="text" 
                          id="tableSearch" 
                          class="form-control" 
                          placeholder="Search..." 
                          aria-label="Search..."
                          style="max-width: 250px; margin-left: 10px; border-radius: 10px;"
                        />
                      </div>
                    </div>
                    <div class="table-responsive text-nowrap">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase" onclick="sortTable(0)">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase" onclick="sortTable(1)">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase" onclick="sortTable(2)">Created At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase" onclick="sortTable(3)">Actions</th>
                          </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="tableBody">
                          <?php foreach ($admins as $admin): ?>
                          <tr id="admin-<?= $admin['id'] ?>">
                            <td class="px-6 py-4"><?= htmlspecialchars($admin['name']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($admin['email']) ?></td>
                            <td class="px-6 py-4"><?= date('M d, Y H:i', strtotime($admin['created_at'])) ?></td>
                            <td class="px-6 py-4">
                                <?php if ($admin['id'] != $_SESSION['admin_id']): ?>
                                <a href="admins.php" 
                                   class="text-red-500 hover:text-red-700"
                                   onclick="deleteAdmin(<?= $admin['id'] ?>)">
                                    Delete
                                </a>
                                <?php else: ?>
                                <span class="text-gray-400">Current Admin</span>
                                <?php endif; ?>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- / Table for managing candidates -->
                </div>
            <!-- / Content -->
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
    
    <script>document.getElementById('tableSearch').addEventListener('input', function () {
        let searchQuery = this.value.toLowerCase();
        let tableRows = document.querySelectorAll('#tableBody tr');
    
        tableRows.forEach(function (row) {
          let rowText = row.textContent.toLowerCase();
          if (rowText.includes(searchQuery)) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });
    </script>
  
    <script>
      let currentSortColumn = -1;
      let currentSortDirection = 'asc';
    
      function sortTable(columnIndex) {
        let table = document.querySelector('table');
        let rows = Array.from(table.rows).slice(1); // Get rows excluding the header row
        let isNumericColumn = columnIndex === 2 || columnIndex === 3 || columnIndex === 5; // Numeric columns: Mobile, DOB, and Salary
    
        // Sort the rows based on the selected column
        rows.sort((rowA, rowB) => {
          let cellA = rowA.cells[columnIndex].textContent.trim();
          let cellB = rowB.cells[columnIndex].textContent.trim();
    
          // Convert to numeric if it's a numeric column
          if (isNumericColumn) {
            cellA = parseFloat(cellA.replace(/[^0-9.-]+/g, "")); // Remove any non-numeric characters (e.g., '$' in salary)
            cellB = parseFloat(cellB.replace(/[^0-9.-]+/g, ""));
          }
    
          // Compare in ascending or descending order based on the current sort direction
          if (currentSortDirection === 'asc') {
            return cellA > cellB ? 1 : cellA < cellB ? -1 : 0;
          } else {
            return cellA < cellB ? 1 : cellA > cellB ? -1 : 0;
          }
        });
    
        // Re-attach sorted rows to the table body
        rows.forEach(row => table.tBodies[0].appendChild(row));
    
        // Toggle the sort direction for the next click
        currentSortDirection = (currentSortDirection === 'asc') ? 'desc' : 'asc';
    
        // Optional: Add or remove a sorting indicator (e.g., arrows)
        updateSortIndicators(columnIndex);
      }
    
      function updateSortIndicators(columnIndex) {
        let headers = document.querySelectorAll('th');
        headers.forEach((header, index) => {
          if (index === columnIndex) {
            header.classList.add(currentSortDirection === 'asc' ? 'sorted-asc' : 'sorted-desc');
          } else {
            header.classList.remove('sorted-asc', 'sorted-desc');
          }
        });
      }
    </script>
    
    <script>
    function deleteAdmin(adminId) {
        if (confirm('Are you sure you want to delete this admin?')) {
            fetch(`admins.php?delete=${adminId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove row from table
                        document.getElementById(`admin-${adminId}`).remove();
                        showToast(data.message || 'Admin deleted successfully!', 'success');
                    } else {
                        showToast(data.error || 'Error deleting admin', 'error');
                    }
                })
                .catch(error => {
                    showToast('Network error - please try again', 'error');
                    console.error('Error:', error);
                });
        }
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 p-4 rounded-lg text-white ${
            type === 'error' ? 'bg-red-500' : 
            type === 'success' ? 'bg-green-500' : 'bg-blue-500'
        }`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
    </script>


    <!-- Main JS -->

    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>