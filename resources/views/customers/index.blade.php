@extends('layouts.auth')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <!-- Executive Page Header Card -->
    <div class="index-header-card d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="index-header-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <h1 class="h3 text-white mb-1 fw-bold">Customers Directory</h1>
                <p class="text-white-50 mb-0 small">Manage workspace customers, contact details, and account status.</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('customers.print') }}" target="_blank" class="index-action-btn">
                <i class="bi bi-printer"></i> Print List
            </a>
            <a href="{{ route('customers.create') }}" class="index-action-btn index-action-btn-primary">
                <i class="bi bi-person-plus"></i> Add Customer
            </a>
        </div>
    </div>

    <!-- Table List Box Panel -->
    <section class="panel-custom mt-3" id="printSection">
        <div class="panel-header-custom">
            <div class="panel-header-title">
                <i class="bi bi-table text-primary fs-5"></i>
                <span>Customer Accounts</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fs-7 ms-2">
                    {{ count($customers) }} Records
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <input class="form-control form-control-sm table-search" type="search" placeholder="Search customers..." data-table-search="usersTable" aria-label="Search users" style="min-width: 220px; border-radius: 8px;">
            </div>
        </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0" id="usersTable" data-searchable-table>
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>Landline</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
            <tbody>

            @forelse($customers as $key => $customer)

            <tr>

                {{-- S.No --}}
                <td>
                    {{ $key + 1 }}
                </td>

                {{-- Name --}}
                <td>
                    {{ $customer->name }}
                </td>

                {{-- Mobile --}}
                <td>
                    {{ $customer->mobile_no }}
                </td>

                {{-- Landline --}}
                <td>
                    {{ $customer->landline ?? '' }}
                </td>

                {{-- Email --}}
                <td>
                    {{ $customer->email ?? '' }}
                </td>

                {{-- Address --}}
                <td>
                    {{ $customer->address }}
                </td>

                {{-- Status --}}
                <td>
                    @if($customer->status == 1)
                        <span class="badge text-bg-success">
                            Active
                        </span>
                    @else
                        <span class="badge text-bg-danger">
                            Inactive
                        </span>
                    @endif
                </td>

                {{-- Action --}}
               <td class="text-end">

                <div class="d-flex justify-content-end gap-2">

                    {{-- Edit --}}
                    <a class="btn-action btn-action-primary"
                       href="{{ route('customers.edit', $customer->id) }}">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>

                    {{-- Delete Button --}}
                    <button type="button"
                            class="btn-action btn-action-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal{{ $customer->id }}">
                        <i class="bi bi-trash"></i> Delete
                    </button>

                </div>

                {{-- Delete Modal --}}
                <div class="modal fade"
                    id="deleteModal{{ $customer->id }}"
                    tabindex="-1"
                    aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered">

                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Delete Customer
                                </h5>

                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                Are you sure want to delete
                                <strong>{{ $customer->name }}</strong>?
                            </div>

                            <div class="modal-footer">

                                <button type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal">
                                    Cancel
                                </button>

                                <form action="{{ route('customers.destroy', $customer->id) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </td>

            </tr>

            @empty

            <tr>
                <td colspan="8" class="text-center py-4">
                    No Customers Found
                </td>
            </tr>

            @endforelse

            </tbody>
              </table>
            </div>
           
          </section>
        </div>

@endsection