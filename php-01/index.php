<?php
if(!empty($_GET["name"])) {
    $content = file_get_contents("https://api.agify.io?name={$_GET['name']}");
    $obj = json_decode($content, true);
    $age = $obj['age'];
}
    // var_dump($obj);
    //echo "Hello " . $obj["results"][0]["name"]["first"] . " " . $obj["results"][0]["name"]["last"] . "\n";
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <?php if (isset($age)): ?>
    Suggested age <?= $age ?>
    <?php endif; ?>
<form>
    <label for="name">First name</label>
    <input name="name" id="name" />
    <button>Push me</button>
</form>
</body>
</html>
