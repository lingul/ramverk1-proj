<?php

namespace Anax\View;

?>
<h3 class="startTitle">Användare med högst poäng</h3>
<div class="startUsers">
<?php foreach ($allUsers as $user) : ?>
    <div class="divUser">
        <a class="startUser" href="<?= url("user/profile/{$user->id}"); ?>">
            <img class="profileImg" src="<?= "https://www.gravatar.com/avatar/" . md5(strtolower(trim($user->email))) . "?d=" . urlencode("https://www.gravatar.com/avatar") . "&s=" . 150; ?>" alt="" />
            <h4 class="startAcro">
                <?= $user->acronym ?>
            </h4>
        </a>
    </div>
<?php endforeach; ?>
</div>
