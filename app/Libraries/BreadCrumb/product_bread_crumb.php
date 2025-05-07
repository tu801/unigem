<div class="tf-breadcrumb-list">
    <?php
    $count = count($breadcrumbs);
    $output = '';
    foreach ($breadcrumbs as $index => $item) {
        if ($index < $count - 1) {
            // Render link cho tất cả các item trừ item cuối cùng
            $output .= '<a class="text" href="' . esc($item['href']) . '">' . esc($item['title']) . '</a>';
            $output .= '<i class="icon icon-arrow-right"></i>';
        } else {
            // Render text cho item cuối cùng (không có link)
            $output .= '<span class="text">';
            $output .= esc($item['title']);
            $output .= '</span>';
        }
    }
    echo $output;
    ?>
</div>