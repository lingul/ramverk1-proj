<?php

namespace Anax\View;

?>
<div class="comment">
    <div class ="aUser">
        <p class="qAcro"><strong><a href="<?= url("user/profile/{$userInfo->id}"); ?>"> <?= $userInfo->acronym ?></a></strong></p>
        <img class="aUserImg" src="<?= "https://www.gravatar.com/avatar/" . md5(strtolower(trim($userInfo->email))) . "?d=" . urlencode("https://www.gravatar.com/avatar") . "&s=" . 150; ?>" alt="" />
        <p class="qCreated"><?= $comment->created ?></p>
    </div>

    <div class="voting">
        <div>
            <form action=<?=url("question/vote")?> method="get">
                <input hidden name="votedId" value="<?=$comment->id?>">
                <input hidden name="votedType" value="comment">
                <input hidden name="questId" value="<?=$comment->questionid?>">
                <button type="submit" name="vote" value="up" <?=$canVote?>>
                    <i class="fas fa-chevron-up"></i>
                </button>
            </form>
        </div>
        <div>
            <p class="h4"><b><?=$comment->votes?></b>
        </div>
        <div>
            <form action=<?=url("question/vote")?> method="get">
                <input hidden name="votedId" value="<?=$comment->id?>">
                <input hidden name="votedType" value="comment">
                <input hidden name="questId" value="<?=$comment->questionid?>">
                <button type="submit" name="vote" value="down" <?=$canVote?>>
                    <i class="fas fa-chevron-down"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="cText"><?= $parsedText ?></div>
</div>
