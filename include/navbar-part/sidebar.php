<aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="<?php echo BASE_URL; ?>./index.php" class="text-nowrap logo-img">
            <img src="<?php echo BASE_URL; ?>assets/images/logos/logo.svg" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-6"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="<?php echo BASE_URL; ?>index.php" aria-expanded="false">
                <i class="ti ti-atom"></i>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link justify-content-between" href="#" aria-expanded="false">
                <div class="d-flex align-items-center gap-3">
                  <span class="d-flex">
                    <i class="ti ti-user-circle"></i>
                  </span>
                  <span class="hide-menu">User Profile</span>
                </div>

              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link justify-content-between" href="<?php echo BASE_URL ?>calendar.php" aria-expanded="false">
                <div class="d-flex align-items-center gap-3">
                  <span class="d-flex">
                    <i class="ti ti-calendar"></i>
                  </span>
                  <span class="hide-menu">Calendar</span>
                </div>

              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link justify-content-between" href="<?php echo BASE_URL ?>to-do.php" aria-expanded="false">
                <div class="d-flex align-items-center gap-3">
                  <span class="d-flex">
                    <i class="ti ti-list-check fs-6"></i>
                  </span>
                  <span class="hide-menu">To-Do</span>
                </div>

              </a>
            </li>


            <!-- Sidebar -->
            <div class="sidebar p-2 border-end" id="sidebarContainer">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="m-0"></h5>

                <!-- 3-Dots Menu -->
                <div class="dropdown">
                  <button class="btn btn-light border-0" type="button" id="sidebarMenuBtn" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="ti ti-dots-vertical fs-5"></i>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="sidebarMenuBtn">
                    <li><a class="dropdown-item" href="javascript:void(0)" id="addHeading">Add Header</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0)" id="addSidebarItem">Add Sidebar Item</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0)" id="addSublist">Add Sublist</a></li>
                  </ul>
                </div>
              </div>

              <ul class="nav flex-column" id="sidebarnav"></ul>
            </div>


            <!-- Modal for Adding Header -->
            <div class="modal fade" id="addHeaderModal" tabindex="-1" aria-labelledby="addHeaderModalLabel"
              aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form id="addHeaderForm">
                    <div class="modal-header">
                      <h5 class="modal-title" id="addHeaderModalLabel">Add Header</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <label for="headerName" class="form-label">Header Name</label>
                      <input type="text" class="form-control" id="headerName" name="headerName"
                        placeholder="Enter header name" required>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Add Header</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Modal for Adding Sidebar Item -->
            <div class="modal fade" id="sidebarItemModal" tabindex="-1" aria-labelledby="sidebarItemModalLabel"
              aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form id="sidebarItemForm">
                    <div class="modal-header">
                      <h5 class="modal-title" id="sidebarItemModalLabel">Add Sidebar Item</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="sidebarItemInputName" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="sidebarItemInputName" name="sidebarItemInputName"
                          placeholder="Enter item name" required>
                      </div>

                      <div class="mb-3">
                        <label for="headerSelect" class="form-label">Select Header</label>
                        <select class="form-select" id="headerSelect" name="headerSelect" required>
                          <option value="">-- Select Header --</option>
                        </select>
                      </div>
                      <!-- 🔹 Icon selector dropdown -->
                      <div class="mb-3">
                        <label for="iconDropdown" class="form-label">Select Icon</label>
                        <div class="dropdown">
                          <button
                            class="btn btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center justify-content-between"
                            type="button" id="iconDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <span id="selectedIcon"><i class="ti ti-components me-2"></i> components</span>
                          </button>
                          <ul class="dropdown-menu w-100" id="iconDropdownMenu">
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-components"
                                href="#"><i class="ti ti-components mx-2"></i> components</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-shopping-cart fs-5"
                                href="#"><i class="ti ti-shopping-cart fs-5 mx-2"></i> shopping-cart fs-5</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-x fs-6" href="#"><i
                                  class="ti ti-x fs-6 mx-2"></i> x fs-6</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-atom" href="#"><i
                                  class="ti ti-atom mx-2"></i> atom</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-aperture"
                                href="#"><i class="ti ti-aperture mx-2"></i> aperture</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-shopping-cart"
                                href="#"><i class="ti ti-shopping-cart mx-2"></i> shopping-cart</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-layout-grid"
                                href="#"><i class="ti ti-layout-grid mx-2"></i> layout-grid</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-circle" href="#"><i
                                  class="ti ti-circle mx-2"></i> circle</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-chart-donut-3"
                                href="#"><i class="ti ti-chart-donut-3 mx-2"></i> chart-donut-3</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-user-circle"
                                href="#"><i class="ti ti-user-circle mx-2"></i> user-circle</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-mail" href="#"><i
                                  class="ti ti-mail mx-2"></i> mail</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-basket" href="#"><i
                                  class="ti ti-basket mx-2"></i> basket</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-calendar"
                                href="#"><i class="ti ti-calendar mx-2"></i> calendar</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-layout-kanban"
                                href="#"><i class="ti ti-layout-kanban mx-2"></i> layout-kanban</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-message-dots"
                                href="#"><i class="ti ti-message-dots mx-2"></i> message-dots</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-notes" href="#"><i
                                  class="ti ti-notes mx-2"></i> notes</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-phone" href="#"><i
                                  class="ti ti-phone mx-2"></i> phone</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-list-details"
                                href="#"><i class="ti ti-list-details mx-2"></i> list-details</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-file-text"
                                href="#"><i class="ti ti-file-text mx-2"></i> file-text</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-accessible"
                                href="#"><i class="ti ti-accessible mx-2"></i> accessible</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-user-search"
                                href="#"><i class="ti ti-user-search mx-2"></i> user-search</a></li>
                            <li><a class="dropdown-item d-flex align-items-center"
                                data-value="ti ti-brand-google-photos" href="#"><i
                                  class="ti ti-brand-google-photos mx-2"></i> brand-google-photos</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-masks-theater"
                                href="#"><i class="ti ti-masks-theater mx-2"></i> masks-theater</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-sort-ascending"
                                href="#"><i class="ti ti-sort-ascending mx-2"></i> sort-ascending</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-currency-dollar"
                                href="#"><i class="ti ti-currency-dollar mx-2"></i> currency-dollar</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-help" href="#"><i
                                  class="ti ti-help mx-2"></i> help</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-app-window"
                                href="#"><i class="ti ti-app-window mx-2"></i> app-window</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-layout" href="#"><i
                                  class="ti ti-layout mx-2"></i> layout</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-layers-subtract"
                                href="#"><i class="ti ti-layers-subtract mx-2"></i> layers-subtract</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-alert-circle"
                                href="#"><i class="ti ti-alert-circle mx-2"></i> alert-circle</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-cards" href="#"><i
                                  class="ti ti-cards mx-2"></i> cards</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-typography"
                                href="#"><i class="ti ti-typography mx-2"></i> typography</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-cards" href="#"><i
                                  class="ti ti-cards mx-2"></i> cards</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-qrcode" href="#"><i
                                  class="ti ti-qrcode mx-2"></i> qrcode</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-files" href="#"><i
                                  class="ti ti-files mx-2"></i> files</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-file-pencil"
                                href="#"><i class="ti ti-file-pencil mx-2"></i> file-pencil</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-topology-star-3"
                                href="#"><i class="ti ti-topology-star-3 mx-2"></i> topology-star-3</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-layout-sidebar"
                                href="#"><i class="ti ti-layout-sidebar mx-2"></i> layout-sidebar</a></li>
                            <li><a class="dropdown-item d-flex align-items-center"
                                data-value="ti ti-air-conditioning-disabled" href="#"><i
                                  class="ti ti-air-conditioning-disabled mx-2"></i> air-conditioning-disabled</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-chart-line"
                                href="#"><i class="ti ti-chart-line mx-2"></i> chart-line</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-chart-area"
                                href="#"><i class="ti ti-chart-area mx-2"></i> chart-area</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-chart-bar"
                                href="#"><i class="ti ti-chart-bar mx-2"></i> chart-bar</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-chart-arcs"
                                href="#"><i class="ti ti-chart-arcs mx-2"></i> chart-arcs</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-chart-radar"
                                href="#"><i class="ti ti-chart-radar mx-2"></i> chart-radar</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-login" href="#"><i
                                  class="ti ti-login mx-2"></i> login</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-user-plus"
                                href="#"><i class="ti ti-user-plus mx-2"></i> user-plus</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-rotate" href="#"><i
                                  class="ti ti-rotate mx-2"></i> rotate</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-zoom-code"
                                href="#"><i class="ti ti-zoom-code mx-2"></i> zoom-code</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-settings"
                                href="#"><i class="ti ti-settings mx-2"></i> settings</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-mood-smile"
                                href="#"><i class="ti ti-mood-smile mx-2"></i> mood-smile</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-archive"
                                href="#"><i class="ti ti-archive mx-2"></i> archive</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-file" href="#"><i
                                  class="ti ti-file mx-2"></i> file</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-bell" href="#"><i
                                  class="ti ti-bell mx-2"></i> bell</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-user fs-6"
                                href="#"><i class="ti ti-user fs-6 mx-2"></i> user fs-6</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-mail fs-6"
                                href="#"><i class="ti ti-mail fs-6 mx-2"></i> mail fs-6</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-list-check fs-6"
                                href="#"><i class="ti ti-list-check fs-6 mx-2"></i> list-check fs-6</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-shopping-cart fs-6"
                                href="#"><i class="ti ti-shopping-cart fs-6 mx-2"></i> shopping-cart fs-6</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-star fs-6"
                                href="#"><i class="ti ti-star fs-6 mx-2"></i> star fs-6</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-message-dots fs-6"
                                href="#"><i class="ti ti-message-dots fs-6 mx-2"></i> message-dots fs-6</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-diamond fs-6"
                                href="#"><i class="ti ti-diamond fs-6 mx-2"></i> diamond fs-6</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-edit fs-5"
                                href="#"><i class="ti ti-edit fs-5 mx-2"></i> edit fs-5</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-check fs-5"
                                href="#"><i class="ti ti-check fs-5 mx-2"></i> check fs-5</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-heart fs-5"
                                href="#"><i class="ti ti-heart fs-5 mx-2"></i> heart fs-5</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-circle-x fs-5"
                                href="#"><i class="ti ti-circle-x fs-5 mx-2"></i> circle-x fs-5</a></li>
                            <li><a class="dropdown-item d-flex align-items-center"
                                data-value="ti ti-heart text-danger fs-5" href="#"><i
                                  class="ti ti-heart text-danger fs-5 mx-2"></i> heart text-danger fs-5</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-sun-high fs-4"
                                href="#"><i class="ti ti-sun-high fs-4 mx-2"></i> sun-high fs-4</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-cloud fs-4"
                                href="#"><i class="ti ti-cloud fs-4 mx-2"></i> cloud fs-4</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-cloud-rain fs-4"
                                href="#"><i class="ti ti-cloud-rain fs-4 mx-2"></i> cloud-rain fs-4</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" data-value="ti ti-cloud-snow fs-4"
                                href="#"><i class="ti ti-cloud-snow fs-4 mx-2"></i> cloud-snow fs-4</a></li>




                          </ul>

                          <label for="sidebarItemLink" class="form-label">Page Link (href)</label>
                          <input type="text" class="form-control" id="sidebarItemLink" name="sidebarItemLink"
                            placeholder="e.g. dashboard.php" value="include/front-end/" required>
                        </div>
                      </div>


                    </div>

                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Add Item</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>



            <!-- Modal for Add Sublist -->
            <div class="modal fade" id="addSublistModal" tabindex="-1" aria-labelledby="addSublistModalLabel"
              aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form id="addSublistForm">
                    <div class="modal-header">
                      <h5 class="modal-title">Add Sublist</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                      <!-- Header Dropdown -->
                      <div class="mb-3">
                        <label class="form-label">Select Header</label>
                        <select id="newheaderSelect" name="newheaderSelect" class="form-select" required>
                          <option value="">Loading headers...</option>
                        </select>
                      </div>

                      <!-- Sidebar Item Dropdown -->
                      <div class="mb-3">
                        <label class="form-label">Select Sidebar Item</label>
                        <select id="itemSelect" name="itemSelect" class="form-select" required>
                          <option value="">Select a header first</option>
                        </select>
                      </div>

                      <!-- Sublist Name -->
                      <div class="mb-3">
                        <label class="form-label">Sublist Name</label>
                        <input type="text" name="sublistName" id="sublistName" class="form-control"
                          placeholder="Enter sublist name" required>
                      </div>

                      <!-- ✅ New Page Link Field -->
                      <div class="mb-3">
                        <label for="sidebarItemLink" class="form-label">Page Link (href)</label>
                        <input type="text" class="form-control" id="sidebarItemLink" name="sidebarItemLink"
                          placeholder="e.g. dashboard.php" value="include/front-end/" required>
                      </div>
                    </div>

                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Save Sublist</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>



            <?php

            // DEBUG: view raw POST for troubleshooting - remove/disable in production
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['debug_check'])) {
              header('Content-Type: application/json');
              echo json_encode(['debug' => true, 'received_post' => $_POST, 'received_raw' => file_get_contents('php://input')]);
              exit;
            }


            $conn = new mysqli("localhost", "root", "", "journal");

            $sql = "
SELECT 
  h.id AS header_id,
  h.parent_id AS header_par_id,
  h.name AS header_name,
  h.icon AS header_icon,         -- ✅ Header icon
  h.href AS header_href,         -- ✅ Header link
  h.type AS header_type,         -- ✅ Header type

  i.id AS item_id,
  i.parent_id AS item_par_id,
  i.name AS item_name,
  i.icon AS item_icon,           -- ✅ Item icon
  i.href AS item_href,           -- ✅ Item link
  i.type AS item_type,           -- ✅ Item type

  s.id AS sublist_id,
  s.parent_id AS sublist_par_id,
  s.name AS sublist_name,
  s.icon AS sublist_icon,        -- ✅ Sublist icon
  s.href AS sublist_href,        -- ✅ Sublist link
  s.type AS sublist_type         -- ✅ Sublist type

FROM sidebar h
LEFT JOIN sidebar i ON i.parent_id = h.id AND i.type = 'item'
LEFT JOIN sidebar s ON s.parent_id = i.id AND s.type = 'sublist'
WHERE h.type = 'header'
ORDER BY h.id, i.id, s.id;


";

            $result = mysqli_query($conn, $sql);
            $current_header = null;
            $current_item = null;

            while ($row = mysqli_fetch_assoc($result)) {

              // 🔹 When we hit a new header
              if ($current_header !== $row['header_id']) {

                // Close previous item <ul> if open
                if ($current_item !== null) {
                  echo "</ul></li>";
                  $current_item = null;
                }

                echo "
      <li><span class='sidebar-divider lg'></span></li>
      <li class='nav-small-cap d-flex justify-content-between align-items-center'>
        <div class='d-flex align-items-center gap-2'>
          <iconify-icon icon='solar:menu-dots-linear' class='nav-small-cap-icon fs-4'></iconify-icon>
          <span class='hide-menu'>" . htmlspecialchars($row['header_name']) . "</span>
        </div>

        <!-- 🔽 Dropdown for Delete Options -->
        <div class='dropdown'>
          <button class='btn btn-sm btn-light border-0' type='button' data-bs-toggle='dropdown'>
            <a href='#' class='dropdown-item delete-all' data-header-id='" . $row['header_id'] . "'><i class='ti ti-trash'></i></a>
          </button>
         
        </div>
      </li>
    ";

                $current_header = $row['header_id'];
              }

              // 🔹 When we hit a new item
              if (!empty($row['item_id']) && $current_item !== $row['item_id']) {
                // Close previous item’s sublist if open
                if ($current_item !== null) {
                  echo "</ul></li>";
                }

                if ($row['item_id'] == $row['sublist_par_id']) {
                  $item_href = '#';
                } else {
                  $item_href = BASE_URL . '' . $row['item_href'];
                }

              

                $has_sublist = !empty($row['sublist_id']) ? 'has-arrow' : '';
                echo " <li class='sidebar-item'>
        <a class='sidebar-link justify-content-between {$has_sublist}' href='{$item_href}' aria-expanded='false'>
          <div class='d-flex align-items-center gap-3'>
            <span class='d-flex'>
              <i class='{$row['item_icon']}'></i>
            </span>
            <span class='hide-menu'>" . htmlspecialchars($row['item_name']) . "</span>
          </div>

          <!-- ❌ Remove icon for sidebar item -->
          <button class='btn btn-sm d-flex align-items-center mx-2 text-danger remove-item' data-item-id='" . $row['item_id'] . "'>
            <i class='ti ti-trash'></i>
          </button>
        </a>
    ";

                echo "<ul aria-expanded='false' class='collapse first-level'>";
                $current_item = $row['item_id'];
              }

              // 🔹 If sublists exist, print them
              if (!empty($row['sublist_id'])) {
             $sublisthref = BASE_URL . '' . $row['sublist_href'];
echo "
  <li class='sidebar-item d-flex justify-content-between align-items-center'>
    <a class='sidebar-link' href='{$sublisthref}'>
      <div class='d-flex align-items-center gap-3'>
        <div class='round-16 d-flex align-items-center justify-content-center'>
          <i class='ti ti-circle'></i>
        </div>
        <span class='hide-menu'>" . htmlspecialchars($row['sublist_name']) . "</span>
      </div>
    </a>

    <!-- 🗑️ Delete sublist icon -->
    <button class='btn btn-sm text-danger remove-sublist' data-sublist-id='" . $row['sublist_id'] . "'>
      <i class='ti ti-x'></i>
    </button>
  </li>
";

              }
            }

            if ($current_item !== null) {
              echo "</ul></li>";
            }
            ?>



          </ul>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>