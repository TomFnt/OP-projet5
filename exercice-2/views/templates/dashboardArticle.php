
<?php

foreach($articles as $article): ?>
<p>id de l'article : <?=$article['id']?></p>
<p>Titre de l'article : <?=$article['title']?></p>
<p> Nombre de commentaire : <?=$article['nbComments']?></p>
<p>Nombre de vues sur la page : <?=$article['nbViews'] ?></p>
<p>Date de création de l'article : <?=Utils::convertDateToFrenchFormat($article['date_add'])?></p>
<br/>
<?php endforeach?>