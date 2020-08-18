<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Controller;
use DB;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
                         
        if ($request->ajax()) {            
            $products = Product::with('categories')->select('products.*'); 
            return Datatables::eloquent($products)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editItem">Edit</a>';
   
                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteItem">Delete</a>';
                            return $btn;
                    })                  
                    ->rawColumns(['action'])
                    ->make(true);                
        }
      
        return view('admins.product.index');
    }

    public function getCategory()
    {
        $data = Category::all();
        return json_encode(array('data'=>$data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        Product::updateOrCreate(['id' => $request->Item_id],
        ['product_code' => $request->product_code,
        'product_name'=> $request->product_name,
        'category_id'=>$request->category_id,
        'slug'=>Str::slug($request->product_name)]);        

        return response()->json(['success'=>'Data saved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Product::find($id);
        return response()->json($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::find($id)->delete();
     
       return response()->json(['success'=>'Data deleted successfully.']);
    }
}
