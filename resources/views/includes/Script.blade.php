<!-- ========================================================================= -->
<!-- START OF STACKED SCRIPTS PUSH SECTION     -->
<!-- This Laravel Blade directive pushes the enclosed script stack to the      -->
<!-- master layout template, ensuring scripts load at the correct page section.-->
<!-- ========================================================================= -->

<!-- ========================================================================= -->
<!-- CORE JS FILES AND PLUGINS    -->
<!-- Import foundational JavaScript libraries including jQuery, Popper,        -->
<!-- Bootstrap, and custom UI scrollbar extensions.    -->
<!-- ========================================================================= -->
<script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
{{-- <script src="{{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script> --}}

<!-- ========================================================================= -->
<!-- GOOGLE MAPS AND CHART PLUGINS    -->
<!-- Load external mapping services and chart-related assets for visualization.-->
<!-- ========================================================================= -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>

<!-- ========================================================================= -->
<!-- NOW UI DASHBOARD CONTROL CENTER SCRIPTS    -->
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Select2 JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- ========================================================================= -->
<!-- INITIALIZE FLATPICKR ON DATE INPUTS     -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        flatpickr("input[type='date'], .datepicker", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            allowInput: true
        });
    });
</script>

<!-- ========================================================================= -->
<!-- INITIALIZE DASHBOARD CHARTS    -->
<!-- ========================================================================= -->
<script>
    $(document).ready(function () {
        if (typeof demo !== 'undefined' && typeof demo.initDashboardPageCharts === 'function') {
            demo.initDashboardPageCharts();
        }
    });
</script>

<!-- ========================================================================= -->
<!-- LIVE SEARCH FILTER FOR PROJECTS TABLE    -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const projectSearchInput = document.getElementById("projectSearchInput");
        const projectRows = document.querySelectorAll("#projectsTable tbody tr.project-row");

        if (projectSearchInput) {
            projectSearchInput.addEventListener("keyup", function () {
                const query = this.value.toLowerCase().trim();
                projectRows.forEach(row => {
                    const textContent = row.textContent.toLowerCase();
                    row.style.display = textContent.includes(query) ? "" : "none";
                });
            });
        }
    });
</script>

<!-- ========================================================================= -->
<!-- LIVE SEARCH FILTER FOR TASKS TABLE    -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const taskSearchInput = document.getElementById("taskSearchInput");
        const taskRows = document.querySelectorAll("#tasksTable tbody tr, .task-row, #projectsTable tbody tr, .project-row, #teamsTable tbody tr, .team-row");

        if (taskSearchInput) {
            taskSearchInput.addEventListener("keyup", function () {
                const query = this.value.toLowerCase().trim();
                taskRows.forEach(row => {
                    const textContent = row.textContent.toLowerCase();
                    row.style.display = (query === "" || textContent.includes(query)) ? "" : "none";
                });
            });
        }
    });
</script>

<!-- ========================================================================= -->
<!-- INITIALIZE AND RENDER TASK STATUS CHART    -->
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
                            '#fbc658', '#51cbce', '#6bd098', '#9b59b6', '#ef8157'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
    });
</script>

<!-- ========================================================================= -->
<!-- CONFIRM DELETE DIALOG USING SWEETALERT2    -->
<!-- ========================================================================= -->
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
                document.getElementById('delete-form-' + type + '-' + id).submit();
            }
        });
    }
</script>

<!-- ========================================================================= -->
<!-- AUTOMATIC ALERT DISMISSAL SCRIPT    -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(function () {
            let alerts = document.querySelectorAll('.custom-auto-dismiss-alert');
            alerts.forEach(function (alert) {
                let dismissBtn = alert.querySelector('.close');
                if (dismissBtn) dismissBtn.click();
            });
        }, 4000);
    });
</script>

<!-- ========================================================================= -->
<!-- WELCOME MODAL CONTROL SCRIPT    -->
<!-- ========================================================================= -->
<script>
    function dismissWelcomeModal() {
        const modal = document.getElementById('custom-welcome-modal');
        if (modal) {
            modal.style.transition = 'opacity 0.3s ease';
            modal.style.opacity = '0';
            setTimeout(() => { modal.style.display = 'none'; }, 300);
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const progressBar = document.getElementById('welcome-progress-bar');
        const timeoutDuration = 5000;

        if (progressBar) {
            progressBar.style.transition = 'none';
            progressBar.style.width = '0%';
            setTimeout(() => {
                progressBar.style.transition = `width ${timeoutDuration}ms linear`;
                progressBar.style.width = '100%';
            }, 500);
        }

        setTimeout(function () {
            dismissWelcomeModal();
        }, timeoutDuration);
    });
</script>

<!-- ========================================================================= -->
<!-- EXPORT AND PRINT REPORT ACTIONS HANDLER    -->
<!-- ========================================================================= -->
<script>
    function confirmAndExport(type) {
        let titleText = "";
        let confirmButtonText = "";

        if (type === 'pdf') {
            titleText = "Are you sure you want to download the PDF report?";
            confirmButtonText = "Yes, download";
        } else if (type === 'excel') {
            executeExportAction('excel');
            return;
        } else {
            titleText = "Are you sure you want to print the report?";
            confirmButtonText = "Yes, print now";
        }

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
                if (result.isConfirmed) executeExportAction(type);
            });
        } else {
            if (confirm(titleText)) executeExportAction(type);
        }
    }

    function executeExportAction(type) {
        let urlParams = new URLSearchParams(window.location.search);
        urlParams.delete('search');

        if (type === 'pdf') {
            let basePdfUrl = "{{ route('admin.report.task-report.pdf') }}";
            let finalUrl = basePdfUrl + (urlParams.toString() !== "" ? "?" + urlParams.toString() : "");
            window.location.href = finalUrl;
        } else if (type === 'excel') {
            let baseExcelUrl = "{{ route('admin.report.project-report.excel') }}";
            let finalUrl = baseExcelUrl + (urlParams.toString() !== "" ? "?" + urlParams.toString() : "");
            window.location.href = finalUrl;
        } else {
            let basePrintUrl = window.location.href.includes('task') ?
                "{{ route('admin.report.task-report.print') }}" :
                "{{ route('admin.report.project-report.print') }}";

            let finalPrintUrl = basePrintUrl + (urlParams.toString() !== "" ? "?" + urlParams.toString() : "");

            const existingIframe = document.getElementById('print-iframe');
            if (existingIframe) existingIframe.remove();

            const iframe = document.createElement('iframe');
            iframe.id = 'print-iframe';
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = '0';
            iframe.src = finalPrintUrl;

            iframe.onload = function() {
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

<!-- ================================================================= -->
<!-- START OF SCRIPT: DYNAMIC REMAINING DAYS CALCULATOR    -->
<!-- ================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        @isset($task)
        const startStr = "{{ $task->start_date ?? '' }}";
        const dueStr = "{{ $task->due_date ?? '' }}";
        const taskStatus = "{{ strtolower($task->status ?? '') }}";
        const counterElement = document.getElementById("live-actual-hours");

        if (counterElement) {
            let displayText = "";
            if (taskStatus === 'completed' || taskStatus === 'complete') {
                displayText = "TASK COMPLETED";
            } else if (!dueStr) {
                displayText = "No Deadline";
            } else {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const dueTime = new Date(dueStr);
                dueTime.setHours(0, 0, 0, 0);

                const startTime = startStr ? new Date(startStr) : null;
                if (startTime) startTime.setHours(0, 0, 0, 0);

                if (startTime && today.getTime() < startTime.getTime()) {
                    const totalDays = Math.ceil((dueTime.getTime() - startTime.getTime()) / (1000 * 60 * 60 * 24));
                    displayText = `${totalDays} Days Total <span class="text-danger" style="font-size: 12px;">(Not Started)</span>`;
                } else {
                    const diffDays = Math.ceil((dueTime.getTime() - today.getTime()) / (1000 * 60 * 60 * 24));
                    if (diffDays > 1) displayText = `${diffDays} Days Remaining`;
                    else if (diffDays === 1) displayText = `1 Day Remaining`;
                    else if (diffDays === 0) displayText = `Due Today`;
                    else displayText = `Overdue by ${Math.abs(diffDays)} Days`;
                }
            }
            counterElement.innerHTML = displayText;
        }
        @endisset
    });
</script>

<!-- ========================================================================= -->
<!-- START OF SCRIPT: SELECT2 INITIALIZATION FOR AJAX PROJECT SEARCH           -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
            $('.select2-ajax').select2({
                placeholder: 'Search and select a project...',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '{{ route("admin.projects.search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) { return { q: params.term }; },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return { id: item.id, text: item.title };
                            })
                        };
                    },
                    cache: true
                }
            });
        }
    });
</script>

<!-- ========================================================================= -->
<!-- START OF SCRIPT: ROBUST COLUMN REORDERING AND DRAG-AND-DROP HANDLER     -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        ["projectsTable", "teamsTable", "tasksTable"].forEach(tableId => {
            const table = document.getElementById(tableId);
            if (!table) return;

            const storageKey = tableId + "_column_order_map";
            const headerRow = table.querySelector("thead tr");
            if (!headerRow) return;

            // Give each th a permanent unique attribute index if not already present
            const originalThs = Array.from(headerRow.children);
            originalThs.forEach((th, idx) => {
                if (!th.hasAttribute('data-col-index')) {
                    th.setAttribute('data-col-index', idx);
                }
            });

            // Function to apply an array of column indices to the table
            function applyColumnOrder(indexArray) {
                if (!indexArray || !Array.isArray(indexArray) || indexArray.length !== originalThs.length) return;

                // 1. Reorder THs in the header
                const currentThs = Array.from(headerRow.children);
                indexArray.forEach(originalIdx => {
                    const thToMove = currentThs.find(th => parseInt(th.getAttribute('data-col-index')) === originalIdx);
                    if (thToMove) {
                        headerRow.appendChild(thToMove);
                    }
                });

                // 2. Reorder TDs in every row of tbody
                const rows = table.querySelectorAll("tbody tr");
                rows.forEach(row => {
                    const currentTds = Array.from(row.children);
                    if (currentTds.length === indexArray.length) {
                        indexArray.forEach(originalIdx => {
                            const tdToMove = currentTds.find((_, idx) => parseInt(originalThs[idx].getAttribute('data-col-index')) === originalIdx);
                            if (tdToMove) {
                                row.appendChild(tdToMove);
                            }
                        });
                    }
                });
            }

            // Restore saved order from localStorage
            const savedOrder = JSON.parse(localStorage.getItem(storageKey));
            if (savedOrder) {
                applyColumnOrder(savedOrder);
            }

            let draggedTh = null;
            const headers = table.querySelectorAll(".draggable-header, .draggable-th");

            headers.forEach(th => {
                th.setAttribute('draggable', true);

                th.addEventListener("dragstart", function (e) {
                    draggedTh = this;
                    e.dataTransfer.effectAllowed = "move";
                    this.style.opacity = "0.4";
                });

                th.addEventListener("dragend", function () {
                    this.style.opacity = "1";
                    headers.forEach(h => h.style.backgroundColor = "");
                });

                th.addEventListener("dragover", function (e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = "move";
                });

                th.addEventListener("drop", function (e) {
                    e.preventDefault();
                    if (draggedTh && draggedTh !== this) {
                        // Re-query current headers order
                        const currentThs = Array.from(headerRow.children);
                        const fromIdx = currentThs.indexOf(draggedTh);
                        const toIdx = currentThs.indexOf(this);

                        if (fromIdx !== -1 && toIdx !== -1) {
                            // Move DOM element in header
                            if (fromIdx < toIdx) {
                                headerRow.insertBefore(draggedTh, this.nextSibling);
                            } else {
                                headerRow.insertBefore(draggedTh, this);
                            }

                            // Re-apply and re-map for all rows in tbody
                            const newThsOrder = Array.from(headerRow.children);
                            const newIndexMap = newThsOrder.map(th => parseInt(th.getAttribute('data-col-index')));

                            const rows = table.querySelectorAll("tbody tr");
                            rows.forEach(row => {
                                const rowTds = Array.from(row.children);
                                newIndexMap.forEach(origIdx => {
                                    const matchingTd = rowTds.find((_, idx) => parseInt(originalThs[idx].getAttribute('data-col-index')) === origIdx);
                                    if (matchingTd) {
                                        row.appendChild(matchingTd);
                                    }
                                });
                            });

                            // Save current layout map to localStorage
                            localStorage.setItem(storageKey, JSON.stringify(newIndexMap));
                        }
                    }
                });
            });
        });
    });
</script>
