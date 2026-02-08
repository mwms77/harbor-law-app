<?php

namespace App\Http\Controllers;

use App\Models\ImportantContact;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ImportantContactController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $contacts = $user->importantContacts()->get()->groupBy('role_type');
        $roleLabels = ImportantContact::allowedRoleTypes();

        return view('important-contacts.index', [
            'contactsByRole' => $contacts,
            'roleLabels' => $roleLabels,
        ]);
    }

    public function create(Request $request)
    {
        $roleLabels = ImportantContact::allowedRoleTypes();
        return view('important-contacts.create', ['roleLabels' => $roleLabels]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_type' => 'required|string|in:' . implode(',', array_keys(ImportantContact::allowedRoleTypes())),
            'full_name' => 'required|string|max:255',
            'relationship' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $request->user()->importantContacts()->create(array_merge($validated, [
            'sort_order' => $request->user()->importantContacts()->max('sort_order') + 1,
        ]));

        return redirect()->route('important-contacts.index')
            ->with('success', 'Contact added successfully.');
    }

    public function edit(ImportantContact $contact)
    {
        if ($contact->user_id !== request()->user()->id) {
            abort(403);
        }
        $roleLabels = ImportantContact::allowedRoleTypes();

        return view('important-contacts.edit', [
            'contact' => $contact,
            'roleLabels' => $roleLabels,
        ]);
    }

    public function update(Request $request, ImportantContact $contact)
    {
        if ($contact->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'relationship' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $contact->update($validated);

        return redirect()->route('important-contacts.index')
            ->with('success', 'Contact updated successfully.');
    }

    /**
     * Copy all intake fiduciaries into Important Contacts (skips if already present by role+name).
     */
    public function importFromIntake(Request $request)
    {
        $user = $request->user();
        $fiduciaries = $user->intakeFiduciaries;
        $imported = 0;

        foreach ($fiduciaries as $f) {
            $exists = $user->importantContacts()
                ->where('role_type', $f->role_type)
                ->where('full_name', $f->full_name)
                ->exists();
            if (!$exists && trim($f->full_name) !== '') {
                ImportantContact::create([
                    'user_id' => $user->id,
                    'role_type' => $f->role_type,
                    'full_name' => $f->full_name,
                    'relationship' => $f->relationship,
                    'address' => $f->address,
                    'phone' => $f->phone,
                    'email' => $f->email,
                    'sort_order' => $f->sort_order,
                ]);
                $imported++;
            }
        }

        $message = $imported > 0
            ? "Imported {$imported} contact(s) from your intake form."
            : "No new contacts to import. Your intake data is already in Important Contacts or the intake form has no contacts yet.";

        return redirect()->route('important-contacts.index')->with('success', $message);
    }

    public function downloadPdf(Request $request)
    {
        $user = $request->user();
        $contacts = $user->importantContacts()->get()->groupBy('role_type');
        $roleLabels = ImportantContact::allowedRoleTypes();

        $pdf = Pdf::loadView('important-contacts.pdf', [
            'user' => $user,
            'contactsByRole' => $contacts,
            'roleLabels' => $roleLabels,
        ]);

        $filename = 'important-contacts-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
}
