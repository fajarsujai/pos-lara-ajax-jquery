<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {            
            $data = ProductUnit::with(['products','units'])->select('product_units.*');
            return Datatables::eloquent($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"   data-id="'.$row->id.'" data-original-title="Beli" class="btn btn-success btn-sm btnBeli">Beli</a>';
                            return $btn;
                    })                  
                    ->rawColumns(['action'])
                    ->make(true);                
        }
      
        return view('admins.transaction.index');
    }

    public function getProductUnitById($id)
    {
       $data = ProductUnit::with(['products','units'])->select('product_units.*')->find($id);
        return response()->json($data, 200);    
    }
}
