<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2>Inscription d'un nouveau membre sur MayotteSport.com</h2>
    <div>
        Voici les informations enregistrées : <br>
        <?php 
            foreach ($user as $key => $value) {
                echo "<p> $key : $value </p>";
            }
        ?>
    </div>
  </body>
</html>