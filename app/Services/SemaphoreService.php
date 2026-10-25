<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service for sending SMS messages via the Semaphore API.
 */
class SemaphoreService
{
    protected string $apiKey;

    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = (string) config('services.semaphore.api_key', '');
        $this->baseUrl = config('services.semaphore.url', 'https://api.semaphore.co/api/v4/messages');
    }

    /**
     * Send an SMS message to the specified phone number.
     *
     * @param  string  $number  Recipient phone number
     * @param  string  $message  Message content
     * @return array<string, mixed>
     *
     * @throws \Exception
     */
    public function sendSMS(string $number, string $message): array
    {
        $response = Http::post($this->baseUrl, [
            'apikey' => $this->apiKey,
            'number' => $number,
            'message' => $message,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Semaphore SMS failed', [
            'number' => $number,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        throw new \Exception('Failed to send SMS: '.$response->body());
    }
}
