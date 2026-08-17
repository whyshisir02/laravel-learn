<?php

namespace App\Http\Controllers\Api\V1;

// use OpenApi\Attributes as OA;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\User;

class UserController extends Controller
{
    
    public function show(int $id)
    {
        $user = User::with('subscriptions.plan')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    // public function show(int $id)
    // {
    //     $user = User::with('subscriptions.plan')
    //         ->findOrFail($id);

    //     return response()->json([
    //         'success' => true,
    //         'data' => [
    //             'id' => $user->id,
    //             'name' => $user->name,

    //             'subscriptions' => $user->subscriptions->map(function ($subscription) {
    //                 return [
    //                     'id' => $subscription->id,
    //                     'name' => $subscription->name,
    //                     'subscription_type' => $subscription->subscription_type,
    //                     'amount' => $subscription->amount,
    //                     'paid' => $subscription->paid,

    //                     'plan' => [
    //                         'id' => $subscription->plan->id,
    //                         'name' => $subscription->plan->name,
    //                         'price' => $subscription->plan->price,
    //                     ],
    //                 ];
    //             }),
    //         ],
    //     ]);
    // }

    public function plan(int $id)
    {
        $plan = SubscriptionPlan::with('subscriptions.user')->findOrFail($id);

        $totalUsers = $plan->subscriptions->count();

        return response()->json([
            'success' => true,
            // 'data' => [
            //     'id' => $plan->id,
            //     'name' => $plan->name,
            //     'price' => $plan->price,

            //     'subscriptions' => $plan->subscriptions->map(function ($subscription) {
            //         return [
            //             'id' => $subscription->id,
            //             'name' => $subscription->name,
            //             'subscription_type' => $subscription->subscription_type,
            //             'amount' => $subscription->amount,
            //             'paid' => $subscription->paid,

            //             'user' => [
            //                 'id' => $subscription->user->id,
            //                 'name' => $subscription->user->name,
            //             ],
            //         ];
            //     }),
            // ],
            // 'data' => [
            //     'id' => $plan->id,
            //     'name' => $plan->name,
            //     'price' => $plan->price,
            //     'total_users' => $plan->subscriptions->count(),
            // ],

            'data' => $plan,
        ]);
    }
}