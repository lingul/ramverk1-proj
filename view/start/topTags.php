<?php

namespace Anax\View;

?>
<h3 class="startTitle">Trendiga taggar</h3>
<div class="tags">
<?php foreach ($allTags as $Tag) : ?>
    <tr>
        <td>
            <a class="pageTag" href="<?= url("tags/view/{$Tag->text}"); ?>"> <?= $Tag->text ?></a>
        </td>
    </tr>
<?php endforeach; ?>
</div>
