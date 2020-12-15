<?php

namespace Anax\View;

?>

<div class="userQuestion">
    <div class="userQuestTitle">
        <a class="userQuestTitle" href="<?= url("question/view/{$question->id}"); ?>"> <?= $question->title ?></a>
    </div>
    <div class="userQuestText">
        <p><?= $parsedText ?></p>
    </div>
</div>
