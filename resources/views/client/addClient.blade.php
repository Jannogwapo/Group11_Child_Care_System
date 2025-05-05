@extends('layout')

@section('content')
   <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add New Client') }}</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('clients.store') }}">
                        @csrf

                        <!-- Last Name -->
                        <div class="form-group row mb-3">
                            <label for="lname" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>
                            <div class="col-md-6">
                                <input id="lname" type="text" class="form-control @error('lname') is-invalid @enderror" name="lname" value="{{ old('lname') }}" required>
                                @error('lname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- First Name -->
                        <div class="form-group row mb-3">
                            <label for="fname" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>
                            <div class="col-md-6">
                                <input id="fname" type="text" class="form-control @error('fname') is-invalid @enderror" name="fname" value="{{ old('fname') }}" required>
                                @error('fname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Middle Name -->
                        <div class="form-group row mb-3">
                            <label for="mname" class="col-md-4 col-form-label text-md-right">{{ __('Middle Name') }}</label>
                            <div class="col-md-6">
                                <input id="mname" type="text" class="form-control @error('mname') is-invalid @enderror" name="mname" value="{{ old('mname') }}" required>
                                @error('mname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Birthdate -->
                        <div class="form-group row mb-3">
                            <label for="birthdate" class="col-md-4 col-form-label text-md-right">{{ __('Birthdate') }}</label>
                            <div class="col-md-6">
                                <input id="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror" name="birthdate" value="{{ old('birthdate') }}" required>
                                @error('birthdate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Age -->
                        <div class="form-group row mb-3">
                            <label for="age" class="col-md-4 col-form-label text-md-right">{{ __('Age') }}</label>
                            <div class="col-md-6">
                                <input id="age" type="number" class="form-control @error('age') is-invalid @enderror" name="age" value="{{ old('age') }}" required>
                                @error('age')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="form-group row mb-3">
                            <label for="gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>
                            <div class="col-md-6">
                                <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                    <option value="">Select Gender</option>
                                    @foreach($genders as $gender)
                                        <option value="{{ $gender->id }}" {{ old('gender') == $gender->id ? 'selected' : '' }}>
                                            {{ $gender->gender_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-group row mb-3">
                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>
                            <div class="col-md-6">
                                <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" placeholder="Enter complete address" required>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Guardian -->
                        <div class="form-group row mb-3">
                            <label for="guardian" class="col-md-4 col-form-label text-md-right">{{ __('Guardian') }}</label>
                            <div class="col-md-6">
                                <input id="guardian" type="text" class="form-control @error('guardian') is-invalid @enderror" name="guardian" value="{{ old('guardian') }}" required>
                                @error('guardian')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Guardian Relationship -->
                        <div class="form-group row mb-3">
                            <label for="guardianRelationship" class="col-md-4 col-form-label text-md-right">{{ __('Guardian Relationship') }}</label>
                            <div class="col-md-6">
                                <input id="guardianRelationship" type="text" class="form-control @error('guardianRelationship') is-invalid @enderror" name="guardianRelationship" value="{{ old('guardianRelationship') }}" required>
                                @error('guardianRelationship')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Parent Contact -->
                        <div class="form-group row mb-3">
                            <label for="parentContact" class="col-md-4 col-form-label text-md-right">{{ __('Parent Contact') }}</label>
                            <div class="col-md-6">
                                <input id="parentContact" type="text" class="form-control @error('parentContact') is-invalid @enderror" name="parentContact" value="{{ old('parentContact') }}" required>
                                @error('parentContact')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Case -->
                        <div class="form-group row mb-3">
                            <label for="case_id" class="col-md-4 col-form-label text-md-right">{{ __('Case') }}</label>
                            <div class="col-md-6">
                                <select id="case_id" class="form-control @error('case_id') is-invalid @enderror" name="case_id" required>
                                    <option value="">Select Case</option>
                                    @foreach($cases as $case)
                                        <option value="{{ $case->id }}" {{ old('case_id') == $case->id ? 'selected' : '' }}>
                                            {{ $case->case_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('case_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Admission Date -->
                        <div class="form-group row mb-3">
                            <label for="admissionDate" class="col-md-4 col-form-label text-md-right">{{ __('Admission Date') }}</label>
                            <div class="col-md-6">
                                <input id="admissionDate" type="date" class="form-control @error('admissionDate') is-invalid @enderror" name="admissionDate" value="{{ old('admissionDate') }}" required>
                                @error('admissionDate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group row mb-3">
                            <label for="status_id" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>
                            <div class="col-md-6">
                                <select id="status_id" class="form-control @error('status_id') is-invalid @enderror" name="status_id" required>
                                    <option value="">Select Status</option>
                                    @foreach($status as $statusItem)
                                        <option value="{{ $statusItem->id }}" {{ old('status_id') == $statusItem->id ? 'selected' : '' }}>
                                            {{ $statusItem->status_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Is A Student -->
                        <div class="form-group row mb-3">
                            <label for="isAStudent" class="col-md-4 col-form-label text-md-right">{{ __('Is A Student') }}</label>
                            <div class="col-md-6">
                                <select id="isAStudent" class="form-control @error('isAStudent') is-invalid @enderror" name="isAStudent" required>
                                    <option value="">Select Status</option>
                                    @foreach($isAStudent as $student)
                                        <option value="{{ $student->id }}" {{ old('isAStudent') == $student->id ? 'selected' : '' }}>
                                            {{ $student->status }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('isAStudent')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Is A PWD -->
                        <div class="form-group row mb-3">
                            <label for="isAPwd" class="col-md-4 col-form-label text-md-right">{{ __('Is A PWD') }}</label>
                            <div class="col-md-6">
                                <select id="isAPwd" class="form-control @error('isAPwd') is-invalid @enderror" name="isAPwd" required>
                                    <option value="">Select Status</option>
                                    @foreach($isAPwd as $pwd)
                                        <option value="{{ $pwd->id }}" {{ old('isAPwd') == $pwd->id ? 'selected' : '' }}>
                                            {{ $pwd->status }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('isAPwd')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="form-group row mb-3">
                            <label for="location_id" class="col-md-4 col-form-label text-md-right">{{ __('Location') }}</label>
                            <div class="col-md-6">
                                <select id="location_id" class="form-control @error('location_id') is-invalid @enderror" name="location_id" required>
                                    <option value="">Select Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->location }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add Client') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
</script>
@endsection
