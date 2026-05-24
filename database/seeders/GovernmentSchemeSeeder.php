<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GovernmentScheme;

class GovernmentSchemeSeeder extends Seeder
{
    public function run(): void
    {
        $schemes = [
            [
                'name' => 'PM-KISAN (Pradhan Mantri Kisan Samman Nidhi)',
                'description' => 'An initiative by the Government of India that provides up to ₹6,000 per year in three equal installments to all small and marginal farmers as minimum income support.',
                'eligibility' => "• All landholding farmers' families in the country having cultivable landholding in their names.\n• Excludes institutional landholders, government employees, and income tax payers.",
                'benefits' => "• Direct financial benefit of ₹6,000 per annum paid in three equal installments of ₹2,000 every four months directly into bank accounts.",
                'apply_link' => 'https://pmkisan.gov.in/',
                'state' => 'All India',
                'category' => 'Farming Support',
            ],
            [
                'name' => 'PM Fasal Bima Yojana (PMFBY)',
                'description' => 'A government-sponsored crop insurance scheme that integrates multiple stakeholders and provides insurance coverage against crop damage from natural disasters, pests, or diseases.',
                'eligibility' => "• All farmers, including sharecroppers and tenant farmers, growing notified crops in notified areas are eligible.\n• Compulsory for farmers who have taken crop loans (KCC) for notified crops.",
                'benefits' => "• Comprehensive risk cover from pre-sowing to post-harvest stages.\n• Low premium rates: Max 2% for Kharif, 1.5% for Rabi, and 5% for commercial/horticultural crops.",
                'apply_link' => 'https://pmfby.gov.in/',
                'state' => 'All India',
                'category' => 'Insurance',
            ],
            [
                'name' => 'Kisan Credit Card (KCC) Loan Scheme',
                'description' => 'Provides farmers with timely access to credit for their cultivation and other needs (post-harvest, consumption, investment) at a very subsidized rate of interest.',
                'eligibility' => "• All farmers - individuals/joint borrowers who are owner cultivators.\n• Tenant farmers, oral lessees, and sharecroppers.\n• Self-Help Groups (SHGs) or Joint Liability Groups (JLGs) of farmers.",
                'benefits' => "• Short term credit limit up to ₹3 lakh at a low interest rate of 4% per annum (after prompt repayment subsidy).\n• Insurance coverage against accidental death or permanent disability up to ₹50,000.",
                'apply_link' => 'https://www.sbi.co.in/web/personal-banking/loans/agriculture-rural-loans/kisan-credit-card',
                'state' => 'All India',
                'category' => 'Loan',
            ],
            [
                'name' => 'Soil Health Card Scheme',
                'description' => 'Promotes organic fertilizers and crop-wise nutrient management. Farmers receive a card detailing their soil health (12 parameters) once every two years to guide fertilizer application.',
                'eligibility' => '• All landholding farmers across the country are eligible to have their soils tested and receive Soil Health Cards.',
                'benefits' => "• Free soil testing and recommendation on dosage of nutrients and fertilizers.\n• Enhances soil productivity and reduces excess fertilizer usage cost by 15-20%.",
                'apply_link' => 'https://soilhealth.dac.gov.in/',
                'state' => 'All India',
                'category' => 'Farming Support',
            ],
            [
                'name' => 'PM Krishi Sinchayee Yojana (PMKSY) - Drip Irrigation Subsidy',
                'description' => 'Focuses on improving water-use efficiency on farms ("Per Drop More Crop") through micro-irrigation systems (drip and sprinkler systems) with high financial support.',
                'eligibility' => '• Farmers who own agricultural land with a viable water source.\n• Cooperative societies, member farmers of water user associations, and self-help groups.',
                'benefits' => '• Subsidies ranging from 45% up to 55% of the total system cost for small/marginal farmers.\n• Central and state governments coordinate additional state-top-up subsidies.',
                'apply_link' => 'https://pmksy.gov.in/',
                'state' => 'All India',
                'category' => 'Subsidy',
            ],
            [
                'name' => 'Paramparagat Krishi Vikas Yojana (PKVY)',
                'description' => 'A sub-component of Soil Health Management under National Mission for Sustainable Agriculture (NMSA) to promote organic farming in cluster-based models.',
                'eligibility' => '• Groups of farmers forming clusters of 50 or more holding a total of 50 acres of land.\n• Individual small and marginal farmers integrated within clusters.',
                'benefits' => '• Financial assistance of ₹50,000 per hectare over 3 years, of which ₹31,000 (62%) is given directly for organic inputs (seeds, bio-fertilizers, bio-pesticides).',
                'apply_link' => 'https://pgsindia-ncof.dac.gov.in/pkvy/index.aspx',
                'state' => 'All India',
                'category' => 'Organic',
            ],
            [
                'name' => 'Sub-Mission on Agricultural Mechanization (SMAM) - Tractor Subsidy',
                'description' => 'Aims to increase the reach of farm mechanization to small and marginal farmers and in regions where availability of farm power is low.',
                'eligibility' => '• Small, marginal, SC/ST, and women farmers are prioritized.\n• Must have valid land records and bank details.',
                'benefits' => '• 40% to 50% subsidy on purchase of tractors, rotavators, power tillers, laser land levelers, and custom hiring center machinery.',
                'apply_link' => 'https://agrimachinery.nic.in/',
                'state' => 'All India',
                'category' => 'Subsidy',
            ],
            // State-Specific Announcements/Subsidies
            [
                'name' => 'Punjab Free Electricity for Tube-wells',
                'description' => 'The Government of Punjab provides 100% free electricity to tube-wells used for agricultural irrigation to relieve Punjabi farmers of high farming operational costs.',
                'eligibility' => '• Resident farmers of Punjab owning registered agricultural land with active tube-well connections.',
                'benefits' => '• Complete waiver of electricity bills for tube-well operations, saving up to ₹40,000 per tube-well per year.',
                'apply_link' => 'http://www.agriculture.punjab.gov.in/',
                'state' => 'Punjab',
                'category' => 'Subsidy',
            ],
            [
                'name' => 'Maharashtra Chhatrapati Shivaji Maharaj Shetkari Sanman Yojana (Crop Loan Waiver)',
                'description' => 'Maharashtra state scheme to waive outstanding crop loans and agricultural loans for distressed farmers to reduce debts and farmer suicides.',
                'eligibility' => '• Resident farmers of Maharashtra with outstanding crop loans up to ₹1.5 lakh.',
                'benefits' => '• Direct loan waiver/clearance of up to ₹1.5 lakh. Non-defaulting farmers receive a prompt repayment bonus incentive of up to ₹25,000.',
                'apply_link' => 'https://mjpsky.maharashtra.gov.in/',
                'state' => 'Maharashtra',
                'category' => 'Loan',
            ],
            [
                'name' => 'Uttar Pradesh Paradarshi Kisan Seva Yojana',
                'description' => 'A digital gateway for UP farmers to register and claim direct benefit transfer (DBT) subsidies on certified seeds, agricultural equipment, bio-fertilizers, and crop defense kits.',
                'eligibility' => '• Farmers resident in Uttar Pradesh, registered on the state farmer portal with Aadhaar card validation.',
                'benefits' => '• 50% direct cash-back subsidy into bank accounts on high-quality hybrid seeds (wheat, paddy, pulses) and equipment.',
                'apply_link' => 'http://upagriculture.com/',
                'state' => 'Uttar Pradesh',
                'category' => 'Subsidy',
            ],
            [
                'name' => 'Krishak Bandhu Scheme (West Bengal)',
                'description' => 'A landmark scheme by the Government of West Bengal providing financial support to all recorded farmers and sharecroppers, alongside life insurance support.',
                'eligibility' => '• Resident farmers/sharecroppers of West Bengal aged between 18 and 60 years.',
                'benefits' => '• Assured grant of ₹10,000 per acre per year (paid in two installments: Kharif & Rabi).\n• One-time death benefit of ₹2 lakh paid to families of deceased farmers.',
                'apply_link' => 'https://krishakbandhu.wb.gov.in/',
                'state' => 'West Bengal',
                'category' => 'Farming Support',
            ],
            [
                'name' => 'Cooperative Crop Loan Waiver Scheme (Tamil Nadu)',
                'description' => 'Waiver of short-term crop loans outstanding with cooperative banks for farmers in Tamil Nadu to support them following cyclone/monsoon failures.',
                'eligibility' => '• Farmers who availed short-term crop loans from cooperative banks in Tamil Nadu.',
                'benefits' => '• Full waiver of outstanding principal and interest dues on eligible short-term crop loans.',
                'apply_link' => 'https://www.tn.gov.in/',
                'state' => 'Tamil Nadu',
                'category' => 'Loan',
            ],
        ];

        foreach ($schemes as $scheme) {
            GovernmentScheme::create($scheme);
        }
    }
}
