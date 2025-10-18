<?php

declare(strict_types=1);

namespace App\Domain\Services\Contact;

use App\Domain\DTOs\Contact\ContactListRequestDTO;
use App\Domain\DTOs\Contact\CreateContactDTO;
use App\Models\Communication\Contact;

class ContactService implements ContactServiceInterface
{
    public function getList(ContactListRequestDTO $dto): \Illuminate\Contracts\Pagination\LengthAwarePaginator
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

        return $query->orderBy('created_at', 'desc')
            ->paginate($dto->limit, ['*'], 'page', $dto->page);
    }

    public function getById(int $id): Contact
    {
        return Contact::findOrFail($id);
    }

    public function create(CreateContactDTO $dto): Contact
    {
        return Contact::create([
            'id_user' => $dto->userId,
            'name' => $dto->name,
            'email' => $dto->email,
            'subject' => $dto->subject,
            'body' => $dto->message,
            'status' => 1,
        ]);
    }

    public function markAsDeleted(int $id): void
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['status' => Contact::STATUS_DELETED]);
    }
}
