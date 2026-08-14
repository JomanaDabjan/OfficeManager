@push('Script')

<!-- ========================================================================= -->
<!-- CORE JS FILES AND PLUGINS                                                 -->
<!-- Import foundational JavaScript libraries including jQuery, Popper,        -->
<!-- Bootstrap, and custom UI scrollbar extensions.                          -->
<!-- ========================================================================= -->
<script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>

<!-- ========================================================================= -->
<!-- GOOGLE MAPS AND CHART PLUGINS                                             -->
<!-- Load external mapping services and chart-related assets for visualization.-->
<!-- ========================================================================= -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>

<!-- ========================================================================= -->
<!-- NOW UI DASHBOARD CONTROL CENTER SCRIPTS                                 -->
<!-- Load dashboard core scripts, demo presets, and modern CDN libraries.      -->
<!-- ========================================================================= -->
<script src="{{ asset('assets/js/now-ui-dashboard.js?v=1.0.1') }}"></script>
<script src="{{ asset('assets/demo/demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ========================================================================= -->
<!-- INITIALIZE DASHBOARD CHARTS                                               -->
<!-- Safely trigger default dashboard charts if demo object is available.      -->
<!-- ========================================================================= -->
<script>
    $(document).ready(function () {
        // Check if demo object and initialization function exist before calling
        if (typeof demo !== 'undefined' && typeof demo.initDashboardPageCharts === 'function') {
            demo.initDashboardPageCharts();
        }
    });
</script>

<!-- ========================================================================= -->
<!-- LIVE SEARCH FILTER FOR PROJECTS TABLE                                     -->
<!-- Filter project rows dynamically as the user types in the search input.    -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get the search input element and all project rows from the table
        const projectSearchInput = document.getElementById("projectSearchInput");
        const projectRows = document.querySelectorAll("#projectsTable tbody tr.project-row");

        // Check if the search input exists on the current page
        if (projectSearchInput) {
            // Listen for keyboard keyup events to process live filtering
            projectSearchInput.addEventListener("keyup", function () {
                const query = this.value.toLowerCase().trim();

                // Loop through each project row and toggle visibility based on query match
                projectRows.forEach(row => {
                    const textContent = row.textContent.toLowerCase();
                    if (textContent.includes(query)) {
                        row.style.display = ""; // Show row if it matches
                    } else {
                        row.style.display = "none"; // Hide row if it doesn't match
                    }
                });
            });
        }
    });
</script>

<!-- ========================================================================= -->
<!-- LIVE SEARCH FILTER FOR TASKS TABLE                                        -->
<!-- Filter task rows dynamically based on real-time keyboard input.           -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get task search input and target table rows
        const taskSearchInput = document.getElementById("taskSearchInput");
        const taskRows = document.querySelectorAll("#tasksTable tbody tr, .task-row");

        if (taskSearchInput) {
            taskSearchInput.addEventListener("keyup", function () {
                const query = this.value.toLowerCase().trim();

                // Iterate through rows and evaluate text inclusion
                taskRows.forEach(row => {
                    const textContent = row.textContent.toLowerCase();
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

<!-- ========================================================================= -->
<!-- INITIALIZE AND RENDER TASK STATUS CHART                                   -->
<!-- Build a dynamic doughnut chart representing task states using Chart.js.   -->
<!-- ========================================================================= -->
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
                            '#fbc658', // Pending color
                            '#51cbce', // In Progress color
                            '#6bd098', // Completed color
                            '#9b59b6', // Accepted color
                            '#ef8157'  // Rejected color
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom', // Align chart legend at the bottom
                        }
                    }
                }
            });
        }
    });
</script>

<!-- ========================================================================= -->
<!-- CONFIRM DELETE DIALOG USING SWEETALERT2                                   -->
<!-- Prompt the user for confirmation before submitting individual delete forms. -->
<!-- ========================================================================= -->
<script>
    function confirmDelete(type, id) {
        // Trigger SweetAlert confirmation popup
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
            // Submit form if user confirms action
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + type + '-' + id).submit();
            }
        });
    }
</script>

<!-- ========================================================================= -->
<!-- AUTOMATIC ALERT DISMISSAL SCRIPT                                          -->
<!-- Automatically close custom flash notification alerts after a set timeout. -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(function () {
            let alerts = document.querySelectorAll('.custom-auto-dismiss-alert');
            alerts.forEach(function (alert) {
                let dismissBtn = alert.querySelector('.close');
                if (dismissBtn) {
                    dismissBtn.click(); // Trigger close button click programmatically
                }
            });
        }, 4000); // Wait 4 seconds before dismissing alerts
    });
</script>

<!-- ========================================================================= -->
<!-- WELCOME MODAL CONTROL SCRIPT WITH AUTO-DISMISS & ANIMATED PROGRESS BAR    -->
<!-- Handles closing the welcome popup via buttons, icon, or automatic timer   -->
<!-- while synchronizing the visual progress bar fill animation.               -->
<!-- ========================================================================= -->
<script>
    /**
     * Smoothly hide the welcome modal element from the page DOM.
     */
    function dismissWelcomeModal() {
        const modal = document.getElementById('custom-welcome-modal');
        if (modal) {
            // Apply smooth fade-out CSS transition
            modal.style.transition = 'opacity 0.3s ease';
            modal.style.opacity = '0';

            // Completely hide the element after transition ends
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    }

    /**
     * Automatically trigger modal dismissal after a 5-second delay
     * and animate the bottom progress bar to visually match the countdown.
     */
    document.addEventListener("DOMContentLoaded", function () {
        const progressBar = document.getElementById('welcome-progress-bar');
        const timeoutDuration = 5000; // 5000 milliseconds = 5 seconds

        if (progressBar) {
            // Force browser reflow to ensure CSS transition triggers properly
            progressBar.style.transition = 'none';
            progressBar.style.width = '0%';

            setTimeout(() => {
                progressBar.style.transition = `width ${timeoutDuration}ms linear`;
                progressBar.style.width = '100%';
            }, 50);
        }

        // Automatically hide the modal after the duration completes
        setTimeout(function () {
            dismissWelcomeModal();
        }, timeoutDuration);
    });
</script>

<!-- ========================================================================= -->
<!-- EXPORT AND PRINT REPORT ACTIONS HANDLER                                   -->
<!-- Manage PDF export confirmations and handle direct Excel downloads.       -->
<!-- ========================================================================= -->
<script>
    /**
     * Handle actions for PDF confirmation or direct Excel triggering.
     *
     * @param {string} type - The report export type ('pdf', 'excel', or 'print')
     */
    function confirmAndExport(type) {
        let titleText = "";
        let confirmButtonText = "";

        // Customize confirmation dialog text based on selected format
        if (type === 'pdf') {
            titleText = "Are you sure you want to download the PDF report?";
            confirmButtonText = "Yes, download";
        } else if (type === 'excel') {
            // DIRECT EXCEL DOWNLOAD: Skip confirmation alert and execute action immediately
            executeExportAction('excel');
            return;
        } else {
            titleText = "Are you sure you want to print the report?";
            confirmButtonText = "Yes, print now";
        }

        // Show SweetAlert confirmation for PDF or Print actions
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: titleText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f96332',
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    executeExportAction(type);
                }
            });
        } else {
            // Fallback to native browser confirm dialog if SweetAlert is missing
            if (confirm(titleText)) {
                executeExportAction(type);
            }
        }
    }

    /**
     * Execute the underlying URL redirection or print command with active filters.
     *
     * @param {string} type - Export format type
     */
    function executeExportAction(type) {
        // Grab current URL parameters to preserve active user filters and search queries
        let urlParams = new URLSearchParams(window.location.search);
        urlParams.delete('search'); // Remove live search filter to export full clean dataset

        if (type === 'pdf') {
            // Updated route to match the corrected project report pdf route name
            let basePdfUrl = "{{ route('admin.report.task-report.pdf') }}";
            let finalUrl = basePdfUrl;

            // Append query parameters if they exist
            if (urlParams.toString() !== "") {
                finalUrl += "?" + urlParams.toString();
            }

            // Redirect browser to trigger PDF download
            window.location.href = finalUrl;

        } else if (type === 'excel') {
            // -----------------------------------------------------------------
            // EXCEL EXPORT DIRECT ROUTE HANDLING
            // Pull the base route for Excel export and append current filter query params
            // -----------------------------------------------------------------
            let baseExcelUrl = "{{ route('admin.report.project-report.excel') }}";
            let finalUrl = baseExcelUrl;

            if (urlParams.toString() !== "") {
                finalUrl += "?" + urlParams.toString();
            }

            // Redirect browser to trigger direct Excel file download
            window.location.href = finalUrl;

        } else {
            // -----------------------------------------------------------------
            // HIDDEN IFRAME PRINT HANDLER (Triggers native print dialog locally)
            // -----------------------------------------------------------------
            let basePrintUrl = "{{ route('admin.report.project-report.print') }}";
            let finalPrintUrl = basePrintUrl;

            if (urlParams.toString() !== "") {
                finalPrintUrl += "?" + urlParams.toString();
            }

            // Remove existing temporary iframe if present
            const existingIframe = document.getElementById('print-iframe');
            if (existingIframe) {
                existingIframe.remove();
            }

            // Create a hidden iframe to load the print route and invoke local print window
            const iframe = document.createElement('iframe');
            iframe.id = 'print-iframe';
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = '0';

            iframe.src = finalPrintUrl;

            iframe.onload = function5 = function() {
                try {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                } catch (error) {
                    console.error('Print execution error: ', error);
                    window.open(finalPrintUrl, '_blank');
                }
            };

            document.body.appendChild(iframe);
        }
    }
</script>
@endpush
