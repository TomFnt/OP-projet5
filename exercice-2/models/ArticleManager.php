<?php

/**
 * Classe qui gère les articles.
 */
class ArticleManager extends AbstractEntityManager
{
    /**
     * Récupère tous les articles.
     * @param array $info
     * @return Article[]
     */

    public function getAllArticles( array $info=[]) : array
    {

        //default request for admin view
        $sql = "SELECT * FROM article";

        //define specified querry part in case if page and column ordering are specified in url. Querry use for dashboard Article view
        if(isset($info['actual_page']) && isset($info['limiter'])) {
            $actualPage = $info['actual_page'];
            $limiter = $info['limiter'];

            if ($actualPage == 1) {
                $firstArticle = 0;
            } else {
                $firstArticle = ($actualPage - 1) * $limiter;
            }

            if (isset($info['column']) && isset($info['order'])) {
                $column = $info['column'];
                $order = $info['order'];
                $filter = " ORDER BY $column $order ";
            }
            else{
                $filter="";
            }

            if (isset($info['actual_page'])) {
                $page = " LIMIT $firstArticle, $limiter ";
            }

            $sql ="SELECT a.*, COALESCE(c.nb_comments, 0) AS nb_comments
                   FROM article a
                   LEFT JOIN (
                    SELECT id_article, COUNT(*) AS nb_comments
                    FROM comment
                    GROUP BY id_article
                    ) c ON a.id = c.id_article $filter $page";

        }
        var_dump($sql);

        $result = $this->db->query($sql);
        $articles = [];

        while ($article = $result->fetch()) {
            $articles[] = new Article($article);
        }
        return $articles;
    }

    /**
     * Récupère un article par son id.
     * @param int $id : l'id de l'article.
     * @return Article|null : un objet Article ou null si l'article n'existe pas.
     */
    public function getArticleById(int $id) : ?Article
    {
        $sql = "SELECT * FROM article WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $article = $result->fetch();
        if ($article) {
            return new Article($article);
        }
        return null;
    }

    /**
     * Ajoute ou modifie un article.
     * On sait si l'article est un nouvel article car son id sera -1.
     * @param Article $article : l'article à ajouter ou modifier.
     * @return void
     */
    public function addOrUpdateArticle(Article $article) : void
    {
        if ($article->getId() == -1) {
            $this->addArticle($article);
        } else {
            $this->updateArticle($article);
        }
    }

    /**
     * Ajoute un article.
     * @param Article $article : l'article à ajouter.
     * @return void
     */
    public function addArticle(Article $article) : void
    {
        $sql = "INSERT INTO article (id_user, title, content, date_creation) VALUES (:id_user, :title, :content, NOW())";
        $this->db->query($sql, [
            'id_user' => $article->getIdUser(),
            'title' => $article->getTitle(),
            'content' => $article->getContent()
        ]);
    }

    /**
     * Modifie un article.
     * @param Article $article : l'article à modifier.
     * @return void
     */
    public function updateArticle(Article $article) : void
    {
        $sql = "UPDATE article SET title = :title, content = :content, date_update = NOW() WHERE id = :id";
        $this->db->query($sql, [
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'id' => $article->getId()
        ]);
    }

    /**
     * Supprime un article.
     * @param int $id : l'id de l'article à supprimer.
     * @return void
     */
    public function deleteArticle(int $id) : void
    {
        $sql = "DELETE FROM article WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }

    /**
     * compter nombre de vues d'un article
     * @param int $id
     * @param int $nbView
     * @return void
     */
    function incrementNbViews($id) {
        $sql ="UPDATE article SET nb_views = nb_views + 1 WHERE id= :id";
        $this->db->query($sql, [
            'id' => $id
        ]);
    }

    /**
     *
     * Compte le nombre total d'article créer
     * @return int $nbPages
     */
    public function countAllArticles()
    {
        $sql = "SELECT COUNT(*) AS nb_page FROM article;";
        $result = $this->db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $nbAticle= $row["nb_page"];

        return $nbAticle;
    }

    /**
     * Compte le nombre de page à afficher dans la pagination du tableau de la page dashboard
     * @param  int $actualPage
     * @return array $info
     */
    public function countTablePage($actualPage)
    {
        //parameter from number of article to display in page
        $nbArticleInPage=5;
        $listArticle = $this->countAllArticles();

        $nbPage = ceil($listArticle/ $nbArticleInPage);

        $info=[];
        $info['nb_pages'] = intval($nbPage);
        $info['limiter'] = $nbArticleInPage;
        $info['actual_page']=$actualPage;

        return $info;
    }

}