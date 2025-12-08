<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Enums\ProjectStage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Super Admin
        $admin = User::create([
            'name' => 'Elite Admin',
            'email' => 'admin@eliteelevators.com',
            'phone' => '+2348000000000',
            'password' => Hash::make('password'), // Easy password for testing
            'is_admin' => true,
            'company_name' => 'Elite Elevators HQ',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin created: admin@eliteelevators.com / password');

        // 2. Create Clients
        $clients = [
            [
                'name' => 'Lekki Gardens Ltd',
                'email' => 'info@lekkigardens.com',
                'company_name' => 'Lekki Gardens',
                'phone' => '+2348012345678',
            ],
            [
                'name' => 'Dangote Refinery Ops',
                'email' => 'logistics@dangote.com',
                'company_name' => 'Dangote Group',
                'phone' => '+2348098765432',
            ],
            [
                'name' => 'Eko Atlantic Towers',
                'email' => 'maintenance@ekoatlantic.com',
                'company_name' => 'Eko Atlantic',
                'phone' => '+2348123456789',
            ],
            [
                'name' => 'Mr. Tunde Bakare',
                'email' => 'tunde@gmail.com',
                'company_name' => null, // Individual client
                'phone' => '+2347055555555',
            ],
        ];

        foreach ($clients as $clientData) {
            $user = User::create(array_merge($clientData, [
                'password' => Hash::make('password'),
                'is_admin' => false,
                'address' => '123 Victoria Island, Lagos',
                'email_verified_at' => now(),
            ]));

            // 3. Create Projects for this Client
            $this->createProjectsForClient($user);
        }
    }

    private function createProjectsForClient(User $user)
    {
        // Scenario A: Just Started (Scheduled Visit)
        $p1 = Project::create([
            'user_id' => $user->id,
            'title' => $user->company_name ? "{$user->company_name} - Main Reception Lift" : "Private Residence Lift",
            'location' => 'Victoria Island, Lagos',
            'current_status' => ProjectStage::SCHEDULED_VISIT,
            'notes' => 'Client requires a glass finish cabin.',
            'tracking_code' => strtoupper(fake()->bothify('??####??')),
        ]);
        
        $p1->updates()->create([
            'status_key' => ProjectStage::SCHEDULED_VISIT,
            'description' => 'Project initiated. Site visit scheduled for measurements.',
            'created_at' => now()->subDays(2),
        ]);

        // Scenario B: In Production (Active)
        $p2 = Project::create([
            'user_id' => $user->id,
            'title' => $user->company_name ? "{$user->company_name} - Cargo Lift" : "Duplex Cargo Lift",
            'location' => 'Lekki Phase 1',
            'current_status' => ProjectStage::PRODUCTION,
            'installation_date' => now()->addDays(45),
            'notes' => 'Heavy duty motor ordered.',
            'tracking_code' => strtoupper(fake()->bothify('??####??')),
        ]);

        // Add history for B
        $this->addHistory($p2, [
            [ProjectStage::SCHEDULED_VISIT, 'Site measurements taken.', 20],
            [ProjectStage::DRAWING_COMPLETED, 'Technical drawings approved by client.', 15],
            [ProjectStage::PRODUCTION, 'Manufacturing process started in Turkey factory.', 2],
        ]);

        // Scenario C: Shipped (Logistics)
        $p3 = Project::create([
            'user_id' => $user->id,
            'title' => "Penthouse Elevator",
            'location' => 'Ikoyi, Lagos',
            'current_status' => ProjectStage::SHIPPED,
            'installation_date' => now()->addDays(20),
            'tracking_code' => strtoupper(fake()->bothify('??####??')),
        ]);

        $this->addHistory($p3, [
            [ProjectStage::SCHEDULED_VISIT, 'Initial consultation.', 60],
            [ProjectStage::DRAWING_COMPLETED, 'Drawings signed off.', 50],
            [ProjectStage::PRODUCTION, 'Production commenced.', 40],
            [ProjectStage::PRODUCTION_COMPLETED, 'Quality control passed.', 10],
            [ProjectStage::SHIPPED, 'Container loaded on MSC vessel #994.', 1],
        ]);
    }

    private function addHistory(Project $project, array $stages)
    {
        foreach ($stages as $stageData) {
            [$status, $desc, $daysAgo] = $stageData;
            $project->updates()->create([
                'status_key' => $status,
                'description' => $desc,
                'created_at' => now()->subDays($daysAgo),
            ]);
        }
    }
}