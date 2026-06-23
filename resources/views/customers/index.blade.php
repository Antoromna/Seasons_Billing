@extends('layouts.auth')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
          <div class="page-heading">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
              <div>
                <h1 class="h3 mb-1">Customers</h1>
              </div>
            </div>
            <div class="heading-actions">
                <a href="{{ route('customers.print') }}"
                target="_blank"
                class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-printer"></i> Print
                </a>
                <a class="btn btn-primary btn-sm" href="{{ route('customers.create') }}"><i class="bi bi-person-plus" aria-hidden="true"></i> Add Customer</a></div>
          </div>

        

          <section class="panel mt-3" id="printSection">
            <div class="panel-header">
              <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span>User List</span></h2>
                {{-- <p class="text-muted mb-0">Search, review, and manage team member accounts.</p> --}}
              </div>
              <div class="d-flex flex-wrap gap-2">
                <input class="form-control form-control-sm table-search" type="search" placeholder="Search users" data-table-search="usersTable" aria-label="Search users">
                {{-- <a class="btn btn-primary btn-sm" href="{{ route('customers.create') }}"><i class="bi bi-person-plus" aria-hidden="true"></i> Add Customer</a> --}}
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
                    <a class="btn btn-primary btn-sm"
                    href="{{ route('customers.edit', $customer->id) }}">
                        Edit
                    </a>

                    {{-- Delete Button --}}
                    <button type="button"
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal{{ $customer->id }}">
                        Delete
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