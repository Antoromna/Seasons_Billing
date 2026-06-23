@extends('layouts.auth')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <div class="page-heading">
        <h1 class="h3 mb-1">
            Edit Product
        </h1>
    </div>

    <div class="panel p-4">

        <form action="{{ route('products.update', $product->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="row g-3">

                {{-- Product Name --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Product Name *
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name', $product->name) }}"
                           class="form-control @error('name') is-invalid @enderror">

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- Product Code --}}
                {{-- <div class="col-md-6">

                    <label class="form-label">
                        Product Code *
                    </label>

                    <input type="text"
                           name="code"
                           value="{{ old('code', $product->code) }}"
                           class="form-control @error('code') is-invalid @enderror">

                    @error('code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div> --}}

                {{-- Unit --}}
<div class="col-md-6">

    <label class="form-label">
        Unit *
    </label>

    <select name="unit"
            id="unit"
            class="form-select @error('unit') is-invalid @enderror">

        <option value="box"
            {{ old('unit', $product->unit) == 'box' ? 'selected' : '' }}>
            Box
        </option>

        <option value="kgs"
            {{ old('unit', $product->unit) == 'kgs' ? 'selected' : '' }}>
            Kgs
        </option>

        <option value="tray"
            {{ old('unit', $product->unit) == 'tray' ? 'selected' : '' }}>
            Tray
        </option>

    </select>

</div>

                {{-- Stock --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Stock
                    </label>

                    <input type="number"
                           name="stock"
                           value="{{ old('stock', $product->stock) }}"
                           class="form-control">

                </div>

                {{-- Selling Price --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Selling Price
                    </label>

                    <input type="number"
                           step="0.01"
                           name="selling_price"
                           value="{{ old('selling_price', $product->selling_price) }}"
                           class="form-control">

                </div>

                {{-- GST --}}
                <div class="col-md-6">

                    <label class="form-label">
                        GST %
                    </label>

                    <input type="number"
                           step="0.01"
                           name="gst"
                           value="{{ old('gst', $product->gst) }}"
                           class="form-control">

                </div>

                {{-- HSN --}}
                <div class="col-md-6">

                    <label class="form-label">
                        HSN No
                    </label>

                    <input type="text"
                           name="hsn_no"
                           value="{{ old('hsn_no', $product->hsn_no) }}"
                           class="form-control">

                </div>

{{-- Tray Required --}}
<div class="col-md-6"
     id="trayRequiredDiv"
     style="{{ old('unit', $product->unit) != 'box' ? '' : 'display:none;' }}">

    <label class="form-label d-block">
        Tray Required *
    </label>

    <div class="form-check form-check-inline">

        <input type="radio"
               name="tray_required"
               value="1"
               class="form-check-input"
               {{ old('tray_required', $product->tray_required) == 1 ? 'checked' : '' }}>

        <label class="form-check-label">
            Yes
        </label>

    </div>

    <div class="form-check form-check-inline">

        <input type="radio"
               name="tray_required"
               value="0"
               class="form-check-input"
               {{ old('tray_required', $product->tray_required) == 0 ? 'checked' : '' }}>

        <label class="form-check-label">
            No
        </label>

    </div>

    @error('tray_required')
         <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror

</div>

                {{-- Status --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="status"
                            class="form-select">

                        <option value="1"
                            {{ $product->status == 1 ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0"
                            {{ $product->status == 0 ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                </div>

            </div>

            <div class="mt-4 d-flex gap-2">

                <a href="{{ route('products.index') }}"
                   class="btn btn-secondary">

                    Back

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    Update Product

                </button>

            </div>

        </form>

    </div>

</div>

@endsection