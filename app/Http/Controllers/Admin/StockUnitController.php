<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Product;
use App\Models\StockUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ProductStock;
use App\Models\Unit;

class StockUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($data);
        if ($request->ajax()) {            
            $data = StockUnit::with(['products','units'])->select('stock_units.*'); 
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
      
        return view('admins.stock-unit.index');
    }

    public function getProduct()
    {
        $product_code = [];
        $product_codes = DB::table('product_stocks')
                ->select('product_code')
                ->groupBy('product_code')
                ->get();
        
        foreach ($product_codes as $key => $value) {
            $product_code[] = $value->product_code;
        }
        
        $data = Product::select('product_code','product_name')
                ->whereIn('product_code',$product_code)
                ->get();

        // $data = Product::all();
        
        return json_encode(array('data'=>$data));
    }

    public function getStock()
    {       
        $data = ProductStock::all();
        
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
        $stock = $request->stock * $request->jumlah_unit_dalam_satuan;
        if ($request->product_code) {            
            StockUnit::updateOrCreate(['id'=>$request->Item_id],
            ['product_code'=>$request->product_code,
                'unit_id' => $request->unit_id,
                'jumlah_unit_dalam_satuan' => $request->jumlah_unit_dalam_satuan,
                'stock' => $stock
            ]);
            if ($request->stock) {
                ProductStock::where('product_code',$request->product_code)->decrement('stock',$request->stock);                    
            }
        }


        return response()->json(['success'=>'Data saved successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
