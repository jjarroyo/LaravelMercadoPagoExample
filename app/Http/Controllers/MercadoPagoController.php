<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\Item;
use MercadoPago\Payer;
use MercadoPago\Payment;
use MercadoPago\Preference;

class MercadoPagoController extends Controller
{
    public function payProduct(Request $request,$id){
        \MercadoPago\SDK::setAccessToken(env('MERCADO_PAGO_ACCESS_TOKEN'));
        $preference = new Preference();
        $items = array();        
        $_item = new Item();
        $_item->id = $id;
        $_item->title = "Product".$id;
        $_item->quantity = 1;
        $_item->unit_price = 5000 * $id;

        $items[] = $_item;

        $preference->items = $items;

        $preference->back_urls = array(
            "success" => env("APP_URL")."/sale/complete",
            "failure" => env("APP_URL")."/sale/error", 
            "pending" => env("APP_URL")."/sale/pending"
        );
        $preference->auto_return = "approved"; 
        $preference->external_reference = $id;
      //  $preference->notification_url = env("APP_URL")."/notification?source_news=webhooks";

        
        $payer = new Payer();
        $payer->name =  "Test Test";           
        $payer->email = "test_user_6836934@testuser.com";
        $payer->date_created = Carbon::now();
        $payer->phone = array(
            "area_code" => "",
            "number" =>  "3005686985"
        );
        $preference->payer = $payer;

        $payment_methods  = new Payment();
        $payment_methods->installments = 1;

        $preference->payment_methods = $payment_methods;

        $preference->save();

        return redirect()->away($preference->sandbox_init_point);
    }

    public function completeTransaction(Request $request){   
        $data = $request->all();   
        Log::debug(json_encode($data));
        return redirect()->route("index")->with('message', 'Pedido realizado correctamente!');
    }

    public function pendingTransaction(Request $request){   
        $data = $request->all();  
        Log::debug(json_encode($data)); 
        return redirect()->route("index")->with('message', 'Pedido pendiente de validacion!');
    }

    public function errorTransaction(Request $request){   
        $data = $request->all();   
        Log::debug(json_encode($data));
        return redirect()->route("index")->with('message', 'Errror al pagar!');
    }
}
