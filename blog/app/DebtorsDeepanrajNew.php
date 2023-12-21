<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DebtorsDeepanrajNew extends Model
{
    protected $table = 'debtors_deepanraj_new';

    protected $fillable = [
        'ic_no',
        'name',
        'contact_number',
        'email',
        'financial_institution',
        'loan_type',
        'loan_account_number',
        'loan_amount',
        'amount_due',
        'payment',
    ];
}
