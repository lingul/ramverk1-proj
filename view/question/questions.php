<?php

namespace Anax\View;

?>

<div class="showQuestion">
    <div class="userQuestTitle">
        <div class="userQuestTitleLink">
            <a href="<?= url("question/view/{$question->id}"); ?>"> <?= $question->title ?></a>
        </div>
        <div class="userQuestTitleUser">
            Skapad av:
            <a href="<?= url("user/profile/{$userInfo->id}"); ?>"> <?= $userInfo->acronym ?></a>
        </div>
    </div>
    <div class="questPreview">
        <div class="questVotes">
            <p><b>Betyg</b></p>
            <p><b><?=$question->votes?></b></p>
        </div>
        <div class="questText">
            <p><?= $parsedText ?></p>
        </div>
        <div class="questAnswers">
            <p><b>Svar</b></p>
            <p><b><?=$answerSum[0]->num?></b></p>
        </div>
    </div>
</div>
