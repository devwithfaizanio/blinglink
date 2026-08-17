<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\PaymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Stripe\Customer;
use Stripe\EphemeralKey;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\SetupIntent;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Subscription;
use function PHPUnit\Framework\returnArgument;

class StripeController extends Controller
{
    public function __construct()
    {
        $strip_key = config('services.stripe.key');

        Stripe::setApiKey($strip_key);
    }

    public function generateEphemeralKey(Request $request)
    {
        try {
            $strip_key = config('services.stripe.key');

            Stripe::setApiKey($strip_key);



            $intent = PaymentIntent::create([
                'amount' => $request->get('amount', 0) * 100,
                'currency' => 'sek',
                'payment_method_types' => ['card'],
            ]);
            $client_secret = $intent->client_secret;
            $api_version = '2022-11-15';

            $customer = $request->user()->createOrGetStripeCustomer();

            $key = EphemeralKey::create(
                ['customer' => $customer->id],
                ['stripe_version' => $api_version]
            );

            return $this->success(message: 'success',data: [
                'ephemeral_Key' => $key->secret,
                'customer_id' => $customer->id,
                'payment_intent_key' => $client_secret,
            ]);


        } catch (\Exception $e) {

            return $this->error($e->getMessage(), $e->getCode());
        }

    }

    //change user promo code status to used

    /**
     * @throws ApiErrorException
     */
    public function successPayment(Request $request)
    {
        $validator = Validator::make(
            $request->all(), [
            'subscription_type' => ['required', Rule::in(['yearly', 'life_time'])],
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('status_code' => 201, 'message' => $error_messages);
            return response()->json($response_array, 403);
        } else {
            $user = $request->user();
//            $user->is_premium = $request->is_premium;
            $user->is_premium = '1';
            $user->save();

            $payment_history = new PaymentHistory();
            $payment_history->user_id = auth()->user()->id;
            $payment_history->amount = $request->amount;
            $payment_history->subscription_type = $request->subscription_type;
            $payment_history->save();

            $user_premium = auth()->user()['is_premium'] == '1' ? 'user premium' : 'user not premium';
            return response()->json([
                'message' => 'Payment Success ' .$user_premium,
            ]);
        }
    }

}
