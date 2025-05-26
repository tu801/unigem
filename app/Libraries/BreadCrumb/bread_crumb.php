<ul class="breadcrumbs d-flex align-items-center justify-content-center">
    <?php
    $count = count($breadcrumbs);
    $output = '';
    foreach ($breadcrumbs as $index => $item) {
        if ($index < $count - 1) {
            // Render link cho tất cả các item trừ item cuối cùng
            $output .= '<li>';
            $output .= '<a href="' . esc($item['href']) . '">' . esc($item['title']) . '</a>';
            $output .= '</li>';
            $output .= '<li>';
            $output .= '<i class="icon-arrow-right"></i>';
            $output .= '</li>';
        } else {
            // Render text cho item cuối cùng (không có link)
            $output .= '<li>';
            $output .= esc($item['title']);
            $output .= '</li>';
        }
    }
    echo $output;
    ?>
</ul>