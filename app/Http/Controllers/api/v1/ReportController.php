<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\report\addReportRequest;
use App\Http\Requests\api\v1\report\getReportRequest;
use App\Http\Requests\api\v1\report\updateReportRequest;
use App\Http\Resources\api\v1\report\getReportResource;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Report a user
     */
    public function store(addReportRequest $request)
    {

        if ($request->to_user_id == auth()->id()) {
            return response()->json([
                'message' => 'You cannot report yourself.'
            ], 422);
        }

        $alreadyReported = Report::where('from_user_id', auth()->id())
            ->where('to_user_id', $request->to_user_id)
            ->exists();

        if ($alreadyReported) {
            return $this->error(
                message: 'You have already reported this user.',
                code: 422
            );
        }

        Report::create([
            'from_user_id'    => auth()->id(),
            'to_user_id'      => $request->to_user_id,
            'reason_to_report'=> $request->reason_to_report,
            'status'          => 'pending',
        ]);


        return $this->success(message: 'User reported successfully.', data: null);

    }

    /**
     * Get reports with optional status filter
     */
    public function index(getReportRequest $request)
    {
        $limit  = $request->limit ?? 10;
        $status = $request->status ?? 'all';
        $isMine = $request->isMine ?? false;

        if ($isMine) {
            $query = Report::where('from_user_id', auth()->id());
        } else {
            $query = Report::query();
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $reports = $query->latest()->paginate($limit);

        $paginationInfo = getPaginationInfo($reports, $limit);

        return $this->success(
            message: 'successfully',
            data: [
                'reports' => getReportResource::collection($reports),
                'pagination' => $paginationInfo,
            ]
        );
    }

    /**
     * Admin action on report
     */
    public function update(updateReportRequest $request)
    {

        $report = Report::query()->find($request->report_id);
        $report->status  = $request->status ?? $report->status;
        $report->admin_quote  = $request->admin_quote ?? $report->admin_quote;
        $report->save();

        $message = match ($request->status) {
            'resolved' => 'Report has been resolved successfully.',
            'dismissed' => 'Report has been dismissed successfully.',
            default => 'Report updated successfully.',
        };

        return $this->success(
            message: $message,
            data: null
        );

    }
}
