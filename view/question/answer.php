<?php

namespace Anax\View;

?>

<div class="answer <?php if ($answer->accepted) {
    echo 'acceptedAnswer';
                   }
                    ?>">
    <div class ="aUser">
        <p class="qAcro"><strong><a href="<?= url("user/profile/{$userInfo->id}"); ?>"> <?= $userInfo->acronym ?></a></strong></p>
        <img class="aUserImg" src="<?= "https://www.gravatar.com/avatar/" . md5(strtolower(trim($userInfo->email))) . "?d=" . urlencode("https://www.gravatar.com/avatar") . "&s=" . 150; ?>" alt="" />
        <p class="qCreated"><?= $answer->created ?></p>
    </div>

    <div class="voting">
        <div>
            <form action=<?=url("question/vote")?> method="get">
                <input hidden name="votedId" value="<?=$answer->id?>">
                <input hidden name="votedType" value="answer">
                <input hidden name="questId" value="<?=$answer->questionid?>">
                <button type="submit" name="vote" value="up" <?=$canVote?>>
                    <i class="fas fa-chevron-up"></i>
                </button>
            </form>
        </div>
        <div>
            <p class="h4"><b><?=$answer->votes?></b>
        </div>
        <div>
            <form action=<?=url("question/vote")?> method="get">
                <input hidden name="votedId" value="<?=$answer->id?>">
                <input hidden name="votedType" value="answer">
                <input hidden name="questId" value="<?=$answer->questionid?>">
                <button type="submit" name="vote" value="down" <?=$canVote?>>
                    <i class="fas fa-chevron-down"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="aText"><?= $parsedText ?></div>
    <a class="commentBtn" href="<?= url("question/comment?question={$answer->questionid}&answer={$answer->id}"); ?>">Kommentera</a>
    <div class="acceptBtn" <?php if ($loggedIn != $theQuestion->userId) {
        echo 'hidden';
                           } ?>>
        <form action=<?=url("question/accept")?> method="get">
            <input hidden name="questionId" value="<?=$answer->questionid?>">
            <input hidden name="answerId" value="<?=$answer->id?>">
            <button class="acBtn" type="submit" name="vote" value="up">
                <i class="fas fa-check"></i>
            </button>
        </form>
    </div>

</div>
