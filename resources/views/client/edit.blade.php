@extends('layout')

@section('content')
   <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Client') }}</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('clients.update', $client->id) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Last Name --> 
                        <div class="form-group row mb-3">
                            <label for="lname" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>
                            <div class="col-md-6">
                                <input id="lname" type="text" class="form-control @error('lname') is-invalid @enderror" name="lname" value="{{ old('lname', $client->clientLastName) }}" required>
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
                                <input id="fname" type="text" class="form-control @error('fname') is-invalid @enderror" name="fname" value="{{ old('fname', $client->clientFirstName) }}" required>
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
                                <input id="mname" type="text" class="form-control @error('mname') is-invalid @enderror" name="mname" value="{{ old('mname', $client->clientMiddleName) }}" required>
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
                                <input id="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror" name="birthdate" value="{{ old('birthdate', $client->clientBirthdate) }}" required>
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
                                <input id="age" type="number" class="form-control @error('age') is-invalid @enderror" name="age" value="{{ old('age', $client->clientAge) }}" required>
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
                                        <option value="{{ $gender->id }}" {{ (old('gender', $client->gender_id) == $gender->id) ? 'selected' : '' }}>
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
                                <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $client->clientaddress) }}" placeholder="Enter complete address" required>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Guardian -->
                        <div class="form-group row mb-3">
                            <label for="guardian" class="col-md-4 col-form-label text-md-right">{{ __('Guardian Name') }}</label>
                            <div class="col-md-6">
                                <input id="guardian" type="text" class="form-control @error('guardian') is-invalid @enderror" name="guardian" value="{{ old('guardian', $client->clientguardian) }}" required>
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
                                <input id="guardianRelationship" type="text" class="form-control @error('guardianRelationship') is-invalid @enderror" name="guardianRelationship" value="{{ old('guardianRelationship', $client->clientguardianrelationship) }}" required>
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
                                <input id="parentContact" type="text" class="form-control @error('parentContact') is-invalid @enderror" name="parentContact" value="{{ old('parentContact', $client->guardianphonenumber) }}" required>
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
                                        <option value="{{ $case->id }}" {{ (old('case_id', $client->case_id) == $case->id) ? 'selected' : '' }}>
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
                                <input id="admissionDate" type="date" class="form-control @error('admissionDate') is-invalid @enderror" name="admissionDate" value="{{ old('admissionDate', $client->clientdateofadmission) }}" required>
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
                                    @foreach($statuses as $statusItem) <!-- Use $statuses instead of $status -->
                                        <option value="{{ $statusItem->id }}" {{ (old('status_id', $client->status_id) == $statusItem->id) ? 'selected' : '' }}>
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

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Client') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection