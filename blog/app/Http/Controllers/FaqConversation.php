<?php

namespace App\Http\Controllers;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\FaqsModel;



class FaqConversation extends Conversation
{
    protected $faqs;
    protected $shouldEndConversation;

    public function __construct($faqs)
    {
        $this->faqs = $faqs;
        $this->shouldEndConversation = false;
    }

    public function run()
    {
        $this->askQuestion();
    }

    public function askQuestion()
    {
        $buttons = [];

        foreach ($this->faqs as $faq) {
            $buttons[] = Button::create($faq->title)->value($faq->id);
        }

        $question = Question::create('Here are some FAQs:')
            ->addButtons($buttons);

        $this->ask($question, function (Answer $answer) {
            $selectedId = $answer->getValue();
            $selectedFAQ = FaqsModel::find($selectedId);

            if ($selectedFAQ) {
                $this->say("Title: {$selectedFAQ->title}<br><br>Description: {$selectedFAQ->description}");
                $this->askIfContinue();
            } else {
                $this->say('Invalid selection. Please choose a valid FAQ.');
                $this->askQuestion();
            }
        });
    }

    public function askIfContinue()
    {
        $this->ask('Do you want to continue with FAQs? Type <b>No</b> to exit', function (Answer $answer) {
            $response = strtolower($answer->getText());

            if ($response === 'no') {
                $this->say("Thanks, Have a Nice Day");
                $this->say("Please type <b>'FAQ'</b> to get answer for general question <br> Please type <b>'Payments'</b> to make payments.");
                $this->shouldEndConversation = true;
            }

            if (!$this->shouldEndConversation) {
                $this->askQuestion();
            }
        });
    }

    public function stopsConversation(IncomingMessage $message)
    {
        return $this->shouldEndConversation;
    }
}
