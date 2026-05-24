<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GovernmentAlert;

class GovernmentAlertSeeder extends Seeder
{
    public function run(): void
    {
        $alerts = [
            [
                'title' => 'MSP for Kharif Crops 2026 Announced!',
                'message' => 'The Cabinet has approved a major hike in Minimum Support Price (MSP) for all mandated Kharif crops. Paddy (Common) MSP is raised by ₹143 to ₹2,323 per quintal. Cotton (Medium Staple) increased by ₹508 to ₹7,128 per quintal.',
                'type' => 'MSP',
                'state' => 'All India',
                'severity' => 'info',
                'deadline' => null,
            ],
            [
                'title' => 'PM Krishi Sinchayee Yojana Subsidy Registration Deadline',
                'message' => 'Farmers in Uttar Pradesh can submit online applications for drip and sprinkler irrigation subsidies up to 55%. Ensure bank accounts are Aadhaar-seeded.',
                'type' => 'Deadline',
                'state' => 'Uttar Pradesh',
                'severity' => 'moderate',
                'deadline' => now()->addDays(15)->toDateString(),
            ],
            [
                'title' => 'Heatwave Alert & Irrigation Advisory for Punjab & Haryana',
                'message' => 'Meteorological Dept issues orange warning for heatwave conditions. Maximum temperatures expected to reach 45°C. Farmers are advised to provide light and frequent watering to sugarcane, cotton, and vegetables during evening hours.',
                'type' => 'Disaster',
                'state' => 'Punjab',
                'severity' => 'severe',
                'deadline' => now()->addDays(4)->toDateString(),
            ],
            [
                'title' => 'Registration for PM Fasal Bima Yojana (Crop Insurance) Open',
                'message' => 'Enrollment for Kharif Crop Insurance under PMFBY has officially started. Apply online or visit your nearest Common Service Center (CSC) or Cooperative Bank branch before the cutoff date.',
                'type' => 'Deadline',
                'state' => 'All India',
                'severity' => 'moderate',
                'deadline' => now()->addDays(30)->toDateString(),
            ],
            [
                'title' => 'Solar Pump Subsidies Under PM-KUSUM Scheme',
                'message' => 'Maharashtra Energy Development Agency (MEDA) invites applications for off-grid solar agricultural pumps with up to 90% subsidy for SC/ST farmers and 60% for general farmers.',
                'type' => 'Subsidy',
                'state' => 'Maharashtra',
                'severity' => 'info',
                'deadline' => now()->addDays(20)->toDateString(),
            ],
            [
                'title' => 'Advisory: Organic Pesticide Spraying in Tamil Nadu',
                'message' => 'State Agriculture Dept issues guidelines for control of Fall Armyworm in Maize using organic neem formulations. Visit your Block Agri Office for free bio-pesticide distribution kits.',
                'type' => 'Policy',
                'state' => 'Tamil Nadu',
                'severity' => 'info',
                'deadline' => null,
            ]
        ];

        foreach ($alerts as $alert) {
            GovernmentAlert::create($alert);
        }
    }
}
