<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Actions\Contact\CreateContactAction;
use App\Domain\Actions\Contact\DeleteContactAction;
use App\Domain\Actions\Contact\GetContactAction;
use App\Domain\Actions\Contact\GetContactListAction;
use App\Domain\DTOs\Contact\ContactListRequestDTO;
use App\Domain\DTOs\Contact\CreateContactDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\Web\ContactRequest;
use App\Models\Communication\Contact;
use Exception;
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
        private readonly GetContactAction $getContactAction,
        private readonly DeleteContactAction $deleteContactAction
    ) {}

    /**
     * Display a listing of contacts
     */
    public function index(Request $request): View
    {
        try {
            $dto = new ContactListRequestDTO(
                page: $request->get('page', 1),
                limit: $request->get('limit', 20),
                search: $request->get('search'),
                status: $request->get('status', 1)
            );

            $result = $this->getContactListAction->execute($dto->toArray());
            $contacts = $result->getData()->data;

            return ViewFacade::make('web.contact.index', [
                'contacts' => $contacts,
                'filters' => $request->only(['search', 'status']),
            ]);
        } catch (Exception $e) {
            return ViewFacade::make('web.contact.index', [
                'contacts' => collect(),
                'filters' => [],
                'error' => 'Failed to load contacts: '.$e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new contact
     */
    public function create(): View
    {
        return ViewFacade::make('web.contact.create');
    }

    /**
     * Store a newly created contact
     */
    public function store(ContactRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $dto = new CreateContactDTO(
                idUser: $validated['id_user'] ?? null,
                name: $validated['name'],
                email: $validated['email'],
                subject: $validated['subject'] ?? null,
                body: $validated['body'],
                status: $validated['status'] ?? 1
            );

            $result = $this->createContactAction->execute($dto);

            if ($result->getData()->success) {
                return Redirect::to(route('web.contact.index'))
                    ->with('success', 'Contact created successfully.');
            }

            return Redirect::back()
                ->withErrors(['error' => $result->getData()->message ?? 'Failed to create contact.'])
                ->withInput();

        } catch (Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Failed to create contact: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified contact
     */
    public function show(Contact $contact): View
    {
        $contact->load('user');

        return ViewFacade::make('web.contact.show', [
            'contact' => $contact,
        ]);
    }

    /**
     * Show the form for editing the specified contact
     */
    public function edit(Contact $contact): View
    {
        return ViewFacade::make('web.contact.edit', [
            'contact' => $contact,
        ]);
    }

    /**
     * Update the specified contact
     */
    public function update(ContactRequest $request, Contact $contact): RedirectResponse
    {
        try {
            $contact->update($request->validated());

            return Redirect::to(route('web.contact.index'))
                ->with('success', 'Contact updated successfully.');

        } catch (Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Failed to update contact: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified contact
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        try {
            $dto = new \App\DTOs\Contact\ContactDeleteRequestDTO(
                id: $contact->id
            );

            $result = $this->deleteContactAction->execute($dto);

            if ($result->getData()->success) {
                return Redirect::to(route('web.contact.index'))
                    ->with('success', 'Contact deleted successfully.');
            }

            return Redirect::back()
                ->withErrors(['error' => $result->getData()->message ?? 'Failed to delete contact.']);

        } catch (Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Failed to delete contact: '.$e->getMessage()]);
        }
    }

    /**
     * Toggle contact status
     */
    public function toggleStatus(Contact $contact): RedirectResponse
    {
        try {
            $newStatus = $contact->status === 1 ? 2 : 1; // Toggle between active and viewed
            $contact->update(['status' => $newStatus]);

            $status = $newStatus === 1 ? 'marked as active' : 'marked as viewed';

            return Redirect::back()
                ->with('success', "Contact {$status} successfully.");

        } catch (Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Failed to toggle contact status: '.$e->getMessage()]);
        }
    }

    /**
     * Mark contact as replied
     */
    public function markAsReplied(Contact $contact): RedirectResponse
    {
        try {
            $contact->update(['status' => 2]); // STATUS_VIEWED/REPLIED

            return Redirect::back()
                ->with('success', 'Contact marked as replied successfully.');

        } catch (Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Failed to mark contact as replied: '.$e->getMessage()]);
        }
    }
}
