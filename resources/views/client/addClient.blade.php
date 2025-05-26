@extends('layout')

@section('content')
<div style="display: flex; justify-content: center; align-items: flex-start; min-height: 80vh; background: #f3fcfa;">
    <div style="width: 600px; background: #fff; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); overflow: hidden; margin-top: 40px;">
        <div style="background: #5fd1b3; color: #fff; font-size: 1.3rem; font-weight: 600; padding: 18px 32px; border-top-left-radius: 16px; border-top-right-radius: 16px;">
            Add New Client
        </div>
        <div style="padding: 32px;">
            @if (
                count($errors) > 0)
                <div class="alert alert-danger" style="margin-bottom: 18px;">
                    <ul style="margin-bottom: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('clients.store') }}">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 18px;">
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="lname" style="width: 160px; font-weight: 500;">Last Name</label>
                        <input id="lname" type="text" class="form-control @error('lname') is-invalid @enderror" name="lname" value="{{ old('lname') }}" required style="flex: 1;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="fname" style="width: 160px; font-weight: 500;">First Name</label>
                        <input id="fname" type="text" class="form-control @error('fname') is-invalid @enderror" name="fname" value="{{ old('fname') }}" required style="flex: 1;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="mname" style="width: 160px; font-weight: 500;">Middle Name</label>
                        <input id="mname" type="text" class="form-control @error('mname') is-invalid @enderror" name="mname" value="{{ old('mname') }}" style="flex: 1;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="birthdate" style="width: 160px; font-weight: 500;">Birthdate</label>
                        <input id="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror" name="birthdate" value="{{ old('birthdate') }}" required style="flex: 1;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="age" style="width: 160px; font-weight: 500;">Age</label>
                        <input id="age" type="text" class="form-control @error('age') is-invalid @enderror" name="age" value="{{ old('age') }}" readonly style="flex: 1;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="gender" style="width: 160px; font-weight: 500;">Gender</label>
                        <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender" required style="flex: 1;">
                            <option value="">Select Gender</option>
                            @foreach($genders as $gender)
                                <option value="{{ $gender->id }}" {{ old('gender') == $gender->id ? 'selected' : '' }}>{{ $gender->gender_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="address" style="width: 160px; font-weight: 500;">Address</label>
                        <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" style="flex: 1;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="guardian" style="width: 160px; font-weight: 500;">Guardian Name</label>
                        <input id="guardian" type="text" class="form-control @error('guardian') is-invalid @enderror" name="guardian" value="{{ old('guardian') }}" required style="flex: 1;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="guardianRelationship" style="width: 160px; font-weight: 500;">Guardian Relationship</label>
                        <input id="guardianRelationship" type="text" class="form-control @error('guardianRelationship') is-invalid @enderror" name="guardianRelationship" value="{{ old('guardianRelationship') }}" required style="flex: 1;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="parentContact" style="width: 160px; font-weight: 500;">Parent Contact</label>
                        <input id="parentContact" type="text" class="form-control @error('parentContact') is-invalid @enderror" name="parentContact" value="{{ old('parentContact') }}" maxlength="11" style="flex: 1;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="case_id" style="width: 160px; font-weight: 500;">Case</label>
                        <select id="case_id" class="form-control @error('case_id') is-invalid @enderror" name="case_id" required style="flex: 1;">
                            <option value="">Select Case</option>
                            @foreach($cases as $case)
                                <option value="{{ $case->id }}" {{ old('case_id') == $case->id ? 'selected' : '' }}>{{ $case->case_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="admissionDate" style="width: 160px; font-weight: 500;">Admission Date</label>
                        <input id="admissionDate" type="date" class="form-control @error('admissionDate') is-invalid @enderror" name="admissionDate" value="{{ old('admissionDate') }}" required style="flex: 1;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="status_id" style="width: 160px; font-weight: 500;">Status</label>
                        <select id="status_id" class="form-control @error('status_id') is-invalid @enderror" name="status_id" required style="flex: 1;">
                            <option value="">Select Status</option>
                            @foreach($status as $statusItem)
                                <option value="{{ $statusItem->id }}" {{ old('status_id') == $statusItem->id ? 'selected' : '' }}>{{ $statusItem->status_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="isAStudent" style="width: 160px; font-weight: 500;">Is a Student</label>
                        <select id="isAStudent" class="form-control @error('isAStudent') is-invalid @enderror" name="isAStudent" required style="flex: 1;">
                            <option value="">Select Status</option>
                            @foreach($isAStudent as $student)
                                <option value="{{ $student->id }}" {{ old('isAStudent') == $student->id ? 'selected' : '' }}>
                                    {{ $student->status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="isAPwd" style="width: 160px; font-weight: 500;">Is a PWD</label>
                        <select id="isAPwd" class="form-control @error('isAPwd') is-invalid @enderror" name="isAPwd" required style="flex: 1;">
                            <option value="">Select Status</option>
                            @foreach($isAPwd as $pwd)
                                <option value="{{ $pwd->id }}" {{ old('isAPwd') == $pwd->id ? 'selected' : '' }}>
                                    {{ $pwd->status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <label for="location_id" style="width: 160px; font-weight: 500;">Location</label>
                        <select id="location_id" class="form-control @error('location_id') is-invalid @enderror" name="location_id" required style="flex: 1;">
                            <option value="">Select Location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->location }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Add more fields as needed -->
                    <div style="display: flex; justify-content: flex-end; margin-top: 24px;">
                        <button type="submit" class="btn btn-primary" style="background: #21807a; color: #fff; font-weight: 600; border-radius: 8px; padding: 10px 32px;">Add Client</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('birthdate').addEventListener('change', function() {
    var birthdate = new Date(this.value);
    var today = new Date();
    var age = today.getFullYear() - birthdate.getFullYear();
    var m = today.getMonth() - birthdate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
        age--;
    }
    document.getElementById('age').value = age;
});
</script>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const barangaySelect = document.getElementById('barangay');
    const streetAddressInput = document.getElementById('street_address');
    const addressInput = document.getElementById('address');

    // Function to update the complete address
    function updateCompleteAddress() {
        const street = streetAddressInput.value;
        const province = provinceSelect.options[provinceSelect.selectedIndex].text;
        const city = citySelect.options[citySelect.selectedIndex].text;
        const barangay = barangaySelect.options[barangaySelect.selectedIndex].text;
        
        const completeAddress = `${street}, ${barangay}, ${city}, ${province}`;
        addressInput.value = completeAddress;
    }

    // Add event listeners for address updates
    [provinceSelect, citySelect, barangaySelect, streetAddressInput].forEach(element => {
        element.addEventListener('change', updateCompleteAddress);
    });

    // Load cities when province is selected
    provinceSelect.addEventListener('change', function() {
        const provinceId = this.value;
        citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

        if (provinceId) {
            fetch(`/api/cities/${provinceId}`)
                .then(response => response.json())
                .then(cities => {
                    cities.forEach(city => {
                        const option = new Option(city.name, city.id);
                        citySelect.add(option);
                    });
                });
        }
        updateCompleteAddress();
    });

    // Load barangays when city is selected
    citySelect.addEventListener('change', function() {
        const cityId = this.value;
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

        if (cityId) {
            fetch(`/api/barangays/${cityId}`)
                .then(response => response.json())
                .then(barangays => {
                    barangays.forEach(barangay => {
                        const option = new Option(barangay.name, barangay.id);
                        barangaySelect.add(option);
                    });
                });
        }
        updateCompleteAddress();
    });

    // Initialize with old values if they exist
    if (provinceSelect.value) {
        provinceSelect.dispatchEvent(new Event('change'));
        if (citySelect.value) {
            citySelect.dispatchEvent(new Event('change'));
        }
    }
});

function validatePhoneNumber(input) {
    // Remove any non-numeric characters
    input.value = input.value.replace(/[^0-9]/g, '');
    
    // Check if the input is exactly 11 digits
    if (input.value.length > 0 && input.value.length !== 11) {
        input.setCustomValidity('Phone number must be exactly 11 digits');
    } else {
        input.setCustomValidity('');
    }
}
</script>
@endsection
