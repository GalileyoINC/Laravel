<?php

declare(strict_types=1);

namespace App\Domain\Services\Contact;

use App\Domain\DTOs\Contact\CreateContactDTO;
use App\Models\Communication\Contact;

class ContactService implements ContactServiceInterface
{
    public function getList(int $page, int $limit, ?string $search, int $status): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Contact::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('subject', 'like', '%'.$search.'%');
            });
        }

        $query->where('status', $status);

        return $query->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    public function getById(int $id): Contact
    {
        return Contact::findOrFail($id);
    }

    public function create(CreateContactDTO $dto): Contact
    {
        return Contact::create([
            'id_user' => $dto->idUser,
            'name' => $dto->name,
            'email' => $dto->email,
            'subject' => $dto->subject,
            'body' => $dto->body,
            'status' => 1,
        ]);
    }

    public function markAsDeleted(int $id): void
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['status' => Contact::STATUS_DELETED]);
    }
}
