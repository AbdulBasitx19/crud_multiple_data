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
}
