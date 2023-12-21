<?php

namespace App\Http\Controllers;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\DebtorsDeepanrajNew as Debtor;


class DebtorInfoConversation extends Conversation
{
    protected $shouldEndConversation;

    public function __construct()
    {
        $this->shouldEndConversation = false;
    }

    public function run()
    {
        $this->askICNumber();
    }

    public function askICNumber()
    {
        $this->ask('Please provide your Malaysian Identity Card No:', function (Answer $answer) {
            $icNumber = $answer->getText();

            $debtor = Debtor::where('ic_no', $icNumber)->first();

            if ($debtor) {
                $info = "Debtor Information:<br>";
                $info .= "Name: {$debtor->name}<br>";
                $info .= "Contact Number: {$debtor->contact_number}<br>";
                $info .= "Email: {$debtor->email}<br>";
                $info .= "Financial Institution: {$debtor->financial_institution}<br>";
                $info .= "Loan Type: {$debtor->loan_type}<br>";
                $info .= "Loan Account Number: {$debtor->loan_account_number}<br>";
                $info .= "Loan Amount: {$debtor->loan_amount}<br>";
                $info .= "Amount due: {$debtor->amount_due}<br>";
                $info .= "Payment: {$debtor->payment}";

                $this->say($info);

                if ($debtor->payment === 'pending') {
                    $this->askForPayment($debtor->id);
                } else {
                    $this->say('No Pending Payment, Thank You');
                    $this->shouldEndConversation = true;
                }
            } else {
                $this->say('Debtor Details not found. Please check your Malaysian Identity Card No and try again.');
                $this->askICNumber();
            }
        });
    }

    public function askForPayment($debtorId)
    {
        $question = Question::create('Your payment is pending, Would you like to proceed with the payment?')
            ->addButtons([
                Button::create('Yes')->value('yes'),
                Button::create('No')->value('no'),
            ]);

        $this->ask($question, function (Answer $answer) use ($debtorId) {
            $response = strtolower($answer->getValue());

            if ($response === 'yes') {
                $paymentLink = "<a href='http://127.0.0.1:8000/api/fakepage/{$debtorId}' target='_blank'>Click here to proceed with the payment</a>";
                $this->say("Please click the following link for the payment:<br>{$paymentLink}");
            } elseif ($response === 'no') {
                $this->say("Okay. If you have more questions, feel free to ask.");
            }
        });

        $this->shouldEndConversation = true;
    }


    public function stopsConversation(IncomingMessage $message)
    {
        return $this->shouldEndConversation;
    }
}
