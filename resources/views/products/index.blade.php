@extends('layouts.auth')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <!-- Executive Page Header Card -->
    <div class="index-header-card d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="index-header-icon">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div>
                <h1 class="h3 text-white mb-1 fw-bold">Products Inventory</h1>
                <p class="text-white-50 mb-0 small">Manage workspace product catalog, unit types, tray requirements, and status.</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('products.print') }}" target="_blank" class="index-action-btn">
                <i class="bi bi-printer"></i> Print List
            </a>
            <a href="{{ route('products.create') }}" class="index-action-btn index-action-btn-primary">
                <i class="bi bi-plus-circle"></i> Add Product
            </a>
        </div>
    </div>

    <!-- Table List Box Panel -->
    <section class="panel-custom mt-3">
        <div class="panel-header-custom">
            <div class="panel-header-title">
                <i class="bi bi-table text-success fs-5"></i>
                <span>Products List</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fs-7 ms-2">
                    {{ count($products) }} Products
                </span>
            </div>
        </div>

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        {{-- <th>Code</th> --}}
                        <th>Unit</th>
                        <th>Tray Required</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($products as $key => $product)

                    <tr>

                        <td>
                            {{ $key + 1 }}
                        </td>

                        <td>
                            {{ $product->name }}
                        </td>

                        {{-- <td>
                            {{ $product->code }}
                        </td> --}}

                        <td>
                            {{ ucfirst($product->unit) }}
                        </td>

                        <td>
                            @if($product->tray_required)
                                Yes
                            @else
                                No
                            @endif
                        </td>

                       
                        <td>
                            @if($product->status == 1)

                                <span class="badge text-bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge text-bg-danger">
                                    Inactive
                                </span>

                            @endif
                        </td>

                        <td class="text-end">

                            <div class="d-flex justify-content-end gap-2">

                                {{-- Edit --}}
                                <a class="btn-action btn-action-primary"
                                   href="{{ route('products.edit', $product->id) }}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                {{-- Delete --}}
                                <button type="button"
                                        class="btn-action btn-action-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $product->id }}">
                                    <i class="bi bi-trash"></i> Delete
                                </button>

                            </div>

                            {{-- Delete Modal --}}
                            <div class="modal fade"
                                 id="deleteModal{{ $product->id }}"
                                 tabindex="-1">

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title">
                                                Delete Product
                                            </h5>

                                            <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"></button>

                                        </div>

                                        <div class="modal-body">

                                            Are you sure want to delete
                                            <strong>{{ $product->name }}</strong>?

                                        </div>

                                        <div class="modal-footer">

                                            <button type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal">

                                                Cancel

                                            </button>

                                            <form action="{{ route('products.destroy', $product->id) }}"
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

                        <td colspan="9"
                            class="text-center py-4">

                            No Products Found

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection