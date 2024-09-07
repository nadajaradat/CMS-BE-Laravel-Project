<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReceptionistResource extends JsonResource
{
    public function toArray($request)
    {
        return collect($this["receptionists"] ?? $this["receptionist"]);
    }
}
