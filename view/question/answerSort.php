<?php

namespace Anax\View;

?>
<hr>
<div class="sortBtns">
    <p>Sortera med:
        <a class="commentBtn" href="<?= url("question/view/{$question->id}?sort=votes"); ?>">Röster</a>
        <a class="commentBtn" href="<?= url("question/view/{$question->id}?sort=created"); ?>">Nyaste</a>
    </p>
</div>
