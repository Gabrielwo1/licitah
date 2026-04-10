<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__."/../../../../admin/config.php";
include "css.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap 5 Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container">
<?
$css = new generateCss();

$families = $css->renderRequiredFonts(SETUP['config']['tipografia']);
foreach(SETUP['config']['tipografia'] as $typo){
    $fonts = $css->renderTypography($typo['selector'],$typo);
}

echo '<style>'.$families.$fonts.'</style><h1>hello world</h1>';

?>
</div>

</body>
</html>