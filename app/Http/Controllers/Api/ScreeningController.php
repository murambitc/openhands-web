<?php

namespace App\Http\Controllers\Api;

use App\Models\Organization;
use App\Models\Screening;
use Illuminate\Http\Request;

class ScreeningController extends ApiController
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'age'         => ['required', 'string'],
            'gender'      => ['required', 'string'],
            'pronouns'    => ['required', 'string'],
            'orientation' => ['required', 'string'],
            'counselor'   => ['required', 'string'],
            'therapist'   => ['required', 'string'],
            'concern'     => ['required', 'string'],
            'rating'      => ['required', 'string'],
            'medication'  => ['required', 'string'],
            'support'     => ['required', 'string'],
            'substance'   => ['required', 'string'],
            'sleep'       => ['required', 'string'],
            'selfHarm'    => ['required', 'string'],
            'danger'      => ['required', 'string'],
            'supportNeed' => ['required', 'string'],
            'referral'    => ['nullable'],
        ]);

        $risk = 'low';
        if ($validated['selfHarm'] === 'Yes' || $validated['danger'] === 'Yes') {
            $risk = 'high';
        } elseif (
            $validated['selfHarm'] === 'Sometimes' ||
            $validated['rating'] === 'Poor' ||
            $validated['support'] === 'No'
        ) {
            $risk = 'medium';
        }

        $referral = ($validated['referral'] && $validated['referral'] !== 'none')
            ? (int) $validated['referral']
            : null;

        $screening = Screening::create([
            'user_id'              => $request->user()->id,
            'age_group'            => $validated['age'],
            'gender'               => $validated['gender'],
            'pronouns'             => $validated['pronouns'],
            'orientation'          => $validated['orientation'],
            'counselor_preference' => $validated['counselor'],
            'therapist_history'    => $validated['therapist'],
            'main_concern'         => $validated['concern'],
            'mental_rating'        => $validated['rating'],
            'medication'           => $validated['medication'],
            'support_system'       => $validated['support'],
            'substance_use'        => $validated['substance'],
            'sleep'                => $validated['sleep'],
            'self_harm'            => $validated['selfHarm'],
            'danger'               => $validated['danger'],
            'support_need'         => $validated['supportNeed'],
            'risk_level'           => $risk,
            'preferred_referral'   => $referral,
        ]);

        $organization = $referral
            ? Organization::find($referral)?->only(['id', 'name', 'description', 'phone', 'whatsapp', 'email', 'website'])
            : null;

        return $this->ok([
            'screening_id' => $screening->id,
            'risk_level'   => $risk,
            'organization' => $organization,
            'urgent'       => $risk === 'high',
        ], 'Screening submitted.', 201);
    }

    public function index(Request $request)
    {
        $screenings = $request->user()
            ->screenings()
            ->latest()
            ->get()
            ->map(fn ($s) => [
                'id'          => $s->id,
                'risk_level'  => $s->risk_level,
                'main_concern'=> $s->main_concern,
                'created_at'  => $s->created_at->toIso8601String(),
            ]);

        return $this->ok($screenings);
    }

    public function show(Request $request, $id)
    {
        $screening = $request->user()->screenings()->find($id);

        if (! $screening) {
            return $this->fail('Screening not found.', 404);
        }

        return $this->ok($screening);
    }
}