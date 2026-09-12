<!-- ========================================================================= -->
<!-- CORE JAVASCRIPT LIBRARIES FOR DOM MANIPULATION AND BOOTSTRAP COMPONENTS   -->
<!-- ========================================================================= -->
<script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
{{-- <script src="{{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script> --}}


<!-- ========================================================================= -->
<!-- THIRD-PARTY PLUGINS FOR MAPS, VISUAL CHARTS, AND NOTIFICATION ALERTS      -->
<!-- ========================================================================= -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>


<!-- ========================================================================= -->
<!-- TEMPLATE SPECIFIC SCRIPTS, CDN CHART UTILITIES, AND SWEETALERT DIALOGS    -->
<!-- ========================================================================= -->
<script src="{{ asset('assets/js/now-ui-dashboard.js?v=1.0.1') }}"></script>
<script src="{{ asset('assets/demo/demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- ========================================================================= -->
<!-- FLATPICKR PLUGIN FOR USER-FRIENDLY DATEPICKER INPUT INTERACTION           -->
<!-- ========================================================================= -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<!-- ========================================================================= -->
<!-- SELECT2 PLUGIN FOR ENHANCED SEARCHABLE DROP-DOWN SELECTION ELEMENTS       -->
<!-- ========================================================================= -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<!-- ========================================================================= -->
<!-- DATATABLES STYLESHEETS AND SCRIPTS FOR ADVANCED TABLE MANAGEMENT          -->
<!-- ========================================================================= -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>



<!-- ========================================== -->
<!-- SYNCHRONIZED TOP & BOTTOM SCROLLBARS      -->
<!-- ========================================== -->

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const topScroll = document.getElementById('projectTableScrollTop');
        const topScrollInner = document.getElementById('projectTableScrollTopInner');

        const tableScroll = document.getElementById('projectTableScroll');

        const bottomScroll = document.getElementById('projectTableScrollBottom');

        if (!topScroll || !topScrollInner || !tableScroll || !bottomScroll) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Make the top scrollbar exactly the same width as the table
        |--------------------------------------------------------------------------
        */

        function updateScrollWidth() {

            const table = document.getElementById('projectsTable');

            if (!table) {
                return;
            }

            const tableWidth = table.scrollWidth;

            topScrollInner.style.width = tableWidth + 'px';

            const bottomInner = bottomScroll.querySelector('div');

            if (bottomInner) {
                bottomInner.style.width = tableWidth + 'px';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Synchronize TOP scrollbar with table
        |--------------------------------------------------------------------------
        */

        let syncingTop = false;
        let syncingTable = false;
        let syncingBottom = false;


        topScroll.addEventListener('scroll', function () {

            if (syncingTop) {
                return;
            }

            syncingTable = true;
            syncingBottom = true;

            tableScroll.scrollLeft = topScroll.scrollLeft;
            bottomScroll.scrollLeft = topScroll.scrollLeft;

            requestAnimationFrame(function () {
                syncingTable = false;
                syncingBottom = false;
            });

        });


        /*
        |--------------------------------------------------------------------------
        | Synchronize ACTUAL TABLE scrollbar
        |--------------------------------------------------------------------------
        */

        tableScroll.addEventListener('scroll', function () {

            if (syncingTable) {
                return;
            }

            syncingTop = true;
            syncingBottom = true;

            topScroll.scrollLeft = tableScroll.scrollLeft;
            bottomScroll.scrollLeft = tableScroll.scrollLeft;

            requestAnimationFrame(function () {
                syncingTop = false;
                syncingBottom = false;
            });

        });


        /*
        |--------------------------------------------------------------------------
        | Synchronize BOTTOM scrollbar with table
        |--------------------------------------------------------------------------
        */

        bottomScroll.addEventListener('scroll', function () {

            if (syncingBottom) {
                return;
            }

            syncingTop = true;
            syncingTable = true;

            topScroll.scrollLeft = bottomScroll.scrollLeft;
            tableScroll.scrollLeft = bottomScroll.scrollLeft;

            requestAnimationFrame(function () {
                syncingTop = false;
                syncingTable = false;
            });

        });


        /*
        |--------------------------------------------------------------------------
        | Initial calculation
        |--------------------------------------------------------------------------
        */

        updateScrollWidth();


        /*
        |--------------------------------------------------------------------------
        | Recalculate after window resize
        |--------------------------------------------------------------------------
        */

        window.addEventListener('resize', function () {

            updateScrollWidth();

        });


        /*
        |--------------------------------------------------------------------------
        | Recalculate after Bootstrap/layout rendering
        |--------------------------------------------------------------------------
        */

        setTimeout(function () {

            updateScrollWidth();

        }, 100);


        setTimeout(function () {

            updateScrollWidth();

        }, 500);

    });
</script>


<!-- ========================================================================= -->
<!-- DOCUMENT READY SCRIPT TO INITIALIZE FLATPICKR CONFIGURATIONS              -->
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
<!-- SCRIPT TO SAFELY INITIALIZE NOW UI DASHBOARD PAGE CHARTS                  -->
<!-- ========================================================================= -->
<script>
    $(document).ready(function () {

        if (typeof demo !== 'undefined' && typeof demo.initDashboardPageCharts === 'function') {

            demo.initDashboardPageCharts();

        }

    });
</script>


<!-- ========================================================================= -->
<!-- CLIENT-SIDE LIVE FILTER SCRIPT FOR PROJECT ROWS SEARCH INTERACTION        -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const projectSearchInput =
            document.getElementById("projectSearchInput");

        const projectRows =
            document.querySelectorAll(
                "#projectsTable tbody tr.project-row, #projectListContainer .project-item"
            );

        if (projectSearchInput) {

            projectSearchInput.addEventListener("keyup", function () {

                const query =
                    this.value.toLowerCase().trim();

                projectRows.forEach(row => {

                    const textContent =
                        row.textContent.toLowerCase();

                    row.style.display =
                        (
                            query === "" ||
                            textContent.includes(query)
                        )
                            ? ""
                            : "none";

                });

            });

        }

    });
</script>


<!-- ========================================================================= -->
<!-- LIVE SEARCH FILTER FOR MULTIPLE TABLES INCLUDING TASKS, PROJECTS, AND TEAMS -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const taskSearchInput =
            document.getElementById("taskSearchInput");

        const taskRows =
            document.querySelectorAll(
                "#tasksTable tbody tr, .task-row, #projectsTable tbody tr, .project-row, #teamsTable tbody tr, .team-row"
            );

        if (taskSearchInput) {

            taskSearchInput.addEventListener("keyup", function () {

                const query =
                    this.value.toLowerCase().trim();

                taskRows.forEach(row => {

                    const textContent =
                        row.textContent.toLowerCase();

                    row.style.display =
                        (
                            query === "" ||
                            textContent.includes(query)
                        )
                            ? ""
                            : "none";

                });

            });

        }

    });
</script>


<!-- ========================================================================= -->
<!-- LIVE SEARCH FOR ASSIGNED TO DROPDOWN                                    -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const searchInput = document.getElementById("UserLiveSearch");
        const usersList = document.getElementById("assignedUsersList");
        const noResults = document.getElementById("assignedNoResults");
        const dropdownMenu = document.querySelector("#dropdownAssigned + .dropdown-menu");

        if (!searchInput || !usersList) {
            return;
        }

        const userItems = usersList.querySelectorAll(".assigned-user-item");

        function filterUsers() {

            const searchValue = searchInput.value
                .toLowerCase()
                .trim();

            let visibleCount = 0;

            userItems.forEach(function (item) {

                const userName = (
                    item.getAttribute("data-user-name") ||
                    item.textContent ||
                    ""
                )
                    .toLowerCase()
                    .trim();

                if (userName.indexOf(searchValue) !== -1) {

                    item.style.display = "";

                    visibleCount++;

                } else {

                    item.style.display = "none";

                }

            });

            if (noResults) {

                noResults.style.display =
                    visibleCount === 0 ? "block" : "none";

            }

        }

        searchInput.addEventListener("input", filterUsers);

        /*
         * Prevent Bootstrap dropdown from closing while
         * interacting with the search input.
         */
        searchInput.addEventListener("click", function (event) {
            event.stopPropagation();
        });

        searchInput.addEventListener("mousedown", function (event) {
            event.stopPropagation();
        });

        searchInput.addEventListener("keydown", function (event) {
            event.stopPropagation();
        });

        if (dropdownMenu) {

            dropdownMenu.addEventListener("click", function (event) {

                if (event.target === searchInput) {
                    event.stopPropagation();
                }

            });

            dropdownMenu.addEventListener("mousedown", function (event) {

                if (event.target === searchInput) {
                    event.stopPropagation();
                }

            });

        }

    });
</script>


<!-- ========================================================================= -->
<!-- LIVE SEARCH FILTER FOR EMPLOYEES AND USERS TABLE MANAGEMENT ROWS          -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const employeeSearchInput =
            document.getElementById("employeeSearchInput");

        if (!employeeSearchInput) {
            return;
        }

        employeeSearchInput.addEventListener("input", function () {

            const query =
                this.value.toLowerCase().trim();

            const employeeRows =
                document.querySelectorAll(
                    "#usersTable tbody tr.border-bottom"
                );

            employeeRows.forEach(row => {

                const textContent =
                    row.textContent.toLowerCase();

                row.style.display =
                    (
                        query === "" ||
                        textContent.includes(query)
                    )
                        ? ""
                        : "none";

            });

        });

    });
</script>


<!-- ========================================================================= -->
<!-- INITIALIZE AND RENDER TASK STATUS DOUGHNUT CHART WITH BLADE VARIABLES     -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const canvasElement =
            document.getElementById('tasksChart');

        if (canvasElement) {

            const ctx =
                canvasElement.getContext('2d');

            new Chart(ctx, {

                type: 'doughnut',

                data: {

                    labels: [
                        'Pending',
                        'In Progress',
                        'Completed',
                        'Accepted',
                        'Rejected',
                        'Overdue',
                        'Due Today'
                    ],

                    datasets: [{

                        data: [
                            {{ $pendingTasks ?? 0 }},
                            {{ $inProgressTasks ?? 0 }},
                            {{ $completedTasks ?? 0 }},
                            {{ $acceptedTasks ?? 0 }},
                            {{ $rejectedTasks ?? 0 }},
                            {{ $overdueTasks ?? 0 }},
                            {{ $dueTodayTasks ?? 0 }}
                        ],

                        backgroundColor: [

                            /* Pending */
                            '#11cdef',

                            /* In Progress */
                            '#fbb140',

                            /* Completed */
                            '#2dce89',

                            /* Accepted - Green Transparent */
                            'rgba(40, 167, 69, 0.35)',

                            /* Rejected - Red Transparent */
                            'rgba(220, 53, 69, 0.35)',

                            /* Overdue - Dark Red */
                            '#dc3545',

                            /* Due Today - Purple */
                            '#8965e0'

                        ],

                        borderColor: [

                            /* Pending */
                            '#1171ef',

                            /* In Progress */
                            '#f39c12',

                            /* Completed */
                            '#198754',

                            /* Accepted */
                            'rgba(40, 167, 69, 0.65)',

                            /* Rejected */
                            'rgba(220, 53, 69, 0.65)',

                            /* Overdue */
                            '#a71d2a',

                            /* Due Today */
                            '#6f42c1'

                        ],

                        borderWidth: 1

                    }]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {

                            position: 'bottom'

                        }

                    }

                }

            });

        }

    });
</script>


<!-- ========================================================================= -->
<!-- CONFIRM DELETE DIALOG USING SWEETALERT2 FOR SECURE ITEM DELETION           -->
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

                confirmButton:
                    'btn btn-primary btn-round px-4',

                cancelButton:
                    'btn btn-secondary btn-round px-4'

            },

            buttonsStyling: true

        }).then((result) => {

            if (result.isConfirmed) {

                document
                    .getElementById(
                        'delete-form-' + type + '-' + id
                    )
                    .submit();

            }

        });

    }
</script>


<!-- ========================================================================= -->
<!-- AUTOMATIC ALERT DISMISSAL SCRIPT TO HIDE NOTIFICATIONS AFTER FOUR SECONDS -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {

        setTimeout(function () {

            let alerts =
                document.querySelectorAll(
                    '.custom-auto-dismiss-alert'
                );

            alerts.forEach(function (alert) {

                let dismissBtn =
                    alert.querySelector('.close');

                if (dismissBtn) {
                    dismissBtn.click();
                }

            });

        }, 4000);

    });
</script>


<!-- ========================================================================= -->
<!-- WELCOME MODAL CONTROL SCRIPT WITH PROGRESS BAR AND AUTO TIMED DISMISSAL   -->
<!-- ========================================================================= -->
<script>
    function dismissWelcomeModal() {

        const modal =
            document.getElementById(
                'custom-welcome-modal'
            );

        if (modal) {

            modal.style.transition =
                'opacity 0.35s ease';

            modal.style.opacity =
                '0';

            setTimeout(() => {

                modal.style.display =
                    'none';

            }, 350);
        }

    }


    document.addEventListener("DOMContentLoaded", function () {

        const progressBar =
            document.getElementById(
                'welcome-progress-bar'
            );

        const timeoutDuration =
            5000;


        if (progressBar) {

            progressBar.style.transition =
                'none';

            progressBar.style.width =
                '0%';


            setTimeout(() => {

                progressBar.style.transition =
                    `width ${timeoutDuration}ms linear`;

                progressBar.style.width =
                    '100%';

            }, 300);

        }


        setTimeout(function () {

            dismissWelcomeModal();

        }, timeoutDuration);

    });
</script>


<!-- ========================================================================= -->
<!-- DYNAMIC REMAINING DAYS CALCULATOR FOR TASKS BASED ON DATES AND STATUS     -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {

        @isset($task)

        const startStr =
            "{{ $task->start_date ?? '' }}";

        const dueStr =
            "{{ $task->due_date ?? '' }}";

        const taskStatus =
            "{{ strtolower($task->status ?? '') }}";

        const counterElement =
            document.getElementById(
                "live-actual-hours"
            );

        if (counterElement) {

            let displayText = "";

            if (
                taskStatus === 'completed' ||
                taskStatus === 'complete'
            ) {

                displayText =
                    "TASK COMPLETED";

            } else if (!dueStr) {

                displayText =
                    "No Deadline";

            } else {

                const today =
                    new Date();

                today.setHours(
                    0,
                    0,
                    0,
                    0
                );

                const dueTime =
                    new Date(dueStr);

                dueTime.setHours(
                    0,
                    0,
                    0,
                    0
                );

                const startTime =
                    startStr
                        ? new Date(startStr)
                        : null;

                if (startTime) {

                    startTime.setHours(
                        0,
                        0,
                        0,
                        0
                    );

                }

                if (
                    startTime &&
                    today.getTime() <
                        startTime.getTime()
                ) {

                    const totalDays =
                        Math.ceil(
                            (
                                dueTime.getTime() -
                                startTime.getTime()
                            ) /
                            (
                                1000 *
                                60 *
                                60 *
                                24
                            )
                        );

                    displayText =
                        `${totalDays} Days Total <span class="text-danger" style="font-size: 12px;">(Not Started)</span>`;

                } else {

                    const diffDays =
                        Math.ceil(
                            (
                                dueTime.getTime() -
                                today.getTime()
                            ) /
                            (
                                1000 *
                                60 *
                                60 *
                                24
                            )
                        );

                    if (diffDays > 1) {

                        displayText =
                            `${diffDays} Days Remaining`;

                    } else if (diffDays === 1) {

                        displayText =
                            `1 Day Remaining`;

                    } else if (diffDays === 0) {

                        displayText =
                            `Due Today`;

                    } else {

                        displayText =
                            `Overdue by ${Math.abs(diffDays)} Days`;

                    }

                }

            }

            counterElement.innerHTML =
                displayText;

        }

        @endisset

    });
</script>


<!-- ========================================================================= -->
<!-- SELECT2 INITIALIZATION FOR AJAX PROJECT SEARCH                            -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {

        if (
            typeof jQuery !== 'undefined' &&
            typeof jQuery.fn.select2 !== 'undefined'
        ) {

            $('.select2-ajax').select2({

                placeholder:
                    'Search and select a project...',

                allowClear:
                    true,

                width:
                    '100%',

                minimumInputLength:
                    0,

                ajax: {

                    url:
                        '{{ route("admin.projects.search") }}',

                    dataType:
                        'json',

                    delay:
                        250,

                    data:
                        function (params) {

                            const searchTerm =
                                (params.term || '').trim();

                            return {
                                q: searchTerm
                            };

                        },

                    processResults:
                        function (data) {

                            return {

                                results:
                                    $.map(
                                        data,
                                        function (item) {

                                            return {

                                                id:
                                                    item.id,

                                                text:
                                                    item.title

                                            };

                                        }
                                    )

                            };

                        },

                    cache:
                        true

                }

            });

        }

    });
</script>


<!-- ========================================================================= -->
<!-- ROBUST COLUMN REORDERING AND DRAG-AND-DROP HANDLER                        -->
<!-- ========================================================================= -->

<script>
    document.addEventListener("DOMContentLoaded", function () {

        // ================================================================
        // TABLES THAT SUPPORT COLUMN REORDERING
        // ================================================================

        [
            "projectsTable",
            "teamsTable",
            "tasksTable",
            "employeesTable",
            "usersTable"
        ].forEach(function (tableId) {

            const table =
                document.getElementById(tableId);

            if (!table) {
                return;
            }

            // ============================================================
            // STORAGE KEY
            // ============================================================

            const storageKey =
                tableId + "_column_order_map";

            // ============================================================
            // HEADER ROW
            // ============================================================

            const headerRow =
                table.querySelector("thead tr");

            if (!headerRow) {
                return;
            }

            // ============================================================
            // ASSIGN PERMANENT ORIGINAL INDEX TO HEADER CELLS
            // ============================================================

            const originalHeaders =
                Array.from(
                    headerRow.children
                );

            originalHeaders.forEach(
                function (th, index) {

                    if (
                        !th.hasAttribute(
                            "data-col-index"
                        )
                    ) {

                        const existingColumn =
                            th.getAttribute(
                                "data-column"
                            );

                        th.setAttribute(
                            "data-col-index",
                            existingColumn !== null
                                ? existingColumn
                                : index
                        );
                    }

                }
            );

            // ============================================================
            // ASSIGN SAME ORIGINAL INDEX TO BODY CELLS
            // ============================================================

            const originalHeaderIndexes =
                Array.from(
                    headerRow.children
                ).map(function (th, index) {

                    return String(
                        th.getAttribute(
                            "data-col-index"
                        ) ?? index
                    );

                });

            table
                .querySelectorAll(
                    "tbody tr"
                )
                .forEach(function (row) {

                    const cells =
                        Array.from(
                            row.children
                        );

                    cells.forEach(
                        function (
                            td,
                            index
                        ) {

                            if (
                                !td.hasAttribute(
                                    "data-col-index"
                                )
                            ) {

                                const headerIndex =
                                    originalHeaderIndexes[
                                        index
                                    ];

                                if (
                                    headerIndex !==
                                    undefined
                                ) {

                                    td.setAttribute(
                                        "data-col-index",
                                        headerIndex
                                    );
                                }
                            }

                        }
                    );

                });

            // ============================================================
            // GET CURRENT COLUMN ORDER
            // ============================================================

            function getCurrentOrder() {

                return Array.from(
                    headerRow.children
                ).map(
                    function (th) {

                        return String(
                            th.getAttribute(
                                "data-col-index"
                            )
                        );

                    }
                );

            }

            // ============================================================
            // APPLY SPECIAL COLUMN RULES
            // ============================================================

            function normalizeOrder(
                order
            ) {

                let normalizedOrder =
                    Array.from(order).map(
                        String
                    );

                // --------------------------------------------------------
                // Remove duplicates
                // --------------------------------------------------------

                normalizedOrder =
                    normalizedOrder.filter(
                        function (
                            value,
                            index,
                            array
                        ) {

                            return (
                                array.indexOf(value) ===
                                index
                            );

                        }
                    );

                // --------------------------------------------------------
                // Keep the exact order selected by the user.
                // No fixed order is forced for tasks or teams.
                // --------------------------------------------------------

                // --------------------------------------------------------
                // Make sure no valid column disappears
                // --------------------------------------------------------

                originalHeaderIndexes.forEach(
                    function (index) {

                        if (
                            !normalizedOrder.includes(
                                index
                            )
                        ) {

                            normalizedOrder.push(
                                index
                            );

                        }

                    }
                );

                return normalizedOrder;
            }

            // ============================================================
            // APPLY COLUMN ORDER
            // ============================================================

            function applyColumnOrder(
                requestedOrder,
                saveOrder
            ) {

                if (
                    !Array.isArray(
                        requestedOrder
                    )
                ) {
                    return;
                }

                const normalizedOrder =
                    normalizeOrder(
                        requestedOrder
                    );

                // ========================================================
                // REORDER HEADER CELLS
                // ========================================================

                normalizedOrder.forEach(
                    function (originalIndex) {

                        const targetHeader =
                            Array.from(
                                headerRow.children
                            ).find(
                                function (th) {

                                    return (
                                        String(
                                            th.getAttribute(
                                                "data-col-index"
                                            )
                                        ) ===
                                        String(
                                            originalIndex
                                        )
                                    );

                                }
                            );

                        if (targetHeader) {

                            headerRow.appendChild(
                                targetHeader
                            );
                        }

                    }
                );

                // ========================================================
                // REORDER BODY CELLS
                // ========================================================

                table
                    .querySelectorAll(
                        "tbody tr"
                    )
                    .forEach(
                        function (row) {

                            const currentCells =
                                Array.from(
                                    row.children
                                );

                            /*
                             * IMPORTANT:
                             * Find every cell by its permanent original
                             * column index instead of its current position.
                             */

                            normalizedOrder.forEach(
                                function (
                                    originalIndex
                                ) {

                                    const targetCell =
                                        currentCells.find(
                                            function (td) {

                                                return (
                                                    String(
                                                        td.getAttribute(
                                                            "data-col-index"
                                                        )
                                                    ) ===
                                                    String(
                                                        originalIndex
                                                    )
                                                );

                                            }
                                        );

                                    if (targetCell) {

                                        row.appendChild(
                                            targetCell
                                        );
                                    }

                                }
                            );

                        }
                    );

                // ========================================================
                // SAVE
                // ========================================================

                if (saveOrder !== false) {

                    localStorage.setItem(
                        storageKey,
                        JSON.stringify(
                            normalizedOrder
                        )
                    );
                }

            }

            // ============================================================
            // FORCE CORRECT INITIAL ORDER FOR TASKS TABLE
            // ============================================================

            if (tableId === "tasksTable") {

                const taskOrderVersionKey =
                    "tasksTable_column_order_version";

                const currentTaskOrderVersion =
                    "v2";

                const savedTaskOrderVersion =
                    localStorage.getItem(
                        taskOrderVersionKey
                    );

                if (
                    savedTaskOrderVersion !==
                    currentTaskOrderVersion
                ) {

                    localStorage.removeItem(
                        storageKey
                    );

                    localStorage.setItem(
                        taskOrderVersionKey,
                        currentTaskOrderVersion
                    );
                }

            }

            // ============================================================
            // DEFAULT INITIAL ORDER
            // ============================================================

            const defaultOrder =
                [
                    "title",
                    "description",
                    "user_id",
                    "attachment",
                    "status",
                    "review_action",
                    "actions"
                ];

            // ============================================================
            // LOAD SAVED ORDER
            // ============================================================

            let savedOrder = null;

            try {

                savedOrder =
                    JSON.parse(
                        localStorage.getItem(
                            storageKey
                        )
                    );

            } catch (error) {

                savedOrder =
                    null;
            }

            // ============================================================
            // APPLY SAVED ORDER OR DEFAULT ORDER
            // ============================================================

            if (
                Array.isArray(savedOrder) &&
                savedOrder.length
            ) {

                applyColumnOrder(
                    savedOrder,
                    false
                );

            } else if (tableId === "tasksTable") {

                applyColumnOrder(
                    defaultOrder,
                    true
                );

            } else {

                const initialOrder =
                    normalizeOrder(
                        getCurrentOrder()
                    );

                applyColumnOrder(
                    initialOrder,
                    true
                );
            }

            // ============================================================
            // DRAG VARIABLES
            // ============================================================

            let draggedTh = null;

            // ============================================================
            // DRAGGABLE HEADERS
            // ============================================================

            const headers =
                table.querySelectorAll(
                    ".draggable-header, .draggable-th"
                );

            headers.forEach(
                function (th) {

                    th.setAttribute(
                        "draggable",
                        "true"
                    );

                    // ----------------------------------------------------
                    // DRAG START
                    // ----------------------------------------------------

                    th.addEventListener(
                        "dragstart",
                        function (e) {

                            draggedTh =
                                this;

                            e.dataTransfer.effectAllowed =
                                "move";

                            this.style.opacity =
                                "0.4";

                        }
                    );

                    // ----------------------------------------------------
                    // DRAG END
                    // ----------------------------------------------------

                    th.addEventListener(
                        "dragend",
                        function () {

                            this.style.opacity =
                                "1";

                            headers.forEach(
                                function (header) {

                                    header.style.backgroundColor =
                                        "";

                                }
                            );

                            draggedTh =
                                null;

                        }
                    );

                    // ----------------------------------------------------
                    // DRAG OVER
                    // ----------------------------------------------------

                    th.addEventListener(
                        "dragover",
                        function (e) {

                            e.preventDefault();

                            e.dataTransfer.dropEffect =
                                "move";

                        }
                    );

                    // ----------------------------------------------------
                    // DROP
                    // ----------------------------------------------------

                    th.addEventListener(
                        "drop",
                        function (e) {

                            e.preventDefault();

                            if (
                                !draggedTh ||
                                draggedTh === this
                            ) {
                                return;
                            }

                            const currentHeaders =
                                Array.from(
                                    headerRow.children
                                );

                            const fromPosition =
                                currentHeaders.indexOf(
                                    draggedTh
                                );

                            const toPosition =
                                currentHeaders.indexOf(
                                    this
                                );

                            if (
                                fromPosition === -1 ||
                                toPosition === -1
                            ) {
                                return;
                            }

                            // ------------------------------------------------
                            // Move header
                            // ------------------------------------------------

                            if (
                                fromPosition <
                                toPosition
                            ) {

                                headerRow.insertBefore(
                                    draggedTh,
                                    this.nextSibling
                                );

                            } else {

                                headerRow.insertBefore(
                                    draggedTh,
                                    this
                                );
                            }

                            // =================================================
                            // GET NEW ORDER
                            // =================================================

                            let newIndexMap =
                                getCurrentOrder();

                            // =================================================
                            // APPLY SPECIAL RULES
                            // =================================================

                            newIndexMap =
                                normalizeOrder(
                                    newIndexMap
                                );

                            // =================================================
                            // REAPPLY HEADER ORDER
                            // =================================================

                            applyColumnOrder(
                                newIndexMap,
                                false
                            );

                            // =================================================
                            // SAVE NEW ORDER
                            // =================================================

                            localStorage.setItem(
                                storageKey,
                                JSON.stringify(
                                    newIndexMap
                                )
                            );

                        }
                    );

                }
            );

        });

    });
</script>

<!-- ========================================================================= -->
<!-- TABLE COLUMN REORDERING AND PERSISTENCE MODULE                            -->
<!-- ========================================================================= -->

<script>
    /*
     * Column ordering is completely handled by the robust module above.
     *
     * This block intentionally performs no second conflicting reorder.
     */
    document.addEventListener("DOMContentLoaded", function () {
        return;
    });
</script>


<!-- ========================================================================= -->
<!-- LIVE SEARCH DROPDOWN FILTER MODULE                                        -->
<!-- ========================================================================= -->

<script>
    document.addEventListener("DOMContentLoaded", function () {

        const projectSearch =
            document.getElementById(
                "projectLiveSearch"
            );

        const projectOptions =
            document.querySelectorAll(
                ".project-option"
            );

        const noProjectResults =
            document.getElementById(
                "noProjectResults"
            );

        const projectIdInput =
            document.getElementById(
                "project_id"
            );

        const selectedProjectText =
            document.getElementById(
                "selectedProjectText"
            );

        const projectDropdownButton =
            document.getElementById(
                "projectDropdownButton"
            );

        if (!projectSearch) {
            return;
        }

        function normalizeText(text) {

            return text
                .toLocaleLowerCase()
                .trim()
                .replace(/\s+/g, " ");

        }

        projectSearch.addEventListener(
            "input",
            function () {

                const query =
                    normalizeText(
                        this.value
                    );

                const searchWords =
                    query
                        .split(" ")
                        .filter(
                            word =>
                                word.length > 0
                        );

                let visibleProjects =
                    0;

                projectOptions.forEach(
                    function (option) {

                        const projectTitle =
                            normalizeText(
                                option.getAttribute(
                                    "data-title"
                                ) ||
                                option.textContent
                            );

                        const matches =
                            searchWords.every(
                                function (word) {

                                    return projectTitle.includes(
                                        word
                                    );

                                }
                            );

                        if (
                            query === "" ||
                            matches
                        ) {

                            option.style.display =
                                "";

                            visibleProjects++;

                        } else {

                            option.style.display =
                                "none";
                        }

                    }
                );

                if (noProjectResults) {

                    noProjectResults.style.display =
                        visibleProjects === 0
                            ? "block"
                            : "none";
                }

            }
        );

        projectSearch.addEventListener(
            "focus",
            function () {

                this.style.setProperty(
                    "border-color",
                    "#f96332",
                    "important"
                );

                this.style.setProperty(
                    "box-shadow",
                    "0 0 0 2px rgba(249, 99, 50, 0.15)",
                    "important"
                );

            }
        );

        projectSearch.addEventListener(
            "blur",
            function () {

                this.style.setProperty(
                    "border-color",
                    "#ced4da",
                    "important"
                );

                this.style.setProperty(
                    "box-shadow",
                    "none",
                    "important"
                );

            }
        );

        projectOptions.forEach(
            function (option) {

                option.addEventListener(
                    "click",
                    function (event) {

                        event.preventDefault();

                        event.stopPropagation();

                        const projectId =
                            this.getAttribute(
                                "data-id"
                            );

                        const projectTitle =
                            this.textContent.trim();

                        if (projectIdInput) {

                            projectIdInput.value =
                                projectId;
                        }

                        if (selectedProjectText) {

                            selectedProjectText.textContent =
                                projectTitle;
                        }

                        projectSearch.value =
                            "";

                        projectOptions.forEach(
                            function (projectOption) {

                                projectOption.style.display =
                                    "";

                            }
                        );

                        if (noProjectResults) {

                            noProjectResults.style.display =
                                "none";
                        }

                        if (
                            typeof jQuery !== "undefined" &&
                            typeof jQuery.fn.dropdown !== "undefined"
                        ) {

                            jQuery(
                                projectDropdownButton
                            ).dropdown("hide");

                        }

                    }
                );

            }
        );

        projectSearch.addEventListener(
            "click",
            function (event) {
                event.stopPropagation();
            }
        );

        projectSearch.addEventListener(
            "keydown",
            function (event) {
                event.stopPropagation();
            }
        );

    });
</script>

<!-- ========================================================================= -->
<!-- FILE UPLOAD PREVIEW AND MANAGEMENT MODULE                                 -->
<!-- ========================================================================= -->
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const attachmentInput =
            document.getElementById(
                "attachmentInput"
            );

        const uploadPrompt =
            document.getElementById(
                "uploadPrompt"
            );

        const filePreviewContainer =
            document.getElementById(
                "filePreviewContainer"
            );

        const fileNameDisplay =
            document.getElementById(
                "fileNameDisplay"
            );

        const fileSizeDisplay =
            document.getElementById(
                "fileSizeDisplay"
            );

        const selectedFilesList =
            document.getElementById(
                "selectedFilesList"
            );

        const selectedFilesCount =
            document.getElementById(
                "selectedFilesCount"
            );

        const filesNamesContainer =
            document.getElementById(
                "filesNamesContainer"
            );

        if (!attachmentInput) {
            return;
        }

        attachmentInput.addEventListener(
            "change",
            function () {

                const files =
                    Array.from(
                        this.files
                    );

                if (
                    files.length === 0
                ) {

                    uploadPrompt.classList.remove(
                        "d-none"
                    );

                    filePreviewContainer.classList.add(
                        "d-none"
                    );

                    selectedFilesList.classList.add(
                        "d-none"
                    );

                    return;
                }

                uploadPrompt.classList.add(
                    "d-none"
                );

                filePreviewContainer.classList.remove(
                    "d-none"
                );

                filePreviewContainer.classList.add(
                    "d-flex"
                );

                selectedFilesList.classList.remove(
                    "d-none"
                );

                selectedFilesCount.textContent =
                    files.length;

                fileNameDisplay.textContent =
                    files.length === 1
                        ? files[0].name
                        : files.length +
                          " files selected";

                const totalSize =
                    files.reduce(
                        function (
                            total,
                            file
                        ) {

                            return total +
                                   file.size;

                        },
                        0
                    );

                function formatFileSize(
                    bytes
                ) {

                    if (
                        bytes < 1024
                    ) {

                        return bytes +
                               " B";
                    }

                    if (
                        bytes <
                        1024 *
                        1024
                    ) {

                        return (
                            bytes /
                            1024
                        ).toFixed(1) +
                        " KB";
                    }

                    return (
                        bytes /
                        (
                            1024 *
                            1024
                        )
                    ).toFixed(2) +
                    " MB";
                }

                fileSizeDisplay.textContent =
                    files.length === 1
                        ? formatFileSize(
                            files[0].size
                        )
                        : "Total size: " +
                          formatFileSize(
                              totalSize
                          );

                filesNamesContainer.innerHTML =
                    "";

                files.forEach(
                    function (
                        file,
                        index
                    ) {

                        const fileRow =
                            document.createElement(
                                "div"
                            );

                        fileRow.className =
                            "d-flex align-items-center justify-content-between px-3 py-2 mb-1";

                        fileRow.style.cssText =
                            `background: #f9fbfd; border: 1px solid #edf0f2; border-radius: 7px; transition: all 0.2s ease;`;

                        const leftSide =
                            document.createElement(
                                "div"
                            );

                        leftSide.className =
                            "d-flex align-items-center";

                        const icon =
                            document.createElement(
                                "div"
                            );

                        icon.style.cssText =
                            `width: 32px; height: 32px; border-radius: 7px; background: #fff1eb; color: #f96332; display: flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 16px; flex-shrink: 0;`;

                        icon.innerHTML =
                            "📄";

                        const details =
                            document.createElement(
                                "div"
                            );

                        details.style.minWidth =
                            "0";

                        const name =
                            document.createElement(
                                "div"
                            );

                        name.className =
                            "font-weight-bold text-dark";

                        name.style.cssText =
                            `font-size: 13px; word-break: break-word;`;

                        name.textContent =
                            file.name;

                        const size =
                            document.createElement(
                                "small"
                            );

                        size.className =
                            "text-muted";

                        size.textContent =
                            formatFileSize(
                                file.size
                            );

                        details.appendChild(
                            name
                        );

                        details.appendChild(
                            size
                        );

                        leftSide.appendChild(
                            icon
                        );

                        leftSide.appendChild(
                            details
                        );

                        const number =
                            document.createElement(
                                "span"
                            );

                        number.className =
                            "badge badge-light";

                        number.style.cssText =
                            `font-size: 11px; color: #8898aa; flex-shrink: 0; margin-left: 10px;`;

                        number.textContent =
                            "#" +
                            (
                                index + 1
                            );

                        fileRow.appendChild(
                            leftSide
                        );

                        fileRow.appendChild(
                            number
                        );

                        fileRow.addEventListener(
                            "mouseenter",
                            function () {

                                this.style.borderColor =
                                    "#f96332";

                                this.style.background =
                                    "#fffaf7";

                            }
                        );

                        fileRow.addEventListener(
                            "mouseleave",
                            function () {

                                this.style.borderColor =
                                    "#edf0f2";

                                this.style.background =
                                    "#f9fbfd";

                            }
                        );

                        filesNamesContainer.appendChild(
                            fileRow
                        );

                    }
                );

            }
        );

    });
</script>


<!-- ========================================================= -->
<!-- CLEAR ALL SELECTED FILES MODULE                           -->
<!-- ========================================================= -->
<script>
    // Define a global function to reset all selected files, inputs, preview containers, and counters back to their default empty states.
    window.clearFiles = function () {
        // Reset the file input value to an empty string to clear any selected files from memory.
        const attachmentInput = document.getElementById("attachmentInput");
        const uploadPrompt = document.getElementById("uploadPrompt");
        const filePreviewContainer = document.getElementById("filePreviewContainer");
        const selectedFilesList = document.getElementById("selectedFilesList");
        const selectedFilesCount = document.getElementById("selectedFilesCount");
        const filesNamesContainer = document.getElementById("filesNamesContainer");
        const fileNameDisplay = document.getElementById("fileNameDisplay");
        const fileSizeDisplay = document.getElementById("fileSizeDisplay");

        if (attachmentInput) attachmentInput.value = "";
        if (uploadPrompt) uploadPrompt.classList.remove("d-none");
        if (filePreviewContainer) {
            filePreviewContainer.classList.add("d-none");
            filePreviewContainer.classList.remove("d-flex");
        }
        if (selectedFilesList) selectedFilesList.classList.add("d-none");
        if (selectedFilesCount) selectedFilesCount.textContent = "0";
        if (filesNamesContainer) filesNamesContainer.innerHTML = "";
        if (fileNameDisplay) fileNameDisplay.textContent = "";
        if (fileSizeDisplay) fileSizeDisplay.textContent = "";
    };
</script>


<!-- ========================================================================= -->
<!-- TITLE LIVE SEARCH DROPDOWN FILTER MODULE                                  -->
<!-- ========================================================================= -->

<script>
    // Wait for the DOM content to fully load before initializing the title search component.
    document.addEventListener("DOMContentLoaded", function () {

        // Retrieve the title live search input element from the document using its ID.
        const titleSearchInput = document.getElementById("titleLiveSearch");

        // Retrieve all dropdown option elements associated with titles using their class name.
        const titleOptions = document.querySelectorAll(".title-option");

        // Retrieve the notification element displayed when no title options match the search query.
        const noTitleResults = document.getElementById("noTitleResults");

        // Stop script execution safely if either the search input or title options do not exist on the page.
        if (!titleSearchInput || !titleOptions.length) {
            return;
        }

        // Listen for user input events inside the title search field to trigger real-time filtering.
        titleSearchInput.addEventListener("input", function () {

            // Normalize the search input value by converting text to lowercase, trimming whitespace, and collapsing extra spaces.
            const query = this.value.toLocaleLowerCase().trim().replace(/\s+/g, " ");

            // Split the normalized query string into an array of individual words and filter out any empty entries.
            const searchWords = query.split(" ").filter(word => word.length > 0);

            // Initialize a counter variable to track how many title options match the search criteria.
            let visibleTitles = 0;

            // Iterate through each individual title option element to evaluate matching conditions.
            titleOptions.forEach(function (option) {

                // Extract the title text from the data attribute or element text content, then normalize it.
                const title = (
                    option.getAttribute("data-title") ||
                    option.textContent ||
                    ""
                ).toLocaleLowerCase().trim().replace(/\s+/g, " ");

                // Check whether every individual search word is contained within the title string.
                const matches = searchWords.every(function (word) {
                    return title.includes(word);
                });

                // Display the option if the query is completely empty or if all search words match, otherwise hide it.
                if (query === "" || matches) {
                    option.style.display = "";
                    visibleTitles++;
                } else {
                    option.style.display = "none";
                }

            });

            // Show or hide the no-results container depending on whether any matching title options were found.
            if (noTitleResults) {
                noTitleResults.style.display =
                    visibleTitles === 0 ? "block" : "none";
            }

        });

        // Prevent click events inside the title search input from bubbling up and closing the parent dropdown.
        titleSearchInput.addEventListener("click", function (event) {
            event.stopPropagation();
        });

        // Prevent keyboard keydown events inside the title search input from bubbling up and closing the dropdown.
        titleSearchInput.addEventListener("keydown", function (event) {
            event.stopPropagation();
        });

    });
</script>


<!-- ========================================================================= -->
<!-- DATATABLES INITIALIZATION FOR ALL REPORTS                              -->
<!-- ========================================================================= -->

<script>
    document.addEventListener("DOMContentLoaded", function () {

        // ================================================================
        // CHECK JQUERY
        // ================================================================

        if (typeof jQuery === "undefined") {
            return;
        }

        // ================================================================
        // CHECK DATATABLES
        // ================================================================

        if (typeof jQuery.fn.DataTable === "undefined") {
            return;
        }

        // ================================================================
        // REPORT CONFIGURATION
        // ================================================================

        const reportConfigurations = {

            teamsTable: {
                title: "Team Report",
                filename: "team-report"
            },

            projectsTable: {
                title: "Project Report",
                filename: "project-report"
            },

            tasksTable: {
                title: "Task Report",
                filename: "task-report"
            },

            usersTable: {
                title: "User Report",
                filename: "user-report"
            }

        };

        // ================================================================
        // EMPTY TABLE ICONS
        // ================================================================

        const emptyStateIcons = {

            teamsTable:
                "now-ui-icons users_circle-08",

            projectsTable:
                "now-ui-icons business_briefcase-24",

            tasksTable:
                "now-ui-icons design_bullet-list-67",

            usersTable:
                "now-ui-icons users_single-02"

        };

        // ================================================================
        // EMPTY TABLE TITLES
        // ================================================================

        const emptyStateTitles = {

            teamsTable:
                "No teams available",

            projectsTable:
                "No projects available",

            tasksTable:
                "No tasks available",

            usersTable:
                "No users available"

        };

        // ================================================================
        // EMPTY TABLE DESCRIPTIONS
        // ================================================================

        const emptyStateDescriptions = {

            teamsTable:
                "There is no team data to display at the moment.",

            projectsTable:
                "There is no project data to display at the moment.",

            tasksTable:
                "There is no task data to display at the moment.",

            usersTable:
                "There is no user data to display at the moment."

        };

        // ================================================================
        // CREATE BEAUTIFUL EMPTY STATE
        // ================================================================

        function createEmptyState(tableId) {

            const icon =
                emptyStateIcons[tableId] ||
                "now-ui-icons ui-1_simple-remove";

            const title =
                emptyStateTitles[tableId] ||
                "No data available";

            const description =
                emptyStateDescriptions[tableId] ||
                "There is no data to display at the moment.";

            return `
                <div
                    class="datatable-empty-state"
                    style="
                        padding: 45px 20px;
                        text-align: center;
                        width: 100%;
                    "
                >

                    <div
                        style="
                            width: 64px;
                            height: 64px;
                            margin: 0 auto 16px auto;
                            border-radius: 50%;
                            background: linear-gradient(
                                135deg,
                                #fff1eb 0%,
                                #ffe4d8 100%
                            );
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 6px 18px rgba(249, 99, 50, 0.12);
                        "
                    >

                        <i
                            class="${icon}"
                            style="
                                font-size: 28px;
                                color: #f96332;
                            "
                        ></i>

                    </div>

                    <div
                        style="
                            font-size: 16px;
                            font-weight: 700;
                            color: #32325d;
                            margin-bottom: 6px;
                        "
                    >
                        ${title}
                    </div>

                    <div
                        style="
                            font-size: 13px;
                            color: #8898aa;
                            max-width: 420px;
                            margin: 0 auto;
                            line-height: 1.6;
                        "
                    >
                        ${description}
                    </div>

                </div>
            `;

        }

        // ================================================================
        // USERS EMPTY STATE ELEMENT
        // ================================================================

        const usersTableEmptyState =
            document.getElementById(
                "usersTableEmptyState"
            );

        if (usersTableEmptyState) {

            usersTableEmptyState.innerHTML =
                createEmptyState(
                    "usersTable"
                );

        }

        // ================================================================
        // REMOVE DUPLICATE TEXT
        // ================================================================

        function removeDuplicateText(text) {

            if (!text) {
                return "";
            }

            text =
                text
                    .replace(/\s+/g, " ")
                    .trim();

            // ------------------------------------------------------------
            // Detect exact repeated text.
            // ------------------------------------------------------------

            const words =
                text.split(" ");

            if (words.length >= 4) {

                for (
                    let size = Math.floor(words.length / 2);
                    size >= 2;
                    size--
                ) {

                    const firstPart =
                        words
                            .slice(0, size)
                            .join(" ");

                    const secondPart =
                        words
                            .slice(size, size * 2)
                            .join(" ");

                    if (
                        firstPart.toLowerCase() ===
                        secondPart.toLowerCase()
                    ) {

                        const remaining =
                            words
                                .slice(size * 2)
                                .join(" ");

                        text =
                            firstPart +
                            (
                                remaining
                                    ? " " + remaining
                                    : ""
                            );

                        break;

                    }

                }

            }

            // ------------------------------------------------------------
            // Detect repeated complete sentences.
            // ------------------------------------------------------------

            const sentences =
                text
                    .split(/(?<=[.!?])\s+/)
                    .map(function (sentence) {
                        return sentence.trim();
                    })
                    .filter(Boolean);

            if (sentences.length > 1) {

                const uniqueSentences = [];

                sentences.forEach(
                    function (sentence) {

                        const normalized =
                            sentence
                                .toLowerCase()
                                .replace(/\s+/g, " ")
                                .trim();

                        const alreadyExists =
                            uniqueSentences.some(
                                function (item) {

                                    return (
                                        item
                                            .toLowerCase()
                                            .replace(/\s+/g, " ")
                                            .trim() ===
                                        normalized
                                    );

                                }
                            );

                        if (!alreadyExists) {

                            uniqueSentences.push(
                                sentence
                            );

                        }

                    }
                );

                text =
                    uniqueSentences.join(" ");

            }

            return text.trim();

        }

        // ================================================================
        // GET FULL TEXT FROM MORE BUTTON / MODAL
        // ================================================================

        function getMoreContentText(cell) {

            if (!cell) {
                return "";
            }

            const $cell =
                jQuery(cell);

            // ------------------------------------------------------------
            // Full description elements inside the cell
            // ------------------------------------------------------------

            const fullDescription =
                $cell.find(
                    ".print-full-description, .full-description, .print-description"
                ).first();

            if (fullDescription.length) {

                const fullText =
                    fullDescription
                        .text()
                        .replace(/\s+/g, " ")
                        .trim();

                if (fullText) {

                    return removeDuplicateText(
                        fullText
                    );

                }

            }

            // ------------------------------------------------------------
            // Find More / Read More / Show More button
            // ------------------------------------------------------------

            const moreButton =
                $cell.find(
                    ".more, .read-more, .show-more, [data-toggle='modal'], [data-bs-toggle='modal']"
                ).first();

            if (!moreButton.length) {
                return "";
            }

            // ------------------------------------------------------------
            // Check common data attributes
            // ------------------------------------------------------------

            const dataAttributes = [

                "full-description",
                "description",
                "content",
                "text",
                "more-text"

            ];

            for (
                let i = 0;
                i < dataAttributes.length;
                i++
            ) {

                const value =
                    moreButton.attr(
                        "data-" +
                        dataAttributes[i]
                    );

                if (value) {

                    const cleaned =
                        jQuery("<div>")
                            .html(value)
                            .text()
                            .replace(/\s+/g, " ")
                            .trim();

                    if (cleaned) {

                        return removeDuplicateText(
                            cleaned
                        );

                    }

                }

            }

            // ------------------------------------------------------------
            // Find modal target
            // ------------------------------------------------------------

            let modalSelector =
                moreButton.attr("data-target") ||
                moreButton.attr("data-bs-target");

            if (!modalSelector) {

                const href =
                    moreButton.attr("href");

                if (
                    href &&
                    href.charAt(0) === "#"
                ) {

                    modalSelector =
                        href;

                }

            }

            // ------------------------------------------------------------
            // Read modal content
            // ------------------------------------------------------------

            if (modalSelector) {

                try {

                    const $modal =
                        jQuery(
                            modalSelector
                        );

                    if ($modal.length) {

                        let $content =
                            $modal.find(
                                ".modal-body"
                            ).first();

                        if (!$content.length) {

                            $content =
                                $modal.find(
                                    ".modal-content"
                                ).first();

                        }

                        if ($content.length) {

                            const modalClone =
                                $content.clone();

                            modalClone
                                .find(
                                    "button, .btn, .close, .modal-footer"
                                )
                                .remove();

                            const modalText =
                                modalClone
                                    .text()
                                    .replace(/\s+/g, " ")
                                    .trim();

                            if (modalText) {

                                return removeDuplicateText(
                                    modalText
                                );

                            }

                        }

                    }

                } catch (error) {

                    // Ignore invalid modal selectors.

                }

            }

            // ------------------------------------------------------------
            // Search related modal using button id
            // ------------------------------------------------------------

            const buttonId =
                moreButton.attr("id");

            if (buttonId) {

                const $relatedModal =
                    jQuery(
                        ".modal[aria-labelledby='" +
                        buttonId +
                        "'], " +
                        ".modal[data-more-button='" +
                        buttonId +
                        "']"
                    ).first();

                if ($relatedModal.length) {

                    let $content =
                        $relatedModal.find(
                            ".modal-body"
                        ).first();

                    if (!$content.length) {

                        $content =
                            $relatedModal.find(
                                ".modal-content"
                            ).first();

                    }

                    if ($content.length) {

                        const modalClone =
                            $content.clone();

                        modalClone
                            .find(
                                "button, .btn, .close, .modal-footer"
                            )
                            .remove();

                        const modalText =
                            modalClone
                                .text()
                                .replace(/\s+/g, " ")
                                .trim();

                        if (modalText) {

                            return removeDuplicateText(
                                modalText
                            );

                        }

                    }

                }

            }

            // ------------------------------------------------------------
            // Search nearby modal if the button contains a modal target
            // in another common attribute
            // ------------------------------------------------------------

            const modalId =
                moreButton.attr("data-modal-id") ||
                moreButton.attr("data-description-id") ||
                moreButton.attr("data-target-id");

            if (modalId) {

                const $modal =
                    jQuery(
                        "#" + modalId
                    ).first();

                if ($modal.length) {

                    let $content =
                        $modal.find(
                            ".modal-body"
                        ).first();

                    if (!$content.length) {

                        $content =
                            $modal.find(
                                ".modal-content"
                            ).first();

                    }

                    if ($content.length) {

                        const modalClone =
                            $content.clone();

                        modalClone
                            .find(
                                "button, .btn, .close, .modal-footer"
                            )
                            .remove();

                        const modalText =
                            modalClone
                                .text()
                                .replace(/\s+/g, " ")
                                .trim();

                        if (modalText) {

                            return removeDuplicateText(
                                modalText
                            );

                        }

                    }

                }

            }

            return "";

        }

        // ================================================================
        // CLEAN CELL TEXT
        // ================================================================

        function cleanCellText(cell) {

            if (!cell) {
                return "";
            }

            const $cell =
                jQuery(cell);

            // ------------------------------------------------------------
            // IMPORTANT: Get complete More content first
            // ------------------------------------------------------------

            const moreContent =
                getMoreContentText(cell);

            if (moreContent) {

                return moreContent;

            }

            // ------------------------------------------------------------
            // Full description
            // ------------------------------------------------------------

            const fullDescription =
                $cell.find(
                    ".print-full-description, .full-description, .print-description"
                ).first();

            if (fullDescription.length) {

                const fullText =
                    fullDescription
                        .text()
                        .replace(/\s+/g, " ")
                        .trim();

                if (fullText) {

                    return removeDuplicateText(
                        fullText
                    );

                }

            }

            // ------------------------------------------------------------
            // Clone cell
            // ------------------------------------------------------------

            const clone =
                cell.cloneNode(true);

            // ------------------------------------------------------------
            // Remove hidden / interactive elements
            // ------------------------------------------------------------

            jQuery(clone)
                .find(
                    ".d-print-none, .d-none, .print-only, .print-full-description, .full-description, .print-description, button, .modal, .more, .read-more, .show-more"
                )
                .remove();

            // ------------------------------------------------------------
            // Get visible text
            // ------------------------------------------------------------

            let text =
                jQuery(clone)
                    .text()
                    .replace(/\s+/g, " ")
                    .trim();

            // ------------------------------------------------------------
            // Remove trailing ellipsis
            // ------------------------------------------------------------

            text =
                text.replace(
                    /\s*\.{3,}\s*$/g,
                    ""
                );

            // ------------------------------------------------------------
            // Remove duplicate text
            // ------------------------------------------------------------

            text =
                removeDuplicateText(
                    text
                );

            return text;

        }

        // ================================================================
        // REPORT DATE COLUMN DETECTION
        // ================================================================

        function isReportDateColumn(
            tableId,
            column
        ) {

            const header =
                jQuery(
                    "#" +
                    tableId +
                    " thead th"
                )
                    .eq(column)
                    .text()
                    .replace(/\s+/g, " ")
                    .trim()
                    .toLowerCase();

            // ------------------------------------------------------------
            // Projects
            // ------------------------------------------------------------

            if (tableId === "projectsTable") {

                return (
                    header === "start date" ||
                    header === "end date" ||
                    header.includes("start date") ||
                    header.includes("end date")
                );

            }

            // ------------------------------------------------------------
            // Users
            // ------------------------------------------------------------

            if (tableId === "usersTable") {

                return (
                    header === "joined date" ||
                    header.includes("joined date")
                );

            }

            return false;

        }

        // ================================================================
        // GET VISIBLE DATE COLUMNS IN EXCEL ORDER
        // ================================================================

        function getExcelDateColumns(tableId) {

            const dateColumns = [];

            const headers =
                jQuery(
                    "#" +
                    tableId +
                    " thead th"
                );

            let exportColumnIndex = 1;

            headers.each(
                function (sourceIndex) {

                    const $header =
                        jQuery(this);

                    if (!$header.is(":visible")) {

                        return;

                    }

                    if (
                        isReportDateColumn(
                            tableId,
                            sourceIndex
                        )
                    ) {

                        dateColumns.push(
                            exportColumnIndex
                        );

                    }

                    exportColumnIndex++;

                }
            );

            return dateColumns;

        }

        // ================================================================
        // EXCEL COLUMN LETTERS TO NUMBER
        // ================================================================

        function columnLettersToNumber(letters) {

            let number = 0;

            for (
                let i = 0;
                i < letters.length;
                i++
            ) {

                number =
                    number * 26 +
                    (
                        letters.charCodeAt(i) -
                        64
                    );

            }

            return number;

        }

      function initializeReport(
    tableId,
    configuration
) {

    const table =
        jQuery(
            "#" + tableId
        );

    // ------------------------------------------------------------
    // Table does not exist on this page.
    // ------------------------------------------------------------

    if (!table.length) {
        return;
    }

    // ============================================================
    // REMOVE BLADE EMPTY ROW BEFORE DATATABLE INITIALIZATION
    // ============================================================
    // Blade uses a single TD with colspan when the table is empty.
    // DataTables expects the number of TD elements to match the
    // number of TH elements, so remove the Blade empty row and
    // let DataTables display its own empty state.
    // ============================================================

    table.find("tbody tr").each(function () {

        const $row = jQuery(this);

        const $cells = $row.children("td, th");

        if (
            $cells.length === 1 &&
            $cells.first().attr("colspan")
        ) {
            $row.remove();
        }

    });

    // ============================================================
    // IMPORTANT FIX
    // ============================================================
    //
    // Management tables and Report tables use the same IDs.
    //
    // Management tables contain:
    //
    //      #tableHeaders
    //
    // because their headers support dragging/reordering.
    //
    // Therefore:
    //
    //      Management table = DO NOT initialize DataTables
    //
    //      Report table     = initialize DataTables normally
    //
    // ============================================================

    if (
        table.find(
            "#tableHeaders"
        ).length
    ) {

        return;

    }

    // ------------------------------------------------------------
    // Already initialized.
    // ------------------------------------------------------------

    if (
        jQuery.fn.DataTable.isDataTable(
            "#" + tableId
        )
    ) {

        return;

    }

    // ============================================================
    // DATATABLE
    // ============================================================

    table.DataTable({

        paging: false,

        searching: false,

        ordering: false,

        info: false,

        lengthChange: false,

        dom: "Brt",

        language: {

            emptyTable:
                createEmptyState(
                    tableId
                ),

            zeroRecords:
                createEmptyState(
                    tableId
                )

        },

                // ========================================================
                // EMPTY TABLE DISPLAY
                // ========================================================

                language: {

                    emptyTable:
                        createEmptyState(
                            tableId
                        ),

                    zeroRecords:
                        createEmptyState(
                            tableId
                        )

                },

                // ========================================================
                // BUTTONS
                // ========================================================

                buttons: [

                    // ====================================================
                    // EXCEL
                    // ====================================================

                    {

                        extend: "excelHtml5",

                        title:
                            configuration.title,

                        filename:
                            configuration.filename,

                        exportOptions: {

                            columns:
                                ":visible",

                            format: {

                                body: function (
                                    data,
                                    row,
                                    column,
                                    node
                                ) {

                                    return cleanCellText(
                                        node
                                    );

                                }

                            }

                        },

                        // =================================================
                        // EXCEL CUSTOMIZATION
                        // =================================================

                        customize:
                            function (xlsx) {

                                const sheet =
                                    xlsx.xl.worksheets[
                                        "sheet1.xml"
                                    ];

                                const styles =
                                    xlsx.xl[
                                        "styles.xml"
                                    ];

                                const $sheet =
                                    jQuery(sheet);

                                const $styles =
                                    jQuery(styles);

                                const $fonts =
                                    $styles.find(
                                        "fonts"
                                    );

                                const $fills =
                                    $styles.find(
                                        "fills"
                                    );

                                const $borders =
                                    $styles.find(
                                        "borders"
                                    );

                                const $cellXfs =
                                    $styles.find(
                                        "cellXfs"
                                    );

                                // =================================================
                                // DATE COLUMNS
                                // =================================================

                                const excelDateColumns =
                                    getExcelDateColumns(
                                        tableId
                                    );

                                let fontCount =
                                    parseInt(
                                        $fonts.attr(
                                            "count"
                                        ) ||
                                        $fonts.children().length,
                                        10
                                    );

                                let fillCount =
                                    parseInt(
                                        $fills.attr(
                                            "count"
                                        ) ||
                                        $fills.children().length,
                                        10
                                    );

                                let borderCount =
                                    parseInt(
                                        $borders.attr(
                                            "count"
                                        ) ||
                                        $borders.children().length,
                                        10
                                    );

                                let xfCount =
                                    parseInt(
                                        $cellXfs.attr(
                                            "count"
                                        ) ||
                                        $cellXfs.children().length,
                                        10
                                    );

                                const regularFontId =
                                    fontCount;

                                $fonts.append(
                                    '<font>' +
                                        '<sz val="10"/>' +
                                        '<name val="Arial"/>' +
                                        '<family val="2"/>' +
                                        '<color rgb="FF000000"/>' +
                                    '</font>'
                                );

                                fontCount++;

                                const headerFontId =
                                    fontCount;

                                $fonts.append(
                                    '<font>' +
                                        '<b/>' +
                                        '<sz val="10"/>' +
                                        '<name val="Arial"/>' +
                                        '<family val="2"/>' +
                                        '<color rgb="FFFFFFFF"/>' +
                                    '</font>'
                                );

                                fontCount++;

                                const titleFontId =
                                    fontCount;

                                $fonts.append(
                                    '<font>' +
                                        '<b/>' +
                                        '<sz val="11"/>' +
                                        '<name val="Arial"/>' +
                                        '<family val="2"/>' +
                                        '<color rgb="FFFFFFFF"/>' +
                                    '</font>'
                                );

                                fontCount++;

                                const orangeFillId =
                                    fillCount;

                                $fills.append(
                                    '<fill>' +
                                        '<patternFill patternType="solid">' +
                                            '<fgColor rgb="FFFF7043"/>' +
                                            '<bgColor indexed="64"/>' +
                                        '</patternFill>' +
                                    '</fill>'
                                );

                                fillCount++;

                                const darkOrangeFillId =
                                    fillCount;

                                $fills.append(
                                    '<fill>' +
                                        '<patternFill patternType="solid">' +
                                            '<fgColor rgb="FFE85D2A"/>' +
                                            '<bgColor indexed="64"/>' +
                                        '</patternFill>' +
                                    '</fill>'
                                );

                                fillCount++;

                                const whiteFillId =
                                    fillCount;

                                $fills.append(
                                    '<fill>' +
                                        '<patternFill patternType="solid">' +
                                            '<fgColor rgb="FFFFFFFF"/>' +
                                            '<bgColor indexed="64"/>' +
                                        '</patternFill>' +
                                    '</fill>'
                                );

                                fillCount++;

                                const grayFillId =
                                    fillCount;

                                $fills.append(
                                    '<fill>' +
                                        '<patternFill patternType="solid">' +
                                            '<fgColor rgb="FFF7F9FA"/>' +
                                            '<bgColor indexed="64"/>' +
                                        '</patternFill>' +
                                    '</fill>'
                                );

                                fillCount++;

                                const borderId =
                                    borderCount;

                                $borders.append(
                                    '<border>' +
                                        '<left style="thin"><color rgb="FFD5DBE0"/></left>' +
                                        '<right style="thin"><color rgb="FFD5DBE0"/></right>' +
                                        '<top style="thin"><color rgb="FFD5DBE0"/></top>' +
                                        '<bottom style="thin"><color rgb="FFD5DBE0"/></bottom>' +
                                    '</border>'
                                );

                                borderCount++;

                                const titleXfId =
                                    xfCount;

                                $cellXfs.append(
                                    '<xf numFmtId="0" fontId="' +
                                        titleFontId +
                                        '" fillId="' +
                                        darkOrangeFillId +
                                        '" borderId="' +
                                        borderId +
                                        '" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">' +
                                        '<alignment horizontal="center" vertical="center" wrapText="1"/>' +
                                    '</xf>'
                                );

                                xfCount++;

                                const headerXfId =
                                    xfCount;

                                $cellXfs.append(
                                    '<xf numFmtId="0" fontId="' +
                                        headerFontId +
                                        '" fillId="' +
                                        orangeFillId +
                                        '" borderId="' +
                                        borderId +
                                        '" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">' +
                                        '<alignment horizontal="center" vertical="center" wrapText="1"/>' +
                                    '</xf>'
                                );

                                xfCount++;

                                const bodyWhiteXfId =
                                    xfCount;

                                $cellXfs.append(
                                    '<xf numFmtId="0" fontId="' +
                                        regularFontId +
                                        '" fillId="' +
                                        whiteFillId +
                                        '" borderId="' +
                                        borderId +
                                        '" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">' +
                                        '<alignment horizontal="center" vertical="center" wrapText="1"/>' +
                                    '</xf>'
                                );

                                xfCount++;

                                const bodyGrayXfId =
                                    xfCount;

                                $cellXfs.append(
                                    '<xf numFmtId="0" fontId="' +
                                        regularFontId +
                                        '" fillId="' +
                                        grayFillId +
                                        '" borderId="' +
                                        borderId +
                                        '" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">' +
                                        '<alignment horizontal="center" vertical="center" wrapText="1"/>' +
                                    '</xf>'
                                );

                                xfCount++;

                                // =================================================
                                // CREATE EXCEL DATE FORMAT
                                // =================================================

                                const $numFmts =
                                    $styles.find(
                                        "numFmts"
                                    );

                                let dateNumFmtId =
                                    164;

                                if ($numFmts.length) {

                                    const existingNumFmtIds =
                                        $numFmts
                                            .find(
                                                "numFmt"
                                            )
                                            .map(
                                                function () {

                                                    return parseInt(
                                                        jQuery(this).attr(
                                                            "numFmtId"
                                                        ),
                                                        10
                                                    );

                                                }
                                            )
                                            .get();

                                    while (
                                        existingNumFmtIds.includes(
                                            dateNumFmtId
                                        )
                                    ) {

                                        dateNumFmtId++;

                                    }

                                    $numFmts.append(
                                        '<numFmt numFmtId="' +
                                            dateNumFmtId +
                                            '" formatCode="d-m-yyyy"/>'
                                    );

                                    $numFmts.attr(
                                        "count",
                                        $numFmts.find(
                                            "numFmt"
                                        ).length
                                    );

                                } else {

                                    const $newNumFmts =
                                        jQuery(
                                            '<numFmts count="1">' +
                                                '<numFmt numFmtId="' +
                                                    dateNumFmtId +
                                                    '" formatCode="d-m-yyyy"/>' +
                                            '</numFmts>'
                                        );

                                    $styles
                                        .find(
                                            "fonts"
                                        )
                                        .before(
                                            $newNumFmts
                                        );

                                }

                                // =================================================
                                // DATE WHITE STYLE
                                // =================================================

                                const dateWhiteXfId =
                                    xfCount;

                                $cellXfs.append(
                                    '<xf numFmtId="' +
                                        dateNumFmtId +
                                        '" fontId="' +
                                        regularFontId +
                                        '" fillId="' +
                                        whiteFillId +
                                        '" borderId="' +
                                        borderId +
                                        '" xfId="0" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">' +
                                        '<alignment horizontal="center" vertical="center" wrapText="1"/>' +
                                    '</xf>'
                                );

                                xfCount++;

                                // =================================================
                                // DATE GRAY STYLE
                                // =================================================

                                const dateGrayXfId =
                                    xfCount;

                                $cellXfs.append(
                                    '<xf numFmtId="' +
                                        dateNumFmtId +
                                        '" fontId="' +
                                        regularFontId +
                                        '" fillId="' +
                                        grayFillId +
                                        '" borderId="' +
                                        borderId +
                                        '" xfId="0" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">' +
                                        '<alignment horizontal="center" vertical="center" wrapText="1"/>' +
                                    '</xf>'
                                );

                                xfCount++;

                                $fonts.attr(
                                    "count",
                                    fontCount
                                );

                                $fills.attr(
                                    "count",
                                    fillCount
                                );

                                $borders.attr(
                                    "count",
                                    borderCount
                                );

                                $cellXfs.attr(
                                    "count",
                                    xfCount
                                );

                                function getExcelCellText(
                                    cell
                                ) {

                                    const $cell =
                                        jQuery(cell);

                                    const type =
                                        $cell.attr(
                                            "t"
                                        );

                                    if (
                                        type === "s"
                                    ) {

                                        const value =
                                            $cell
                                                .find(
                                                    "v"
                                                )
                                                .first()
                                                .text();

                                        if (
                                            value !== ""
                                        ) {

                                            const index =
                                                parseInt(
                                                    value,
                                                    10
                                                );

                                            const sharedStrings =
                                                xlsx.xl[
                                                    "sharedStrings.xml"
                                                ];

                                            if (
                                                sharedStrings
                                            ) {

                                                const $shared =
                                                    jQuery(
                                                        sharedStrings
                                                    );

                                                const items =
                                                    $shared.find(
                                                        "si"
                                                    );

                                                if (
                                                    items.length &&
                                                    items.eq(
                                                        index
                                                    ).length
                                                ) {

                                                    return items
                                                        .eq(
                                                            index
                                                        )
                                                        .find(
                                                            "t"
                                                        )
                                                        .map(
                                                            function () {

                                                                return jQuery(
                                                                    this
                                                                ).text();

                                                            }
                                                        )
                                                        .get()
                                                        .join("");

                                                }

                                            }

                                        }

                                    }

                                    const inlineText =
                                        $cell
                                            .find(
                                                "is t"
                                            )
                                            .map(
                                                function () {

                                                    return jQuery(
                                                        this
                                                    ).text();

                                                }
                                            )
                                            .get()
                                            .join("");

                                    if (
                                        inlineText
                                    ) {

                                        return inlineText;

                                    }

                                    return (
                                        $cell
                                            .find(
                                                "v"
                                            )
                                            .first()
                                            .text() ||
                                        ""
                                    );

                                }

                                $sheet
                                    .find(
                                        "row"
                                    )
                                    .each(
                                        function () {

                                            const $row =
                                                jQuery(
                                                    this
                                                );

                                            const excelRow =
                                                parseInt(
                                                    $row.attr(
                                                        "r"
                                                    ),
                                                    10
                                                );

                                            if (
                                                excelRow ===
                                                1
                                            ) {

                                                $row.attr(
                                                    "customHeight",
                                                    "1"
                                                );

                                                $row.attr(
                                                    "ht",
                                                    "30"
                                                );

                                                $row.find(
                                                    "c"
                                                ).attr(
                                                    "s",
                                                    titleXfId
                                                );

                                                return;

                                            }

                                            if (
                                                excelRow ===
                                                2
                                            ) {

                                                $row.attr(
                                                    "customHeight",
                                                    "1"
                                                );

                                                $row.attr(
                                                    "ht",
                                                    "28"
                                                );

                                                $row.find(
                                                    "c"
                                                ).attr(
                                                    "s",
                                                    headerXfId
                                                );

                                                return;

                                            }

                                            const bodyIndex =
                                                excelRow -
                                                3;

                                            const styleId =
                                                bodyIndex %
                                                2 ===
                                                0
                                                    ? bodyWhiteXfId
                                                    : bodyGrayXfId;

                                            // =================================================
                                            // APPLY DATE STYLE ONLY TO DATE COLUMNS
                                            // =================================================

                                            $row
                                                .find(
                                                    "c"
                                                )
                                                .each(
                                                    function () {

                                                        const $cell =
                                                            jQuery(
                                                                this
                                                            );

                                                        const reference =
                                                            $cell.attr(
                                                                "r"
                                                            ) ||
                                                            "";

                                                        const letters =
                                                            reference.replace(
                                                                /[0-9]/g,
                                                                ""
                                                            );

                                                        const columnNumber =
                                                            columnLettersToNumber(
                                                                letters
                                                            );

                                                        if (
                                                            excelDateColumns.includes(
                                                                columnNumber
                                                            )
                                                        ) {

                                                            $cell.attr(
                                                                "s",
                                                                bodyIndex %
                                                                    2 ===
                                                                    0
                                                                    ? dateWhiteXfId
                                                                    : dateGrayXfId
                                                            );

                                                        } else {

                                                            $cell.attr(
                                                                "s",
                                                                styleId
                                                            );

                                                        }

                                                    }
                                                );

                                        }
                                    );

                                const columnLengths =
                                    {};

                                $sheet
                                    .find(
                                        "row"
                                    )
                                    .each(
                                        function () {

                                            const $row =
                                                jQuery(
                                                    this
                                                );

                                            $row
                                                .find(
                                                    "c"
                                                )
                                                .each(
                                                    function () {

                                                        const $cell =
                                                            jQuery(
                                                                this
                                                            );

                                                        const reference =
                                                            $cell.attr(
                                                                "r"
                                                            ) ||
                                                            "";

                                                        const letters =
                                                            reference.replace(
                                                                /[0-9]/g,
                                                                ""
                                                            );

                                                        if (!letters) {
                                                            return;
                                                        }

                                                        const columnNumber =
                                                            columnLettersToNumber(
                                                                letters
                                                            );

                                                        const text =
                                                            getExcelCellText(
                                                                this
                                                            )
                                                                .replace(
                                                                    /\s+/g,
                                                                    " "
                                                                )
                                                                .trim();

                                                        const length =
                                                            text.length;

                                                        if (
                                                            !columnLengths[
                                                                columnNumber
                                                            ]
                                                        ) {

                                                            columnLengths[
                                                                columnNumber
                                                            ] =
                                                                0;

                                                        }

                                                        if (
                                                            length >
                                                            columnLengths[
                                                                columnNumber
                                                            ]
                                                        ) {

                                                            columnLengths[
                                                                columnNumber
                                                            ] =
                                                                length;

                                                        }

                                                    }
                                                );

                                        }
                                    );

                                const $cols =
                                    $sheet.find(
                                        "cols"
                                    ).length
                                        ? $sheet.find(
                                            "cols"
                                        )
                                        : jQuery(
                                            "<cols></cols>"
                                        );

                                if (
                                    !$cols.parent().length
                                ) {

                                    $sheet
                                        .find(
                                            "sheetData"
                                        )
                                        .before(
                                            $cols
                                        );

                                }

                                $cols.empty();

                                let totalColumns =
                                    0;

                                $sheet
                                    .find(
                                        "row"
                                    )
                                    .each(
                                        function () {

                                            jQuery(
                                                this
                                            )
                                                .find(
                                                    "c"
                                                )
                                                .each(
                                                    function () {

                                                        const reference =
                                                            jQuery(
                                                                this
                                                            ).attr(
                                                                "r"
                                                            ) ||
                                                            "";

                                                        const letters =
                                                            reference.replace(
                                                                /[0-9]/g,
                                                                ""
                                                            );

                                                        if (
                                                            letters
                                                        ) {

                                                            const number =
                                                                columnLettersToNumber(
                                                                    letters
                                                                );

                                                            if (
                                                                number >
                                                                totalColumns
                                                            ) {

                                                                totalColumns =
                                                                    number;

                                                            }

                                                        }

                                                    }
                                                );

                                        }
                                    );

                                for (
                                    let i = 1;
                                    i <=
                                    totalColumns;
                                    i++
                                ) {

                                    const maxLength =
                                        columnLengths[
                                            i
                                        ] ||
                                        10;

                                    let minWidth =
                                        15;

                                    let maxWidth =
                                        55;

                                    if (i === 2) {

                                        minWidth =
                                            22;

                                        maxWidth =
                                            75;

                                    }

                                    if (i === 3) {

                                        minWidth =
                                            20;

                                        maxWidth =
                                            50;

                                    }

                                    if (i === 4) {

                                        minWidth =
                                            18;

                                        maxWidth =
                                            45;

                                    }

                                    if (i === 5) {

                                        minWidth =
                                            18;

                                        maxWidth =
                                            55;

                                    }

                                    if (i === 6) {

                                        minWidth =
                                            20;

                                        maxWidth =
                                            30;

                                    }

                                    // ------------------------------------------------
                                    // Date columns need enough width for d-m-yyyy
                                    // ------------------------------------------------

                                    if (
                                        excelDateColumns.includes(
                                            i
                                        )
                                    ) {

                                        minWidth =
                                            15;

                                        maxWidth =
                                            Math.max(
                                                maxWidth,
                                                18
                                            );

                                    }

                                    let width =
                                        maxLength +
                                        4;

                                    width =
                                        Math.max(
                                            minWidth,
                                            width
                                        );

                                    width =
                                        Math.min(
                                            maxWidth,
                                            width
                                        );

                                    $cols.append(
                                        '<col min="' +
                                            i +
                                            '" max="' +
                                            i +
                                            '" width="' +
                                            width +
                                            '" bestFit="1" customWidth="1"/>'
                                    );

                                }

                                $sheet
                                    .find(
                                        "row"
                                    )
                                    .each(
                                        function () {

                                            const $row =
                                                jQuery(
                                                    this
                                                );

                                            const excelRow =
                                                parseInt(
                                                    $row.attr(
                                                        "r"
                                                    ),
                                                    10
                                                );

                                            if (
                                                excelRow <=
                                                2
                                            ) {

                                                return;

                                            }

                                            let requiredLines =
                                                1;

                                            $row
                                                .find(
                                                    "c"
                                                )
                                                .each(
                                                    function () {

                                                        const $cell =
                                                            jQuery(
                                                                this
                                                            );

                                                        const reference =
                                                            $cell.attr(
                                                                "r"
                                                            ) ||
                                                            "";

                                                        const letters =
                                                            reference.replace(
                                                                /[0-9]/g,
                                                                ""
                                                            );

                                                        if (!letters) {
                                                            return;
                                                        }

                                                        const column =
                                                            columnLettersToNumber(
                                                                letters
                                                            );

                                                        const text =
                                                            getExcelCellText(
                                                                this
                                                            )
                                                                .replace(
                                                                    /\r?\n/g,
                                                                    " "
                                                                )
                                                                .trim();

                                                        if (!text) {
                                                            return;
                                                        }

                                                        const width =
                                                            Math.max(
                                                                15,
                                                                Math.min(
                                                                    100,
                                                                    (
                                                                        columnLengths[
                                                                            column
                                                                        ] ||
                                                                        15
                                                                    ) +
                                                                    4
                                                                )
                                                            );

                                                        const estimatedLines =
                                                            Math.max(
                                                                1,
                                                                Math.ceil(
                                                                    text.length /
                                                                    Math.max(
                                                                        10,
                                                                        width -
                                                                            2
                                                                    )
                                                                )
                                                            );

                                                        if (
                                                            estimatedLines >
                                                            requiredLines
                                                        ) {

                                                            requiredLines =
                                                                estimatedLines;

                                                        }

                                                    }
                                                );

                                            requiredLines =
                                                Math.max(
                                                    1,
                                                    Math.min(
                                                        requiredLines,
                                                        25
                                                    )
                                                );

                                            const rowHeight =
                                                Math.min(
                                                    350,
                                                    Math.max(
                                                        30,
                                                        18 *
                                                            requiredLines +
                                                            8
                                                    )
                                                );

                                            $row.attr(
                                                "customHeight",
                                                "1"
                                            );

                                            $row.attr(
                                                "ht",
                                                rowHeight
                                            );

                                        }
                                    );

                            }

                    },

                    // ====================================================
                    // PDF
                    // ====================================================

                    {

                        extend:
                            "pdfHtml5",

                        title:
                            configuration.title,

                        filename:
                            configuration.filename,

                        orientation:
                            "landscape",

                        pageSize:
                            "A4",

                        exportOptions: {

                            columns:
                                ":visible",

                            format: {

                                body:
                                    function (
                                        data,
                                        row,
                                        column,
                                        node
                                    ) {

                                        return cleanCellText(
                                            node
                                        );

                                    }

                            }

                        },

                        // =================================================
                        // PDF CUSTOMIZATION
                        // =================================================

                        customize:
                            function (doc) {

                                doc.pageMargins = [
                                    20,
                                    30,
                                    20,
                                    30
                                ];

                                doc.defaultStyle = {

                                    fontSize:
                                        8,

                                    alignment:
                                        "center"

                                };

                                doc.styles.tableHeader = {

                                    fontSize:
                                        8.5,

                                    bold:
                                        true,

                                    color:
                                        "#ffffff",

                                    fillColor:
                                        "#ff7043",

                                    alignment:
                                        "center",

                                    margin: [
                                        3,
                                        5,
                                        3,
                                        5
                                    ],

                                    noWrap:
                                        false

                                };

                                // ------------------------------------------------
                                // Locate PDF table
                                // ------------------------------------------------

                                let pdfContentIndex =
                                    -1;

                                let pdfTable =
                                    null;

                                if (
                                    doc.content &&
                                    doc.content.length
                                ) {

                                    doc.content.forEach(
                                        function (
                                            content,
                                            index
                                        ) {

                                            if (
                                                content.table
                                            ) {

                                                pdfTable =
                                                    content.table;

                                                pdfContentIndex =
                                                    index;

                                            }

                                        }
                                    );

                                }

                                if (
                                    pdfTable &&
                                    pdfTable.body &&
                                    pdfTable.body.length
                                ) {

                                    const columnCount =
                                        pdfTable.body[
                                            0
                                        ].length;

                                    // =================================================
                                    // PDF CELL TEXT HELPER
                                    // =================================================

                                    function getPdfCellText(
                                        cell
                                    ) {

                                        if (
                                            typeof cell ===
                                            "string"
                                        ) {

                                            return cell;

                                        }

                                        if (
                                            cell &&
                                            typeof cell.text ===
                                            "string"
                                        ) {

                                            return cell.text;

                                        }

                                        return "";

                                    }

                                    // =================================================
                                    // PREPARE HEADER
                                    // =================================================

                                    const headerRow =
                                        pdfTable.body[
                                            0
                                        ].map(
                                            function (cell) {

                                                const text =
                                                    getPdfCellText(
                                                        cell
                                                    )
                                                        .replace(
                                                            /\s+/g,
                                                            " "
                                                        )
                                                        .trim();

                                                return {

                                                    text:
                                                        text,

                                                    fillColor:
                                                        "#ff7043",

                                                    color:
                                                        "#ffffff",

                                                    bold:
                                                        true,

                                                    alignment:
                                                        "center",

                                                    valign:
                                                        "middle",

                                                    noWrap:
                                                        false,

                                                    margin: [
                                                        3,
                                                        5,
                                                        3,
                                                        5
                                                    ]

                                                };

                                            }
                                        );

                                    // =================================================
                                    // PREPARE BODY ROWS
                                    // =================================================

                                    const dataRows =
                                        [];

                                    for (
                                        let rowIndex = 1;
                                        rowIndex <
                                        pdfTable.body.length;
                                        rowIndex++
                                    ) {

                                        const sourceRow =
                                            pdfTable.body[
                                                rowIndex
                                            ];

                                        const outputRow =
                                            sourceRow.map(
                                                function (
                                                    cell
                                                ) {

                                                    return {

                                                        text:
                                                            removeDuplicateText(
                                                                getPdfCellText(
                                                                    cell
                                                                )
                                                                    .replace(
                                                                        /\s+/g,
                                                                        " "
                                                                    )
                                                                    .trim()
                                                            ),

                                                        alignment:
                                                            "center",

                                                        valign:
                                                            "middle",

                                                        noWrap:
                                                            false,

                                                        margin: [
                                                            3,
                                                            5,
                                                            3,
                                                            5
                                                        ]

                                                    };

                                                }
                                            );

                                        if (
                                            rowIndex %
                                                2 ===
                                            0
                                        ) {

                                            outputRow.forEach(
                                                function (
                                                    cell
                                                ) {

                                                    cell.fillColor =
                                                        "#f7f9fa";

                                                }
                                            );

                                        } else {

                                            outputRow.forEach(
                                                function (
                                                    cell
                                                ) {

                                                    cell.fillColor =
                                                        "#ffffff";

                                                }
                                            );

                                        }

                                        dataRows.push(
                                            outputRow
                                        );

                                    }

                                    // =================================================
                                    // CALCULATE COLUMN WIDTHS FROM ACTUAL CONTENT
                                    // =================================================

                                    const columnLengths =
                                        Array(
                                            columnCount
                                        ).fill(
                                            1
                                        );

                                    [
                                        headerRow
                                    ]
                                    .concat(
                                        dataRows
                                    )
                                    .forEach(
                                        function (
                                            row
                                        ) {

                                            row.forEach(
                                                function (
                                                    cell,
                                                    index
                                                ) {

                                                    const text =
                                                        getPdfCellText(
                                                            cell
                                                        )
                                                            .replace(
                                                                /\s+/g,
                                                                " "
                                                            )
                                                            .trim();

                                                    if (
                                                        text.length >
                                                        columnLengths[
                                                            index
                                                        ]
                                                    ) {

                                                        columnLengths[
                                                            index
                                                        ] =
                                                            text.length;

                                                    }

                                                }
                                            );

                                        }
                                    );

                                    // =================================================
                                    // CREATE SMART WIDTH WEIGHTS
                                    // =================================================

                                    const widthWeights =
                                        columnLengths.map(
                                            function (
                                                length,
                                                index
                                            ) {

                                                let weight =
                                                    Math.sqrt(
                                                        Math.max(
                                                            8,
                                                            length
                                                        )
                                                    );

                                                if (
                                                    index ===
                                                    1
                                                ) {

                                                    weight *=
                                                        1.45;

                                                }

                                                if (
                                                    length >
                                                    70
                                                ) {

                                                    weight *=
                                                        1.25;

                                                }

                                                return Math.max(
                                                    1,
                                                    weight
                                                );

                                            }
                                        );

                                    const totalWeight =
                                        widthWeights.reduce(
                                            function (
                                                total,
                                                value
                                            ) {

                                                return (
                                                    total +
                                                    value
                                                );

                                            },
                                            0
                                        );

                                    let pdfWidths =
                                        widthWeights.map(
                                            function (
                                                weight
                                            ) {

                                                return (
                                                    weight /
                                                    totalWeight
                                                ) *
                                                100;

                                            }
                                        );

                                    // =================================================
                                    // KEEP VERY SHORT COLUMNS FROM BECOMING TOO WIDE
                                    // =================================================

                                    const minimumPercent =
                                        columnCount >=
                                        8
                                            ? 7
                                            : 9;

                                    const maximumPercent =
                                        columnCount >=
                                        8
                                            ? 25
                                            : 32;

                                    pdfWidths =
                                        pdfWidths.map(
                                            function (
                                                width,
                                                index
                                            ) {

                                                const length =
                                                    columnLengths[
                                                        index
                                                    ] ||
                                                    1;

                                                if (
                                                    length <=
                                                    12
                                                ) {

                                                    return Math.max(
                                                        7,
                                                        Math.min(
                                                            minimumPercent,
                                                            width
                                                        )
                                                    );

                                                }

                                                return Math.max(
                                                    minimumPercent,
                                                    Math.min(
                                                        maximumPercent,
                                                        width
                                                    )
                                                );

                                            }
                                        );

                                    // ------------------------------------------------
                                    // Re-normalize to exactly 100%
                                    // ------------------------------------------------

                                    const adjustedTotal =
                                        pdfWidths.reduce(
                                            function (
                                                total,
                                                value
                                            ) {

                                                return (
                                                    total +
                                                    value
                                                );

                                            },
                                            0
                                        );

                                    pdfWidths =
                                        pdfWidths.map(
                                            function (
                                                width
                                            ) {

                                                return (
                                                    width /
                                                    adjustedTotal
                                                ) *
                                                100;

                                            }
                                        );

                                    pdfWidths =
                                        pdfWidths.map(
                                            function (
                                                width
                                            ) {

                                                return (
                                                    width.toFixed(
                                                        2
                                                    ) +
                                                    "%"
                                                );

                                            }
                                        );

                                    // =================================================
                                    // PDF TABLE LAYOUT
                                    // =================================================

                                    const pdfLayout = {

                                        hLineWidth:
                                            function () {
                                                return 0.6;
                                            },

                                        vLineWidth:
                                            function () {
                                                return 0.6;
                                            },

                                        hLineColor:
                                            function () {
                                                return "#d5dbe0";
                                            },

                                        vLineColor:
                                            function () {
                                                return "#d5dbe0";
                                            },

                                        paddingLeft:
                                            function () {
                                                return 4;
                                            },

                                        paddingRight:
                                            function () {
                                                return 4;
                                            },

                                        paddingTop:
                                            function () {
                                                return 5;
                                            },

                                        paddingBottom:
                                            function () {
                                                return 5;
                                            }

                                    };

                                    // =================================================
                                    // CREATE PDF TABLES
                                    // EXACTLY 10 RECORDS PER PDF TABLE
                                    // =================================================

                                    const pageTables =
                                        [];

                                    if (
                                        dataRows.length ===
                                        0
                                    ) {

                                        pageTables.push({

                                            table: {

                                                headerRows:
                                                    1,

                                                widths:
                                                    pdfWidths,

                                                dontBreakRows:
                                                    false,

                                                body: [
                                                    headerRow
                                                ]

                                            },

                                            layout:
                                                pdfLayout

                                        });

                                    } else {

                                        for (
                                            let start = 0;
                                            start <
                                            dataRows.length;
                                            start += 10
                                        ) {

                                            const chunk =
                                                dataRows.slice(
                                                    start,
                                                    start +
                                                        10
                                                );

                                            const pageTable = {

                                                table: {

                                                    headerRows:
                                                        1,

                                                    widths:
                                                        pdfWidths,

                                                    dontBreakRows:
                                                        false,

                                                    keepWithHeaderRows:
                                                        1,

                                                    body: [
                                                        headerRow
                                                    ].concat(
                                                        chunk
                                                    )

                                                },

                                                layout:
                                                    pdfLayout,

                                                margin: [
                                                    0,
                                                    0,
                                                    0,
                                                    0
                                                ]

                                            };

                                            if (
                                                start >
                                                0
                                            ) {

                                                pageTable.pageBreak =
                                                    "before";

                                            }

                                            pageTables.push(
                                                pageTable
                                            );

                                        }

                                    }

                                    // =================================================
                                    // REPLACE ORIGINAL PDF TABLE
                                    // =================================================

                                    if (
                                        pdfContentIndex >=
                                        0
                                    ) {

                                        doc.content.splice(
                                            pdfContentIndex,
                                            1,
                                            ...pageTables
                                        );

                                    }

                                }

                                // =================================================
                                // PDF TITLE
                                // =================================================

                                if (
                                    doc.content &&
                                    doc.content.length
                                ) {

                                    doc.content.forEach(
                                        function (
                                            content
                                        ) {

                                            if (
                                                content.text ===
                                                configuration.title
                                            ) {

                                                content.alignment =
                                                    "center";

                                                content.fontSize =
                                                    18;

                                                content.bold =
                                                    true;

                                                content.margin = [
                                                    0,
                                                    0,
                                                    0,
                                                    15
                                                ];

                                            }

                                        }
                                    );

                                }

                            }

                    },

                    // ====================================================
                    // PRINT
                    // ====================================================

                    {

                        extend:
                            "print",

                        title:
                            configuration.title,

                        exportOptions: {

                            columns:
                                ":visible",

                            format: {

                                body:
                                    function (
                                        data,
                                        row,
                                        column,
                                        node
                                    ) {

                                        return cleanCellText(
                                            node
                                        );

                                    }

                            }

                        },

                        // =================================================
                        // PRINT CUSTOMIZATION
                        // =================================================

                        customize:
                            function (win) {

                                const $body =
                                    jQuery(
                                        win.document.body
                                    );

                                $body.css({

                                    "font-size":
                                        "9pt",

                                    "text-align":
                                        "center"

                                });

                                $body
                                    .find(
                                        "h1"
                                    )
                                    .css({

                                        "text-align":
                                            "center",

                                        "font-size":
                                            "18pt",

                                        "font-weight":
                                            "700",

                                        "margin-bottom":
                                            "20px"

                                    });

                                $body
                                    .find(
                                        "table"
                                    )
                                    .css({

                                        "width":
                                            "100%",

                                        "border-collapse":
                                            "collapse",

                                        "table-layout":
                                            "auto",

                                        "font-size":
                                            "9pt"

                                    });

                                // ------------------------------------------------
                                // Header
                                // ------------------------------------------------

                                $body
                                    .find(
                                        "table thead th"
                                    )
                                    .css({

                                        "background":
                                            "#ff7043",

                                        "background-image":
                                            "none",

                                        "color":
                                            "#ffffff",

                                        "border":
                                            "1px solid #e05a35",

                                        "text-align":
                                            "center",

                                        "vertical-align":
                                            "middle",

                                        "font-weight":
                                            "700",

                                        "padding":
                                            "8px 5px",

                                        "white-space":
                                            "normal",

                                        "overflow-wrap":
                                            "break-word",

                                        "word-wrap":
                                            "break-word"

                                    });

                                // ------------------------------------------------
                                // Body
                                // ------------------------------------------------

                                $body
                                    .find(
                                        "table tbody td"
                                    )
                                    .css({

                                        "border":
                                            "1px solid #d5dbe0",

                                        "text-align":
                                            "center",

                                        "vertical-align":
                                            "middle",

                                        "padding":
                                            "7px 5px",

                                        "white-space":
                                            "normal",

                                        "word-break":
                                            "normal",

                                        "overflow-wrap":
                                            "break-word",

                                        "word-wrap":
                                            "break-word",

                                        "text-overflow":
                                            "clip"

                                    });

                                // ------------------------------------------------
                                // Alternate rows
                                // ------------------------------------------------

                                $body
                                    .find(
                                        "table tbody tr"
                                    )
                                    .each(
                                        function (
                                            index
                                        ) {

                                            if (
                                                index %
                                                    2 ===
                                                0
                                            ) {

                                                jQuery(
                                                    this
                                                )
                                                    .find(
                                                        "td"
                                                    )
                                                    .css(
                                                        "background",
                                                        "#f7f9fa"
                                                    );

                                            } else {

                                                jQuery(
                                                    this
                                                )
                                                    .find(
                                                        "td"
                                                    )
                                                    .css(
                                                        "background",
                                                        "#ffffff"
                                                    );

                                            }

                                        }
                                    );

                                // ------------------------------------------------
                                // Remove hidden / interactive elements
                                // ------------------------------------------------

                                $body
                                    .find(
                                        ".d-print-none, .d-none, .print-only, button, .modal, .more, .read-more, .show-more"
                                    )
                                    .remove();

                                // ------------------------------------------------
                                // Final clean text
                                // ------------------------------------------------

                                $body
                                    .find(
                                        "table tbody td"
                                    )
                                    .each(
                                        function () {

                                            const cleaned =
                                                cleanCellText(
                                                    this
                                                );

                                            jQuery(
                                                this
                                            )
                                                .text(
                                                    cleaned
                                                );

                                        }
                                    );

                            }

                    }

                ]

            });

            // ============================================================
            // HIDE DATATABLES INTERNAL BUTTONS
            // ============================================================

            table
                .closest(
                    ".dataTables_wrapper"
                )
                .find(
                    ".dt-buttons"
                )
                .hide();

        }

        // ================================================================
        // INITIALIZE ALL AVAILABLE REPORT TABLES
        // ================================================================

        Object.keys(
            reportConfigurations
        ).forEach(
            function (tableId) {

                initializeReport(
                    tableId,
                    reportConfigurations[
                        tableId
                    ]
                );

            }
        );

        // ================================================================
        // TRIGGER DATATABLE BUTTON
        // ================================================================

        function triggerDataTableButton(
            selector,
            attempts = 0
        ) {

            // ------------------------------------------------------------
            // Check jQuery
            // ------------------------------------------------------------

            if (
                typeof jQuery ===
                "undefined"
            ) {

                return;

            }

            // ------------------------------------------------------------
            // Check DataTables
            // ------------------------------------------------------------

            if (
                typeof jQuery.fn.DataTable ===
                "undefined"
            ) {

                return;

            }

            // ------------------------------------------------------------
            // Find the table that exists on the current page
            // ------------------------------------------------------------

            let table = null;

            Object.keys(
                reportConfigurations
            ).some(
                function (tableId) {

                    const candidate =
                        jQuery(
                            "#" +
                            tableId
                        );

                    if (
                        candidate.length
                    ) {

                        // =================================================
                        // IMPORTANT FIX
                        // Skip Management tables.
                        //
                        // Management tables have #tableHeaders.
                        // =================================================

                        if (
                            candidate.find(
                                "#tableHeaders"
                            ).length
                        ) {

                            return false;

                        }

                        table =
                            candidate;

                        return true;

                    }

                    return false;

                }
            );

            // ------------------------------------------------------------
            // Stop if no report table exists
            // ------------------------------------------------------------

            if (!table) {
                return;
            }

            const tableId =
                table.attr("id");

            // ------------------------------------------------------------
            // Wait for DataTables initialization
            // ------------------------------------------------------------

            if (
                !jQuery.fn.DataTable.isDataTable(
                    "#" +
                    tableId
                )
            ) {

                if (
                    attempts <
                    20
                ) {

                    setTimeout(
                        function () {

                            triggerDataTableButton(
                                selector,
                                attempts + 1
                            );

                        },
                        100
                    );

                }

                return;

            }

            // ------------------------------------------------------------
            // Get DataTables instance
            // ------------------------------------------------------------

            const dataTable =
                table.DataTable();

            // ------------------------------------------------------------
            // Find button
            // ------------------------------------------------------------

            const button =
                dataTable.button(
                    selector
                );

            // ------------------------------------------------------------
            // Trigger
            // ------------------------------------------------------------

            if (button) {

                button.trigger();

            }

        }

        // ================================================================
        // PRINT REPORT
        // ================================================================

        function printReport() {

            // ------------------------------------------------------------
            // Find current report table
            // ------------------------------------------------------------

            let originalTable =
                null;

            let configuration =
                null;

            Object.keys(
                reportConfigurations
            ).some(
                function (tableId) {

                    const candidate =
                        document.getElementById(
                            tableId
                        );

                    if (!candidate) {

                        return false;

                    }

                    // =====================================================
                    // IMPORTANT FIX
                    // Skip Management tables.
                    //
                    // Management tables contain #tableHeaders.
                    // =====================================================

                    if (
                        candidate.querySelector(
                            "#tableHeaders"
                        )
                    ) {

                        return false;

                    }

                    originalTable =
                        candidate;

                    configuration =
                        reportConfigurations[
                            tableId
                        ];

                    return true;

                }
            );

            // ------------------------------------------------------------
            // Stop if table does not exist
            // ------------------------------------------------------------

            if (
                !originalTable ||
                !configuration
            ) {

                return;

            }

            // ============================================================
            // REMOVE OLD PRINT CONTAINER
            // ============================================================

            const oldContainer =
                document.getElementById(
                    "temporaryPrintContainer"
                );

            if (oldContainer) {

                oldContainer.remove();

            }

            // ============================================================
            // CREATE PRINT CONTAINER
            // ============================================================

            const printContainer =
                document.createElement(
                    "div"
                );

            printContainer.id =
                "temporaryPrintContainer";

            // ============================================================
            // CREATE TITLE
            // ============================================================

            const printTitle =
                document.createElement(
                    "h2"
                );

            printTitle.textContent =
                configuration.title;

            printTitle.style.textAlign =
                "center";

            printTitle.style.marginBottom =
                "15px";

            printTitle.style.fontSize =
                "20px";

            printTitle.style.fontWeight =
                "700";

            // ============================================================
            // CLONE TABLE
            // ============================================================

            const tableClone =
                originalTable.cloneNode(
                    true
                );

            // ============================================================
            // CLEAN ALL CELLS FIRST
            // ============================================================

            tableClone
                .querySelectorAll(
                    "th, td"
                )
                .forEach(
                    function (cell) {

                        let text =
                            cleanCellText(
                                cell
                            );

                        cell.textContent =
                            text;

                    }
                );

            // ============================================================
            // REMOVE INTERACTIVE ELEMENTS AFTER TEXT EXTRACTION
            // ============================================================

            tableClone
                .querySelectorAll(
                    ".d-print-none, .d-none, .print-only, button, .modal, .more, .read-more, .show-more"
                )
                .forEach(
                    function (element) {

                        element.remove();

                    }
                );

            // ============================================================
            // REMOVE DATATABLE CLASS
            // ============================================================

            tableClone.classList.remove(
                "dataTable"
            );

            // ============================================================
            // ADD TITLE + TABLE
            // ============================================================

            printContainer.appendChild(
                printTitle
            );

            printContainer.appendChild(
                tableClone
            );

            // ============================================================
            // ADD TO PAGE
            // ============================================================

            document.body.appendChild(
                printContainer
            );

            // ============================================================
            // PRINT
            // ============================================================

            setTimeout(
                function () {

                    window.print();

                },
                100
            );

            // ============================================================
            // REMOVE AFTER PRINT
            // ============================================================

            window.addEventListener(
                "afterprint",
                function removePrintContainer() {

                    const container =
                        document.getElementById(
                            "temporaryPrintContainer"
                        );

                    if (container) {

                        container.remove();

                    }

                    window.removeEventListener(
                        "afterprint",
                        removePrintContainer
                    );

                }
            );

        }

        // ================================================================
        // CUSTOM DROPDOWN BUTTONS
        // ================================================================

        const pdfButton =
            document.getElementById(
                "downloadPdfBtn"
            );

        const excelButton =
            document.getElementById(
                "downloadExcelBtn"
            );

        const printButton =
            document.getElementById(
                "printReportBtn"
            );

        // ================================================================
        // PDF
        // ================================================================

        if (pdfButton) {

            pdfButton.addEventListener(
                "click",
                function (event) {

                    event.preventDefault();

                    triggerDataTableButton(
                        ".buttons-pdf"
                    );

                }
            );

        }

        // ================================================================
        // EXCEL
        // ================================================================

        if (excelButton) {

            excelButton.addEventListener(
                "click",
                function (event) {

                    event.preventDefault();

                    triggerDataTableButton(
                        ".buttons-excel"
                    );

                }
            );

        }

        // ================================================================
        // PRINT
        // ================================================================

        if (printButton) {

            printButton.addEventListener(
                "click",
                function (event) {

                    event.preventDefault();

                    printReport();

                }
            );

        }

    });

</script>



<script>
    document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const projectIdInput = document.getElementById('project_id');
    const teamSelect = document.getElementById('team_id');
    const userSelect = document.getElementById('user_id');

    const teamHiddenInput = document.getElementById('team_id_hidden');

    /*
    |--------------------------------------------------------------------------
    | INITIAL VALUES FOR EDIT PAGE
    |--------------------------------------------------------------------------
    */

    const initialTeamId = teamSelect
        ? teamSelect.getAttribute('data-current-team')
        : null;

    const initialUserId = userSelect
        ? userSelect.getAttribute('data-current-user')
        : null;


    /*
    |--------------------------------------------------------------------------
    | HELPER: UPDATE TEAM HIDDEN INPUT
    |--------------------------------------------------------------------------
    */

    function updateTeamHiddenInput(value) {

        if (teamHiddenInput) {
            teamHiddenInput.value = value || '';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD TEAMS BY PROJECT
    |--------------------------------------------------------------------------
    */

    function loadTeams(projectId, selectedTeamId = null) {

        if (!teamSelect) {
            return;
        }

        /*
        | Reset Team
        */

        teamSelect.innerHTML = '';

        const defaultOption = document.createElement('option');

        defaultOption.value = '';
        defaultOption.textContent = 'Select team...';
        defaultOption.disabled = true;
        defaultOption.selected = true;

        teamSelect.appendChild(defaultOption);

        updateTeamHiddenInput('');


        /*
        | Reset Employees
        */

        if (userSelect) {

            userSelect.innerHTML = '';

            const userDefaultOption = document.createElement('option');

            userDefaultOption.value = '';
            userDefaultOption.textContent = 'Select employee...';
            userDefaultOption.disabled = true;
            userDefaultOption.selected = true;

            userSelect.appendChild(userDefaultOption);
        }


        /*
        | No Project Selected
        */

        if (!projectId) {

            teamSelect.disabled = true;

            if (userSelect) {
                userSelect.disabled = true;
            }

            return;
        }


        /*
        | Enable Team
        */

        teamSelect.disabled = false;


        /*
        | Loading State
        */

        const loadingOption = document.createElement('option');

        loadingOption.value = '';
        loadingOption.textContent = 'Loading teams...';
        loadingOption.disabled = true;
        loadingOption.selected = true;

        teamSelect.innerHTML = '';
        teamSelect.appendChild(loadingOption);


        /*
        | AJAX Request
        */

        fetch('/admin/task/' + projectId + '/teams', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {

            if (!response.ok) {
                throw new Error('Failed to load teams.');
            }

            return response.json();
        })
        .then(teams => {

            /*
            | Clear Loading State
            */

            teamSelect.innerHTML = '';


            /*
            | Default Option
            */

            const option = document.createElement('option');

            option.value = '';
            option.textContent = 'Select team...';
            option.disabled = true;

            if (!selectedTeamId) {
                option.selected = true;
            }

            teamSelect.appendChild(option);


            /*
            | Add Teams
            */

            teams.forEach(team => {

                const teamOption = document.createElement('option');

                teamOption.value = team.id;
                teamOption.textContent = team.name;

                if (
                    selectedTeamId &&
                    String(selectedTeamId) === String(team.id)
                ) {
                    teamOption.selected = true;
                }

                teamSelect.appendChild(teamOption);
            });


            /*
            | Update Hidden Team Input
            */

            const currentTeamValue = teamSelect.value || '';

            updateTeamHiddenInput(currentTeamValue);


            /*
            | Load Employees For Selected Team
            */

            if (currentTeamValue) {

                loadEmployees(
                    currentTeamValue,
                    initialUserId
                );

            } else {

                if (userSelect) {
                    userSelect.disabled = true;
                }
            }

        })
        .catch(error => {

            console.error(error);

            teamSelect.innerHTML = '';

            const errorOption = document.createElement('option');

            errorOption.value = '';
            errorOption.textContent = 'Unable to load teams';
            errorOption.disabled = true;
            errorOption.selected = true;

            teamSelect.appendChild(errorOption);

            teamSelect.disabled = true;

            if (userSelect) {

                userSelect.innerHTML = '';

                const userErrorOption = document.createElement('option');

                userErrorOption.value = '';
                userErrorOption.textContent = 'Select employee...';
                userErrorOption.disabled = true;
                userErrorOption.selected = true;

                userSelect.appendChild(userErrorOption);

                userSelect.disabled = true;
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD EMPLOYEES BY TEAM
    |--------------------------------------------------------------------------
    */

    function loadEmployees(teamId, selectedUserId = null) {

        if (!userSelect) {
            return;
        }


        /*
        | Reset Employees
        */

        userSelect.innerHTML = '';

        const defaultOption = document.createElement('option');

        defaultOption.value = '';
        defaultOption.textContent = 'Select employee...';
        defaultOption.disabled = true;
        defaultOption.selected = true;

        userSelect.appendChild(defaultOption);


        /*
        | No Team Selected
        */

        if (!teamId) {

            userSelect.disabled = true;

            return;
        }


        /*
        | Enable Employee
        */

        userSelect.disabled = false;


        /*
        | Loading State
        */

        const loadingOption = document.createElement('option');

        loadingOption.value = '';
        loadingOption.textContent = 'Loading employees...';
        loadingOption.disabled = true;
        loadingOption.selected = true;

        userSelect.innerHTML = '';
        userSelect.appendChild(loadingOption);


        /*
        | AJAX Request
        */

        fetch('/admin/task/' + teamId + '/employees', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {

            if (!response.ok) {
                throw new Error('Failed to load employees.');
            }

            return response.json();
        })
        .then(users => {

            /*
            | Clear Loading State
            */

            userSelect.innerHTML = '';


            /*
            | Default Option
            */

            const option = document.createElement('option');

            option.value = '';
            option.textContent = 'Select employee...';
            option.disabled = true;

            if (!selectedUserId) {
                option.selected = true;
            }

            userSelect.appendChild(option);


            /*
            | Add Employees
            */

            users.forEach(user => {

                const userOption = document.createElement('option');

                userOption.value = user.id;
                userOption.textContent = user.name;

                if (
                    selectedUserId &&
                    String(selectedUserId) === String(user.id)
                ) {
                    userOption.selected = true;
                }

                userSelect.appendChild(userOption);
            });

        })
        .catch(error => {

            console.error(error);

            userSelect.innerHTML = '';

            const errorOption = document.createElement('option');

            errorOption.value = '';
            errorOption.textContent = 'Unable to load employees';
            errorOption.disabled = true;
            errorOption.selected = true;

            userSelect.appendChild(errorOption);

            userSelect.disabled = true;
        });
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT CHANGE
    |--------------------------------------------------------------------------
    */

    if (projectIdInput) {

        /*
        | The project is changed through your custom dropdown.
        | The existing Project JavaScript should update project_id.
        |
        | We listen for changes to the hidden input.
        */

        let lastProjectId = projectIdInput.value || '';


        setInterval(function () {

            const currentProjectId = projectIdInput.value || '';

            if (currentProjectId !== lastProjectId) {

                lastProjectId = currentProjectId;

                loadTeams(currentProjectId);

            }

        }, 200);


        /*
        | Initial Load
        */

        if (projectIdInput.value) {

            loadTeams(
                projectIdInput.value,
                initialTeamId
            );

        } else {

            if (teamSelect) {
                teamSelect.disabled = true;
            }

            if (userSelect) {
                userSelect.disabled = true;
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | TEAM CHANGE
    |--------------------------------------------------------------------------
    */

    if (teamSelect) {

        teamSelect.addEventListener('change', function () {

            const teamId = this.value || '';

            updateTeamHiddenInput(teamId);

            loadEmployees(teamId);

        });
    }

});
</script>


<!-- ========================================================================= -->
<!-- SIDEBAR COLLAPSE JAVASCRIPT                                              -->
<!-- ========================================================================= -->

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const sidebar =
            document.querySelector('.sidebar');

        const mainPanel =
            document.querySelector('.main-panel');

        const collapseButton =
            document.getElementById('sidebarCollapseButton');


        if (!sidebar || !collapseButton) {
            return;
        }


        /* ================================================================ */
        /* RESTORE PREVIOUS SIDEBAR STATE                                   */
        /* ================================================================ */

        const savedState =
            localStorage.getItem('coretask_sidebar_collapsed');


        if (savedState === 'true') {

            sidebar.classList.add('sidebar-collapsed');

            if (mainPanel) {

                mainPanel.classList.add(
                    'sidebar-collapsed-panel'
                );

            }

            collapseButton.setAttribute(
                'aria-label',
                'Expand sidebar'
            );

            collapseButton.setAttribute(
                'title',
                'Expand sidebar'
            );

        }


        /* ================================================================ */
        /* COLLAPSE / EXPAND                                                 */
        /* ================================================================ */

        collapseButton.addEventListener('click', function (event) {

            event.preventDefault();
            event.stopPropagation();


            const isCollapsed =
                sidebar.classList.toggle('sidebar-collapsed');


            if (mainPanel) {

                mainPanel.classList.toggle(
                    'sidebar-collapsed-panel',
                    isCollapsed
                );

            }


            /* ============================================================ */
            /* UPDATE BUTTON                                                 */
            /* ============================================================ */

            if (isCollapsed) {

                collapseButton.setAttribute(
                    'aria-label',
                    'Expand sidebar'
                );

                collapseButton.setAttribute(
                    'title',
                    'Expand sidebar'
                );

                localStorage.setItem(
                    'coretask_sidebar_collapsed',
                    'true'
                );

            } else {

                collapseButton.setAttribute(
                    'aria-label',
                    'Collapse sidebar'
                );

                collapseButton.setAttribute(
                    'title',
                    'Collapse sidebar'
                );

                localStorage.setItem(
                    'coretask_sidebar_collapsed',
                    'false'
                );

            }

        });

    });

</script>


<!-- ========================================================================= -->
<!-- NOTIFICATION JAVASCRIPT                                                  -->
<!-- ========================================================================= -->

<script>
    document.addEventListener('DOMContentLoaded', function () {

    const notificationButton =
        document.getElementById('deadlineNotificationButton');

    const notificationMenu =
        document.getElementById('deadlineNotificationMenu');


    if (!notificationButton || !notificationMenu) {
        return;
    }


    /* ================================================================ */
    /* OPEN / CLOSE NOTIFICATION MENU                                   */
    /* ================================================================ */

    notificationButton.addEventListener('click', function (event) {

        event.preventDefault();
        event.stopPropagation();

        const isOpen =
            notificationMenu.classList.contains('show');


        if (isOpen) {

            notificationMenu.classList.remove('show');

            notificationButton.setAttribute(
                'aria-expanded',
                'false'
            );

        } else {

            notificationMenu.classList.add('show');

            notificationButton.setAttribute(
                'aria-expanded',
                'true'
            );

        }

    });


    /* ================================================================ */
    /* PREVENT CLICK INSIDE MENU FROM CLOSING IT                        */
    /* ================================================================ */

    notificationMenu.addEventListener('click', function (event) {

        if (event.target.closest('.deadline-notification-delete')) {
            return;
        }

        event.stopPropagation();

    });


    /* ================================================================ */
    /* CLOSE WHEN CLICKING OUTSIDE                                      */
    /* ================================================================ */

    document.addEventListener('click', function (event) {

        if (
            !notificationMenu.contains(event.target) &&
            !notificationButton.contains(event.target)
        ) {

            notificationMenu.classList.remove('show');

            notificationButton.setAttribute(
                'aria-expanded',
                'false'
            );

        }

    });


    /* ================================================================ */
    /* CLOSE WITH ESCAPE                                                */
    /* ================================================================ */

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            notificationMenu.classList.remove('show');

            notificationButton.setAttribute(
                'aria-expanded',
                'false'
            );

        }

    });

});
</script>


<!-- ========================================================================= -->
<!-- NOTIFICATION DELETE JAVASCRIPT                                           -->
<!-- ========================================================================= -->

<script>
    document.addEventListener('DOMContentLoaded', function () {

    const badge = document.getElementById(
        'deadlineNotificationBadge'
    );

    const headerCount = document.getElementById(
        'deadlineNotificationHeaderCount'
    );


    /* ================================================================= */
    /* NOTIFICATION HIDE TIME                                            */
    /* ================================================================= */

    const hideDuration = 30 * 60 * 1000;


    /* ================================================================= */
    /* GET HIDDEN NOTIFICATIONS FROM SESSION STORAGE                     */
    /* ================================================================= */

    let hiddenNotifications = {};


    try {

        hiddenNotifications = JSON.parse(
            sessionStorage.getItem(
                'coretask_hidden_deadline_notifications'
            ) || '{}'
        );

        if (
            typeof hiddenNotifications !== 'object' ||
            hiddenNotifications === null
        ) {

            hiddenNotifications = {};

        }

    } catch (error) {

        hiddenNotifications = {};

    }


    /* ================================================================= */
    /* REMOVE EXPIRED HIDDEN NOTIFICATIONS                               */
    /* ================================================================= */

    const currentTime = Date.now();


    Object.keys(hiddenNotifications).forEach(function (key) {

        if (currentTime - hiddenNotifications[key] >= hideDuration) {

            delete hiddenNotifications[key];

        }

    });


    sessionStorage.setItem(
        'coretask_hidden_deadline_notifications',
        JSON.stringify(hiddenNotifications)
    );


    /* ================================================================= */
    /* HIDE PREVIOUSLY DISMISSED NOTIFICATIONS                           */
    /* ================================================================= */

    const notificationItems = document.querySelectorAll(
        '.deadline-notification-item[data-notification-key]'
    );


    notificationItems.forEach(function (item) {

        const key = item.getAttribute(
            'data-notification-key'
        );


        if (
            key &&
            hiddenNotifications[key] &&
            currentTime - hiddenNotifications[key] < hideDuration
        ) {

            item.remove();

        }

    });


    /* ================================================================= */
    /* UPDATE NOTIFICATION COUNT                                        */
    /* ================================================================= */

    function updateNotificationCount() {

        const remainingItems =
            document.querySelectorAll(
                '.deadline-notification-item[data-notification-key]'
            );

        const count = remainingItems.length;


        /* ============================================================= */
        /* UPDATE BADGE                                                  */
        /* ============================================================= */

        if (badge) {

            if (count > 0) {

                badge.textContent =
                    count > 99 ? '99+' : count;

                badge.style.display = 'inline-flex';

            } else {

                badge.style.display = 'none';

            }

        }


        /* ============================================================= */
        /* UPDATE HEADER COUNT                                           */
        /* ============================================================= */

        if (headerCount) {

            if (count > 0) {

                headerCount.textContent = count;

                headerCount.style.display = 'inline-flex';

            } else {

                headerCount.style.display = 'none';

            }

        }

    }


    /* ================================================================= */
    /* DELETE / DISMISS NOTIFICATION                                    */
    /* ================================================================= */

    document.addEventListener('click', function (event) {

        const button = event.target.closest(
            '.deadline-notification-delete'
        );


        if (!button) {
            return;
        }


        event.preventDefault();
        event.stopPropagation();


        const key = button.getAttribute(
            'data-notification-key'
        );


        if (!key) {
            return;
        }


        /* ============================================================= */
        /* SAVE DISMISSED NOTIFICATION FOR 30 MINUTES                    */
        /* ============================================================= */

        hiddenNotifications[key] = Date.now();


        sessionStorage.setItem(
            'coretask_hidden_deadline_notifications',
            JSON.stringify(hiddenNotifications)
        );


        /* ============================================================= */
        /* REMOVE NOTIFICATION FROM MENU                                 */
        /* ============================================================= */

        const item = button.closest(
            '.deadline-notification-item[data-notification-key]'
        );


        if (item) {

            item.remove();

        }


        /* ============================================================= */
        /* UPDATE COUNT                                                  */
        /* ============================================================= */

        updateNotificationCount();

    });


    /* ================================================================= */
    /* INITIAL COUNT UPDATE                                              */
    /* ================================================================= */

    updateNotificationCount();

});
</script>
