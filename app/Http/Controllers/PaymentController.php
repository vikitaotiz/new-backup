<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Handle stripe redirect.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function stripeRedirect(Request $request)
    {
        try {
            // Set your secret key: remember to change this to your live secret key in production
            // See your keys here: https://dashboard.stripe.com/account/apikeys
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $response = \Stripe\OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => $request->code,
            ]);

            // Update the model
            User::where('id', auth()->user()->id)->update(['stripe_response' => json_encode($response), 'stripe_user_id' => $response->stripe_user_id]);

            return redirect('settings')->with('success', 'Payment method connected with stripe successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Handle stripe disconnect.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disconnect()
    {
        try {
            // Set your secret key: remember to change this to your live secret key in production
            // See your keys here: https://dashboard.stripe.com/account/apikeys
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            \Stripe\OAuth::deauthorize([
                'client_id' => env('STRIPE_CLIENT_ID'),
                'stripe_user_id' => auth()->user()->stripe_user_id,
            ]);

            // Update the model
            User::where('id', auth()->user()->id)->update(['stripe_response' => null, 'stripe_user_id' => null]);

            return redirect('settings')->with('success', 'Stripe disconnected successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
