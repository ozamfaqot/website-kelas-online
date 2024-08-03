<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentLog;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function midtransHandler(Request $request)
    {
        $data = $request->all();

        $signatureKey = $data['signature_key'];

        $orderId = $data['order_id'];
        $statusCode = $data['status_code'];
        $grossAmount = $data['gross_amount'];
        $serverKey = env('MIDTRANS_SERVER_KEY');

        $mySignatureKey = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        $transactionStatus = $data['transaction_status'];
        $type = $data['payment_type'];
        $fraudStatus = $data['fraud_status'];

        if($signatureKey !== $mySignatureKey)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid signature key'
            ], 400);
        }

        //7-12edjw
        $orderIdReal = explode('-', $orderId)[0];
        $order = Order::find($orderIdReal);

        // return response()->json([
        //     // 'status' => $order->status,
        //     'data' => $order
        // ]);

        if(!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order id not found'
            ], 404);
        }

        if ($order->status === 'success')
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Operation not permitted'
            ], 405);
        }

        // memberik akses kelelas jika pembayaran berhasil
         // Sample transactionStatus handling logic

        if ($transactionStatus == 'capture')
        {
            if ($fraudStatus == 'challenge')
            {
                $order->status = 'challenge';
            }
            else if ($fraudStatus == 'accept')
            {
                // TODO set transaction status on your database to 'success'
                // and response with 200 OK
                $order->status = 'success';
            }
        } else if ($transactionStatus == 'settlement'){
            // TODO set transaction status on your database to 'success'
            // and response with 200 OK
            $order->status = 'success';
        } else if ($transactionStatus == 'cancel' ||
            $transactionStatus == 'deny' ||
            $transactionStatus == 'expire'){
            // TODO set transaction status on your database to 'failure'
            // and response with 200 OK
            $order->status = 'failure';
        } 
        else if ($transactionStatus == 'pending')
        {
            // TODO set transaction status on your database to 'pending' / waiting payment
            // and response with 200 OK
            $order->status = 'pending';
        }

        $logData = [
            'status' => $transactionStatus,
            'payment_type' => $type,
            'raw_response' => json_encode($data),
            'order_id' => $orderIdReal
        ];
        PaymentLog::create($logData);
        $order->save();

        if ($order->status === 'success')
        {
            // Memberikan akses premium -> service course = kepada org yang berhasil bayar
            createPremiumAccess([
                'user_id' => $order->user_id,
                'course_id' => $order->course_id,
            ]);
        }

        return response()->json('ok');
    }
}
