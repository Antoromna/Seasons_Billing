@extends('layouts.auth')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon">
                    <i class="bi bi-person-plus"></i>
                </span>

                <div>
                    <h1 class="h3 mb-1">Add Customer</h1>
                </div>
            </div>

            <div class="heading-actions">
                <a class="btn btn-outline-secondary btn-sm"
                   href="{{ route('customers.index') }}">
                    <i class="bi bi-arrow-left"></i>
                    Back to Customers
                </a>
            </div>
        </div>

        <section class="row g-3">
            <div class="col-12 col-xl-8">

                <form action="{{ route('customers.store') }}"
                      method="POST"
                      class="panel">

                    @csrf

                    <div class="panel-header">
                        <div>
                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-person-plus"></i>
                                <span>Customer Information</span>
                            </h2>
                        </div>
                    </div>

                    <div class="row g-3">

                        {{-- Name --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Customer Name <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}">

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Mobile --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Mobile No <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="mobile_no"
                                   class="form-control @error('mobile_no') is-invalid @enderror"
                                   value="{{ old('mobile_no') }}">

                            @error('mobile_no')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Address --}}
                        <div class="col-12">
                            <label class="form-label">
                                Address <span class="text-danger">*</span>
                            </label>

                            <textarea name="address"
                                      rows="3"
                                      class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>

                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label class="form-label">Email</label>

                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}">

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Landline --}}
                        <div class="col-md-6">
                            <label class="form-label">Landline</label>

                            <input type="text"
                                   name="landline"
                                   class="form-control @error('landline') is-invalid @enderror"
                                   value="{{ old('landline') }}">

                            @error('landline')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- GSTIN --}}
                        <div class="col-md-6">
                            <label class="form-label">GSTIN</label>

                            <input type="text"
                                   name="gstin"
                                   class="form-control @error('gstin') is-invalid @enderror"
                                   value="{{ old('gstin') }}">

                            @error('gstin')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- State --}}
                        <div class="col-md-6">
                            <label class="form-label">State</label>

                            <input type="text"
                                   name="state"
                                   class="form-control @error('state') is-invalid @enderror"
                                   value="{{ old('state') }}">

                            @error('state')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Opening Balance --}}
                        <div class="col-md-6">
                            <label class="form-label">Opening Balance</label>

                            <input type="number"
                                   step="0.01"
                                   name="opening_balance"
                                   class="form-control @error('opening_balance') is-invalid @enderror"
                                   value="{{ old('opening_balance', 0) }}">

                            @error('opening_balance')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label class="form-label">Status</label>

                            <select name="status"
                                    class="form-select">

                                <option value="1">Active</option>
                                <option value="0">Inactive</option>

                            </select>
                        </div>

                    </div>

                    <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">

                        <a class="btn btn-outline-secondary"
                           href="{{ route('customers.index') }}">
                            Cancel
                        </a>

                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-person-check"></i>
                            Save Customer
                        </button>

                    </div>

                </form>

            </div>
        </section>

    </div>
</main>

@endsection