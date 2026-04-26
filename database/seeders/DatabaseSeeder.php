<?php

namespace Database\Seeders;

use App\Models\Crop;
use App\Models\CroppingPattern;
use App\Models\Irrigation;
use App\Models\LandHolding;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $cropsData = [
            [
                'name' => 'Wheat',
                'season' => 'rabi',
                'water_requirement' => 400,
                'why_grown' => 'Staple food crop with high demand; minimum support price ensures stable income',
                'ideal_soil' => 'Alluvial & loamy soils are ideal for wheat cultivation',
                'market_demand' => 'High domestic demand + export potential; food security focus',
                'government_support' => 'MSP (Minimum Support Price) guaranteed by Government of India',
                'emoji' => '🌾',
                'expected_yield' => '30-35 quintals/hectare',
            ],
            [
                'name' => 'Rice',
                'season' => 'kharif',
                'water_requirement' => 1500,
                'why_grown' => 'Primary staple food; water availability supports cultivation',
                'ideal_soil' => 'Loamy & clayey soils; good water retention capacity',
                'market_demand' => 'Essential commodity with consistent domestic demand',
                'government_support' => 'Assured procurement by government through FCI (Food Corporation of India)',
                'emoji' => '🍚',
                'expected_yield' => '50-60 quintals/hectare',
            ],
            [
                'name' => 'Cotton',
                'season' => 'kharif',
                'water_requirement' => 800,
                'why_grown' => 'Cash crop providing higher income than cereals',
                'ideal_soil' => 'Black & loamy soils; moderate fertility requirements',
                'market_demand' => 'Strong textile industry demand + international markets',
                'government_support' => 'Guaranteed price through government monopoly procurement scheme',
                'emoji' => '🧵',
                'expected_yield' => '15-20 quintals/hectare',
            ],
            [
                'name' => 'Soybean',
                'season' => 'kharif',
                'water_requirement' => 850,
                'why_grown' => 'Emerging cash crop with high protein content and export potential',
                'ideal_soil' => 'Well-drained black soils; nitrogen-fixing properties benefit soil health',
                'market_demand' => 'Growing domestic demand + significant export markets',
                'government_support' => 'Included in ISOPOM (Integrated Scheme of Oilseeds, Pulses, Maize)',
                'emoji' => '🌱',
                'expected_yield' => '20-25 quintals/hectare',
            ],
            [
                'name' => 'Pulses (Chickpea, Lentil)',
                'season' => 'rabi',
                'water_requirement' => 450,
                'why_grown' => 'Protein source for population; enriches soil with nitrogen',
                'ideal_soil' => 'Black soils ideal; low fertility soils suitable for pulse cultivation',
                'market_demand' => 'Essential protein source; vegetarian population demand',
                'government_support' => 'MSP support; part of NFSM (National Food Security Mission)',
                'emoji' => '🫘',
                'expected_yield' => '18-22 quintals/hectare',
            ],
            [
                'name' => 'Sugarcane',
                'season' => 'year-round',
                'water_requirement' => 2000,
                'why_grown' => 'High-value cash crop; industrial demand for sugar production',
                'ideal_soil' => 'Deep loamy & clayey soils with good water-holding capacity',
                'market_demand' => 'Sugar industry + ethanol production emerging use',
                'government_support' => 'Assured prices by sugar mills; cooperative support systems',
                'emoji' => '🍬',
                'expected_yield' => '80-100 quintals/hectare',
            ],
            [
                'name' => 'Groundnut',
                'season' => 'kharif',
                'water_requirement' => 500,
                'why_grown' => 'Oil-rich crop; drought tolerant; excellent for region\'s climate',
                'ideal_soil' => 'Light & sandy loams; good drainage prevents waterlogging',
                'market_demand' => 'Oil extraction + food industry; international export market',
                'government_support' => 'ISOPOM scheme support for oilseed promotion',
                'emoji' => '🥜',
                'expected_yield' => '20-25 quintals/hectare',
            ],
            [
                'name' => 'Coffee',
                'season' => 'year-round',
                'water_requirement' => 2000,
                'why_grown' => 'Premium plantation crop; high value per hectare',
                'ideal_soil' => 'Well-drained latosols & volcanic soils; rich organic matter',
                'market_demand' => 'International market; premium pricing for specialty varieties',
                'government_support' => 'Export promotion through APEDA',
                'emoji' => '☕',
                'expected_yield' => '1000-1500 kg/hectare',
            ],
            [
                'name' => 'Spices (Turmeric, Pepper)',
                'season' => 'year-round',
                'water_requirement' => 1800,
                'why_grown' => 'High-value horticulture; global demand for Indian spices',
                'ideal_soil' => 'Well-drained loamy & laterite soils',
                'market_demand' => 'Global demand; India world\'s largest producer & exporter',
                'government_support' => 'MIDH (Mission for Integrated Development of Horticulture) support',
                'emoji' => '🌶️',
                'expected_yield' => '25-30 quintals/hectare',
            ],
            [
                'name' => 'Jute',
                'season' => 'kharif',
                'water_requirement' => 1800,
                'why_grown' => 'Traditional crop; ecological importance; natural fiber demand',
                'ideal_soil' => 'Loamy & clayey alluvial soils; prone to flooding tolerated',
                'market_demand' => 'Eco-friendly alternative to synthetic fibers; burlap bags demand',
                'government_support' => 'Ministry support for sustainable natural fiber promotion',
                'emoji' => '🌾',
                'expected_yield' => '25-30 quintals/hectare',
            ],
            [
                'name' => 'Tea',
                'season' => 'year-round',
                'water_requirement' => 2000,
                'why_grown' => 'Premium plantation crop; climate perfectly suited',
                'ideal_soil' => 'Well-drained laterite soils; acidic soils ideal',
                'market_demand' => 'Global beverage market; India world\'s largest tea producer',
                'government_support' => 'Tea Board support; export promotion initiatives',
                'emoji' => '🍵',
                'expected_yield' => '2000-2500 kg/hectare',
            ],
            [
                'name' => 'Jowar',
                'season' => 'kharif',
                'water_requirement' => 400,
                'why_grown' => 'Extremely drought tolerant. Sandy soil ideal. Low water needs.',
                'ideal_soil' => 'Sandy, Loamy',
                'market_demand' => 'Medium - Animal feed & food',
                'government_support' => 'MSP support, Drought resilient crop',
                'emoji' => '🌾',
                'expected_yield' => '20-30 quintals/hectare',
            ],
            [
                'name' => 'Bajra',
                'season' => 'kharif',
                'water_requirement' => 350,
                'why_grown' => 'Highly drought resistant. Perfect for arid sandy soils.',
                'ideal_soil' => 'Sandy, Sandy-Loam',
                'market_demand' => 'Medium-High - Health food trend',
                'government_support' => 'MSP: ₹2350/quintal',
                'emoji' => '🌾',
                'expected_yield' => '15-20 quintals/hectare',
            ],
            [
                'name' => 'Mustard',
                'season' => 'rabi',
                'water_requirement' => 350,
                'why_grown' => 'Perfectly suited for low rainfall. Winter crop for sandy soils.',
                'ideal_soil' => 'Sandy, Well-drained',
                'market_demand' => 'Very High - Oil industry',
                'government_support' => 'MSP: ₹5900/quintal',
                'emoji' => '🌱',
                'expected_yield' => '15-20 quintals/hectare',
            ],
            [
                'name' => 'Coconut',
                'season' => 'year-round',
                'water_requirement' => 1500,
                'why_grown' => 'Perfect rainfall and soil conditions. Reliable income source.',
                'ideal_soil' => 'Loamy, Sandy-Loam',
                'market_demand' => 'Very High - Multiple uses',
                'government_support' => 'Coconut Development Board schemes',
                'emoji' => '🥥',
                'expected_yield' => '60-80 nuts/tree/year',
            ],
            [
                'name' => 'Vegetables (Mixed)',
                'season' => 'year-round',
                'water_requirement' => 600,
                'why_grown' => 'Perfect for small holdings. High income potential. Year-round production.',
                'ideal_soil' => 'Loamy, Rich organic',
                'market_demand' => 'Very High - Daily demand',
                'government_support' => 'Horticulture Mission, Per drop more crop',
                'emoji' => '🥕',
                'expected_yield' => '200-300 quintals/hectare',
            ],
        ];

        foreach ($cropsData as $cropData) {
            Crop::create($cropData);
        }

        $regionProfiles = [
            [
                'key' => 'north-india',
                'name' => 'North India (Punjab, Haryana, Western UP)',
                'state' => 'Multiple',
                'soil_type' => 'Alluvial',
                'climate' => 'Humid Subtropical',
                'rainfall_range' => '400-900 mm',
                'season' => 'Rabi Season (Oct-March)',
                'health_score' => 85,
                'irrigation_count' => 3,
                'crop_weights' => [
                    'Wheat' => 50,
                    'Rice' => 30,
                    'Cotton' => 20,
                ],
            ],
            [
                'key' => 'central-india',
                'name' => 'Central India (Madhya Pradesh, Chhattisgarh)',
                'state' => 'Multiple',
                'soil_type' => 'Black',
                'climate' => 'Tropical',
                'rainfall_range' => '900-1400 mm',
                'season' => 'Kharif Season (June-October)',
                'health_score' => 72,
                'irrigation_count' => 2,
                'crop_weights' => [
                    'Soybean' => 45,
                    'Pulses (Chickpea, Lentil)' => 30,
                    'Sugarcane' => 25,
                ],
            ],
            [
                'key' => 'south-india',
                'name' => 'South India (Tamil Nadu, Karnataka, Telangana)',
                'state' => 'Multiple',
                'soil_type' => 'Red & Laterite',
                'climate' => 'Tropical Wet & Dry',
                'rainfall_range' => '600-1600 mm',
                'season' => 'Rabi & Kharif (Year-round)',
                'health_score' => 90,
                'irrigation_count' => 3,
                'crop_weights' => [
                    'Groundnut' => 40,
                    'Coffee' => 30,
                    'Spices (Turmeric, Pepper)' => 30,
                ],
            ],
            [
                'key' => 'east-india',
                'name' => 'East India (West Bengal, Assam, Odisha)',
                'state' => 'Multiple',
                'soil_type' => 'Alluvial & Laterite',
                'climate' => 'Tropical Monsoon',
                'rainfall_range' => '1600-2300 mm',
                'season' => 'Kharif Dominant',
                'health_score' => 45,
                'irrigation_count' => 2,
                'crop_weights' => [
                    'Rice' => 50,
                    'Jute' => 25,
                    'Tea' => 25,
                ],
            ],
        ];

        foreach ($regionProfiles as $profile) {
            $region = Region::create([
                'key' => $profile['key'],
                'name' => $profile['name'],
                'state' => $profile['state'],
                'soil_type' => $profile['soil_type'],
                'climate' => $profile['climate'],
                'rainfall_range' => $profile['rainfall_range'],
                'season' => $profile['season'],
                'health_score' => $profile['health_score'],
            ]);

            LandHolding::factory()->create([
                'region_id' => $region->id,
            ]);

            Irrigation::factory()
                ->count($profile['irrigation_count'])
                ->create([
                    'region_id' => $region->id,
                ]);

            foreach ($profile['crop_weights'] as $cropName => $weight) {
                $crop = Crop::where('name', $cropName)->first();
                if ($crop) {
                    CroppingPattern::create([
                        'region_id' => $region->id,
                        'crop_id' => $crop->id,
                        'area_percentage' => $weight,
                    ]);
                }
            }
        }
    }
}
