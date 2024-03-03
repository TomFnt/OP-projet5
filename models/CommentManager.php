<?php

/**
 * Cette classe sert à gérer les commentaires.
 */
class CommentManager extends AbstractEntityManager
{
    /**
     * Récupère tous les commentaires d'un article.
     * @param int $idArticle : l'id de l'article.
     * @param array $info
     * @return array : un tableau d'objets Comment.
     */
    public function getAllCommentsByArticleId(int $idArticle, array $info = []): array
    {
        $page = "";
        $filter = "";

        //define specified querry part in case if page and column ordering are specified in url. Querry use for dashboard Article view
        if (isset($info['actual_page']) && isset($info['limiter'])) {
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
            if (isset($info['actual_page'])) {
                $page = " LIMIT $firstArticle, $limiter ";
            }
        }
        $sql = "SELECT * FROM comment WHERE id_article = $idArticle $filter $page";
        $result = $this->db->query($sql);
        $comments = [];
        while ($comment = $result->fetch()) {
            $comments[] = new Comment($comment);
        }
        return $comments;
    }

    /**
     * Récupère un commentaire par son id.
     * @param int $id : l'id du commentaire.
     * @return Comment|null : un objet Comment ou null si le commentaire n'existe pas.
     */
    public function getCommentById(int $id): ?Comment
    {
        $sql = "SELECT * FROM comment WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $comment = $result->fetch();
        if ($comment) {
            return new Comment($comment);
        }
        return null;
    }

    /**
     * Ajoute un commentaire.
     * @param Comment $comment : l'objet Comment à ajouter.
     * @return bool : true si l'ajout a réussi, false sinon.
     */
    public function addComment(Comment $comment): bool
    {
        $sql = "INSERT INTO comment (pseudo, content, id_article, date_creation) VALUES (:pseudo, :content, :idArticle, NOW())";
        $result = $this->db->query($sql, [
            'pseudo' => $comment->getPseudo(),
            'content' => $comment->getContent(),
            'idArticle' => $comment->getIdArticle()
        ]);
        return $result->rowCount() > 0;
    }

    /**
     * Supprime un commentaire.
     * @param Comment $comment : l'objet Comment à supprimer.
     * @return bool : true si la suppression a réussi, false sinon.
     */
    public function deleteComment(Comment $comment): bool
    {
        $sql = "DELETE FROM comment WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $comment->getId()]);
        return $result->rowCount() > 0;
    }

    /**
     * Compte le nombre de commentaire d'un article.
     * @param
     * @return array $nbComments
     */
    public function countComments(): array
    {
        $sql = "SELECT id_article, COUNT(*) AS nb_comments FROM comment GROUP BY id_article;";
        $nbComments = $this->db->query($sql);


        return $nbComments->fetchAll();
    }

    /**
     * Compte le nombre de page à afficher dans la pagination du tableau de la page dashboard Comment
     * @param int $actualPage
     * @param int $idArticle
     * @return array $info
     */
    public function countPageComment(int $actualPage, int $idArticle): array
    {
        //parameter from number of article to display in page
        $nbCommentInPage = 5;
        $countList = $this->countComments();
        $countComment = 0;

        foreach ($countList as $comment) {
            if ($comment['id_article'] == $idArticle) {
                $countComment = $comment['nb_comments'];
            }
        }

        $nbPage = ceil($countComment / $nbCommentInPage);

        $info = [];
        $info['nb_pages'] = intval($nbPage);
        $info['limiter'] = $nbCommentInPage;
        $info['actual_page'] = $actualPage;

        return $info;
    }

}
