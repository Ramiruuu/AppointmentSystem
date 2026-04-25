<?php

namespace Database\Seeders;

use App\Models\Hospital;
use Illuminate\Database\Seeder;

class HospitalSeeder extends Seeder
{
    public function run()
    {
        $hospitals = [
            [
                'name' => 'Cagayan de Oro Medical Center',
                'address' => 'Barangay Carmen, Cagayan de Oro City',
                'latitude' => 8.4762,
                'longitude' => 124.6449,
                'phone' => '(088) 856-3210',
                'email' => 'info@cdomedical.com',
                'operating_hours' => '24/7',
                'services_offered' => json_encode(['General Checkup', 'Dermatology', 'Cardiology', 'Dentistry', 'Physical Therapy', 'X-Ray', 'Laboratory'])
            ],
            [
                'name' => 'Maria Reyna Hospital',
                'address' => 'Capt. Vicente Roa St., Cagayan de Oro City',
                'latitude' => 8.4856,
                'longitude' => 124.6502,
                'phone' => '(088) 857-1533',
                'email' => 'info@mariareyna.com',
                'operating_hours' => '24/7',
                'services_offered' => json_encode(['General Checkup', 'Cardiology', 'Pediatrics', 'OB-Gyne', 'Surgery', 'Dermatology'])
            ],
            [
                'name' => 'The Medical City CDO',
                'address' => 'Masterson Mile, Upper Carmen, Cagayan de Oro City',
                'latitude' => 8.4738,
                'longitude' => 124.6531,
                'phone' => '(088) 888-0456',
                'email' => 'cdo@themedicalcity.com',
                'operating_hours' => '24/7',
                'services_offered' => json_encode(['General Checkup', 'Cardiology', 'Neurology', 'Orthopedics', 'Dermatology', 'ENT', 'Ophthalmology'])
            ],
            [
                'name' => 'Sabal General Hospital',
                'address' => 'Tirso Neri St., Cagayan de Oro City',
                'latitude' => 8.4815,
                'longitude' => 124.6467,
                'phone' => '(088) 856-2888',
                'email' => 'info@sabalhospital.com',
                'operating_hours' => '24/7',
                'services_offered' => json_encode(['General Checkup', 'Pediatrics', 'Dentistry', 'Laboratory', 'Physical Therapy'])
            ],
            [
                'name' => 'Polymedic General Hospital',
                'address' => 'Velez St., Cagayan de Oro City',
                'latitude' => 8.4832,
                'longitude' => 124.6524,
                'phone' => '(088) 857-1212',
                'email' => 'polymedic@yahoo.com',
                'operating_hours' => '24/7',
                'services_offered' => json_encode(['General Checkup', 'Cardiology', 'Dermatology', 'Orthopedics', 'Surgery', 'Laboratory'])
            ],
            [
                'name' => 'Madonna and Child Hospital',
                'address' => 'Corrales Ave., Cagayan de Oro City',
                'latitude' => 8.4798,
                'longitude' => 124.6489,
                'phone' => '(088) 856-1732',
                'email' => 'info@madonnahospital.com',
                'operating_hours' => '24/7',
                'services_offered' => json_encode(['General Checkup', 'Pediatrics', 'OB-Gyne', 'Dentistry', 'Physical Therapy'])
            ],
            [
                'name' => 'ACE Medical Center CDO',
                'address' => 'Corrales Extension, Cagayan de Oro City',
                'latitude' => 8.4851,
                'longitude' => 124.6435,
                'phone' => '(088) 323-4567',
                'email' => 'acemedical@acemed.com',
                'operating_hours' => '24/7',
                'services_offered' => json_encode(['General Checkup', 'Cardiology', 'Dermatology', 'Neurology', 'Ophthalmology', 'ENT'])
            ],
            [
                'name' => 'Northern Mindanao Medical Center',
                'address' => 'Capt. Vicente Roa St., Cagayan de Oro City',
                'latitude' => 8.4874,
                'longitude' => 124.6493,
                'phone' => '(088) 850-1234',
                'email' => 'nmmc@doh.gov.ph',
                'operating_hours' => '24/7',
                'services_offered' => json_encode(['General Checkup', 'Cardiology', 'Pediatrics', 'Surgery', 'Orthopedics', 'Dermatology', 'Psychiatry'])
            ],
            [
                'name' => 'Cagayan de Oro Polymedic Medical Plaza',
                'address' => 'Pueblo de Oro, Cagayan de Oro City',
                'latitude' => 8.4689,
                'longitude' => 124.6598,
                'phone' => '(088) 851-2345',
                'email' => 'polymedicplaza@gmail.com',
                'operating_hours' => '24/7',
                'services_offered' => json_encode(['General Checkup', 'Dermatology', 'Cardiology', 'Physical Therapy', 'Dentistry'])
            ],
            [
                'name' => 'St. Francis General Hospital',
                'address' => 'Tomas Saco St., Cagayan de Oro City',
                'latitude' => 8.4768,
                'longitude' => 124.6517,
                'phone' => '(088) 856-9876',
                'email' => 'stfrancis@yahoo.com',
                'operating_hours' => '24/7',
                'services_offered' => json_encode(['General Checkup', 'Pediatrics', 'OB-Gyne', 'Dentistry', 'Laboratory'])
            ],
        ];

        foreach ($hospitals as $hospital) {
            Hospital::create($hospital);
        }
    }
}