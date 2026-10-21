<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialUserSeeder extends Seeder
{
    public function run()
    {
        $exists = $this->db->table('users')->where('username', 'admin')->countAllResults();
        if ($exists > 0) {
            echo "Admin user already exists; no changes made.\n";
            return;
        }

        $this->db->table('users')->insert([
            'username' => 'admin',
            'full_name' => 'POS Administrator',
            'password' => password_hash('Admin123!', PASSWORD_DEFAULT),
            'avatar' => null,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        echo "Initial account created: username admin / password Admin123!\nChange this password after logging in.\n";
    }
}
