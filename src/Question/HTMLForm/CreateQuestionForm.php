<?php

namespace Pon\Question\HTMLForm;

use Pon\Question\Question;
use Pon\User\User;
use Pon\Tags\TagQuestion;
use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;

/**
 * Example of FormModel implementation.
 */
class CreateQuestionForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $user = $this->di->session->get('loggedIn');
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Skapa fråga",
            ],
            [
                "title" => [
                    "type"        => "text",
                ],

                "text" => [
                    "type"        => "textarea",
                ],

                "user" => [
                    "type"        => "hidden",
                    "value"       => $user,
                ],

                "tags" => [
                    "type"        => "textarea",
                    "placeholder"        => "php, python, c++",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Skapa fråga",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        // Get values from the submitted form
        $title       = $this->form->value("title");
        $text        = $this->form->value("text");
        $userId      = $this->form->value("user");
        $tags        = $this->form->value("tags");
        $createdDate = date("Y/m/d G:i:s", time());

        // $currUser = new User();
        // $currUser->setDb($this->di->get("dbqb"));
        // $userInfo = $currUser->find('id', $userId);

        // Save to database
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->title = $title;
        $question->created = $createdDate;
        $question->text = $text;
        $question->votes = 0;
        $question->userId = $userId;
        $question->save();

        $questionId = $question->id;

        $TagQuestion = new TagQuestion();
        $TagQuestion->setDb($this->di->get("dbqb"));
        $arrTags = explode(",", $tags);

        foreach ($arrTags as $tag) {
            $TagQuestion = new TagQuestion();
            $TagQuestion->setDb($this->di->get("dbqb"));
            $TagQuestion->questionid = $questionId;
            $TagQuestion->text = str_replace(' ', '', strtolower($tag));
            $TagQuestion->save();
        }

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("id", $userId);
        $user->score = $user->score + 1;
        $user->save();
        $this->form->addOutput("Frågan har skapats.");
        return true;
    }
}
