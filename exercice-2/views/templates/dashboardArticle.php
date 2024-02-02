<h2>Edition des articles</h2>
    <table class="dashboard">
        <thead>
        <tr>
            <?php
            foreach($rows as $i => $row){ ?>
                <?php if($row['column']==$info['column'] && $info['order']=='ASC') {?>
                    <th>
                        <a href="<?=$info['action_block'].$info['page_block'].'&column='.$info['column']?>">
                            <?= $row['label']?>
                            <span class="arrow-down"></span>
                        </a>
                    </th>
                <?php }
                 elseif($row['column']==$info['column'] && $info['order']=='DESC') {?>
                    <th>
                        <a href="<?=$info['action_block'].$info['page_block'].'&column='.$info['column'].'&order=ASC'?>">
                        <?= $row['label']?>
                            <span class="arrow-up"></span>
                        </a>
                    </th>
                <?php }
                else{ ?>
                    <th>
                        <a href="<?=$info['action_block'].$info['page_block'].'&column='.$row['column'].'&order=DESC'?>">
                            <?= $row['label']?>
                        </a>
                    </th>
                <?php }?>
            <?php }?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($articles as $i => $article) {?>
            <tr>
                <td> <?=$article['title']?></td>
                <td> <?=$article['nbComments']?></td>
                <td><?=$article['nbViews'] ?></td>
                <td> <?=Utils::convertDateToFrenchFormat($article['date_add'])?></td>
            </tr>
        <?php }?>
        </tbody>
</table>

<div class="pagination">
    <?php for($page = 1; $page <= $info['nb_pages']; $page++): ?>
    <a href="<?= $info['action_block']."&page=$page".$info['filter_block']?>" class="<?=($page==$info['actual_page'])? 'current-page': ''?>">
        <?=$page?>
    </a>
    <?php endfor ?>
</div>


