<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AsaasWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $token = $request->header('asaas-access-token');

        if (! hash_equals((string) config('services.asaas.webhook_token'), (string) $token)) {
            Log::warning('Asaas webhook: token inválido');

            return response('Unauthorized', 401);
        }

        $event = $request->input('event');
        $payment = $request->input('payment', []);
        $subscriptionPayload = $request->input('subscription', []);

        Log::info('Asaas webhook', [
            'event' => $event,
            'payment_id' => $payment['id'] ?? null,
            'subscription' => $payment['subscription'] ?? ($subscriptionPayload['id'] ?? null),
        ]);

        $asaasSubscriptionId = $payment['subscription']
            ?? ($subscriptionPayload['id'] ?? null);

        if ($asaasSubscriptionId) {
            $this->syncSubscriptionStatus($event, $asaasSubscriptionId, $payment);
        }

        // Asaas exige HTTP 200
        return response()->json(['received' => true], 200);
    }

    protected function syncSubscriptionStatus(string $event, string $asaasSubscriptionId, array $payment): void
    {
        $subscription = Subscription::query()
            ->where('asaas_subscription_id', $asaasSubscriptionId)
            ->first();

        if (! $subscription) {
            Log::warning('Asaas webhook: subscription local não encontrada', [
                'asaas_subscription_id' => $asaasSubscriptionId,
            ]);

            return;
        }

        $status = match ($event) {
            'PAYMENT_RECEIVED', 'PAYMENT_CONFIRMED' => 'active',
            'PAYMENT_OVERDUE' => 'overdue',
            'PAYMENT_CREATED' => 'pending',
            'SUBSCRIPTION_DELETED', 'SUBSCRIPTION_INACTIVATED' => 'canceled',
            default => null,
        };

        if ($status) {
            $subscription->update([
                'status' => $status,
                'current_period_end' => isset($payment['dueDate'])
                    ? \Carbon\Carbon::parse($payment['dueDate'])->endOfDay()
                    : now()->addMonth(),
            ]);
        }
    }
}
