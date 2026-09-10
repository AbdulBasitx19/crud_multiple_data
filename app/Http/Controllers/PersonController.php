<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Contact;
use App\Models\Skill;
use Illuminate\Http\Request;


class PersonController extends Controller
{
    public function index()
    {
        $people = Person::with('contacts')->latest()->get();
        return view('people.index', compact('people'));
    }

    public function create()
    {
        $skills = Skill::all();
        return view('people.create', compact('skills'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer',
            'contacts' => 'required|array|min:1',
            'contacts.*.type' => 'required|string',      
            'contacts.*.value' => 'required|string',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',

        ]);

        $person = Person::create([
            'name' => $validated['name'],
            'age' => $validated['age'],
        ]);

        foreach ($validated['contacts'] as $contactData) {
            $person->contacts()->create([
                'type' => $contactData['type'],
                'value' => $contactData['value'],
            ]);
        }
        if(!empty($validated['skills']))
            {
                $person->skills()->attach($validated['skills']);
            }


        return redirect()->route('people.index')->with('success', 'Person Created Successfully.');
    }

    public function edit(Person $person)
    {
        $person->load('contacts' , 'skills');
        $skills = Skill::all();
        return view('people.edit', compact('person', 'skills'));
    }

    public function update(Request $request, Person $person)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer',
            'contacts' => 'required|array|min:1',
            'contacts.*.id' => 'nullable|exists:contacts,id',
            'contacts.*.type' => 'required|string',
            'contacts.*.value' => 'required|string',
            'skills' => 'nullable|array',
            'skills.*' =>  'exists:skills,id'
        ]);

        $person->update([
            'name' => $validated['name'],
            'age' => $validated['age'],
        ]);

        $existingContactIds = $person->contacts->pluck('id')->toArray();
        $incomingContactIds = []; 

        foreach ($validated['contacts'] as $contactData) {
            if (!empty($contactData['id'])) {
                Contact::where('id', $contactData['id'])->update([
                    'type' => $contactData['type'],
                    'value' => $contactData['value'],
                ]);
                $incomingContactIds[] = $contactData['id']; 
            } else {
                $newContact = $person->contacts()->create([
                    'type' => $contactData['type'],
                    'value' => $contactData['value'],
                ]);
                $incomingContactIds[] = $newContact->id; 
            }
        }


        $contactsToDelete = array_diff($existingContactIds, $incomingContactIds);
        if (!empty($contactsToDelete)) {
            Contact::destroy($contactsToDelete);
        }


        $person->skills()->sync($validated['skills'] ?? []);

        return redirect()->route('people.index')->with('success', 'Person and contacts updated successfully!');
    }

    public function destroy(Person $person)
    {
        $person->delete();
        return redirect()->route('people.index')->with('success', 'Person deleted successfully!');
    }


}