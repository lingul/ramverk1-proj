<?php

namespace ligm19\User\HTMLForm;

use ligm19\User\User;
use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;

/**
 * Example of FormModel implementation.
 */
class CreateUserForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Skapa användare",
            ],
            [
                "acronym" => [
                    "type"        => "text",
                ],

                "password" => [
                    "type"        => "password",
                ],

                "password-again" => [
                    "type"        => "password",
                    "validation" => [
                        "match" => "password"
                    ],
                ],

                "email" => [
                    "type"        => "email",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Skapa användare",
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
        $acronym       = $this->form->value("acronym");
        $password      = $this->form->value("password");
        $passwordAgain = $this->form->value("password-again");
        $email         = $this->form->value("email");
        $createdDate   = date("Y/m/d G:i:s", time());

        // Check password matches
        if ($password !== $passwordAgain) {
            $this->form->rememberValues();
            $this->form->addOutput("Password did not match.");
            return false;
        }

        // Save to database
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $disUser = $user->findWhere("acronym = ?", $acronym);
        if ($disUser->id != null) {
            $this->form->addOutput("Acronym är redan tagen.");
            return false;
        };
        $user->created = $createdDate;
        $user->acronym = $acronym;
        $user->score = 0;
        $user->setPassword($password);
        $user->email = $email;
        $user->save();

        $this->form->addOutput("Användare blev skapad.");
        return true;
    }
}
