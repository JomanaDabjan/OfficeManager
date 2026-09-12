<?php

namespace App\Http\Controllers\Admin;

// =========================================================================
// IMPORT NECESSARY CLASSES AND PACKAGES
// =========================================================================

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\Request;
//use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * =========================================================================
 * REPORT CONTROLLER (ANALYTICS & REPORTING MANAGEMENT)
 * =========================================================================
 *
 * This controller is responsible only for:
 *
 * - Authorization
 * - Receiving the request
 * - Calling ReportService
 * - Returning the appropriate view / AJAX response
 *
 * All reporting queries, statistics, filters and role-based report logic
 * are handled inside ReportService.
 */
class ReportController extends Controller
{
    use AuthorizesRequests;

    /**
     * Report service responsible for all report business logic.
     */
    public function __construct(
        protected ReportService $reportService
    ) {}

    /**
     * =====================================================================
     * DISPLAY REPORT INDEX HUB
     * =====================================================================
     *
     * Render the main report selection dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $this->authorize('viewAny', Report::class);

        return view('contents.report.Index');
    }

    /**
     * =====================================================================
     * EMPLOYEE PERFORMANCE / TASK REPORT
     * =====================================================================
     *
     * Generate the task report using ReportService.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function taskreport(Request $request): View
    {
        $this->authorize('viewAny', Report::class);

        $data = $this->reportService->taskReportData(
            $request,
            auth()->user()
        );

        return view(
            'contents.report.TaskReport',
            $data
        );
    }

    /**
     * =====================================================================
     * PROJECT MANAGERS REPORT
     * =====================================================================
     *
     * Generate the project report using ReportService.
     *
     * Supports both:
     *
     * - Normal page request
     * - AJAX request for the projects table
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function projectreport(Request $request): View
    {
        $this->authorize('viewAny', Report::class);

        $data = $this->reportService->projectReportData(
            $request,
            auth()->user()
        );

        /**
         * ================================================================
         * AJAX RESPONSE
         * ================================================================
         *
         * The service prepares all project report data.
         * For AJAX we only return the projects table partial.
         */
        if ($request->ajax()) {
            return view(
                'contents.report.partials.projects-table',
                [
                    'projects' => $data['projects'],
                ]
            );
        }

        /**
         * ================================================================
         * FULL PROJECT REPORT PAGE
         * ================================================================
         */
        return view(
            'contents.report.ProjectReport',
            $data
        );
    }

    /**
     * =====================================================================
     * USER REPORT
     * =====================================================================
     *
     * Generate the user report using ReportService.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function userreport(Request $request): View
    {
        $this->authorize('viewAny', Report::class);

        $data = $this->reportService->userReportData(
            $request,
            auth()->user()
        );

        return view(
            'contents.report.UserReport',
            $data
        );
    }

    /**
     * =====================================================================
     * TEAM REPORT
     * =====================================================================
     *
     * Generate the team report using ReportService.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function teamreport(Request $request): View
    {
        $this->authorize('viewAny', Report::class);

        $data = $this->reportService->teamReportData(
            $request,
            auth()->user()
        );

        return view(
            'contents.report.TeamReport',
            $data
        );
    }
}
