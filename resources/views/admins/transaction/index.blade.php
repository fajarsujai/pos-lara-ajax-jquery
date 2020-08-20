@extends('layouts.app')
@section('content')
@include('layouts.admin.headers.cards-empty')
<div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="card mt-5">
          <div class="card-header">
             <div class="col-md-12">
                 <h4 class="card-title">Datatable Produk Satuan - nicesnippets.com  
                 </h4>
             </div>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered data-table">
                 <thead>
                     <tr>
                         <th width="5%">No</th>
                         <th>Nama Produk</th>
                         <th>Nama Unit</th>
                         <th>Qty Minimum</th>
                         <th>Stock Unit</th>
                         <th>Harga Dasar</th>
                         <th>Harga Jual</th>
                         <th width="15%">Action</th>
                     </tr>
                 </thead>
                 <tbody>
                 </tbody>
             </table>
         </div> 
         
         <div class="table-responsive">
            <table class="table table-bordered" id="table-transaction">
                 <thead>
                     <tr>
                         {{-- <th width="5%">No</th> --}}
                         <th>Nama Produk</th>
                         <th>Nama Unit</th>
                         <th>Harga Satuan</th>
                         <th>Qty</th>
                         <th>Harga Total</th>
                         <th width="15%">Action</th>
                     </tr>                                      
                 </thead>
                 <tbody>
                 </tbody>
                 <tfoot>
                  <tr>
                    <th>SubTotal</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td id="subtotal"></td>
                  </tr>  
                 </tfoot>
             </table>
         </div>
        </div>
      </div>
    </div>
    @include('layouts.admin.headers.cards-empty')
  </div>
@push('js')
<script type="text/javascript">
    $(function () {
        var item_id = '';
        var dataProducts = [];
        var sellPrices = [];

    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ url('transaction') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'id'},
              {data: 'products.product_name', name: 'products.product_name'},
              {data: 'units.unit_name', name: 'units.unit_name'},              
              {data: 'qty_minimum', name: 'qty_minimum'},
              {data: 'stock', name: 'stock'},
              {data: 'base_price', name: 'base_price'},
              {data: 'sell_price', name: 'sell_price'},
              {data: 'action', name: 'action', orderable: false, searchable: true},
          ]
      });
      
      
      $('body').on('click', '.btnBeli', function(){
        item_id = $(this).data('id');

          $.get("{{ url('transaction') }}"+'/'+item_id+'/'+'getProductUnitById', function(data){
              data_sell_price = {"id":data.id,"sell_price":data.sell_price}
              dataProducts.push(data_sell_price);
              console.log(dataProducts);
              var markup = "<tr><td data-product_name="+data.products.product_name+" id='product_name"+item_id+"'>"+data.products.product_name+"</td><td data-unit_name="+data.units.unit_name+" id='unit_name"+item_id+"'>"+data.units.unit_name+"</td><td data-sell_price="+data.sell_price+" id='sell_price"+item_id+"'>"+data.sell_price+"</td><td><input type='number' value=1 id='qty"+item_id+"' class='form-control qty' data-id="+item_id+" /></td><td id='harga_total"+item_id+"'>"+data.sell_price+"</td><td><button type='button' class='btn btn-danger btn-sm remove-tr'>-</button></td></tr>";
              // append to tbody
              $("#table-transaction tbody").append(markup);
                // delete per row
                $(document).on('click', '.remove-tr', function(){
                    $(this).parents('tr').remove();
                });

                var subtotal = dataProducts.reduce(function(previousValue, currentValue) {
                  return {
                      sell_price: previousValue.sell_price + currentValue.sell_price,
                  }
                });
                
                $('#subtotal').html(subtotal.sell_price);
              console.log(subtotal);
            });
      });

      // change qty
      $('body').on('change','.qty',function(){
            item_id = $(this).attr('data-id');

            var harga_total = $('#sell_price'+item_id+'').attr('data-sell_price') * $('#qty'+item_id+'').val();
            $('#harga_total'+item_id+'').html(harga_total);

            changeDesc( item_id, harga_total);

            var subtotal = dataProducts.reduce(function(previousValue, currentValue) {
                  return {
                      sell_price: previousValue.sell_price + currentValue.sell_price,
                  }
                });
                
                $('#subtotal').html(subtotal.sell_price);


      });

      Array.prototype.sum = function (prop) {
          var total = 0
          for ( var i = 0, _len = this.length; i < _len; i++ ) {
              total += this[i][prop]
          }
          return total
      }


      function changeDesc( value, desc ) {
        for (var i in dataProducts) {
          if (dataProducts[i].id == value) {
              dataProducts[i].sell_price = desc;
              break; //Stop this loop, we found it!
          }
        }
      }


    });
  </script>
@endpush
@endsection