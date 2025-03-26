<?php
/**
 * Author: tmtuan
 * Created date: 9/1/2023
 **/

$img = (isset($row->images['thumbnail']) && $row->images['thumbnail'] !== null) ? $row->images['thumbnail'] : base_url($config->noimg);
?>
<tr>
    <td><?=$num?></td>
    <td>
        <a href="<?= route_to("edit_post", $row->id) ?>"><?= $row->title ?></a>
    </td>
    <td><?= $row->created_at->format('d/m/Y') ?></td>
</tr>
