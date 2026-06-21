<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TicketService
{
    public function createTicket(array $data): array
    {
        $apiBaseUrl = implode('/', array_slice(explode('/', config('services.ticket_system.support_domain')), 0, 3));

        $response = Http::withHeaders([
            'X-API-Key' => config('services.ticket_system.api_key'),
            'X-API-Secret' => config('services.ticket_system.api_secret'),
            'Accept' => 'application/json',
        ])->post(
            $apiBaseUrl . '/api/v2/tickets',
            $data
        );

        if (! $response->successful()) {
            $message = $response->json('message', 'Ticket creation failed');
            $errors = $response->json('errors');
            if ($errors) {
                $message .= ': ' . json_encode($errors);
            }
            throw new \Exception($message);
        }

        return $response->json();
    }
}
