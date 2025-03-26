<div class="container">
    <div class="breadcrumbs">
        <?php //dd($breadcrumbs);
        for ($i = 0; $i <= $total-1; $i++) {
            if ( $i == 0 ) {
                echo '<a href="'.$breadcrumbs[$i]['href'].'"><i class="las la-home"></i></a>';
            } elseif ( $i == $total - 1 ) {
                echo '<a href="'.$breadcrumbs[$i]['href'].'" class="active">'.$breadcrumbs[$i]['title'].'</a>';
            } else {
                echo '<a href="'.$breadcrumbs[$i]['href'].'" >'.$breadcrumbs[$i]['title'].'</a>';
            }
        }
        ?>
    </div>
</div>