<?php $i=1;?>

    <h2>Edition des articles</h2>
    <table>
        <thead>
        <tr>
            <th>Titre de l'article</th>
            <th>Nombre de commentaires</th>
            <th>Nombre de vues</th>
            <th>Date de création de l'article</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($articles as $article) {
            if($i % 2 == 0){?>
                <tr class="pair-row">
                    <td> <?=$article['title']?></td>
                    <td> <?=$article['nbComments']?></td>
                    <td><?=$article['nbViews'] ?></td>
                    <td> <?=Utils::convertDateToFrenchFormat($article['date_add'])?></td>
                </tr>
            <?php
            }
            else {?>
                <tr class="impair-row">
                    <td> <?=$article['title']?></td>
                    <td> <?=$article['nbComments']?></td>
                    <td><?=$article['nbViews'] ?></td>
                    <td> <?=Utils::convertDateToFrenchFormat($article['date_add'])?></td>
                </tr>
        <?php
            }
            $i++;
        } ?>
        </tbody>
</table>


