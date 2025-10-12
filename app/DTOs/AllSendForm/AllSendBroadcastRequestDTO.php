<?php

declare(strict_types=1);

namespace App\DTOs\AllSendForm;

use Illuminate\Http\Request;

class AllSendBroadcastRequestDTO
{
    public function __construct(
        public readonly ?string $uuid,
        public readonly string $text,
        public readonly ?string $textShort = null,
        public readonly ?array $subscriptions = null,
        public readonly ?array $privateFeeds = null,
        public readonly ?bool $isSchedule = false,
        public readonly ?string $schedule = null,
        public readonly ?string $timezone = null,
        public readonly ?string $url = null,
        public readonly ?bool $isLink = false,
        public readonly ?array $files = null
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            uuid: $data['uuid'] ?? null,
            text: $data['text'],
            textShort: $data['text_short'] ?? null,
            subscriptions: $data['subscriptions'] ?? null,
            privateFeeds: $data['private_feeds'] ?? null,
            isSchedule: $data['is_schedule'] ?? false,
            schedule: $data['schedule'] ?? null,
            timezone: $data['timezone'] ?? null,
            url: $data['url'] ?? null,
            isLink: $data['is_link'] ?? false,
            files: $data['files'] ?? null
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            uuid: $request->input('uuid') ?: null,
            text: $request->input('text'),
            textShort: $request->input('text_short'),
            subscriptions: $request->input('subscriptions'),
            privateFeeds: $request->input('private_feeds'),
            isSchedule: $request->boolean('is_schedule'),
            schedule: $request->input('schedule'),
            timezone: $request->input('timezone'),
            url: $request->input('url'),
            isLink: $request->boolean('is_link'),
            files: $request->file('files')
        );
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'text' => $this->text,
            'text_short' => $this->textShort,
            'subscriptions' => $this->subscriptions,
            'private_feeds' => $this->privateFeeds,
            'is_schedule' => $this->isSchedule,
            'schedule' => $this->schedule,
            'timezone' => $this->timezone,
            'url' => $this->url,
            'is_link' => $this->isLink,
            'files' => $this->files,
        ];
    }

    public function validate(): bool
    {
        return ! empty($this->text);
    }
}
