<?php

namespace App\Support;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MongoDB\BSON\UTCDateTime;

class MongoNotificationRoute
{
    public function __construct(private readonly Model $notifiable)
    {
    }

    public function create(array $payload): object
    {
        $id = (string) ($payload['id'] ?? Str::uuid());
        $timestamp = new UTCDateTime(now()->toDateTime());

        $document = [
            '_id' => $id,
            'id' => $id,
            'type' => $payload['type'] ?? null,
            'notifiable_type' => $this->notifiable::class,
            'notifiable_id' => (string) $this->notifiable->getKey(),
            'data' => $this->normalizeForMongo($payload['data'] ?? []),
            'read_at' => $this->normalizeForMongo($payload['read_at'] ?? null),
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];

        DB::connection('mongodb')
            ->getCollection('notifications')
            ->insertOne($document);

        return (object) $document;
    }

    private function normalizeForMongo(mixed $value): mixed
    {
        if ($value instanceof DateTimeInterface) {
            return new UTCDateTime($value);
        }

        if (is_array($value)) {
            return array_map(fn ($item) => $this->normalizeForMongo($item), $value);
        }

        if (is_object($value) && method_exists($value, '__toString')) {
            return (string) $value;
        }

        return $value;
    }
}
