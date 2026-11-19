<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Screening;
use Illuminate\Http\Request;

class ScreeningController extends Controller
{
    public function index()
    {
        $organizations = Organization::orderBy('name')->get();

        return view('screening', compact('organizations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'age'         => 'required|string',
            'gender'      => 'required|string',
            'pronouns'    => 'required|string',
            'orientation' => 'required|string',
            'counselor'   => 'required|string',
            'therapist'   => 'required|string',
            'concern'     => 'required|string',
            'rating'      => 'required|string',
            'medication'  => 'required|string',
            'support'     => 'required|string',
            'substance'   => 'required|string',
            'sleep'       => 'required|string',
            'selfHarm'    => 'required|string',
            'danger'      => 'required|string',
            'supportNeed' => 'required|string',
            'referral'    => 'nullable',
        ]);

        // Risk calculation
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

        Screening::create([
            'user_id'              => auth()->id(),
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
            ? Organization::find($referral)?->only([
                'name', 'description', 'phone', 'whatsapp', 'email', 'website'
            ])
            : null;

        return response()->json([
            'status'       => 'success',
            'risk'         => $risk,
            'organization' => $organization,
        ]);
    }
}