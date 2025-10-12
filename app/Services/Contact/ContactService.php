<?php

declare(strict_types=1);

namespace App\Services\Contact;

use App\DTOs\Contact\ContactListRequestDTO;
use App\Models\Communication\Contact;

class ContactService implements ContactServiceInterface
{
    public function getList(ContactListRequestDTO $dto): array
    {
        $query = Contact::query();

        if ($dto->search) {
            $query->where(function ($q) use ($dto) {
                $q->where('name', 'like', '%'.$dto->search.'%')
                    ->orWhere('email', 'like', '%'.$dto->search.'%')
                    ->orWhere('subject', 'like', '%'.$dto->search.'%');
            });
        }

        $query->where('status', $dto->status);

        $contacts = $query->orderBy('created_at', 'desc')
            ->paginate($dto->limit, ['*'], 'page', $dto->page);

        return [
            'data' => $contacts->items(),
            'pagination' => [
                'current_page' => $contacts->currentPage(),
                'last_page' => $contacts->lastPage(),
                'per_page' => $contacts->perPage(),
                'total' => $contacts->total(),
            ],
        ];
    }

    public function getById(int $id): Contact
    {
        return Contact::findOrFail($id);
    }

    public function markAsDeleted(int $id): void
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['status' => Contact::STATUS_DELETED]);
    }
}
