<?php
/**
 * Mount the controller onto a mountpoint.
 */
return [
    "routes" => [
        [
            "info" => "Comment controller.",
            "mount" => "question/view/",
            "handler" => "\ligm19\Tags\TagsController",
        ],
    ]
];
