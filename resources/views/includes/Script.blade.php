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
        const searchInput = document.getElementById("projectSearchInput");
        const rows = document.querySelectorAll("#projectsTable tbody tr.project-row");

        if (searchInput) {
            searchInput.addEventListener("keyup", function () {
                const query = this.value.toLowerCase().trim();

                rows.forEach(row => {
                    const textContent = row.textContent.toLowerCase();
                    // Show or hide table rows based on real-time matching query
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
<!-- LIVE SEARCH FILTER FOR TASKS TABLE         -->
<!-- ========================================== -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const taskSearchInput = document.getElementById("taskSearchInput");
        const taskRows = document.querySelectorAll("#tasksTable tbody tr.task-row");

        if (taskSearchInput) {
            taskSearchInput.addEventListener("keyup", function () {
                const query = this.value.toLowerCase().trim();

                taskRows.forEach(row => {
                    const textContent = row.textContent.toLowerCase();
                    // Toggle visibility of task rows dynamically during typing
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
                // Dynamically submit the correct delete form based on type ('project' or 'task')
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

@endpush
