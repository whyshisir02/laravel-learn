<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plan = SubscriptionPlan::inRandomOrder()->first();

        return [
            'subscription_plan_id' => $plan->id,
            'name' => $plan->name . ' Subscription',
            'subscription_type' => fake()->randomElement([
                'monthly',
                'yearly',
            ]),
            'amount' => $plan->price,
            'paid' => fake()->boolean(80),
        ];
    }
}
