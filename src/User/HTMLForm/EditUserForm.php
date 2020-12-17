<?php

namespace ligm\User\HTMLForm;

use ligm\User\User;
use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;

/**
 * Example of FormModel implementation.
 */
class EditUserForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $userInfo)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Redigera användare",
            ],
            [
                "id" => [
                    "type"        => "hidden",
                    "value"       => $userInfo->id,
                ],
                "acronym" => [
                    "type"        => "text",
                    "value"       => $userInfo->acronym
                ],
                "email" => [
                    "type"        => "email",
                    "value"       => $userInfo->email
                ],

                "new-password" => [
                    "type"        => "password",
                    "placeholder" => "Valfritt",
                ],

                "password-again" => [
                    "type"        => "password",
                    "validation" => [
                        "match" => "new-password"
                    ],
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Spara",
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
        $id            = $this->form->value("id");
        $acronym       = $this->form->value("acronym");
        $email         = $this->form->value("email");
        $newPsw        = $this->form->value("new-password");
        $againPsw      = $this->form->value("password-again");
        $updatedDate   = date("Y/m/d G:i:s", time());

        // Check password matches
        if ($newPsw !== $againPsw) {
            $this->form->rememberValues();
            $this->form->addOutput("Det nya lösenordet matchar ej.");
            return false;
        }

        // Save to database
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("id", $id);
        if ($newPsw) {
            // Check password matches
            if ($newPsw !== $againPsw) {
                $this->form->rememberValues();
                $this->form->addOutput("Det nya lösenordet matchar ej.");
                return false;
            } else {
                $user->setPassword($newPsw);
            }
        }
        $user->acronym = $acronym;
        $user->email = $email;
        $user->updated = $updatedDate;
        $user->save();
        $this->form->addOutput("Din information har uppdaterats.");

        return true;
    }
}
