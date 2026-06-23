@extends('layouts.auth')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <div class="page-heading">

        <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-box"></i>
            </span>

            <div>
                <h1 class="h3 mb-1">
                    Products
                </h1>
            </div>
        </div>

        <div class="heading-actions">

            <a class="btn btn-primary btn-sm"
               href="{{ route('products.create') }}">

                <i class="bi bi-plus-circle"></i>
                Add Product

            </a>

        </div>

    </div>

    <section class="panel mt-3">

        <div class="panel-header">

            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-table"></i>
                    <span>Products List</span>
                </h2>
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
                                <a class="btn btn-primary btn-sm"
                                   href="{{ route('products.edit', $product->id) }}">

                                    Edit

                                </a>

                                {{-- Delete --}}
                                <button type="button"
                                        class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $product->id }}">

                                    Delete

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