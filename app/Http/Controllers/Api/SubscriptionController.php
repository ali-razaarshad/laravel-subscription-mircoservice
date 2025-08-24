<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    // GET /api/subscriptions (admin only)
    public function index(Request $request)
    {
        Gate::authorize('view-all-subscriptions');

        $subs = Subscription::with('user')
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 15));

        return response()->json($subs);
    }

    // POST /api/subscribe
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'plan_name'  => ['required', 'string', 'max:100'],
            'auto_renew' => ['sometimes', 'boolean'],
            'start_date' => ['sometimes', 'date'], // defaults to today
        ]);

        $user = $request->user();

        $start = isset($data['start_date']) ? \Carbon\Carbon::parse($data['start_date']) : now()->startOfDay();
        $end   = $start->copy()->addMonthNoOverflow()->startOfDay();

        $subscription = Subscription::create([
            'user_id'    => $user->id,
            'plan_name'  => $data['plan_name'],
            'start_date' => $start,
            'end_date'   => $end,
            'is_active'  => true,
            'auto_renew' => (bool)($data['auto_renew'] ?? false),
        ]);

        return response()->json([
            'message' => 'Subscribed successfully.',
            'data'    => $subscription,
        ], 201);
    }

    // GET /api/user/subscriptions
    public function myActive(Request $request)
    {
        $user = $request->user();

        $subs = Subscription::query()
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->orderByDesc('id')
            ->get();

        return response()->json($subs);
    }
}
