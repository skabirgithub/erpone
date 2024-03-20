<?php

namespace Database\Seeders;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::create(
            [
                'name' => 'Free Plan',
                'price' => 0,
                'duration' => 'lifetime',
                'max_users' => 1000,
                'max_customers' => 50000,
                'max_venders' => 50000,
                'max_clients' => 50000,
                'storage_limit' => 1024,
                'crm' => 1,
                'hrm' => 1,
                'account' => 1,
                'project' => 1,
                'pos' => 1,
                'chatgpt' => 1,
                'image'=>'free_plan.png',
            ]
        );
    }
}
