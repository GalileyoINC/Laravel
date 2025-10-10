<?php

namespace App\DTOs\Influencer;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class InfluencerFeedCreateRequestDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $alias,
        public readonly string $pageTitle,
        public readonly string $pageDescription,
        public readonly ?UploadedFile $imageFile = null
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
            alias: $data['alias'],
            pageTitle: $data['page_title'],
            pageDescription: $data['page_description'],
            imageFile: $data['image_file'] ?? null
        );
    }

    public static function fromRequest(Request $request): static
    {
        return new self(
            title: $request->input('title'),
            description: $request->input('description'),
            alias: $request->input('alias'),
            pageTitle: $request->input('page_title'),
            pageDescription: $request->input('page_description'),
            imageFile: $request->file('image_file')
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'alias' => $this->alias,
            'page_title' => $this->pageTitle,
            'page_description' => $this->pageDescription,
            'image_file' => $this->imageFile
        ];
    }

    public function validate(): bool
    {
        return !empty($this->title) && 
               !empty($this->description) && 
               !empty($this->alias) && 
               !empty($this->pageTitle) && 
               !empty($this->pageDescription);
    }
}
