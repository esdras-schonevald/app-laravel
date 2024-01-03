<?php

namespace App\Http\Resources;

use App\Http\Services\DateHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            =>  $this->id,
            'title'         =>  $this->title,
            'description'   =>  $this->description,
            'start_date'    =>  DateHelper::dbToBr($this->start_date),
            'deadline_date' =>  DateHelper::dbToBr($this->deadline_date),
            'end_date'      =>  DateHelper::dbToBr($this->end_date),
            'status'        =>  $this->status
        ];
    }

}
