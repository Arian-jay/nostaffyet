<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SyncDatabaseUsers extends Command
{
    // Command signature and description
    protected $signature = 'sync:database-users';
    protected $description = 'Sync Laravel users with PostgreSQL database users';

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $dbUsername = strtolower($user->role . '_' . $user->name);
            $dbPassword = $user->password;

            $existingUser = DB::select("SELECT 1 FROM pg_roles WHERE rolname = ?", [$dbUsername]);

            if (!$existingUser) {
                DB::statement("CREATE USER \"$dbUsername\" WITH PASSWORD '$dbPassword'");

                if ($user->role === 'admin') {
                    DB::statement("GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO \"$dbUsername\"");
                } elseif ($user->role === 'staff') {
                    DB::statement("GRANT SELECT, INSERT, UPDATE ON ALL TABLES IN SCHEMA public TO \"$dbUsername\"");
                } elseif ($user->role === 'customer') {
                    DB::statement("GRANT SELECT ON ALL TABLES IN SCHEMA public TO \"$dbUsername\"");
                }
            }
        }

        $this->info('Database users synced successfully!');
    }
}
