<?php
/**
 * Mount the controller onto a mountpoint.
 */
return [
    "routes" => [
        [
            "info" => "Question controller.",
            "mount" => "question",
            "handler" => "\ligm\Question\QuestionController",
        ],
    ]
];
