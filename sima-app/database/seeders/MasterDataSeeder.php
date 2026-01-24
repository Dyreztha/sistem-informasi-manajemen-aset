<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Location;
use App\Models\Vendor;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categories
        $categories = [
            [
                'name' => 'IT & Computer',
                'code' => 'CAT-IT',
                'description' => 'Komputer, Laptop, Server, Networking',
                'depreciation_rate' => 25,
                'depreciation_method' => 'straight_line'
            ],
            [
                'name' => 'Furniture',
                'code' => 'CAT-FUR',
                'description' => 'Meja, Kursi, Lemari',
                'depreciation_rate' => 10,
                'depreciation_method' => 'straight_line'
            ],
            [
                'name' => 'Kendaraan',
                'code' => 'CAT-VEH',
                'description' => 'Mobil, Motor',
                'depreciation_rate' => 20,
                'depreciation_method' => 'double_declining'
            ],
            [
                'name' => 'Elektronik',
                'code' => 'CAT-ELC',
                'description' => 'AC, TV, Proyektor',
                'depreciation_rate' => 15,
                'depreciation_method' => 'straight_line'
            ],
            [
                'name' => 'Mesin & Peralatan',
                'code' => 'CAT-MCH',
                'description' => 'Mesin Produksi, Generator',
                'depreciation_rate' => 12,
                'depreciation_method' => 'straight_line'
            ],
        ];
        
        foreach ($categories as $category) {
            Category::create($category);
        }
        
        // Locations
        $locations = [
            // Head Office
            [
                'name' => 'Kantor Pusat',
                'code' => 'LOC-HO',
                'building' => 'Gedung A',
                'floor' => null,
                'room' => null,
                'address' => 'Jl. Sudirman No. 123, Jakarta',
                'parent_id' => null
            ],
            // Floors
            [
                'name' => 'Lantai 1',
                'code' => 'LOC-HO-L1',
                'building' => 'Gedung A',
                'floor' => '1',
                'room' => null,
                'address' => null,
                'parent_id' => 1
            ],
            [
                'name' => 'Lantai 2',
                'code' => 'LOC-HO-L2',
                'building' => 'Gedung A',
                'floor' => '2',
                'room' => null,
                'address' => null,
                'parent_id' => 1
            ],
            // Rooms
            [
                'name' => 'Ruang Server',
                'code' => 'LOC-HO-L1-SRV',
                'building' => 'Gedung A',
                'floor' => '1',
                'room' => 'Server Room',
                'address' => null,
                'parent_id' => 2
            ],
            [
                'name' => 'Ruang IT',
                'code' => 'LOC-HO-L1-IT',
                'building' => 'Gedung A',
                'floor' => '1',
                'room' => 'IT Department',
                'address' => null,
                'parent_id' => 2
            ],
            [
                'name' => 'Ruang Staff',
                'code' => 'LOC-HO-L2-STF',
                'building' => 'Gedung A',
                'floor' => '2',
                'room' => 'Staff Area',
                'address' => null,
                'parent_id' => 3
            ],
            [
                'name' => 'Ruang Meeting',
                'code' => 'LOC-HO-L2-MTG',
                'building' => 'Gedung A',
                'floor' => '2',
                'room' => 'Meeting Room A',
                'address' => null,
                'parent_id' => 3
            ],
            // Warehouse
            [
                'name' => 'Gudang Utama',
                'code' => 'LOC-WH',
                'building' => 'Gudang',
                'floor' => null,
                'room' => null,
                'address' => 'Jl. Industri No. 45, Jakarta',
                'parent_id' => null
            ],
        ];
        
        foreach ($locations as $location) {
            Location::create($location);
        }
        
        // Vendors
        $vendors = [
            [
                'name' => 'PT. Tech Solutions Indonesia',
                'code' => 'VEN-TSI',
                'email' => 'sales@techsolutions.co.id',
                'phone' => '021-12345678',
                'address' => 'Jl. Gatot Subroto, Jakarta',
                'contact_person' => 'Budi Santoso',
                'notes' => 'Vendor IT Equipment'
            ],
            [
                'name' => 'CV. Furniture Jaya',
                'code' => 'VEN-FJY',
                'email' => 'order@furniturejaya.com',
                'phone' => '021-87654321',
                'address' => 'Jl. Tanjung Duren, Jakarta',
                'contact_person' => 'Siti Aminah',
                'notes' => 'Vendor Furniture'
            ],
            [
                'name' => 'PT. Auto Mandiri',
                'code' => 'VEN-ATM',
                'email' => 'sales@automandiri.co.id',
                'phone' => '021-55555555',
                'address' => 'Jl. MT Haryono, Jakarta',
                'contact_person' => 'Ahmad Dahlan',
                'notes' => 'Dealer Kendaraan'
            ],
            [
                'name' => 'Toko Elektronik Maju',
                'code' => 'VEN-TEM',
                'email' => 'info@elektronikmaju.com',
                'phone' => '021-66666666',
                'address' => 'Jl. Mangga Dua, Jakarta',
                'contact_person' => 'Rina Wijaya',
                'notes' => 'Vendor Elektronik'
            ],
        ];
        
        foreach ($vendors as $vendor) {
            Vendor::create($vendor);
        }
    }
}
