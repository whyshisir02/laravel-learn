<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        // Create subscription plans
        $basic = SubscriptionPlan::create([
            'name' => 'Basic Plan',
            'price' => 500,
        ]);

        $premium = SubscriptionPlan::create([
            'name' => 'Premium Plan',
            'price' => 1000,
        ]);

        $pro = SubscriptionPlan::create([
            'name' => 'Pro Plan',
            'price' => 2000,
        ]);

        // Create users
        $users = User::factory(10)->create();

        // /
        
        foreach ($users as $user) {

            // Randomly decide how many subscriptions
            $subscriptionCount = fake()->numberBetween(0, 3);

            for ($i = 0; $i < $subscriptionCount; $i++) {

                $plan = collect([
                    $basic,
                    $premium,
                    $pro,
                ])->random();

                Subscription::create([
                    'user_id' => $user->id,
                    'subscription_plan_id' => $plan->id,
                    'name' => $plan->name . ' Subscription',
                    'subscription_type' => fake()->randomElement([
                        'monthly',
                        'yearly',
                    ]),
                    'amount' => $plan->price,
                    'paid' => fake()->boolean(80),
                ]);
            }
    }
}}