<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Contact;

use Illuminate\Http\Request;

class PersonController extends Controller
{
    //

    public function index()
    {
        $people = Person::with(contacts)->latest()->get();
        return view('people.index', compact('people'));
    }

    public function create()
    {
        return view('people.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer',
            'contacts' => 'required|array|min:1',
            'contacts.*.type' => 'reuqired|string',
            'contacts.*.value' => 'required|string',
        ]);

        $person = Person::create([
            'name' => $validated['name'],
            'age' => $validated['age'],
        ]);

        foreach($validated['contacts'] as $contactData)
        {
            $person->contacts()->create([
                'type' => $contactData['type'],
                'value' => $contactData['value'],
            ]);
        }

        return redierct()->route('people.index')->with('success', 'Person Created Successfully.');
    }

    public function edit(Person $person)
    {
        $person->load('contacts');
        return view('people.edit', compact('person'));
    }

    public function update(Request $request, Person $person)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer',
            'contacts' => 'required|array|min:1',
            'contacts.*.type' => 'required|string',
            'contacts.*.value' => 'required|string',
        ]);

        $person->update([
            'name' => $validated['name'],
            'age' => $validated['age'],
        ]);

        $existingContactIds= $person->contacts->pluck('id')->toArray();
        $incommingContactIds = [];

        foreach($validated['contacts'] as $contactData)
            {
                if(!empty($contactData['id']))
                {
                   Contact::where('id', $contactData['id'])->update([
                    'type' => $contactData['type'],
                    'value' => $contactData['value'],
                   ]);
                   $incommingContactIds = $contactData['id'];
                } else {
                     $person->contacts()->create([
                        'type' => $contactData['type'],
                        'value' => $contactData['value'],
                     ]);
                     $incommingContactIds = $contactData['id'];
                    }
            }

    }

    
    
}
