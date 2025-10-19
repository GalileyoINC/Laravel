<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Contact\CreateContactAction;
use App\Domain\Actions\Contact\DeleteContactAction;
use App\Domain\Actions\Contact\GetContactListAction;
use App\Domain\DTOs\Contact\ContactListRequestDTO;
use App\Domain\DTOs\Contact\CreateContactDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\Web\ContactRequest;
use App\Models\Communication\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(
        private readonly CreateContactAction $createContactAction,
        private readonly GetContactListAction $getContactListAction,
        private readonly DeleteContactAction $deleteContactAction
    ) {}

    /**
     * Display a listing of contacts
     */
    public function index(Request $request): View
    {
        $dto = new ContactListRequestDTO(
            page: $request->get('page', 1),
            limit: $request->get('limit', 20),
            search: $request->get('search'),
            status: $request->get('status', 1)
        );

        $contacts = $this->getContactListAction->execute($dto->toArray());

        return ViewFacade::make('contact.index', [
            'contacts' => $contacts,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new contact
     */
    public function create(): View
    {
        return ViewFacade::make('contact.create');
    }

    /**
     * Store a newly created contact
     */
    public function store(ContactRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $dto = new CreateContactDTO(
            idUser: $validated['id_user'] ?? null,
            name: $validated['name'],
            email: $validated['email'],
            subject: $validated['subject'] ?? null,
            body: $validated['body'],
            status: $validated['status'] ?? 1
        );

        $contact = $this->createContactAction->execute($dto);

        return Redirect::to(route('contact.index'))
            ->with('success', 'Contact created successfully.');
    }

    /**
     * Display the specified contact
     */
    public function show(Contact $contact): View
    {
        $contact->load('user');

        return ViewFacade::make('contact.show', [
            'contact' => $contact,
        ]);
    }

    /**
     * Show the form for editing the specified contact
     */
    public function edit(Contact $contact): View
    {
        return ViewFacade::make('contact.edit', [
            'contact' => $contact,
        ]);
    }

    /**
     * Update the specified contact
     */
    public function update(ContactRequest $request, Contact $contact): RedirectResponse
    {
        $contact->update($request->validated());

        return Redirect::to(route('contact.index'))
            ->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the specified contact
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $dto = new \App\Domain\DTOs\Contact\ContactDeleteRequestDTO(
            id: $contact->id
        );

        $this->deleteContactAction->execute($dto->toArray());

        return Redirect::to(route('contact.index'))
            ->with('success', 'Contact deleted successfully.');
    }

    /**
     * Toggle contact status
     */
    public function toggleStatus(Contact $contact): RedirectResponse
    {
        $newStatus = $contact->status === 1 ? 2 : 1;
        $contact->update(['status' => $newStatus]);

        $status = $newStatus === 1 ? 'marked as active' : 'marked as viewed';

        return Redirect::back()
            ->with('success', "Contact {$status} successfully.");
    }

    /**
     * Mark contact as replied
     */
    public function markAsReplied(Contact $contact): RedirectResponse
    {
        $contact->update(['status' => 2]);

        return Redirect::back()
            ->with('success', 'Contact marked as replied successfully.');
    }
}
