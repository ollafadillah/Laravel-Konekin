<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MlRecommendationService
{
    public function baseUrl(): string
    {
        return rtrim((string) config('services.ml.base_url', 'http://127.0.0.1:5000'), '/');
    }

    public function timeout(): int
    {
        return (int) config('services.ml.timeout', 30);
    }

    public function connectTimeout(): int
    {
        return (int) config('services.ml.connect_timeout', 5);
    }

    public function status(): array
    {
        return $this->send('get', '/api/models/status', null, 5);
    }

    public function health(): array
    {
        return $this->send('get', '/health', null, 5);
    }

    public function recommend(array $payload): array
    {
        return $this->send('post', '/api/recommend', $payload);
    }

    public function recommendBySkills(array $payload): array
    {
        return $this->send('post', '/api/recommend/skills', $payload);
    }

    private function send(string $method, string $path, ?array $payload = null, ?int $timeout = null): array
    {
        try {
            $request = Http::baseUrl($this->baseUrl())
                ->acceptJson()
                ->timeout($timeout ?? $this->timeout())
                ->connectTimeout($this->connectTimeout());

            $response = match (strtolower($method)) {
                'get' => $request->get($path),
                'post' => $request->post($path, $payload ?? []),
                default => throw new RuntimeException('Unsupported ML request method: ' . $method),
            };
        } catch (ConnectionException $exception) {
            throw new RuntimeException(
                'Flask ML service belum bisa dihubungi. Pastikan `python app.py` sudah berjalan di `ml-service`.',
                503,
                $exception
            );
        }

        if ($response->failed()) {
            $body = trim((string) $response->body());
            $message = $response->json('error')
                ?: $response->json('message')
                ?: (filled($body) ? $body : 'Flask ML service mengembalikan error.');

            throw new RuntimeException($message, $response->status());
        }

        $data = $response->json();

        if (! is_array($data)) {
            throw new RuntimeException('Flask ML service mengembalikan response yang tidak valid.', 500);
        }

        return $data;
    }
}
