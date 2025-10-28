<script>
        // Show modal when "Add Header" clicked
    $("#addHeading").on("click", function (e) {
      e.preventDefault();
      $("#addHeaderModal").modal("show");
    });

    // Handle form submit
    $("#addHeaderForm").on("submit", function (e) {
      e.preventDefault();
      let headerName = $("#headerName").val().trim();
      if (headerName === "") return;

      $.ajax({
        url: "/Journal/add_header.php",
        method: "POST",
        dataType: "json", // ✅ Tell jQuery to expect JSON
        data: { headerName: headerName },
        success: function (res) { // ✅ res is already an object
          if (res.success) {
            let headerHTML = `<li>
            <span class='sidebar-divider lg'></span>
          </li>
          <li class='nav-small-cap'>
            <iconify-icon icon='solar:menu-dots-linear' class='nav-small-cap-icon fs-4'></iconify-icon>
            <span class='hide-menu'>${headerName}</span>
          </li>`;
            $("#sidebarnav").append(headerHTML);
            $("#addHeaderModal").modal("hide");
            $("#addHeaderForm")[0].reset();
            location.reload();
          } else {
            alert("Error: " + res.message);
            location.reload();
          }
        },
        error: function (xhr, status, error) {
          console.error("AJAX Error:", error);
          alert("Failed to connect to the server.");
          location.reload();

        }
      });
    });






    // Open "Add Sidebar Item" modal
    $("#addSidebarItem").on("click", function (e) {
      e.preventDefault();
      loadSidebarItemHeaders(); // Load header names into dropdown
      $("#sidebarItemModal").modal("show");
    });

    // Load headers into the dropdown
    function loadSidebarItemHeaders() {
      $.ajax({
        url: "/Journal/load_headers.php",
        method: "GET",
        dataType: "json",
        success: function (response) {
          let headerDropdown = $("#sidebarItemHeaderSelect");
          headerDropdown.empty();
          headerDropdown.append(`<option value="">-- Select Header --</option>`);

          if (response.success) {
            response.data.forEach(function (header) {
              headerDropdown.append(`<option value="${header.id}">${header.name}</option>`);
            });
          }
        }
      });
    }



    $(document).ready(function () {

      // ----- Load headers dynamically into dropdown -----
      function loadHeaderOptions() {
        $.ajax({
          url: "/Journal/load_headers.php", // must return JSON: { success:true, data:[{id,name}] }
          method: "GET",
          dataType: "json",
          success: function (response) {
            const headerSelect = $("#headerSelect"); // ✅ Correct ID here
            headerSelect.empty();
            headerSelect.append(`<option value="">-- Select Header --</option>`);

            if (response.success && response.data.length > 0) {
              response.data.forEach((header) => {
                headerSelect.append(
                  `<option value="${header.id}">${header.name}</option>`
                );
              });
            } else {
              headerSelect.append(`<option value="">⚠️ No headers found</option>`);
            }
          },
          error: function (xhr, status, error) {
            console.error("Failed to load headers:", error);
          }
        });
      }

      // Load headers whenever modal opens
      $("#sidebarItemModal").on("show.bs.modal", function () {
        loadHeaderOptions();
      });

      // ----- ICON SELECTION -----
      $("#iconDropdownMenu").on("click", ".dropdown-item", function (e) {
        e.preventDefault();
        const selectedValue = $(this).attr("data-value") || "";
        const selectedText = $(this).text().trim();

        $("#selectedIcon")
          .attr("data-value", selectedValue)
          .html(`<i class="${selectedValue} me-2"></i>${selectedText}`);

        const btn = document.getElementById("iconDropdownButton");
        if (btn) {
          const dd = bootstrap.Dropdown.getOrCreateInstance(btn);
          dd.hide();
        }
      });

      // Helper function to get icon
      function getSelectedIconValue() {
        const val = $("#selectedIcon").attr("data-value");
        if (val && val.trim() !== "") return val.trim();
        const i = $("#selectedIcon").find("i");
        return i.length ? i.attr("class").trim() : "";
      }

      // ----- FORM SUBMIT -----
      $("#sidebarItemForm").on("submit", function (e) {
        e.preventDefault();

        const sidebarItemName = $("#sidebarItemInputName").val().trim();
        const sidebarHeaderId = $("#headerSelect").val().trim(); // ✅ Corrected selector
        const sidebarItemLink = $("#sidebarItemLink").val().trim();
        const sidebarItemIcon = getSelectedIconValue();

        console.group("Sidebar Item Submit Debug");
        console.log("itemName:", sidebarItemName);
        console.log("headerId:", sidebarHeaderId);
        console.log("href:", sidebarItemLink);
        console.log("icon:", sidebarItemIcon);
        console.groupEnd();

        if (!sidebarItemName || !sidebarHeaderId || !sidebarItemLink || !sidebarItemIcon) {
          alert("⚠️ Please fill in all fields and select an icon.");
          return;
        }

        const payload = {
          itemName: sidebarItemName,
          headerId: sidebarHeaderId,
          href: sidebarItemLink,
          icon: sidebarItemIcon,
        };

        $.ajax({
          url: "/Journal/add_sidebar_items.php",
          method: "POST",
          dataType: "json",
          data: payload,
          success: function (res) {
            console.log("Server response:", res);
            if (res && res.success) {
              $("#sidebarItemModal").modal("hide");
              $("#sidebarItemForm")[0].reset();
              $("#selectedIcon").removeAttr("data-value").html('<i class="ti ti-components me-2"></i> components');
              alert("✅ Added successfully!");
              location.reload();
            } else {
              alert("❌ Error: " + (res.message || "Unknown server error"));
            }
          },
          error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
            console.log("XHR responseText:", xhr.responseText);
            alert("⚠️ AJAX request failed. Check console for details.");
          },
        });
      });
    });







    // When clicking "Add Sublist" button
    document.getElementById("addSublist").addEventListener("click", function (e) {
      e.preventDefault();

      const modal = new bootstrap.Modal(document.getElementById("addSublistModal"));
      modal.show();

      // Fetch all headers from SQL
      fetch("/Journal/get_headers.php")
        .then(res => res.json())
        .then(data => {
          const headerSelect = document.getElementById("newheaderSelect");
          headerSelect.innerHTML = '<option value="">Select Header...</option>';

          if (!data || data.length === 0) {
            headerSelect.innerHTML = '<option value="">No headers found</option>';
          } else {
            data.forEach(row => {
              headerSelect.innerHTML += `<option value="${row.id}">${row.name}</option>`;
            });
          }

          // Reset sidebar items
          document.getElementById("itemSelect").innerHTML =
            '<option value="">Select a header first</option>';
        })
        .catch(err => {
          console.error("Header load error:", err);
          alert("⚠️ Failed to load headers.");
        });
    });

    // When header changes → fetch items that belong to that header
    document.getElementById("newheaderSelect").addEventListener("change", function () {
      const headerId = this.value;
      const itemSelect = document.getElementById("itemSelect");

      if (!headerId) {
        itemSelect.innerHTML = '<option value="">Select a header first</option>';
        return;
      }

      fetch("/Journal/get_sidebar_items.php?header_id=" + headerId)
        .then(res => res.json())
        .then(data => {
          itemSelect.innerHTML = '<option value="">Select Sidebar Item...</option>';

          if (!data || data.length === 0) {
            itemSelect.innerHTML = '<option value="">No sidebar items found under this header</option>';
          } else {
            data.forEach(row => {
              itemSelect.innerHTML += `<option value="${row.id}">${row.name}</option>`;
            });
          }
        })
        .catch(err => {
          console.error("Sidebar item load error:", err);
          alert("⚠️ Failed to load sidebar items.");
        });
    });

    document.getElementById("addSublistForm").addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      fetch("/Journal/add_sublist.php", {
        method: "POST",
        body: formData
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert("✅ Sublist added successfully!");
            bootstrap.Modal.getInstance(document.getElementById("addSublistModal")).hide();
            document.getElementById("addSublistForm").reset();
            location.reload();
          } else {
            alert("❌ " + data.message);
          }
        })
        .catch(err => alert("⚠️ Request failed: " + err));
    });

  
  

    document.addEventListener("DOMContentLoaded", () => {

      // 🗑️ Delete All (Header + Items + Sublists)
      $(document).on("click", ".delete-all", function (e) {
        e.preventDefault();
        const headerId = $(this).data("header-id");

        if (confirm("⚠️ Delete this header and everything under it?")) {
          $.post("/Journal/delete_all.php", { headerId }, function (res) {
            alert(res.message);
            if (res.success) location.reload();
          }, "json");
        }
      });

     

      // ❌ Delete a specific Sidebar Item
      $(document).on("click", ".remove-item", function (e) {
        e.preventDefault();
        const itemId = $(this).data("item-id");

        if (confirm("Are you sure you want to delete this sidebar item and its sublists?")) {
          $.post("/Journal/delete_item.php", { itemId }, function (res) {
            alert(res.message);
            if (res.success) location.reload();
          }, "json");
        }
      });

      // 🗑️ Delete Sublist (confirmation)
      $(document).on("click", ".remove-sublist", function (e) {
        e.preventDefault();
        const sublistId = $(this).data("sublist-id");

        if (confirm("Are you sure you want to delete this sublist?")) {
          $.post("/Journal/delete_sublist.php", { sublistId }, function (res) {
            alert(res.message);
            if (res.success) location.reload();
          }, "json");
        }
      });
    });


    
    const dropdownMenu = document.getElementById("iconDropdownMenu");
    const selectedIcon = document.getElementById("selectedIcon");

    dropdownMenu.addEventListener("click", function (e) {
      const item = e.target.closest("a");
      if (item) {
        e.preventDefault();
        const iconValue = item.getAttribute("data-value");
        const icon = document.createElement("i");
        icon.className = iconValue + " me-2";
        const text = item.textContent.trim();

        // Update selected icon display
        selectedIcon.innerHTML = "";
        selectedIcon.appendChild(icon);
        selectedIcon.appendChild(document.createTextNode(text));
        selectedIcon.setAttribute("data-value", iconValue);
      }
    });
</script>