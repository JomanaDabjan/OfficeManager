@push('Script')

<!-- ========================================== -->
<!-- CORE JS FILES AND PLUGINS                  -->
<!-- ========================================== -->
<script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>

<!-- ========================================== -->
<!-- GOOGLE MAPS AND CHART PLUGINS              -->
<!-- ========================================== -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>

<!-- ========================================== -->
<!-- NOW UI DASHBOARD CONTROL CENTER SCRIPTS    -->
<!-- ========================================== -->
<script src="{{ asset('assets/js/now-ui-dashboard.js?v=1.0.1') }}"></script>
<script src="{{ asset('assets/demo/demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ========================================== -->
<!-- INITIALIZE DASHBOARD CHARTS                -->
<!-- ========================================== -->
<script>
    $(document).ready(function () {
        // Initialize template default dashboard charts from demo.js
        demo.initDashboardPageCharts();
    });
</script>

<!-- ========================================== -->
<!-- LIVE SEARCH FILTER FOR PROJECTS TABLE      -->
<!-- ========================================== -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const projectSearchInput = document.getElementById("projectSearchInput");
        const projectRows = document.querySelectorAll("#projectsTable tbody tr, .project-row");

        if (projectSearchInput) {
            projectSearchInput.addEventListener("keyup", function () {
                const query = this.value.toLowerCase().trim();

                projectRows.forEach(row => {
                    const textContent = row.textContent.toLowerCase();
                    // Toggle visibility dynamically as user types letters or words
                    if (textContent.includes(query)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        }
    });
</script>

<!-- ========================================== -->
<!-- LIVE SEARCH FILTER FOR TASKS TABLE (FIXED) -->
<!-- ========================================== -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const taskSearchInput = document.getElementById("taskSearchInput");
        const taskRows = document.querySelectorAll("#tasksTable tbody tr, .task-row");

        if (taskSearchInput) {
            taskSearchInput.addEventListener("keyup", function () {
                const query = this.value.toLowerCase().trim();

                taskRows.forEach(row => {
                    // Extract text content from the entire row to ensure matching works anywhere
                    const textContent = row.textContent.toLowerCase();

                    // Show the row if the input is empty or if the text contains the typed query anywhere
                    if (query === "" || textContent.includes(query)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        }
    });
</script>

<!-- ========================================== -->
<!-- DRAG AND DROP COLUMNS FOR PROJECTS TABLE   -->
<!-- ========================================== -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("projectsTable");
        if (!table) return;

        const headerRow = table.querySelector("thead tr");
        const bodyRows = table.querySelectorAll("tbody tr.project-row");
        const storageKey = "projects_table_column_order";

        // Load saved column order from localStorage on page load
        let savedOrder = JSON.parse(localStorage.getItem(storageKey));
        if (savedOrder) {
            reorderTable(headerRow, bodyRows, savedOrder);
        }

        let draggedHeader = null;

        // Enable HTML5 drag and drop functionality for project table headers
        headerRow.querySelectorAll("th").forEach(th => {
            th.addEventListener("dragstart", function (e) {
                draggedHeader = this;
                e.dataTransfer.effectAllowed = "move";
            });

            th.addEventListener("dragover", function (e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = "move";
            });

            th.addEventListener("drop", function (e) {
                e.preventDefault();
                if (this !== draggedHeader) {
                    let headers = Array.from(headerRow.children);
                    let draggedIndex = headers.indexOf(draggedHeader);
                    let targetIndex = headers.indexOf(this);

                    if (draggedIndex < targetIndex) {
                        headerRow.insertBefore(draggedHeader, this.nextSibling);
                    } else {
                        headerRow.insertBefore(draggedHeader, this);
                    }

                    // Get new column order based on data-column attributes
                    let newOrder = Array.from(headerRow.children).map(th => th.getAttribute("data-column"));
                    reorderTable(headerRow, bodyRows, newOrder);

                    // Save the updated column order to localStorage
                    localStorage.setItem(storageKey, JSON.stringify(newOrder));
                }
            });
        });
    });
</script>

<!-- ========================================== -->
<!-- DRAG AND DROP COLUMNS FOR TASKS TABLE      -->
<!-- ========================================== -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("tasksTable");
        if (!table) return;

        const headerRow = table.querySelector("thead tr");
        const bodyRows = table.querySelectorAll("tbody tr.task-row");
        const storageKey = "tasks_table_column_order";

        // Load saved column order from localStorage on page load
        let savedOrder = JSON.parse(localStorage.getItem(storageKey));
        if (savedOrder) {
            reorderTable(headerRow, bodyRows, savedOrder);
        }

        let draggedHeader = null;

        // Enable HTML5 drag and drop functionality for task table headers
        headerRow.querySelectorAll("th").forEach(th => {
            th.addEventListener("dragstart", function (e) {
                draggedHeader = this;
                e.dataTransfer.effectAllowed = "move";
            });

            th.addEventListener("dragover", function (e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = "move";
            });

            th.addEventListener("drop", function (e) {
                e.preventDefault();
                if (this !== draggedHeader) {
                    let headers = Array.from(headerRow.children);
                    let draggedIndex = headers.indexOf(draggedHeader);
                    let targetIndex = headers.indexOf(this);

                    if (draggedIndex < targetIndex) {
                        headerRow.insertBefore(draggedHeader, this.nextSibling);
                    } else {
                        headerRow.insertBefore(draggedHeader, this);
                    }

                    // Get new column order based on data-column attributes
                    let newOrder = Array.from(headerRow.children).map(th => th.getAttribute("data-column"));
                    reorderTable(headerRow, bodyRows, newOrder);

                    // Save the updated column order to localStorage
                    localStorage.setItem(storageKey, JSON.stringify(newOrder));
                }
            });
        });
    });
</script>

<!-- ========================================== -->
<!-- GLOBAL REORDER HELPER FUNCTION             -->
<!-- ========================================== -->
<script>
    function reorderTable(headerRow, bodyRows, order) {
        // Reorder header elements according to the saved column order array
        let headerMap = {};
        Array.from(headerRow.children).forEach(th => {
            headerMap[th.getAttribute("data-column")] = th;
        });
        order.forEach(colName => {
            if (headerMap[colName]) {
                headerRow.appendChild(headerMap[colName]);
            }
        });

        // Reorder corresponding table cells (td) for each row in the table body
        bodyRows.forEach(row => {
            let cellMap = {};
            Array.from(row.children).forEach(td => {
                cellMap[td.getAttribute("data-column")] = td;
            });
            order.forEach(colName => {
                if (cellMap[colName]) {
                    row.appendChild(cellMap[colName]);
                }
            });
        });
    }
</script>

<!-- ========================================== -->
<!-- INITIALIZE AND RENDER TASK STATUS CHART    -->
<!-- ========================================== -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const canvasElement = document.getElementById('tasksChart');
        if (canvasElement) {
            const ctx = canvasElement.getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'In Progress', 'Completed', 'Accepted', 'Rejected'],
                    datasets: [{
                        data: [
                            {{ $pendingTasks ?? 0 }},
                            {{ $inProgressTasks ?? 0 }},
                            {{ $completedTasks ?? 0 }},
                            {{ $acceptedTasks ?? 0 }},
                            {{ $rejectedTasks ?? 0 }}
                        ],
                        backgroundColor: [
                            '#fbc658', // Yellow for Pending
                            '#51cbce', // Cyan for In Progress
                            '#6bd098', // Green for Completed
                            '#9b59b6', // Blue/Teal for Accepted
                            '#ef8157'  // Orange/Red for Rejected
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        }
    });
</script>

<!-- ========================================== -->
<!-- DYNAMIC FILE UPLOAD PREVIEW HANDLER        -->
<!-- ========================================== -->
<script>
    const attachmentInput = document.getElementById('attachmentInput');
    if (attachmentInput) {
        attachmentInput.addEventListener('change', function(event) {
            const files = event.target.files;
            const uploadPrompt = document.getElementById('uploadPrompt');
            const filePreviewContainer = document.getElementById('filePreviewContainer');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const fileSizeDisplay = document.getElementById('fileSizeDisplay');

            if (files.length > 0) {
                uploadPrompt.classList.add('d-none');
                filePreviewContainer.classList.remove('d-none');
                filePreviewContainer.classList.add('d-flex');

                if (files.length === 1) {
                    fileNameDisplay.textContent = files[0].name;
                    fileSizeDisplay.textContent = (files[0].size / (1024 * 1024)).toFixed(2) + ' MB';
                } else {
                    fileNameDisplay.textContent = files.length + ' files selected';
                    let totalSize = Array.from(files).reduce((acc, file) => acc + file.size, 0);
                    fileSizeDisplay.textContent = 'Total size: ' + (totalSize / (1024 * 1024)).toFixed(2) + ' MB';
                }
            }
        });
    }

    const removeFileBtn = document.getElementById('removeFileBtn');
    if (removeFileBtn) {
        removeFileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const fileInput = document.getElementById('attachmentInput');
            const uploadPrompt = document.getElementById('uploadPrompt');
            const filePreviewContainer = document.getElementById('filePreviewContainer');

            fileInput.value = '';
            filePreviewContainer.classList.remove('d-flex');
            filePreviewContainer.classList.add('d-none');
            uploadPrompt.classList.remove('d-none');
        });
    }
</script>

<!-- ==================================================================== -->
<!-- CONFIRM DELETE DIALOG USING SWEETALERT2 (UPDATED FOR PROJECTS & TASKS) -->
<!-- ==================================================================== -->
<script>
    function confirmDelete(type, id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f96332',
            cancelButtonColor: '#888888',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn btn-primary btn-round px-4',
                cancelButton: 'btn btn-secondary btn-round px-4'
            },
            buttonsStyling: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Dynamically submit the correct delete form based on type and id
                document.getElementById('delete-form-' + type + '-' + id).submit();
            }
        });
    }
</script>

<!-- ==================================================================== -->
<!-- AUTOMATIC ALERT DISMISSAL SCRIPT MESSAGE                             -->
<!-- ==================================================================== -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(function () {
            let alerts = document.querySelectorAll('.custom-auto-dismiss-alert');
            alerts.forEach(function (alert) {
                let dismissBtn = alert.querySelector('.close');
                if (dismissBtn) {
                    dismissBtn.click();
                }
            });
        }, 4000);
    });
</script>

<!-- ==================================================================== -->
<!-- FORCE REMOVE PLUGIN-BASED INTERNAL SCROLL WRAPPERS                           -->
<!-- ==================================================================== -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Force remove any plugin-based internal scroll wrappers if initialized by the template
        const mainPanel = document.querySelector('.main-panel');
        if (mainPanel) {
            mainPanel.style.overflow = "visible";
            mainPanel.style.height = "auto";
        }
        const sidebarWrapper = document.querySelector('.sidebar-wrapper');
        if (sidebarWrapper) {
            sidebarWrapper.style.overflow = "visible";
        }
    });
</script>


<!-- ==================================================================== -->
<!-- SIDEBAR MINIMIZATION SCRIPT                                          -->
<!-- ==================================================================== -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const minimizeBtn = document.getElementById("minimizeSidebar");
        const bodyElement = document.body;

        if (minimizeBtn) {
            minimizeBtn.addEventListener("click", function () {
                // Toggle mini sidebar class smoothly
                bodyElement.classList.toggle("sidebar-mini");
            });
        }
    });
</script>

<!-- ==================================================================== -->
<!-- WELCOME MODAL SCRIPT                                                 -->
<!-- ==================================================================== -->
@php
// Safely check if the welcome session flag is set
$isFirstLoginValid = session('show_welcome_modal', false);
@endphp

<!-- JavaScript to handle modal auto-hide and manual dismissal -->
<script>
    // Function to hide the welcome modal smoothly with fade out effect
    function dismissWelcomeModal() {
        const modal = document.getElementById('custom-welcome-modal');
        if (modal) {
            modal.style.opacity = '0';
            modal.style.transition = 'opacity 0.3s ease';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    }

    // Trigger script execution once the DOM content is fully loaded
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('custom-welcome-modal');
        const progressBar = document.getElementById('welcome-progress-bar');

        // Only trigger modal logic if the modal element exists on the page
        if (modal) {
            // Use safe boolean evaluation from backend without direct variable crashing
            const shouldShow = @json($isFirstLoginValid);

            if (shouldShow) {
                // Display the modal in the center of the screen
                modal.style.display = 'flex';

                // Define how long the modal stays visible in milliseconds (e.g., 5000ms = 5 seconds)
                const displayDuration = 5000;

                // Animate progress bar width reduction over time
                if (progressBar) {
                    progressBar.style.transitionDuration = (displayDuration / 1000) + 's';
                    setTimeout(() => {
                        progressBar.style.width = '0%';
                    }, 50);
                }

                // Automatically close the modal after the specified duration expires
                setTimeout(() => {
                    dismissWelcomeModal();
                }, displayDuration);
            } else {
                // Ensure modal remains hidden on page refreshes or subsequent navigation clicks
                modal.style.display = 'none';
            }
        }
    });
</script>
@endpush
