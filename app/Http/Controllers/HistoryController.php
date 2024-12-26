<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Organization;
use App\Models\SellHistory;
use App\Models\Purchase_History;
use App\Models\Sell;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HistoryController extends Controller
{
    public function history(Request $request)
    {
        // dd('helo');
        $brands = Brand::all();
        $companies = Organization::all();
        $products = Product::all();



        // $stocks = DB::table('batches')
        //     ->join('products', 'batches.product_id', '=', 'products.id')
        //     ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
        //     ->select(
        //         'products.sku',
        //         'batches.batch_number as batch_no',
        //         'batches.buy_price',
        //         DB::raw('COUNT(cartons.id) as cartons'),
        //         DB::raw('SUM(cartons.no_of_items_inside) as total_items'),
        //         DB::raw('SUM(cartons.missing_items) as missing_items'),
        //         'batches.id as batch_id'
        //     )
        //     ->groupBy('batches.id', 'products.sku', 'batches.batch_number', 'batches.buy_price')
        //     ->orderBy('batches.id', 'DESC')
        //     ->get();


        return view('admin.purchaseHistory', compact('companies', 'products','brands'));
    }

    public function getHistory(Request $request)
    {

        if($request->company){
        $organization = Organization::where('id', '=', $request->company)->first();
        $brand = Brand::where('id', '=', $request->brandName)->first();

        $databaseName = Session::get('db_name');
        $selectedDate = $request->selectedDate;

        config(['database.connections.pgsql.database' => $organization->name]);
        DB::purge('pgsql');
        DB::connection('pgsql')->getPdo();
        $query = DB::table('batches')
            ->join('products', 'batches.product_id', '=', 'products.id')
            ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
            ->select(
                'batches.batch_number as batch_no',
                'batches.buy_price',
                DB::raw('COUNT(cartons.id) as cartons'),
                DB::raw('SUM(cartons.no_of_items_inside) as total_items'),
                DB::raw('SUM(cartons.missing_items) as missing_items'),
                'batches.id as batch_id'
            )
            ->groupBy('batches.id', 'batches.batch_number', 'batches.buy_price')
            ->orderBy('batches.id', 'DESC');

        // Apply filter if productId is provided
        if ($request->has('productId') && !empty($request->productId)) {
            $query->where('batches.product_id', $request->productId);
        }

        if (!empty($selectedDate)) {
            $query->where(function ($q) use ($selectedDate) {
                $q->whereDate('batches.manufacturing_date', '<=', $selectedDate)
                  ->whereDate('batches.expiry_date', '>=', $selectedDate);
            });
        }

        $stocks = $query->get();
        }else{
            $stocks = [];
        }

        // dd($stocks);

        // $history = Purchase_History::all();

        // if (!$history) {
        //     return response()->json(['success' => false, 'message' => 'History not found.'], 404);
        // }

        // // Decode the details field
        // $historyDetails = json_decode($history->details, true);

        // if (!$historyDetails) {
        //     return response()->json(['success' => false, 'message' => 'Invalid history details format.'], 422);
        // }

        // // Format the response data
        // $response = [
        //     'id' => $historyDetails['batch']['id'] ?? null,
        //     'sku' => $historyDetails['product']['sku'] ?? null,
        //     'name' => $historyDetails['product']['name'] ?? null,
        //     'description' => $historyDetails['product']['description'] ?? null,
        //     'company_id' => $historyDetails['product']['company_id'] ?? null,
        //     'status' => $historyDetails['product']['status'] ?? null,
        //     'created_at' => $historyDetails['batch']['created_at'] ?? null,
        //     'updated_at' => $historyDetails['batch']['updated_at'] ?? null,
        //     'batch_number' => $historyDetails['batch']['batch_number'] ?? null,
        //     'product_id' => $historyDetails['batch']['product_id'] ?? null,
        //     'manufacturing_date' => $historyDetails['batch']['manufacturing_date'] ?? null,
        //     'expiry_date' => $historyDetails['batch']['expiry_date'] ?? null,
        //     'base_price' => $historyDetails['batch']['base_price'] ?? null,
        //     'exchange_rate' => $historyDetails['batch']['exchange_rate'] ?? null,
        //     'buy_price' => $historyDetails['batch']['buy_price'] ?? null,
        //     'notes' => $historyDetails['batch']['notes'] ?? null,
        //     'cartons' => collect($historyDetails['cartons'] ?? [])->map(function ($carton) {
        //         return [
        //             'carton_number' => $carton['carton_number'] ?? null,
        //             'no_of_items_inside' => $carton['no_of_items_inside'] ?? null,
        //             'missing_items' => $carton['missing_items'] ?? null,
        //         ];
        //     }),
        // ];


        return response()->json(['data' => $stocks]);
    }

    // public function getHistory(Request $request)
    // {
    //     try {
    //         // $organization = Organization::where('id','=',$request->company)->first();

    //         // config(['database.connections.pgsql.database' => $organization->name]);
    //         // DB::purge('pgsql');
    //         // DB::connection('pgsql')->getPdo();
    //         setDatabaseConnection();
    //         $histories = Purchase_History::all();
    //         // dd($histories);

    //         if ($histories->isEmpty()) {
    //             return response()->json(['success' => false, 'message' => 'No history found for the given company.'], 404);
    //         }


    //         $formattedHistories = $histories->map(function ($history) {
    //             $details = json_decode($history->details, true);
    //            // dd($history->batch_id);
    //             if (!$details || !is_array($details)) {
    //                 return null;
    //             }

    //             return collect($details)->map(function ($detail) use ($history)  {

    //                 $totalCartons = count($detail['cartons'] ?? []);
    //                 $totalItems = collect($detail['cartons'] ?? [])->sum('no_of_items_inside');
    //                 $missingItems = collect($detail['cartons'] ?? [])->sum('missing_items');

    //                 return (object) [
    //                     'sku' => $detail['product']['sku'] ?? null,
    //                     'batch_no' => $detail['batch_number'] ?? null,
    //                     'buy_price' => $detail['buy_price'] ?? null,
    //                     'cartons' => $totalCartons,
    //                     'total_items' => $totalItems,
    //                     'missing_items' => $missingItems,
    //                     'batch_id' => $history->id,
    //                 ];
    //             });
    //         })->flatten(1)->filter();

    //         // dd($formattedHistories);

    //         return response()->json(['success' => true, 'data' => $formattedHistories], 200);
    //     } catch (\Exception $e) {
    //         \Log::error('Error retrieving history: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred while fetching history.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }


    public function detailHistory(Request $request, $id, $companyName)
    {

        setDatabaseConnection();
        // config(['database.connections.pgsql.database' => $companyName]);
        // DB::purge('pgsql');
        // DB::connection('pgsql')->getPdo();

        $stocks = DB::table('batches')
            ->join('products', 'batches.product_id', '=', 'products.id')
            ->join('cartons', 'batches.id', '=', 'cartons.batch_id')
            ->select(
                'products.*',
                'batches.*',
                'cartons.carton_number',
                'cartons.no_of_items_inside',
                'cartons.missing_items'
            )
            ->where('batches.id', $id)
            ->orderBy('batches.id', 'DESC')
            ->get();

        // Grouping the cartons under the same batch
        $groupedStocks = $stocks->groupBy('batch_id')->map(function ($group) {
            $first = $group->first();
            $first->cartons = $group->map(function ($item) {
                return [
                    'carton_number' => $item->carton_number,
                    'no_of_items_inside' => $item->no_of_items_inside,
                    'missing_items' => $item->missing_items,
                ];
            })->values();
            return $first;
        })->first(); // Extract the first item to make it an object

        return view('admin.purchaseHistoryDetails', ['batch' => $groupedStocks]);

        //     $purchaseHistory = DB::table('purchase_history')
        //     ->where('id', '=', $id)
        //     ->first(); 

        // if (!$purchaseHistory) {

        //     return redirect()->back()->with('error', 'Purchase history not found.');
        // }

        // $details = json_decode($purchaseHistory->details);

        // $batch = collect($details)->map(function ($item) {
        //     if (isset($item->batch_number)) {
        //         $batch = [
        //             'batch_number' => $item->batch_number,
        //             'product_id' => $item->product_id,
        //             'manufacturing_date' => $item->manufacturing_date,
        //             'expiry_date' => $item->expiry_date,
        //             'base_price' => $item->base_price,
        //             'exchange_rate' => $item->exchange_rate,
        //             'buy_price' => $item->buy_price,
        //             'notes' => $item->notes ?? null,
        //         ];

        //         $cartons = collect($item->cartons)->map(function ($carton) {
        //             return [
        //                 'carton_number' => $carton->carton_number,
        //                 'no_of_items_inside' => $carton->no_of_items_inside,
        //                 'missing_items' => $carton->missing_items,
        //             ];
        //         });

        //         $batch['cartons'] = $cartons;

        //         return $batch;
        //     }

        //     return null;
        // })->filter();
        // $batch = $batch->first();

        //     //  dd($batch);
        //     return view('admin.purchaseHistoryDetails', ['batch' => $batch]);
    }

    public function sellHistory(Request $request)
    {
        $companies = Organization::all();
        return view('admin.sellHistory', compact('companies'));
    }

    public function getSellHistory(Request $request)
    {
        // dd($request->input());
        $organization = Organization::where('id', '=', $request->company)->first();
        config(['database.connections.pgsql.database' => $organization->name]);
        DB::purge('pgsql');
        DB::connection('pgsql')->getPdo();

        $query = Sell::orderBy('id', 'desc');
        if ($request->has('productId') && !empty($request->productId)) {
            $query->where('sell.sku', $request->productId);
        }
        $sells = $query->get();

        return response()->json(['data' => $sells]);
    }

    public function historyProducts(Request $request){
        $organization = Organization::where('id', '=', $request->company)->first();
        config(['database.connections.pgsql.database' => $organization->name]);
        DB::purge('pgsql');
        DB::connection('pgsql')->getPdo();

        $products = Product::select('id', 'name')->get();

        return response()->json(['products' => $products]);    
    }
}
