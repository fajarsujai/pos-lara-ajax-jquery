<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Support\Facades\DB;
class ProductUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {            
            $data = ProductUnit::with(['products','units','stock_units'])->select('product_units.*');
            return Datatables::eloquent($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editItem">Edit</a>';
   
                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteItem">Delete</a>';
                            return $btn;
                    })                  
                    ->rawColumns(['action'])
                    ->make(true);                
        }
      
        return view('admins.product-unit.index');
    }


    public function getProduct()
    {
        $product_code = [];
        $product_codes = DB::table('stock_units')
                ->select('product_code')
                ->groupBy('product_code')
                ->get();
        
        foreach ($product_codes as $key => $value) {
            $product_code[] = $value->product_code;
        }
        
        $data = Product::select('product_code','product_name')
                ->whereIn('product_code',$product_code)
                ->get();
                
        return json_encode(array('data'=>$data));
    }

    public function getUnit()
    {
        $data = Unit::all();        
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
        // dd($request->all());
        ProductUnit::updateOrCreate(['id' => $request->Item_id],
        ['product_code' => $request->product_code,
        'unit_id'=> $request->unit_id,
        'qty_minimum' => $request->qty_minimum,
        'base_price'=>$request->base_price,
        'sell_price' => $request->sell_price
        ]);        

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
        $item = ProductUnit::find($id);
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
        ProductUnit::find($id)->delete();
     
       return response()->json(['success'=>'Data deleted successfully.']);
    }
}
