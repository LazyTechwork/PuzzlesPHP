<?php
require_once 'operational.php';
$dir = scandir('photos/');
?>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mozaik</title>
</head>
<body>
<form action="upload.php" enctype="multipart/form-data" method="POST">
    <img src="" alt="" id="preview"><br>
    <label>
        <span>Upload image</span>
        <input type="file" name="image" width="50%" accept="image/jpeg" id="upload" onchange="uploadImg(event)">
    </label>
    <button type="submit">Send</button>
</form>
<hr>
<h1>Files</h1>
<?php
foreach ($dir as $file) {
    if (is_dir('photos/'.$file)) continue;
    ?>
    <img src="photos/<?=$file; ?>" alt="">
    <form action="cropper.php" method="get">
        <input type="hidden" name="image" value="<?=$file; ?>">
        <input type="number" name="rows" placeholder="Количество строк">
        <input type="number" name="columns" placeholder="Количество колонок">
        <button type="submit">Crop</button>
    </form>
    <?php
}
?>
<script src="jq.js"></script>
<script>
    function uploadImg(event) {
        let file = event.target.files[0];
        let fr = new FileReader();
        fr.readAsDataURL(file);
        fr.onload = e => {
            $('img#preview').attr({src: e.target.result});
        };
    }
</script>
</body>
</html>