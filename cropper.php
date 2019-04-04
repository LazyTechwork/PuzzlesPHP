<?php
require_once 'operational.php';

if (!isset($_GET['image']) || !isset($_GET['rows']) || !isset($_GET['columns'])) redandexit();

$imagename = 'photos/' . $_GET['image'];
$rows = $_GET['rows'];
$cols = $_GET['columns'];

$images = [];
$imgoriginal = [];

$filename = pathinfo($imagename, PATHINFO_FILENAME);
if (!file_exists($imagename)) redandexit();
if ($rows > 40 || $cols > 40) redandexit();

$x = 0;
$y = 0;
$imgres = imagecreatefromjpeg($imagename);
$size = [
    'width' => imagesx($imgres),
    'height' => imagesy($imgres)
];
$partwidth = $size['width'] / $cols;
$partheight = $size['height'] / $rows;

if (!file_exists('photos/' . $filename)) mkdir('photos/' . $filename);
$elll = 0;
for ($i = 0; $i < $cols; $i++) {
    $imgc = imagecrop($imgres, ['x' => $i * $partwidth, 'y' => 0, 'width' => $partwidth, 'height' => $size['height']]);
    if (!file_exists('photos/' . $filename . '/' . $i)) mkdir('photos/' . $filename . '/' . $i);
    for ($ii = 0; $ii < $rows; $ii++) {
        $imgr = imagecrop($imgc, ['x' => 0, 'y' => $ii * $partheight, 'width' => imagesx($imgc), 'height' => $partheight]);
        imagejpeg($imgr, 'photos/' . $filename . '/' . $i . '/' . $ii . '.jpg');
        array_push($images, ['photo' => 'photos/' . $filename . '/' . $i . '/' . $ii . '.jpg', 'original' => $elll]);
        $elll++;
    }
}
$imgoriginal = $images;
shuffle($images);
//debug($imgoriginal);
//debug($images);
?>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cropper</title>
</head>
<body>
<img src="<?= $imagename; ?>" alt="" id="preview">
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    .page_wrapper {
        display: flex;
        flex: 0 0 auto;
    }

    .puzzles {
        width: 50%;
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
    }

    .puzzle_part {
        width: calc(100% / <?=$cols ?>);
        flex: 0 0 auto;
        border: 1px solid #fff;
    }

    .grid {
        width: 50%;
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
    }

    .grid_part {
        width: calc(100% / <?=$cols ?>);
        flex: 0 0 auto;
        border: 1px solid #000000;
    }

    .grid_part:before {
        content: '';
        display: block;
        padding-top: calc(<?=$partheight / $partwidth?> * 100%);
    }

    .grid_part_right {
        box-shadow: 0 0 5px 10px green;
    }

    .success {
        border: none;
    }

    img {
        width: 100%;
        height: auto;
        display: block;
    }

    #preview {
        width: 20%;
        height: auto;
    }

    pre {
        background: #230010;
        color: #0f0;
    }
</style>
<div class="page_wrapper">
    <div class="puzzles">
        <?php
        $elll = 0;
        for ($ii = 0; $ii < $rows; $ii++) {
            for ($i = 0; $i < $cols; $i++) {
                ?>
                <div class="puzzle_part" id="p<?= $images[$elll]['original'] ?>">
                    <img src="<?= $images[$elll]['photo']; ?>" alt="">
                </div>
                <?php
                $elll++;
            }
            echo '<br>';
        }
        ?>
    </div>
    <div class="grid">
        <?php
        for ($ii = 0; $ii < $rows; $ii++) {
            for ($i = 0; $i < $cols; $i++) {
                ?>
                <div class="grid_part" id="p<?= $i * $rows + $ii ?>">

                </div>
                <?php
            }
            echo '<br>';
        }
        ?>
    </div>
</div>
<script src="jq.js"></script>
<script src="jqui.js"></script>
<script>
    let puzzlescount;
    let grid = [];
    $(document).ready(() => {
        puzzlescount = $('.puzzle_part').length;
        $('.puzzle_part').draggable({
            scroll: false,
            containtment: 'document',
        });
        $('.grid_part').droppable({
            accept: '.puzzle_part',
            tolerance: 'intersect',
            drop: function (event, ui) {
                let drop_p = $(this).offset();
                let drag_p = ui.draggable.offset();
                let left_end = drop_p.left - drag_p.left;
                let top_end = drop_p.top - drag_p.top;
                ui.draggable.animate({top: '+=' + top_end, left: '+=' + left_end});
                let drag = $(ui.draggable);
                let drop = $(this);
                if (drop.attr('id') == drag.attr('id')) {
                    drop.addClass('success');
                }
                if ($('.success').length == puzzlescount) {
                    $('.grid').addClass('grid_part_right');
                    $('.puzzle_part').css({border: 'none'});
                } else {
                    $('.grid').removeClass('grid_part_right');
                    $('.puzzle_part').css({border: '1px solid #fff'});
                }
                console.log("DROP");
                console.log(drop.attr('id'), drag.attr('id'));
            },
            out: function (event, ui) {
                let drag = $(ui.draggable);
                let drop = $(this);
                $('.grid').removeClass('grid_part_right');
                if (drop.attr('id') == drag.attr('id')) drop.removeClass('success');
                console.log("OUT");
                console.log(drop.attr('id'), drag.attr('id'));
            }
        });
    });

    function resize() {

    }
</script>
</body>
</html>
