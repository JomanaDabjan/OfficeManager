@push('Script')

<!-- ========================================================================= -->
<!-- START OF STACKED SCRIPTS PUSH SECTION     -->
<!-- This Laravel Blade directive pushes the enclosed script stack to the      -->
<!-- master layout template, ensuring scripts load at the correct page section.-->
<!-- ========================================================================= -->

<!-- ========================================================================= -->
<!-- CORE JS FILES AND PLUGINS     -->
<!-- Import foundational JavaScript libraries including jQuery, Popper,        -->
<!-- Bootstrap, and custom UI scrollbar extensions.    -->
<!-- ========================================================================= -->
<script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>

<!-- ========================================================================= -->
<!-- GOOGLE MAPS AND CHART PLUGINS     -->
<!-- Load external mapping services and chart-related assets for visualization.-->
<!-- ========================================================================= -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>

<!-- ========================================================================= -->
<!-- NOW UI DASHBOARD CONTROL CENTER SCRIPTS     -->
<!-- Load dashboard core scripts, demo presets, and modern CDN libraries.      -->
<!-- ========================================================================= -->
<script src="{{ asset('assets/js/now-ui-dashboard.js?v=1.0.1') }}"></script>
<script src="{{ asset('assets/demo/demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ========================================================================= -->
<!-- FLATPICKR DATEPICKER CDN (CSS & JS)    -->
<!-- Added to format date inputs to DD/MM/YYYY while keeping backend Y-m-d.    -->
<!-- ========================================================================= -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- ========================================================================= -->
<!-- INITIALIZE FLATPICKR ON DATE INPUTS     -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Apply flatpickr to all date inputs or specific classes/IDs
        flatpickr("input[type='date'], .datepicker", {
            dateFormat: "Y-m-d",      // التنسيق الذي يتم إرساله للسيرفر وقاعدة البيانات
            altInput: true,           // تفعيل حقل عرض بديل للمستخدم
            altFormat: "d/m/Y",       // الشكل الذي يظهر للمستخدم (اليوم/الشهر/السنة)
            allowInput: true
        });
    });
</script>

<!-- ========================================================================= -->
<!-- INITIALIZE DASHBOARD CHARTS     -->
<!-- Safely trigger default dashboard charts if demo object is available.      -->
<!-- ========================================================================= -->
<script>
    // Wait until the HTML document is fully loaded and parsed
    $(document).ready(function () {
        // Check if the demo object and its chart initialization function both exist
        if (typeof demo !== 'undefined' && typeof demo.initDashboardPageCharts === 'function') {
            // Execute the dashboard page chart initialization function
            demo.initDashboardPageCharts();
        }
    });
</script>

<!-- ========================================================================= -->
<!-- LIVE SEARCH FILTER FOR PROJECTS TABLE     -->
<!-- ========================================================================= -->
<script>
    // Wait for the DOM content to be fully loaded before running script logic
    document.addEventListener("DOMContentLoaded", function () {
        // Get the search input element for projects by its ID
        const projectSearchInput = document.getElementById("projectSearchInput");
        // Select all table rows inside the projects table that have the class 'project-row'
        const projectRows = document.querySelectorAll("#projectsTable tbody tr.project-row");

        // Check if the search input element actually exists on the current view
        if (projectSearchInput) {
            // Listen for keyup events (when a user types something in the search field)
            projectSearchInput.addEventListener("keyup", function () {
                // Convert input value to lowercase and remove surrounding whitespace
                const query = this.value.toLowerCase().trim();

                // Loop through each individual project table row
                projectRows.forEach(row => {
                    // Extract all text content from the row and convert to lowercase
                    const textContent = row.textContent.toLowerCase();
                    // Show the row (empty string) if it includes the query string, otherwise hide it ('none')
                    row.style.display = textContent.includes(query) ? "" : "none";
                });
            });
        }
    });
</script>

<!-- ========================================================================= -->
<!-- LIVE SEARCH FILTER FOR TASKS TABLE     -->
<!-- ========================================================================= -->
<script>
    // Wait for the DOM content to be fully loaded
    document.addEventListener("DOMContentLoaded", function () {
        // Get the search input element for tasks by its ID
        const taskSearchInput = document.getElementById("taskSearchInput");
        // Select all table rows or elements matching the task row selectors
        const taskRows = document.querySelectorAll("#tasksTable tbody tr, .task-row");

        // Verify if the task search input exists
        if (taskSearchInput) {
            // Trigger filtering logic whenever a key is released inside the input field
            taskSearchInput.addEventListener("keyup", function () {
                // Normalize search query string (lowercase and trimmed)
                const query = this.value.toLowerCase().trim();

                // Iterate through each task row
                taskRows.forEach(row => {
                    // Convert row inner text to lowercase for case-insensitive matching
                    const textContent = row.textContent.toLowerCase();
                    // Display row if query is empty or matches the row content; hide otherwise
                    row.style.display = (query === "" || textContent.includes(query)) ? "" : "none";
                });
            });
        }
    });
</script>

<!-- ========================================================================= -->
<!-- INITIALIZE AND RENDER TASK STATUS CHART     -->
<!-- ========================================================================= -->
<script>
    // Wait for the document to be ready
    document.addEventListener("DOMContentLoaded", function () {
        // Find the canvas element meant for the task status chart
        const canvasElement = document.getElementById('tasksChart');

        // Proceed only if the canvas element is present on the page
        if (canvasElement) {
            // Get the 2D drawing context for the chart canvas
            const ctx = canvasElement.getContext('2d');

            // Create a new Chart.js doughnut chart instance
            new Chart(ctx, {
                // Specify chart type as a doughnut graph
                type: 'doughnut',
                data: {
                    // Define categories/labels for the chart segments
                    labels: ['Pending', 'In Progress', 'Completed', 'Accepted', 'Rejected'],
                    datasets: [{
                        // Inject dynamic data values from Laravel backend variables safely
                        data: [
                            {{ $pendingTasks ?? 0 }},
                            {{ $inProgressTasks ?? 0 }},
                            {{ $completedTasks ?? 0 }},
                            {{ $acceptedTasks ?? 0 }},
                            {{ $rejectedTasks ?? 0 }}
                        ],
                        // Define matching background colors for each chart segment
                        backgroundColor: [
                            '#fbc658', // Yellow for Pending
                            '#51cbce', // Blue for In Progress
                            '#6bd098', // Green for Completed
                            '#9b59b6', // Purple for Accepted
                            '#ef8157'  // Orange/Red for Rejected
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    // Make the chart responsive to screen size changes
                    responsive: true,
                    // Prevent the chart from maintaining an unwanted rigid aspect ratio
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            // Position the chart legend at the bottom of the canvas
                            position: 'bottom',
                        }
                    }
                }
            });
        }
    });
</script>

<!-- ========================================================================= -->
<!-- CONFIRM DELETE DIALOG USING SWEETALERT2     -->
<!-- ========================================================================= -->
<script>
    // Function triggered to show a confirmation popup before deleting a record
    function confirmDelete(type, id) {
        // Invoke SweetAlert2 configuration modal window
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f96332',
            cancelButtonColor: '#888888',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            // Apply custom Bootstrap/Theme classes to modal buttons
            customClass: {
                confirmButton: 'btn btn-primary btn-round px-4',
                cancelButton: 'btn btn-secondary btn-round px-4'
            },
            buttonsStyling: true
        }).then((result) => {
            // Check if the user clicked the confirmation button
            if (result.isConfirmed) {
                // Find and programmatically submit the corresponding hidden delete form
                document.getElementById('delete-form-' + type + '-' + id).submit();
            }
        });
    }
</script>

<!-- ========================================================================= -->
<!-- AUTOMATIC ALERT DISMISSAL SCRIPT    -->
<!-- ========================================================================= -->
<script>
    // Wait for the HTML document to fully load
    document.addEventListener("DOMContentLoaded", function () {
        // Set a timer to execute code after a 4-second delay (4000 milliseconds)
        setTimeout(function () {
            // Select all alert elements that have the auto-dismiss class
            let alerts = document.querySelectorAll('.custom-auto-dismiss-alert');

            // Loop through each found alert box
            alerts.forEach(function (alert) {
                // Find the close button inside the alert element
                let dismissBtn = alert.querySelector('.close');
                // If close button exists, trigger a click event to dismiss it automatically
                if (dismissBtn) {
                    dismissBtn.click();
                }
            });
        }, 4000);
    });
</script>

<!-- ========================================================================= -->
<!-- WELCOME MODAL CONTROL SCRIPT    -->
<!-- ========================================================================= -->
<script>
    // Function to hide the welcome modal smoothly with opacity transition
    function dismissWelcomeModal() {
        const modal = document.getElementById('custom-welcome-modal');
        if (modal) {
            // Apply a smooth CSS transition effect to opacity
            modal.style.transition = 'opacity 0.3s ease';
            modal.style.opacity = '0';
            // Hide the modal element completely after the fade-out transition finishes
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    }

    // Run code after the DOM content is fully loaded
    document.addEventListener("DOMContentLoaded", function () {
        const progressBar = document.getElementById('welcome-progress-bar');
        const timeoutDuration = 5000; // Duration set to 5 seconds

        // Check if the progress bar element exists
        if (progressBar) {
            // Reset transition and set initial width to 0%
            progressBar.style.transition = 'none';
            progressBar.style.width = '0%';

            // Animate progress bar filling up smoothly over the specified timeout duration
            setTimeout(() => {
                progressBar.style.transition = `width ${timeoutDuration}ms linear`;
                progressBar.style.width = '100%';
            }, 500);
        }

        // Automatically dismiss the welcome modal after the timeout finishes
        setTimeout(function () {
            dismissWelcomeModal();
        }, timeoutDuration);
    });
</script>

<!-- ========================================================================= -->
<!-- EXPORT AND PRINT REPORT ACTIONS HANDLER     -->
<!-- ========================================================================= -->
<script>
    // Function to confirm and manage report exporting actions (PDF, Excel, Print)
    function confirmAndExport(type) {
        let titleText = "";
        let confirmButtonText = "";

        // Determine dialog message configuration based on export type
        if (type === 'pdf') {
            titleText = "Are you sure you want to download the PDF report?";
            confirmButtonText = "Yes, download";
        } else if (type === 'excel') {
            // Directly trigger excel export without confirmation popup if needed
            executeExportAction('excel');
            return;
        } else {
            titleText = "Are you sure you want to print the report?";
            confirmButtonText = "Yes, print now";
        }

        // Check if SweetAlert2 is available to show a fancy confirmation dialog
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
                // Execute export action if user confirms the prompt
                if (result.isConfirmed) {
                    executeExportAction(type);
                }
            });
        } else {
            // Fallback to native browser confirmation box if SweetAlert is missing
            if (confirm(titleText)) {
                executeExportAction(type);
            }
        }
    }

    // Function to build URLs and execute the chosen report export/print action
    function executeExportAction(type) {
        // Capture existing URL search parameters to preserve active filters
        let urlParams = new URLSearchParams(window.location.search);
        urlParams.delete('search'); // Remove search parameter if necessary

        // Handle PDF export route generation
        if (type === 'pdf') {
            let basePdfUrl = "{{ route('admin.report.task-report.pdf') }}";
            let finalUrl = basePdfUrl;
            // Append parameters string if filters exist
            if (urlParams.toString() !== "") {
                finalUrl += "?" + urlParams.toString();
            }
            window.location.href = finalUrl;
        }
        // Handle Excel export route generation
        else if (type === 'excel') {
            let baseExcelUrl = "{{ route('admin.report.project-report.excel') }}";
            let finalUrl = baseExcelUrl;
            if (urlParams.toString() !== "") {
                finalUrl += "?" + urlParams.toString();
            }
            window.location.href = finalUrl;
        }
        // Handle Print report view generation using a hidden iframe
        else {
            // تحديد مسار الطباعة بناءً على الصفحة الحالية (مهام أو مشاريع)
            // تم إضافة التحقق لمعرفة ما إذا كنا في صفحة تقارير المهام أو المشاريع لتحديد مسار الطباعة المناسب
            let basePrintUrl = "";
            if (window.location.href.includes('task')) {
                basePrintUrl = "{{ route('admin.report.task-report.print') }}";
            } else {
                basePrintUrl = "{{ route('admin.report.project-report.print') }}";
            }

            let finalPrintUrl = basePrintUrl;
            if (urlParams.toString() !== "") {
                finalPrintUrl += "?" + urlParams.toString();
            }

            // Remove any leftover temporary print iframes from the document body
            const existingIframe = document.getElementById('print-iframe');
            if (existingIframe) {
                existingIframe.remove();
            }

            // Create a hidden iframe dynamically to fetch and print the report view
            const iframe = document.createElement('iframe');
            iframe.id = 'print-iframe';
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = '0';
            iframe.src = finalPrintUrl;

            // Trigger print command once the hidden iframe content loads successfully
            iframe.onload = function() {
                try {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                } catch (error) {
                    console.error('Print execution error: ', error);
                    // Fallback to opening the report in a new browser tab if iframe printing fails
                    window.open(finalPrintUrl, '_blank');
                }
            };

            // Append the iframe element to the body to trigger loading
            document.body.appendChild(iframe);
        }
    }
</script>

<!-- ================================================================= -->
<!-- START OF SCRIPT: DYNAMIC REMAINING DAYS CALCULATOR    -->
<!-- ================================================================= -->
<script>
    // Wait for the DOM content to be fully loaded before calculating remaining days
    document.addEventListener("DOMContentLoaded", function () {
        // Ensure the Laravel task variable exists before injecting values into JS
        @isset($task)
        // Retrieve start date, due date, and status values safely from the Blade task object
        const startStr = "{{ $task->start_date ?? '' }}";
        const dueStr = "{{ $task->due_date ?? '' }}";
        const taskStatus = "{{ strtolower($task->status ?? '') }}";

        // Locate the HTML container element that displays the remaining days counter
        const counterElement = document.getElementById("live-actual-hours");

        // Proceed if the counter element exists on the page
        if (counterElement) {
            let displayText = "";

            // التحقق مما إذا كانت المهمة مكتملة لإيقاف العداد
            if (taskStatus === 'completed' || taskStatus === 'complete') {
                displayText = "TASK COMPLETED";
            } else if (!dueStr) {
                // Check if the due date is missing; display fallback text if true
                displayText = "No Deadline";
            } else {
                // Initialize today's date and reset time components to 00:00:00 for accurate day comparison
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                // Parse and normalize the due date time object
                const dueTime = new Date(dueStr);
                dueTime.setHours(0, 0, 0, 0);

                // Parse and normalize the start date time object if it exists
                const startTime = startStr ? new Date(startStr) : null;
                if (startTime) {
                    startTime.setHours(0, 0, 0, 0);
                }

                // التحقق من الحالات الزمنية للمهمة/المشروع
                if (startTime && today.getTime() < startTime.getTime()) {
                    // 1. مرحلة ما قبل البدء: عرض العدد الكلي الثابت بين Start Date و Due Date مع عبارة (Not Started)
                    const totalDiffTime = dueTime.getTime() - startTime.getTime();
                    const totalDays = Math.ceil(totalDiffTime / (1000 * 60 * 60 * 24));

                    if (totalDays > 1) {
                        displayText = `${totalDays} Days Total <span class="text-danger" style="font-size: 12px;">(Not Started)</span>`;
                    } else if (totalDays === 1) {
                        displayText = `1 Day Total <span class="text-danger" style="font-size: 12px;">(Not Started)</span>`;
                    } else {
                        displayText = `0 Days <span class="text-danger" style="font-size: 12px;">(Not Started)</span>`;
                    }
                } else {
                    // 2. أثناء التنفيذ أو بعده: حساب الأيام المتبقية تنازلياً حتى تاريخ النهاية
                    const diffTime = dueTime.getTime() - today.getTime();
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    if (diffDays > 1) {
                        displayText = `${diffDays} Days Remaining`;
                    } else if (diffDays === 1) {
                        displayText = `1 Day Remaining`;
                    } else if (diffDays === 0) {
                        displayText = `Due Today`;
                    } else {
                        displayText = `Overdue by ${Math.abs(diffDays)} Days`;
                    }
                }
            }

            // Render the final formatted text string inside the target HTML counter element
            counterElement.innerHTML = displayText;
        }
        @endisset
    });
</script>
@endpush
