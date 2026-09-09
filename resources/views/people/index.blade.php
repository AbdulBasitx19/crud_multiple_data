<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>People List</title>
</head>
<body>
    <h1>People Directory</h1>

    @if(session('success'))
    <p style="color:green; font-weight: bold;">{{ session('success') }}</p>
    @endif

    <a href="{{ route('people.create') }}" style="padding: 10px; background: rgb(54, 218, 100); color: white; text-decoration: none;"> Add New Person</a>
    
    <table border="1" cellpadding="10" style="margin-top: 20px; width: 80%; text-align: left;">
        <tr>
            <th>Name</th>
            <th>Age</th>
            <th>Contacts</th>
            <th>Actions</th>
        </tr>
        @foreach($people as $person)
        <tr>
            <td> {{$person->name}}</td>
            <td>{{$person->age ?? 'N/A'}}</td>
                @if($person->contacts->isNotEmpty())
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach($person->contacts as $contact)
                                <li>
                                    <strong>{{ ucfirst($contact->type) }}:</strong> {{ $contact->value }}
                                </li>
                            @endforeach
                        </ul>
                @else 
                <span style="color: gray;">No contacts added</span>
                @endif
            </td>
            <td>
                <a href="{{ route('people.edit', $person->id) }}">Edit</a> |
                <form action="{{ route('people.destroy', $person->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this person and all their contacts?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>