@extends('layouts.auth')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <div class="page-heading">
        <h1 class="h3">Add Tray Return</h1>
    </div>

    <section class="panel mt-3">

        <form method="POST"
              action="{{ route('tray-returns.store') }}">

            @csrf

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label>Customer</label>

                    <select name="customer_id"
                            class="form-select"
                            required>

                        <option value="">
                            Select Customer
                        </option>

                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Return Date</label>

                    <input type="date"
                           name="return_date"
                           class="form-control"
                           value="{{ date('Y-m-d') }}"
                           required>
                </div>

                <div class="col-md-2 mb-3">
                    <label>Tray Type</label>

                    <select name="tray_type"
                            class="form-select"
                            required>
                        <option value="Big">Big</option>
                        <option value="Small">Small</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Tray Qty</label>

                    <input type="number"
                           name="tray_qty"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Remarks</label>

                    <textarea name="remarks"
                              class="form-control"
                              rows="2"></textarea>
                </div>

            </div>

            <button type="submit"
                    class="btn btn-primary">
                Save
            </button>

        </form>

    </section>

</div>

@endsection