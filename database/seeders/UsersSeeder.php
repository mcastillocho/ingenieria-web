<?php

namespace Database\Seeders;

use App\Models\Credential;
use App\Models\Worker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'lastname' => 'Abad',
                'document_type' => 'DNI',
                'document_number' => '00000001',
                'email' => 'admin@abad.local',
                'phone' => '900000001',
                'username' => 'admin',
                'password' => 'Admin#123',
                'role' => 'admin',
            ],
            [
                'name' => 'Logistica',
                'lastname' => 'Abad',
                'document_type' => 'DNI',
                'document_number' => '00000002',
                'email' => 'logistica@abad.local',
                'phone' => '900000002',
                'username' => 'logistica',
                'password' => 'Logistica#123',
                'role' => 'logistica',
            ],
            [
                'name' => 'Ventas',
                'lastname' => 'Abad',
                'document_type' => 'DNI',
                'document_number' => '00000003',
                'email' => 'ventas@abad.local',
                'phone' => '900000003',
                'username' => 'ventas',
                'password' => 'Ventas#123',
                'role' => 'ventas',
            ],
        ];

        foreach ($users as $user) {
            $worker = Worker::updateOrCreate(
                [
                    'document_type' => $user['document_type'],
                    'document_number' => $user['document_number'],
                ],
                [
                    'name' => $user['name'],
                    'lastname' => $user['lastname'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                ]
            );

            Credential::updateOrCreate(
                ['username' => $user['username']],
                [
                    'worker_id' => $worker->id,
                    'password' => Hash::make($user['password']),
                    'role' => $user['role'],
                ]
            );
        }
    }
}
