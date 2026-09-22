<?php

namespace App\Services\Asaas;

use App\Models\Plan;
use App\Models\School;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class AsaasSubscriptionService
{
      public function __construct(
            protected AsaasClient $client
      ) {}

      public function ensureCustomer(School $school): string
      {
            if (filled($school->asaas_customer_id)) {
                  return $school->asaas_customer_id;
            }

            $cpfCnpj = preg_replace('/\D/', '', (string) $school->cnpj);

            $payload = array_filter([
                  'name' => $school->legal_name ?: $school->name,
                  'email' => $school->email,
                  'cpfCnpj' => $cpfCnpj,
                  'phone' => preg_replace('/\D/', '', (string) $school->phone) ?: null,
                  'mobilePhone' => preg_replace('/\D/', '', (string) $school->phone) ?: null,
                  'externalReference' => 'school_' . $school->id,
                  'notificationDisabled' => false,
                  'postalCode' => preg_replace('/\D/', '', (string) $school->zip_code) ?: null,
                  'address' => $school->street,
                  'addressNumber' => $school->number,
                  'complement' => $school->complement,
                  'province' => $school->district,
                  'city' => $school->city,
            ], fn($v) => $v !== null && $v !== '');

            $response = $this->client->request()
                  ->post('/customers', $payload)
                  ->json();

            $school->update(['asaas_customer_id' => $response['id']]);

            return $response['id'];
      }

      public function subscribe(School $school, Plan $plan, string $billingType = 'UNDEFINED'): Subscription
      {
            return DB::transaction(function () use ($school, $plan, $billingType) {
                  $customerId = $this->ensureCustomer($school);

                  $existing = Subscription::query()
                        ->where('school_id', $school->id)
                        ->whereNotNull('asaas_subscription_id')
                        ->whereIn('status', ['pending', 'active', 'overdue'])
                        ->first();

                  if ($existing) {
                        return $existing;
                  }

                  $response = $this->client->request()
                        ->post('/subscriptions', [
                              'customer' => $customerId,
                              'billingType' => $billingType,
                              'nextDueDate' => now()->addDay()->format('Y-m-d'),
                              'value' => (float) $plan->price,
                              'cycle' => 'MONTHLY',
                              'description' => 'Gabometro - ' . $plan->name,
                              'externalReference' => 'school_' . $school->id . '_plan_' . $plan->slug,
                        ])
                        ->json();

                  return Subscription::query()->updateOrCreate(
                        ['school_id' => $school->id],
                        [
                              'plan_id' => $plan->id,
                              'asaas_customer_id' => $customerId,
                              'asaas_subscription_id' => $response['id'],
                              // MVP: só libera no webhook de pagamento
                              'status' => 'pending',
                              'billing_type' => $billingType,
                              'current_period_end' => null,
                        ]
                  );
            });
      }
}
