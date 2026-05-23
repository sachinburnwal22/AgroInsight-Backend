<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\Product;
use Illuminate\Database\Seeder;

class MarketSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Fertilizer Store
        $shop1 = Shop::create([
            'name' => 'BioGrow Fertilizers',
            'category' => 'Fertilizer Store',
            'description' => 'Premium organic and chemical crop nutrients.',
            'position_x' => -15,
            'position_z' => -10,
        ]);

        Product::create([
            'shop_id' => $shop1->id,
            'name' => 'Urea Fertilizer (50kg Bag)',
            'description' => 'High nitrogen content fertilizer designed to stimulate vegetative growth in crops like rice and wheat.',
            'price' => 350.00,
            'stock' => 50,
            'image' => 'https://images.unsplash.com/photo-1599599810769-bcde5a160d32?auto=format&fit=crop&w=600&q=80',
        ]);

        Product::create([
            'shop_id' => $shop1->id,
            'name' => 'NPK Nutrient Complex (25kg)',
            'description' => 'Balanced Nitrogen, Phosphorus, and Potassium blend to promote root development and seed maturity.',
            'price' => 520.00,
            'stock' => 35,
            'image' => 'https://images.unsplash.com/photo-1592595896551-12b371d546d5?auto=format&fit=crop&w=600&q=80',
        ]);

        Product::create([
            'shop_id' => $shop1->id,
            'name' => 'Organic Vermicompost (10kg)',
            'description' => '100% natural earthworm compost to improve soil aeration, structure, and water retention capacity.',
            'price' => 180.00,
            'stock' => 100,
            'image' => 'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?auto=format&fit=crop&w=600&q=80',
        ]);

        // 2. Seed Store
        $shop2 = Shop::create([
            'name' => 'Astra Seeds',
            'category' => 'Seed Store',
            'description' => 'Certified high-yield crop seeds.',
            'position_x' => 15,
            'position_z' => -10,
        ]);

        Product::create([
            'shop_id' => $shop2->id,
            'name' => 'Premium Basmati Rice Seeds (5kg)',
            'description' => 'Grade-A aromatic Basmati rice seeds, disease-resistant and optimized for long grains.',
            'price' => 450.00,
            'stock' => 40,
            'image' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=600&q=80',
        ]);

        Product::create([
            'shop_id' => $shop2->id,
            'name' => 'Hybrid Wheat Seeds (10kg)',
            'description' => 'High-yield hybrid wheat seeds suitable for drylands and resistant to yellow rust.',
            'price' => 380.00,
            'stock' => 60,
            'image' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?auto=format&fit=crop&w=600&q=80',
        ]);

        Product::create([
            'shop_id' => $shop2->id,
            'name' => 'High-Yield Mustard Seeds (2kg)',
            'description' => 'Certified mustard seeds with high oil content and excellent field adaptation.',
            'price' => 290.00,
            'stock' => 30,
            'image' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=600&q=80',
        ]);

        // 3. Farming Tools
        $shop3 = Shop::create([
            'name' => 'Kisan Equipments',
            'category' => 'Farming Tools',
            'description' => 'Durable hand tools and heavy machinery.',
            'position_x' => -20,
            'position_z' => 15,
        ]);

        Product::create([
            'shop_id' => $shop3->id,
            'name' => 'Ergonomic Steel Spade',
            'description' => 'Heavy-duty agricultural spade with tempered steel blade and ash-wood handle.',
            'price' => 650.00,
            'stock' => 15,
            'image' => 'https://images.unsplash.com/photo-1589156280159-27698a70f29e?auto=format&fit=crop&w=600&q=80',
        ]);

        Product::create([
            'shop_id' => $shop3->id,
            'name' => 'Backpack Crop Sprayer (16L)',
            'description' => 'Manual pressure backpack sprayer for pesticide, fungicide, and herbicide applications.',
            'price' => 1850.00,
            'stock' => 10,
            'image' => 'https://images.unsplash.com/photo-1563514220-ea979fda5a18?auto=format&fit=crop&w=600&q=80',
        ]);

        // 4. Irrigation Equipment
        $shop4 = Shop::create([
            'name' => 'HydroFlow Systems',
            'category' => 'Irrigation Equipment',
            'description' => 'Efficient water distribution equipment.',
            'position_x' => 20,
            'position_z' => 15,
        ]);

        Product::create([
            'shop_id' => $shop4->id,
            'name' => 'Drip Irrigation Starter Kit (0.5 Acre)',
            'description' => 'Includes emitters, main lateral tubes, filters, pressure regulator, and connectors for precise watering.',
            'price' => 3200.00,
            'stock' => 8,
            'image' => 'https://images.unsplash.com/photo-1535090486161-40c666874e44?auto=format&fit=crop&w=600&q=80',
        ]);

        Product::create([
            'shop_id' => $shop4->id,
            'name' => 'Submersible Water Pump (1 HP)',
            'description' => 'High flow submersible water pump for borewells and open wells, rust-proof cast iron.',
            'price' => 4500.00,
            'stock' => 5,
            'image' => 'https://images.unsplash.com/photo-1508962914676-134849a727f0?auto=format&fit=crop&w=600&q=80',
        ]);

        // 5. Animal Feed Shop
        $shop5 = Shop::create([
            'name' => 'NutriFeed Corner',
            'category' => 'Animal Feed Shop',
            'description' => 'Premium cattle, poultry, and fish feed.',
            'position_x' => 0,
            'position_z' => -25,
        ]);

        Product::create([
            'shop_id' => $shop5->id,
            'name' => 'High-Protein Cattle Feed (50kg)',
            'description' => 'Enriched with essential minerals and vitamins to maximize milk yield and support digestion.',
            'price' => 850.00,
            'stock' => 25,
            'image' => 'https://images.unsplash.com/photo-1516467508483-a7212febe31a?auto=format&fit=crop&w=600&q=80',
        ]);

        Product::create([
            'shop_id' => $shop5->id,
            'name' => 'Premium Poultry Starter Feed (25kg)',
            'description' => 'Balanced nutrition mash feed designed for broiler chicks to support healthy growth phases.',
            'price' => 720.00,
            'stock' => 30,
            'image' => 'https://images.unsplash.com/photo-1604848698030-c434ba08ece1?auto=format&fit=crop&w=600&q=80',
        ]);
    }
}
