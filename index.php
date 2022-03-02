<?php
session_start();

$mots = file("mots.txt");
$alphabet = range('A', 'Z');

if (isset($_GET['newgame'])) 
{
    session_destroy();
    header('Location: index.php');
}

if (isset($_SESSION['mot'])) 
{
    $motATrouver = $_SESSION['mot'];

    if (!empty($_POST)) 
    {
        $lettreSelectionner = current($_POST);
        $lettretrouve = false;

        foreach (str_split($motATrouver) as $index => $lettre)
        {
            if (strtoupper($lettre) === $lettreSelectionner) {
                $lettretrouve = true;
                $_SESSION['motAfficher'][$index] = $lettre;
            }
        }

        $_SESSION['lettres'][$lettreSelectionner] = $lettretrouve;

        if ($motATrouver === $_SESSION['motAfficher']) 
        {
            $_SESSION['partieTerminer'] = true;

            $_SESSION['victoire'] = "Félicitation vous avez trouvé le mot !";
        }

        else 
        {
            if (!$lettretrouve) 
            {
                $_SESSION['nbErreur']++;

                if ($_SESSION['nbErreur'] === 8) 
                {
                    $_SESSION['partieTerminer'] = true;
                }
            }
        }
    }
} 
else 
{
    $rand_keys = array_rand($mots);
    $motATrouver = trim($mots[$rand_keys]);
    $_SESSION['mot'] = $motATrouver;
    $_SESSION['motAfficher'] = str_repeat('_', strlen($motATrouver));
    $_SESSION['nbErreur'] = 0;
    $_SESSION['partieTerminer'] = false;
}

$partieTerminer = $_SESSION['partieTerminer'];
$motAfficher = $_SESSION['motAfficher'];



ob_start();
?>

<?php if ($_SESSION['nbErreur'] > 0) : ?>

    <div class="div-img">
        <img src="images/pendu_<?= $_SESSION['nbErreur'] ?>.png" />

        <?php if ($_SESSION['nbErreur'] == 8) : ?>

        <?= "Vous avez PERDU! " ?>

<?php endif; ?>
    </div>

<?php endif; ?>


<div class="div-p">
    <p><?= $motAfficher ?></p>
</div>

<div class="div-p">
    <?php if(isset($_SESSION['victoire']))
    {
        echo ($_SESSION['victoire']); 
    } 
    ?>
</div>

<div class="div-p">
<?php 

if(isset($_POST['lettre']))
{
    $lettreJoué = $_POST['lettre'];
    $_SESSION['lettreJoué'][] = $lettreJoué;
    
    echo "Lettre déjà joué :";

        for($i= 0;isset($_SESSION['lettreJoué'][$i]); $i++)
        {
    
                echo " " . strtoupper($_SESSION['lettreJoué'][$i]) ;
        }
}
?>
</div>

<form method="POST">
    <?php foreach ($alphabet as $lettre) : ?>
        <input class="lettres" name="lettre" value="<?= $lettre ?>" type="submit" >
    <?php endforeach; ?>
</form>

<div class="new">
    <a href="?newgame=true">New game</a>
</div>

<?php
$content = ob_get_clean();
require_once 'template.php';
?>