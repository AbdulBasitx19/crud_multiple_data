<!DOCTYPE html>
<html>
<head>
    <title>Edit Person</title>
</head>
<body>
    <h1>Edit Person</h1>
    
    <form action="{{ route('people.update', $person->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <h3>Person Details</h3>
        <label>Name:</label><br>
        <input type="text" name="name" value="{{ old('name', $person->name) }}" required>
        @error('name') <span style="color:red;">{{ $message }}</span> @enderror
        <br><br>

        <label>Age:</label><br>
        <input type="number" name="age" value="{{ old('age', $person->age) }}">
        @error('age') <span style="color:red;">{{ $message }}</span> @enderror
        <br><br>

        <hr>
        <h3>Contacts</h3>
        <div id="contacts-container">
            <!-- Purane contacts ko loop kar ke dikhana -->
            @foreach($person->contacts as $index => $contact)
                <div style="margin-bottom: 15px; padding: 10px; border: 1px solid #ccc;">
                    <!-- Hidden ID taake controller ko pata chale ke yeh update karna hai -->
                    <input type="hidden" name="contacts[{{ $index }}][id]" value="{{ $contact->id }}">
                    
                    <select name="contacts[{{ $index }}][type]" required>
                        <option value="phone" {{ $contact->type == 'phone' ? 'selected' : '' }}>Phone</option>
                        <option value="email" {{ $contact->type == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="whatsapp" {{ $contact->type == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                    </select>
                    <input type="text" name="contacts[{{ $index }}][value]" value="{{ old('contacts.'.$index.'.value', $contact->value) }}" required style="width: 200px;">
                    <button type="button" onclick="this.parentElement.remove()" style="color: red;">Remove</button>
                </div>
            @endforeach
        </div>

        <hr>
        <h3>Skills (Select Multiple)</h3>
        <div style="display: flex; flex-wrap: wrap; gap: 15px">
            @foreach($skills as $skill)
               @php
                $isChecked = in_array($skill->id , old('skills', $person->skills->pluck('id')->toArray()));
               @endphp

               <label style="cursor: pointer;">
                <input type="checkbox" name="skills[]" value="{{ $skill->id }}" {{ $isChecked ? 'checked': '' }}>
                {{ $skill->name}}
               </label>
            @endforeach
        </div>
        @error('skills') <span style="color: red;">{{ $message }}</span> @enderror
        <br><br>
        
        <button type="button" onclick="addNewContactField()" style="margin-top: 10px;">+ Add Another Contact</button>
        <br><br>
        @error('contacts') <span style="color:red;">{{ $message }}</span> @enderror
        <br>

        <button type="submit" style="padding: 10px 20px; background: blue; color: white; border: none;">Update Person</button>
        <a href="{{ route('people.index') }}">Cancel</a>
    </form>

    <script>
        // Index ko existing contacts ki length se start karein taake overlap na ho
        let contactIndex = {{ $person->contacts->count() }};

        function addNewContactField() {
            const container = document.getElementById('contacts-container');
            const div = document.createElement('div');
            div.style.marginBottom = '15px';
            div.style.padding = '10px';
            div.style.border = '1px solid #ccc';
            
            // Naye contact mein 'id' hidden field nahi hoti, kyunke wo naya record hai
            div.innerHTML = `
                <select name="contacts[${contactIndex}][type]" required>
                    <option value="phone">Phone</option>
                    <option value="email">Email</option>
                    <option value="whatsapp">WhatsApp</option>
                </select>
                <input type="text" name="contacts[${contactIndex}][value]" placeholder="Enter new value" required style="width: 200px;">
                <button type="button" onclick="this.parentElement.remove()" style="color: red;">Remove</button>
            `;
            
            container.appendChild(div);
            contactIndex++;
        }
    </script>
</body>
</html>