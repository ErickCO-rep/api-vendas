<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;

class SalesController extends Controller
{
    public function __construct(private Sale $sale){
        $this->sale = $sale;
    }

    public function getSalesSeller($id){

        // Get sales by seller id 
        $sales = Sale::where('seller_id', $id)
        ->with('seller:id,name,email')
        ->get();

        // Validate if there are sales from this seller
        if ($sales->isEmpty()) {
            return response()->json(['error' => 'No sales found for this seller.'], 404);
        }
        
        // Get seller of sales
        $seller = $sales[0]->seller->toArray();

        // Remove seller from each sale
        $salesData = $sales->map(function ($sale) {
            unset($sale->seller);
            return $sale;
        });

        // Struct of return
        $sellerData = [
            'seller' => $seller,
            'sales' => $salesData->toArray(),
        ];

        return response()->json($sellerData, 200);
    }

    public function createSale(Request $request){


        try {
            $request->validate([
                'seller_id' => 'required|string|max:255',
                'value' => [
                    'required',
                ],
            ]);
         
            //Calculate for insert commission of 8.5% in sale
            $request['commission'] = $this->commissionCalculate($request['value']);
            
            $sale = $this->sale->create($request->all());
            $seller = $sale->seller;

            return response()->json([
                'id' => $sale->id,
                'name' => $seller->name,
                'email' => $seller->email,
                'commission' => $sale->commission,
                'sale_value' => $sale->value,
                'sale_date' => $sale->created_at,
                ], 201);
                
        } catch (\Illuminate\Validation\ValidationException $e) {

            $errors = $e->validator->errors()->toArray();
            return response()->json(['errors' =>  $errors], 422);

        }

    }

   
}
