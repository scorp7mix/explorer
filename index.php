<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 30.04.2015
 * Time: 23:31
 */

$server_path = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));

$current_path = $_GET['path'] ? $_GET['path'] : '/';

$current_real_path = str_replace('\\', '/', realpath($server_path . $current_path));
if (!$current_real_path || ($current_real_path < $server_path)) {
    $current_real_path = $server_path . '/';
}

$current_path = str_replace($server_path, '', $current_real_path);

if (is_dir($current_real_path)) {
    $path_scan = scandir($current_real_path);
} else {
    $current_path = str_replace('\\', '/', pathinfo($current_path, PATHINFO_DIRNAME));
    $file_contents = file_get_contents($current_real_path);
    $file_mime = mime_content_type($current_real_path);
    $file_type = substr($file_mime, 0, strpos($file_mime, '/'));
    $file = pathinfo($current_real_path, PATHINFO_BASENAME);
    $current_real_path = pathinfo($current_real_path, PATHINFO_DIRNAME);
    $path_scan = scandir($current_real_path);
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="windows-1251">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
</head>
<body>
<div class="row">
    <div class="col-md-3 well" style="height: 900px; margin: 10px; margin-left: 40px;">
        <h3><code><?php echo ($current_path == '') ? '/' : $current_path; ?></code></h3><br>

        <ul class="list-unstyled" style="margin-left: 30px;">
            <?php
            foreach ($path_scan as $e) {
                $e_path = (($current_path == '/') ? '' : $current_path) . '/' . $e;
                if (is_file($current_real_path . '/' . $e)) {
                    echo "<li style=\"font-family: monospace; padding:2px 4px; font-size:90%; color:#c7254e;\"><a href=\"index.php?path=" . $e_path . "\">" . $e . "</a></li>";

                } else {
                    echo "<li><code><a href=\"index.php?path=" . $e_path . "\">" . $e . "</a></code></li>";
                }
            }
            ?>
        </ul>
    </div>
    <div class="col-md-8 col-md-offset-1 well embed-responsive" style="height: 900px; margin: 10px; margin-left: 40px;">
        <div class="container" style="height: 850px; overflow: scroll">
            <?php
            if (isset($file)) {
                echo "<h3><code>" . $file . ' [' . $file_mime . "]</code></h3><br>";
                if ($file_type == 'image') {
                    echo "<img src=" . $file . ">";
                } else {
                    echo "<pre>" . htmlspecialchars($file_contents) . "</pre>";
                }
            }
            ?>
        </div>
    </div>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</body>
</html>