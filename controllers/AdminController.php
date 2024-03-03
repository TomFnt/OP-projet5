<?php

/**
 * Contrôleur de la partie admin.
 */

class AdminController
{
    /**
     * Affiche la page d'administration.
     * @return void
     */
    public function showAdmin(): void
    {
        // On vérifie que l'utilisateur est connecté.
        $this->checkIfUserIsConnected();

        // On récupère les articles.
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();

        // On affiche la page d'administration.
        $view = new View("Administration");
        $view->render("admin", [
            'articles' => $articles
        ]);
    }

    /**
     * Vérifie que l'utilisateur est connecté.
     * @return void
     */
    private function checkIfUserIsConnected(): void
    {
        // On vérifie que l'utilisateur est connecté.
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
    }

    /**
     * Affichage du formulaire de connexion.
     * @return void
     */
    public function displayConnectionForm(): void
    {
        $view = new View("Connexion");
        $view->render("connectionForm");
    }

    /**
     * Connexion de l'utilisateur.
     * @return void
     */
    public function connectUser(): void
    {
        // On récupère les données du formulaire.
        $login = Utils::request("login");
        $password = Utils::request("password");

        // On vérifie que les données sont valides.
        if (empty($login) || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires. 1");
        }

        // On vérifie que l'utilisateur existe.
        $userManager = new UserManager();
        $user = $userManager->getUserByLogin($login);
        if (!$user) {
            throw new Exception("L'utilisateur demandé n'existe pas.");
        }

        // On vérifie que le mot de passe est correct.
        if (!password_verify($password, $user->getPassword())) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            throw new Exception("Le mot de passe est incorrect : $hash");
        }

        // On connecte l'utilisateur.
        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getId();

        // On redirige vers la page d'administration.

        Utils::redirect("admin");
    }

    /**
     * Déconnexion de l'utilisateur.
     * @return void
     */
    public function disconnectUser(): void
    {
        // On déconnecte l'utilisateur.
        unset($_SESSION['user']);

        // On redirige vers la page d'accueil.
        Utils::redirect("home");
    }

    /**
     * Affichage du formulaire d'ajout d'un article.
     * @return void
     */
    public function showUpdateArticleForm(): void
    {
        $this->checkIfUserIsConnected();

        // On récupère l'id de l'article s'il existe.
        $id = Utils::request("id", -1);

        // On récupère l'article associé.
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($id);

        // Si l'article n'existe pas, on en crée un vide.
        if (!$article) {
            $article = new Article();
        }

        // On affiche la page de modification de l'article.
        $view = new View("Edition d'un article");
        $view->render("updateArticleForm", [
            'article' => $article
        ]);
    }

    /**
     * Ajout et modification d'un article.
     * On sait si un article est ajouté car l'id vaut -1.
     * @return void
     */
    public function updateArticle(): void
    {
        $this->checkIfUserIsConnected();

        // On récupère les données du formulaire.
        $id = Utils::request("id", -1);
        $title = Utils::request("title");
        $content = Utils::request("content");

        // On vérifie que les données sont valides.
        if (empty($title) || empty($content)) {
            throw new Exception("Tous les champs sont obligatoires. 2");
        }

        // On crée l'objet Article.
        $article = new Article([
            'id' => $id, // Si l'id vaut -1, l'article sera ajouté. Sinon, il sera modifié.
            'title' => $title,
            'content' => $content,
            'id_user' => $_SESSION['idUser']
        ]);

        // On ajoute l'article.
        $articleManager = new ArticleManager();
        $articleManager->addOrUpdateArticle($article);

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }


    /**
     * Suppression d'un article.
     * @return void
     */
    public function deleteArticle(): void
    {
        $this->checkIfUserIsConnected();

        $id = Utils::request("id", -1);

        // On supprime l'article.
        $articleManager = new ArticleManager();
        $articleManager->deleteArticle($id);

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }

    /**
     *
     * affiche le dashboard Article.
     * @return void
     */
    public function dashboardArticle(): void
    {

        $this->checkIfUserIsConnected();
        $page = intval(Utils::request("page"));
        if(!isset($page)) {
            $page = 1;
        }

        $column = Utils::request("column");
        $order = Utils::request("order");
        if(!isset($column) || $column == "" || !isset($order) || $order == "") {
            $column = null;
            $order = null;
        }

        //set table rows
        $rows = [];
        $rows[0] = array( "column" => "title", "label" => "Titre de l'article");
        $rows[1] = array("column" => "nb_comments", "label" => "Nombre de commentaires");
        $rows[2] = array("column" => "nb_views", "label" => "Nombre de vues");
        $rows[3] = array("column" => "date_creation", "label" => "Date de création de l'article");


        //On récupère nos articles et countNbComment.
        $articleManager = new ArticleManager();
        $info = $articleManager->countTablePage($page);

        //on passe dans le tableau $info les différentes info qui compose notre l'url
        $info['column'] = $column;
        $info['order'] = $order;
        $info['action_block'] = "?action=dashboardArticle";
        $info['action_comment'] = "?action=dashboardComment&articleId=";
        $info['page_block'] = "&page=$page";
        $info['filter_block'] = $column !== null && $order !== null ? "&column=$column&order=$order" : " ";



        $articleList = $articleManager->getAllArticles($info);

        $commentManager = new CommentManager();
        $commentCountList = $commentManager->countComments();
        $i = 0;


        //regroupe dans un seul array $data les info, plus simple pour l'envoie des données
        foreach($articleList as $article) {
            $articleId = $article->getId();

            // array $data[id, titre, nbvue, nbcomments, date-création]
            $data[$i]['id'] = $articleId;
            $data[$i]['title'] = $article->getTitle();
            $data[$i]['date_add'] = $article->getDateCreation();
            ;
            $data[$i]['nbViews'] = $article->getNbViews();

            //valeur par défaut
            $data[$i]['nbComments'] = 0;

            foreach ($commentCountList as $commentRow) {
                if($commentRow['id_article'] == $articleId) {
                    $data[$i]['nbComments'] = $commentRow['nb_comments'];
                    break;
                }
            }
            $i++;
        }
        // On affiche la page de dashboard l'article.
        $view = new View("Dashboard des articles");
        $view->render("dashboardArticle", [
            'articles' => $data,
            'info' => $info,
            'rows' => $rows

        ]);
    }
}
