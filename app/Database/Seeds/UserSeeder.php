<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@gym.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->table('users')->insert($data);
        
        echo "Admin user created successfully!\n";
        echo "Email: admin@gym.com\n";
        echo "Password: admin123\n";
    }
}
