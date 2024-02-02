<?php

class CommentController 
{
    /**
     * Vérifie que l'utilisateur est connecté.
     * @return void
     */
    private function checkIfUserIsConnected() : void
    {
        // On vérifie que l'utilisateur est connecté.
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
    }

    /**
     * Ajoute un commentaire.
     * @return void
     */
    public function addComment() : void
    {
        // Récupération des données du formulaire.
        $pseudo = Utils::request("pseudo");
        $content = Utils::request("content");
        $idArticle = Utils::request("idArticle");

        // On vérifie que les données sont valides.
        if (empty($pseudo) || empty($content) || empty($idArticle)) {
            throw new Exception("Tous les champs sont obligatoires. 3");
        }

        // On vérifie que l'article existe.
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($idArticle);
        if (!$article) {
            throw new Exception("L'article demandé n'existe pas.");
        }

        // On crée l'objet Comment.
        $comment = new Comment([
            'pseudo' => $pseudo,
            'content' => $content,
            'idArticle' => $idArticle
        ]);

        // On ajoute le commentaire.
        $commentManager = new CommentManager();
        $result = $commentManager->addComment($comment);

        // On vérifie que l'ajout a bien fonctionné.
        if (!$result) {
            throw new Exception("Une erreur est survenue lors de l'ajout du commentaire.");
        }

        // On redirige vers la page de l'article.
        Utils::redirect("showArticle", ['id' => $idArticle]);
    }

    public  function dashboardComment()
    {
        $this->checkIfUserIsConnected();

        // On vérifie que l'article existe.
        $idArticle= Utils::request('articleId');
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($idArticle);
        if (!$article)
        {
            throw new Exception("L'article demandé n'existe pas.");
        }


        $page = intval(Utils::request("page"));
        if(!isset($page))
        {
            $page=1;
        }

        $column = Utils::request("column");
        $order=Utils::request("order");
        $delete=Utils::request("delete");
        if(!isset($column) || $column==""|| !isset($order) || $order=="")
        {
            $column=NULL;
            $order=NULL;
        }
        if(!isset($delete) || $delete=="")
        {
            $delete="hide";
        }
        //set table rows
        $rows=[];
        $rows[0]= array("column" => "pseudo", "label" => "Pseudo");
        $rows[1]= array("column" => "content", "label" => "Commentaire");
        $rows[2]= array("column" => "date_creation", "label" => "Date de création");
        $rows[3]= array("column" => "delete", "label" => "Action");

        //on récupère les infos de la page actuelle et de la pagination
        $commentManager = new CommentManager();
        $info=$commentManager->countPageComment($page, $idArticle);

        //on passe dans le tableau $info les différentes info qui compose notre l'url
        $info['column']= $column;
        $info['order']= $order;
        $info['del-success']=$delete;
        $info['articleId_block']="&articleId=$idArticle";
        $info['action_block']="?action=dashboardComment".$info['articleId_block'];
        $info['delete_block']="?action=deleteComment";
        $info['page_block']="&page=$page";
        $info['filter_block']= $column!== NULL && $order !== NULL ? "&column=$column&order=$order": " ";

        // on récupère les commentaires d'un article prècis, retourne un message d'erreur si aucun commentire
        $result = $commentManager->getAllCommentsByArticleId($idArticle, $info);
        if (!$result)
        {
            throw new Exception("Cette article n'a pas de commentaire.");
        }

        // On affiche la page de modification de l'article.
        $view = new View("Dashboard des commentaires");
        $view->render("dashboardComment", [
            'comments' => $result,
            'info' => $info,
            'rows'=> $rows
        ]);

    }

    public function deleteComment() : void
    {
        $idArticle=(int) Utils::request('articleId');
        $idComment= (int) Utils::request('commentId');

        // On vérifie que l'article existe.
        $commentManager = new CommentManager();
        $comment = $commentManager->getCommentById($idComment);
        if (!$comment)
        {
            throw new Exception("Le commentaire demandé n'existe pas.");
        }

        $commentManager=new CommentManager();
        $commentManager->deleteComment($comment);

        Utils::redirect("dashboardComment&articleId=$idArticle&page=1&delete=true");
    }

}