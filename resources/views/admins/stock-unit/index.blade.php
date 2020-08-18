@extends('layouts.app')
@section('content')
@include('layouts.admin.headers.cards-empty')
<div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="card mt-5">
          <div class="card-header">
             <div class="col-md-12">
                 <h4 class="card-title">Datatable Stok Unit - nicesnippets.com  
                   <a class="btn btn-success ml-5" href="javascript:void(0)" id="createNewItem"> Create New Item</a>
                 </h4>
             </div>
          </div>
          <div class="card-body">
            <table class="table table-bordered data-table">
                 <thead>
                     <tr>
                         <th width="5%">No</th>
                         <th>Nama Produk</th>
                         <th>Nama Unit</th>
                         <th>Jumlah Satuan dalam Stok</th>
                         <th>Stok Unit</th>
                         <th width="15%">Action</th>
                     </tr>
                 </thead>
                 <tbody>
                 </tbody>
             </table>
         </div>         
         <div class="modal fade" id="ajaxModel" aria-hidden="true">
             <div class="modal-dialog">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h4 class="modal-title" id="modelHeading"></h4>
                     </div>
                     <div class="modal-body">
                         <form id="ItemForm" name="ItemForm" class="form-horizontal">
                            <input type="hidden" name="Item_id" id="Item_id">
                            <div class="form-group">
                              <div class="col-sm-12">
                                <label for="product_code" class="col-sm-4 control-label">Nama Produk</label>
                                  <select class="form-control" id="product_code" name="product_code">
                                                                        
                                  </select>                                 
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="col-sm-12">
                                <label for="unit_id" class="col-sm-4 control-label">Nama Unit</label>
                                  <select class="form-control" id="unit_id" name="unit_id">
                                                                        
                                  </select>                                 
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="stock" class="col-sm-6 control-label">Stok Tersedia <span id="stock_tersedia"></span> </label>
                              <div class="col-sm-12">
                                  <input type="number" class="form-control" id="stock" name="stock" placeholder="Masukan Stok Product" value="" max="10" min="0" required="">
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="jumlah_unit_dalam_satuan" class="col-sm-6 control-label">Jumlah Satuan dalam Stok</label>
                              <div class="col-sm-12">
                                  <input type="number" class="form-control" id="jumlah_unit_dalam_satuan" name="jumlah_unit_dalam_satuan" placeholder="Masukan Jumlah dalam Stok" value="" max="10" required="">
                              </div>
                            </div>
                                                        
                             <div class="col-sm-offset-2 col-sm-10">
                              <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Simpan</button>
                             </div>
                         </form>
                     </div>
                 </div>
             </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@push('js')
<script type="text/javascript">
    $(function () {
      var optionData = '';
      var optionDataProduct = '';
      var optionDataUnit = '';
      var product_code = '';

    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('stock-unit.index') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'id'},
              {data: 'products.product_name', name: 'product_name'},  
              {data: 'units.unit_name', name: 'unit_name'},              
              {data: 'jumlah_unit_dalam_satuan', name: 'jumlah_unit_dalam_satuan'},
              {data: 'stock', name: 'stock'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });


      $('#createNewItem').click(function(){
        $('#saveBtn').val("create-Item");
        $('#Item_id').val('');
        $('#ItemForm').trigger("reset");
        $('#modelHeading').html("Create New Item");
        $('#ajaxModel').modal('show');
      });

      $('#ajaxModel').on('shown.bs.modal', function () {
        $('#product_code').focus()
      });

      $('#product_code').change(function () { 
        product_code = $(this).val();

        $.ajax({
        url:"{{ route('stock-unit.getStock') }}",
        type:"POST",
        cache:false,
        dataType:'json',
        success: function(dataResultStock){          
          var resultDataStock = dataResultStock.data;
          console.log('data stock : ',resultDataStock);          
          $.each(resultDataStock, function(index, row){
            if(row.product_code == product_code){
              $('#stock_tersedia').text(row.stock)
              console.log('row stock :',row.stock);
              $('#stock').on('change', function(){
              // var max = parseInt($(this).attr('max', row.stock));
              // console.log(max);
              var min = parseInt($(this).attr('min'));
                if($(this).val() > row.stock){
                  $(this).val(row.stock);
                }else if($(this).val() < min ){
                  $(this).val(0);
                }
              });
            }    
          });
          // $('#product_code').append(optionDataProduct)
        },
        error: function(e){
          console.log('error : ', e);
        }
      });


      });



      $.ajax({
        url:"{{ route('stock-unit.getProduct') }}",
        type:"POST",
        cache:false,
        dataType:'json',
        success: function(dataResultProduct){          
          var resultDataProduct = dataResultProduct.data;
          console.log(resultDataProduct);
          optionDataProduct += '<option disabled selected>Pilih..</option>'
          $.each(resultDataProduct, function(index, row){
            optionDataProduct += '<option class="['+row.id+']" value="'+row.product_code+'">'+row.product_name+'</option>'
          });
          $('#product_code').append(optionDataProduct)
        },
        error: function(e){
          console.log('error : ', e);
        }
      });

      $.ajax({
        url:"{{ route('stock-unit.getUnit') }}",
        type:"POST",
        cache:false,
        dataType:'json',
        success: function(dataResultUnit){          
          var resultDataUnit = dataResultUnit.data;
          console.log(resultDataUnit);
          optionDataUnit += '<option disabled selected>Pilih..</option>'
          $.each(resultDataUnit, function(index, row){
            optionDataUnit += '<option class="['+row.id+']" value="'+row.id+'">'+row.unit_name+'</option>'
          });
          $('#unit_id').append(optionDataUnit)
        },
        error: function(e){
          console.log('error : ', e);
        }
      });

      // $.ajax({
      //   url:"{{ route('stock-unit.getStock') }}",
      //   type:"POST",
      //   cache:false,
      //   dataType:'json',
      //   success: function(dataResultUnit){          
      //     var resultDataUnit = dataResultUnit.data;
      //     console.log(resultDataUnit);
      //     optionDataUnit += '<option disabled selected>Pilih..</option>'
      //     $.each(resultDataUnit, function(index, row){
      //       optionDataUnit += '<option class="['+row.id+']" value="'+row.unit_id+'">'+row.unit_name+'</option>'
      //     });
      //     $('#unit_id').append(optionDataUnit)
      //   },
      //   error: function(e){
      //     console.log('error : ', e);
      //   }
      // });

      $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Sending..');
    
        $.ajax({
          data: $('#ItemForm').serialize(),
          url: "{{ route('stock-unit.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#ItemForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();
         
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });
    });

    $('body').on('click', '.editItem', function () {
      var Item_id = $(this).data('id');
       
      $.get("{{ route('stock-unit.index') }}" +'/' + Item_id +'/edit', function (data) {
        console.log(data);
          $('#modelHeading').html("Edit Item");
          $('#saveBtn').val("edit-user");
          $('#ajaxModel').modal('show');          
          $('#Item_id').val(data.id);
          $('#product_code').val(data.product_code).change();
          $('#stock').val(data.stock);
          
      });
   });

   $('body').on('click', '.deleteItem', function () {
     
     var Item_id = $(this).data("id");
     confirm("Are You sure want to delete !");
   
     $.ajax({
         type: "DELETE",
         url: "{{ route('stock-unit.store') }}"+'/'+Item_id,
         success: function (data) {
             table.draw();
         },
         error: function (data) {
             console.log('Error:', data);
         }
     });
 });

      

      
    });
  </script>
@endpush
@endsection