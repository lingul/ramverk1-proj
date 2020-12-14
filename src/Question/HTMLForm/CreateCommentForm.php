<?php

namespace ligm19\Question\HTMLForm;

use ligm19\Question\Question;
use ligm19\Question\Comment;
use ligm19\Tags\TagQuestion;
use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use ligm19\User\User;

/**
 * Example of FormModel implementation.
 */
class CreateCommentForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $questionid, $answerid)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
            ],
            [
                "Kommentera" => [
                    "type"        => "textarea",
                    "placeholder" => "Har du en kommentar?",
                ],

                "questionid" => [
                    "type"        => "hidden",
                    "value"       => $questionid,
                ],

                "answerid" => [
                    "type"        => "hidden",
                    "value"       => $answerid,
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Skicka",
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
        // $user        = $this->di->session->get('user');
        // $text        = $this->form->value("Kommentera");
        // $questionId  = $this->form->value("questionid");
        // $answerId    = $this->form->value("answerid") ?? null;
        // $createdDate = date("Y/m/d G:i:s", time());
        //
        // $userD = new User();
        // $userD->setDb($this->di->get("dbqb"));
        // $userInfo = $userD->find('acronym', $user);
        // $email = $userInfo->email;
        //
        // $comment = new Comment();
        // $comment->setDb($this->di->get("dbqb"));
        // $comment->user = $user;
        // $comment->created = $createdDate;
        // $comment->text = $text;
        // $comment->email = $email;
        // $comment->questionid = $questionId;
        // $comment->answerid = $answerId;
        //
        // $comment->save();

        return true;
    }
}
