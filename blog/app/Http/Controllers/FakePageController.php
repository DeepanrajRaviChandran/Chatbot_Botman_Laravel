<?php

namespace App\Http\Controllers;

use App\DebtorsDeepanrajNew as Debtor;
use Illuminate\Http\Request;

class FakePageController extends Controller
{
    public function index()
    {
        return view('fakepage');
    }


    public function thanksForPayment($debtorId)
    {
        $debtor = Debtor::find($debtorId);

        $debtor->payment = 'completed';
        $debtor->save();
    }
}
