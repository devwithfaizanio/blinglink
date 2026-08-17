<?php

namespace App\Http\Resources\api\v1\report;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'from_user' => $this->fromUser,
            'to_user' => $this->toUser,
            'reason_to_report' => $this->reason_to_report,
            'status' => $this->status,
            'admin_quote' => $this->admin_quote,
            'report_at' => $this->created_at->format('d M Y, h:i A'),

        ];
    }
}
