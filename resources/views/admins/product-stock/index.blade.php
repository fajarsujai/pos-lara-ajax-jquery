@extends('layouts.app')
@section('content')
@include('layouts.admin.headers.cards-empty')
<div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="card mt-5">
          <div class="card-header">
             <div class="col-md-12">
                 <h4 class="card-title">Datatable Stok Produk - nicesnippets.com  
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
                         <th>Stok</th>
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
                              <label for="stock" class="col-sm-4 control-label">Stok</label>
                              <div class="col-sm-12">
                                  <input type="number" class="form-control" id="stock" name="stock" placeholder="Masukan Stok Product" value="" maxlength="50" required="">
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
      // var product_code = '';

    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('product-stock.index') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'id'},
              {data: 'products.product_name', name: 'product_name'},              
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

      $.ajax({
        url:"{{ route('product-stock.getProduct') }}",
        type:"POST",
        cache:false,
        dataType:'json',
        success: function(dataResultProduct){          
          var resultDataProduct = dataResultProduct.data;
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

      $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Sending..');
    
        $.ajax({
          data: $('#ItemForm').serialize(),
          url: "{{ route('product-stock.store') }}",
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
       
      $.get("{{ route('product-stock.index') }}" +'/' + Item_id +'/edit', function (data) {
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
         url: "{{ route('product-stock.store') }}"+'/'+Item_id,
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