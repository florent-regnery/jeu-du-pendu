<?php

ob_start();
?>

<form method="post">
    <input class="mot" name="mot" id="" type="text" placeholder=" Ajouter un mot" />
</form>

<div class="liste">
    <?php
    $listes = file("mots.txt");
    foreach ($listes as $liste) {
        echo $liste . "<br>";
    }
    if (isset($_POST['mot'])) {
        if (ctype_alpha($_POST['mot'])) {
            $txt = $_POST['mot'];
            $myfile = file_put_contents('mots.txt', $txt . PHP_EOL, FILE_APPEND | LOCK_EX);
            header('location: admin.php');
        } else {
            echo "le mot ne doit contenir que des lettres (A-Z)";
        }
    }
    ?>
</div>

<?php
$content = ob_get_clean();
require_once 'template.php';
?>