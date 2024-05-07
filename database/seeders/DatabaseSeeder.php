<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        User::factory(10)->create();

        $n = 0;

        for ($i = 1; $i < 41; $i++) {
            $status = (rand(1, 2) == 1) ? 'done' : 'todo';
            $id = DB::table('tasks')->insertGetId([
                'title' => 'Task '.($i + $n),
                'description' => 'Description for task '.($i + $n),
                'status' => $status,
                'priority' => rand(1, 5),
                'user_id' => rand(1, 10),
                'completed_at' => $status == 'done' ? date('Y-m-d H:i:s') : null,
            ]);
            if ($status == 'todo') {
                $id2 = DB::table('tasks')->insertGetId([
                    'title' => 'Subtask',
                    'parent_id' => $id,
                    'description' => 'Description for subtask',
                    'status' => 'todo',
                    'priority' => rand(1, 5),
                    'user_id' => rand(1, 10),
                ]);
                $n++;

                if (rand(1, 2) == 1) {
                    DB::table('tasks')->insert([
                        'title' => 'Sub-subtask',
                        'parent_id' => $id,
                        'description' => 'Description for sub-subtask',
                        'status' => 'todo',
                        'priority' => rand(1, 5),
                        'user_id' => rand(1, 10),
                    ]);
                    $n++;
                }
            }
        }

    }
}
