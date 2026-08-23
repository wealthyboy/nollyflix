<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PrivateUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'full_name' => trim($this->name.' '.$this->last_name),
            'phone_number' => $this->phone_number,
            'notificationStatus' => (bool) $this->allow_notifications,
            'videos' => $this->movies->count(),
            'token' => $this->api_token,
        ];
    }
}