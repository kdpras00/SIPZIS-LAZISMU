<?php

if (!function_exists('calculateProfileCompletion')) {
    /**
     * Calculate profile completion percentage for a Muzakki
     *
     * @param \App\Models\Muzakki $muzakki
     * @return int
     */
    function calculateProfileCompletion($muzakki)
    {
        if (!$muzakki) {
            return 0;
        }

        // Use the model's accessor if available
        if (method_exists($muzakki, 'getProfileCompletenessAttribute')) {
            return $muzakki->profile_completeness;
        }

        // Fallback calculation if accessor doesn't exist
        $fields = [
            'name' => $muzakki->name,
            'email' => $muzakki->email,
            'phone' => $muzakki->phone,
            'gender' => $muzakki->gender,
            'address' => $muzakki->address,
            'city' => $muzakki->city,
            'province' => $muzakki->province,
            'district' => $muzakki->district,
            'village' => $muzakki->village,
            'postal_code' => $muzakki->postal_code,
            'country' => $muzakki->country,
            'campaign_url' => $muzakki->campaign_url,
            'profile_photo' => $muzakki->profile_photo,
            'ktp_photo' => $muzakki->ktp_photo,
            'bio' => $muzakki->bio,
            'occupation' => $muzakki->occupation,
            'date_of_birth' => $muzakki->date_of_birth,
        ];

        $filledFields = 0;
        $totalFields = count($fields);

        foreach ($fields as $value) {
            if (!is_null($value) && $value !== '') {
                $filledFields++;
            }
        }

        return $totalFields > 0 ? round(($filledFields / $totalFields) * 100) : 0;
    }
}

