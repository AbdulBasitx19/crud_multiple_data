<!DOCTYPE html>
<html>
<head>
    <title>Create Person</title>
</head>
<body>
    <h1>Add New Person</h1>
    
    <form action="{{ route('people.store') }}" method="POST">
        @csrf
        
        <h3>Person Details</h3>
        <label>Name:</label><br>
        <input type="text" name="name" value="{{ old('name') }}" required>
        @error('name') <span style="color:red;">{{ $message }}</span> @enderror
        <br><br>

        <label>Age:</label><br>
        <input type="number" name="age" value="{{ old('age') }}">
        @error('age') <span style="color:red;">{{ $message }}</span> @enderror
        <br><br>

        <hr>
        <h3>Contacts</h3>
        <div id="contacts-container">
            <!-- Default ek contact field pehle se dikhayein -->
        </div>
        
        <button type="button" onclick="addContactField()" style="margin-top: 10px;">+ Add Another Contact</button>
        <br><br>
        @error('contacts') <span style="color:red;">{{ $message }}</span> @enderror
        <br>

        <button type="submit" style="padding: 10px 20px; background: green; color: white; border: none;">Save Person</button>
        <a href="{{ route('people.index') }}">Cancel</a>
    </form>

    <!-- Simple JavaScript to add multiple contact fields dynamically -->
    <script>
        let contactIndex = 0;

        function addContactField() {
            const container = document.getElementById('contacts-container');
            const div = document.createElement('div');
            div.style.marginBottom = '15px';
            div.style.padding = '10px';
            div.style.border = '1px solid #ccc';
            
            div.innerHTML = `
                <select name="contacts[${contactIndex}][type]" required>
                    <option value="phone">Phone</option>
                    <option value="email">Email</option>
                    <option value="whatsapp">WhatsApp</option>
                </select>
                <input type="text" name="contacts[${contactIndex}][value]" placeholder="Enter value" required style="width: 200px;">
                <button type="button" onclick="this.parentElement.remove()" style="color: red;">Remove</button>
            `;
            
            container.appendChild(div);
            contactIndex++;
        }

        // Page load hone par ek khali contact field add kar do
        window.onload = function() {
            addContactField();
        };
    </script>
</body>
</html>