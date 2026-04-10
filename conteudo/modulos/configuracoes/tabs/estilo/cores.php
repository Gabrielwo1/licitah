<div class="row">
<?


echo '<div class="col-3">';
$input = new Input("INPUT");
$input->set("label", "Primaria");
$input->set("name", "primaria");
$input->set("type", "color");
echo $input->html();
echo '</div>';

echo '<div class="col-3">';
$input = new Input("INPUT");
$input->set("label", "Secundária");
$input->set("name", "secundaria");
$input->set("type", "color");
echo $input->html();
echo '</div>';

echo '<div class="col-3">';
$input = new Input("INPUT");
$input->set("label", "Terciária");
$input->set("name", "terciária");
$input->set("type", "color");
echo $input->html();
echo '</div>';

echo '<div class="col-3">';
$input = new Input("INPUT");
$input->set("label", "Quaternaria");
$input->set("name", "quaternaria");
$input->set("type", "color");
echo $input->html();
echo '</div>';


?>
</div>