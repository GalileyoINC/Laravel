<?php

declare(strict_types=1);

namespace App\Domain\DTOs\PublicFeed;

use Illuminate\Http\Request;

class PublicFeedPublishRequestDTO
{
    /**
     * @param  array<int, mixed>  $files
     * @param  array<int, mixed>  $subscriptions
     */
    public function __construct(
        public readonly string $uuid,
        public readonly string $text,
        public readonly array $subscriptions,
        public readonly ?string $textShort = null,
        public readonly ?string $url = null,
        public readonly ?bool $isLink = false,
        public readonly ?array $files = null
    ) {}

    /**
     * @param array<string, mixed> $data     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        return new self(
            uuid: $data['uuid'],
            text: $data['text'],
            subscriptions: $data['subscriptions'] ?? [],
            textShort: $data['text_short'] ?? null,
            url: $data['url'] ?? null,
            isLink: $data['is_link'] ?? false,
            files: $data['files'] ?? null
        );
    }

    public static function fromRequest(Request $request): static
    {
        /** @var static */
        return new self(
            uuid: $request->input('uuid'),
            text: $request->input('text'),
            subscriptions: $request->input('subscriptions', []),
            textShort: $request->input('text_short'),
            url: $request->input('url'),
            isLink: $request->boolean('is_link'),
            files: $request->file('files')
        );
    }

    /**
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'text' => $this->text,
            'subscriptions' => $this->subscriptions,
            'text_short' => $this->textShort,
            'url' => $this->url,
            'is_link' => $this->isLink,
            'files' => $this->files,
        ];
    }

    public function validate(): bool
    {
        return ! empty($this->uuid) && ! empty($this->text) && ! empty($this->subscriptions);
    }
}
