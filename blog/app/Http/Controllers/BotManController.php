<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use App\FaqsModel;
use App\DebtorsDeepanrajNew as Debtor;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Http\Request;

class BotManController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        $botman->hears('{message}', function ($botman, $message) {
            $message = strtolower($message);

            if ($message == 'hi') {
                $this->askName($botman);
            } elseif ($message == 'faq') {
                $this->listFAQ($botman);
            } elseif ($message == 'payments') {
                $this->getDebtorInfo($botman);
            } else {
                $botman->reply("Write <b>'Hi'</b>, <b>'FAQ</b>' or <b>'Payments'</b> to start a conversation");
            }
        });

        $botman->listen();
    }


    public function askName($botman)
    {
        $botman->ask('Hello! What is your Name?', function (Answer $answer) {

            $name = $answer->getText();

            $this->say('Nice to meet you ' . $name);
            $this->say("Please type <b>'FAQ'</b> to get answer for general question <br> Please type <b>'Payments'</b> to make payments.");
        });
    }


    public function listFAQ($botman)
    {
            $faqs = FaqsModel::all();
            $botman->startConversation(new FaqConversation($faqs));
    }

    public function getDebtorInfo($botman)
    {
        $botman->startConversation(new DebtorInfoConversation());
    }
}
