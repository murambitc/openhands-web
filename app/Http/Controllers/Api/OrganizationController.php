<?php

namespace App\Http\Controllers\Api;

use App\Models\Organization;

class OrganizationController extends ApiController
{
    public function index()
    {
        $orgs = Organization::orderBy('name')->get()->map(fn ($o) => $this->format($o));
        return $this->ok($orgs);
    }

    public function show($id)
    {
        $org = Organization::find($id);
        if (! $org) return $this->fail('Not found.', 404);
        return $this->ok($this->format($org));
    }

    private function format($o): array
    {
        return [
            'id'          => $o->id,
            'name'        => $o->name,
			'logo'        => $o->logo,
				'logo'        => $o->logo,
					'logo'        => $o->logo,
					'latitude' => $o->latitude,
					'longitude' => $o->longitude, 'is_active' => $o->is_active,
            'description' => $o->description,
            'phone'       => $o->phone,
            'whatsapp'    => $o->whatsapp,
            'email'       => $o->email,
            'website'     => $o->website,
            'map_link'    => $o->map_link,
            'logo_url'    => $o->logo ? asset( $o->logo) : null,
        ];
    }
}