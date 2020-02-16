<?php

$page = 31;
$paging = 5; // จำนวนที่ต้องการแสดง เลขคี่

$high = floor($paging / 2);
$low = "-" . $high;

echo "Low : " . $low . "<br>";
echo "High : " . $high . "<br>";

$total_pages = 35;


if (($page + $high) > $total_pages) {
    $y = $total_pages + $low;
} elseif (($page - $high) < 1) {
    $y =  $paging;
} else {
    $y = $paging;
}

//$y = (($page + $high > $total_pages) ? $total_pages - $low : (($page - $high < 1) ? $paging : $paging));


echo "ก่อนหน้า ";
if ($page > $paging) {
    echo " ... ";
}
if ($page >= $paging-2) { // กรณีมากกว่าหน้าแรก
    $offset = 5 ;
    for ($i = $low; $i <= $high; $i++) {
        if (($page+ $high) < $total_pages ) {
            if ($page + $i == $page) {
                echo "<b>" . ($page + $i) . " </b>";
            } else {
                echo $page + $i . " ";
                //$page++;
            }
        } else { // กรณีหน้าสุดท้าย
            for ($i = 0; $i <= 5; $i++) {
            
              //  echo $offset;
               if ($page-$offset == $page) {
                    echo "<b>" . ( $page -$offset) . " </b>";
                    //echo $total_pages-$page;
                } else {
                    echo $page-$offset . " ";
                } 
                $offset--;
            }
        }
    }
} else {
    for ($i = 1; $i <= $y; $i++) {
        if ($i == $page) {
            echo "<b>" . ($page) . " </b>";
        } else {
            echo $i . " ";
        }
    }
}
if ($page < $total_pages - 5) {
    echo " ... ";
}
echo " ถัดไป";
