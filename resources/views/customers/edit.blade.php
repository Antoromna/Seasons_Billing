@extends('layouts.auth')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <div class="page-heading">
        <h1 class="h3 mb-1">Edit Customer</h1>
    </div>

    <div class="panel p-4">

        <form action="{{ route('customers.update', $customer->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">
                        Name *
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name', $customer->name) }}"
                           class="form-control @error('name') is-invalid @enderror">

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Mobile Number *
                    </label>

                    <input type="text"
                           name="mobile_no"
                           value="{{ old('mobile_no', $customer->mobile_no) }}"
                           class="form-control @error('mobile_no') is-invalid @enderror">

                    @error('mobile_no')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Email
                    </label>

                    <input type="email"
                           name="email"
                           value="{{ old('email', $customer->email) }}"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Landline
                    </label>

                    <input type="text"
                           name="landline"
                           value="{{ old('landline', $customer->landline) }}"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        GSTIN
                    </label>

                    <input type="text"
                           name="gstin"
                           value="{{ old('gstin', $customer->gstin) }}"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        State
                    </label>

                    <input type="text"
                           name="state"
                           value="{{ old('state', $customer->state) }}"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Opening Balance
                    </label>

                    <input type="number"
                           step="0.01"
                           name="opening_balance"
                           value="{{ old('opening_balance', $customer->opening_balance) }}"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Status
                    </label>

                    <select name="status" class="form-select">

                        <option value="1"
                            {{ $customer->status == 1 ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0"
                            {{ $customer->status == 0 ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">
                        Address *
                    </label>

                    <textarea name="address"
                              rows="3"
                              class="form-control @error('address') is-invalid @enderror">{{ old('address', $customer->address) }}</textarea>

                    @error('address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">

                <a href="{{ route('customers.index') }}"
                   class="btn btn-secondary">
                    Back
                </a>

                <button type="submit"
                        class="btn btn-primary">
                    Update Customer
                </button>

            </div>

        </form>

    </div>

</div>

@endsection