<?php

namespace Anax\View;

?>
<h1>Allt inom: <?= $tag ?></h1>
<?php if (!$questions) : ?>
    <p>There are no questions.</p>
    <?php
    return;
endif;
?>

<table>
    <tr>
        <th>Fråga</th>
        <th>Användare</th>
        <th>Text</th>
    </tr>
    <?php foreach ($questions as $question) : ?>
    <tr>
        <td>
            <a href="<?= url("question/view/{$question[0]->id}"); ?>"> <?= $question[0]->title ?></a>
        </td>
        <td><?= $question[0]->user ?></td>
        <td><?= $question[0]->text ?></td>
    </tr>
    <?php endforeach; ?>
</table>
