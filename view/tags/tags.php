<?php

namespace Anax\View;

?>
<h1>Alla taggar</h1>
<div class="tags">
<?php foreach ($allTags as $Tag) : ?>
    <tr>
        <td>
            <a class="pageTag" href="<?= url("tags/view/{$Tag}"); ?>"> <?= $Tag ?></a>
        </td>
    </tr>
<?php endforeach; ?>
</div>
