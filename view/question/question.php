<?php

namespace Anax\View;

?>

<?php if (!$question) : ?>
    <p class="errTxt">Denna fråga fins ej.</p>
    <?php
    return;
endif;
?>

<div class="question">
    <h1 class="qTitle"><?= $question->title ?></h1>
    <div class ="qUser">
        <p class="qAcro">
            <a href="<?= url("user/profile/{$userInfo->id}"); ?>"> <?= $userInfo->acronym ?></a>
        </p>
        <img class="qUserImg" src="<?= "https://www.gravatar.com/avatar/" . md5(strtolower(trim($userInfo->email))) . "?d=" . urlencode("https://www.gravatar.com/avatar") . "&s=" . 150; ?>" alt="" />
        <p class="qCreated"><?= $question->created ?></p>
    </div>

    <div class="voting">
        <div>
            <form action=<?=url("question/vote")?> method="get">
                <input hidden name="votedId" value="<?=$question->id?>">
                <input hidden name="votedType" value="question">
                <button type="submit" name="vote" value="up" <?=$canVote?>>
                    <i class="fas fa-chevron-up"></i>
                </button>
            </form>
        </div>
        <div>
            <p class="h4"><b><?=$question->votes?></b>
        </div>
        <div>
            <form action=<?=url("question/vote")?> method="get">
                <input hidden name="votedId" value="<?=$question->id?>">
                <input hidden name="votedType" value="question">
                <button type="submit" name="vote" value="down" <?=$canVote?>>
                    <i class="fas fa-chevron-down"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="qText"><?= $parsedText ?></div>
    <div class="qTags">
        <ul>
    <?php foreach ($tags as $tag) : ?>
            <li class="qTag"><a href="<?= url("tags/view/{$tag->text}"); ?>"><?= $tag->text ?></a></li>
    <?php endforeach; ?>
        </ul>
    </div>

    <a class="commentBtn" href="<?= url("question/comment?question={$question->id}"); ?>" >Kommentera</a>
</div>
