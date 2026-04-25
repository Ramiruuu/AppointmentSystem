<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'General Consultation', 'description' => 'A comprehensive general health consultation with our doctor.', 'duration_minutes' => 30, 'price' => 50.00],
            ['name' => 'Dental Checkup',        'description' => 'Full dental examination including cleaning and x-ray review.',  'duration_minutes' => 45, 'price' => 80.00],
            ['name' => 'Eye Exam',              'description' => 'Complete vision and eye health examination.',                    'duration_minutes' => 30, 'price' => 60.00],
            ['name' => 'Vaccination',           'description' => 'Standard vaccination service for adults and children.',          'duration_minutes' => 15, 'price' => 25.00],
            ['name' => 'Physiotherapy',         'description' => 'One-on-one physiotherapy session with a licensed therapist.',   'duration_minutes' => 60, 'price' => 100.00],
            ['name' => 'Blood Test',            'description' => 'Routine blood panel test with results within 24 hours.',        'duration_minutes' => 15, 'price' => 40.00],
        ];

        foreach ($services as $service) {
            Service::create(array_merge($service, ['is_active' => true]));
        }
    }
}