<?php
/**
 * Supply the basis for the navbar as an array.
 */
return [
    // Use for styling the menu
    "wrapper" => null,
    "class" => "my-navbar rm-default rm-desktop",

    // Here comes the menu items
    "items" => [
        [
            "text" => "Hem",
            "url" => "",
            "title" => "Första sidan, börja här.",
        ],
        [
            "text" => "Frågor",
            "url" => "question",
            "title" => "Visa frågor.",
        ],
        [
            "text" => "Taggar",
            "url" => "tags",
            "title" => "Visa taggar.",
        ],
        [
            "text" => "Om",
            "url" => "about",
            "title" => "Allmän information om webbplatsen.",
        ],
        [
            "text" => "Profil",
            "url" => "user",
            "title" => "Användarens profil.",
        ],
    ],
];
