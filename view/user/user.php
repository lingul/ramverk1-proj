<?php

namespace Anax\View;

?>

<div>
<?php if (!$userInfo) : ?>
    <p>There is no user info.</p>
    <?php
    return;
endif;
?>

<p><?= $userInfo->acronym ?></p>
<p><?= $userInfo->created ?></p>
<p><?= $userInfo->email ?></p>
<img class="profileImg" src="<?= "https://www.gravatar.com/avatar/" . md5(strtolower(trim($userInfo->email))) . "?d=" . urlencode("https://www.gravatar.com/avatar") . "&s=" . 150; ?>" alt="" />
</div>
<a href="<?= url("user/logout") ?>">Logga ut <i class="fas fa-sign-out-alt"></i></a>
<a href="<?= url("user/edit") ?>">Redigera</a>
