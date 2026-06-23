@foreach($sales as $sale)

    @include('sales.invoice', [
        'sale' => $sale
    ])

    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif

@endforeach

<script>
window.onload = function(){
    window.print();
}
</script>