<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $demoUser = User::factory()->create([
            'name' => 'Demo Manager',
            'email' => 'demo@boncard.test',
        ]);

        Card::factory()->count(18)->for($demoUser)->create();

        User::factory()
            ->count(3)
            ->create()
            ->each(function (User $user): void {
                Card::factory()
                    ->count(fake()->numberBetween(4, 12))
                    ->for($user)
                    ->create();
            });
    }
}
