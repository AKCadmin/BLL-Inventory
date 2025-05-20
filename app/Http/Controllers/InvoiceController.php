<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\SellCounter;
use App\Models\Organization;
use App\Models\Product;
use App\Models\Batch;
use PDF;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('invoice.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function download($orderId)
    {
        try {

            setDatabaseConnection();

            $invoice = Invoice::with(['sellCounter' => function ($query) {
                $query->with(['product', 'batch', 'company', 'customerList', 'sellPrice']);
            }])
                ->where('order_id', $orderId)
                ->where('invoice_approved', true)
                ->firstOrFail();
           
            // Get first sell counter record for company details
            $firstSellCounter = $invoice->sellCounter->first();
            if (!$firstSellCounter) {
                throw new \Exception('No sell counter records found');
            }
dd($firstSellCounter);
            // Get organization details
            $organization = $firstSellCounter->company;

            // Prepare products array and calculate total
            $products = [];
            $totalAmount = 0;

            foreach ($invoice->sellCounter as $sellCounter) {
                // dd($sellCounter->customerList);
                $unitPrice = $this->getPriceByCustomerType($sellCounter);
                $amount = $sellCounter->provided_no_of_cartons * $unitPrice;
                $totalAmount += $amount;

                $products[] = [
                    'name' => $sellCounter->product->name,
                    'description' => $sellCounter->product->description,
                    'packaging_type' => $sellCounter->customerList->type_of_customer,
                    'quantity' => $sellCounter->provided_no_of_cartons,
                    'unit_price' =>  $unitPrice,
                    'unit' => $sellCounter->batch->unit,
                    'customer' => $sellCounter->customerList->name,
                    'amount' => $amount,
                    'batch' => [
                        'batch_number' => $sellCounter->batch->batch_number,
                        'manufacturing_date' => $sellCounter->batch->manufacturing_date,
                        'expiry_date' => $sellCounter->batch->expiry_date
                    ]
                ];
            }
dd($organization);
            $invoiceData = [
                'invoice_number' => $invoice->invoice_number,
                'order_id' => $invoice->order_id,
                'date' => $invoice->created_at->format('Y-m-d'),

                // Organization/Company details
                'company_name' => $organization->name != null ? $organization->name : 'N/A',
                'company_address' => $organization->address,
                'company_email' => $organization->contact_email,
                'company_phone' => $organization->phone_no,

                // Customer details
                'customer_name' => $invoice->customer,
                'customer_type' => $invoice->customer_type,

                // Products details
                'products' => $products,
                'total_amount' => $totalAmount
            ];

            // dd($invoiceData);

            // Generate PDF
            $pdf = PDF::loadView('invoice.index', $invoiceData);

            // Set filename
            $filename = 'invoice-' . $invoice->invoice_number . '.pdf';

            // Return the PDF for download
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate invoice',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    private function getPriceByCustomerType($sellCounter)
    {
        if (!$sellCounter->sellPrice) {
            return 0;
        }

        switch ($sellCounter->customer_type) {
            case 'wholesale':
                return $sellCounter->sellPrice->wholesale_price;
            case 'hospital':
                return $sellCounter->sellPrice->hospital_price;
            case 'retail':
                return $sellCounter->sellPrice->retail_price;
            default:
                return 0;
        }
    }
}
