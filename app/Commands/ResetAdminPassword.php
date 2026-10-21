<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ResetAdminPassword extends BaseCommand
{
    protected $group = 'Database';
    protected $name = 'admin:reset-password';
    protected $description = 'Reset the admin account password.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        $user = $db->table('users')
            ->where('username', 'admin')
            ->get()
            ->getRowArray();

        if (! $user) {
            CLI::error('Admin user was not found.');
            return;
        }

        $updated = $db->table('users')
            ->where('username', 'admin')
            ->update([
                'password' => password_hash('Admin123!', PASSWORD_DEFAULT),
            ]);

        if ($updated && password_verify(
            'Admin123!',
            $db->table('users')
                ->where('username', 'admin')
                ->get()
                ->getRowArray()['password']
        )) {
            CLI::write('Admin password reset successfully.', 'green');
        } else {
            CLI::error('Password reset failed. Check the database connection.');
        }
    }
}
