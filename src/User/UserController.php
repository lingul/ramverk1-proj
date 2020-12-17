<?php

namespace ligm\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use ligm\User\HTMLForm\UserLoginForm;
use ligm\User\HTMLForm\EditUserForm;
use ligm\User\HTMLForm\CreateUserForm;
use ligm\Question\Question;
use ligm\Question\Answer;
use ligm\Question\Comment;
use ligm\Question\UserVotes;
use ligm\Filter\Filter;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class UserController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var $data description
     */
    //private $data;



    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    public function initialize() : void
    {
        $this->filter = new Filter();
    }



    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $currUser = $this->di->session->get('loggedIn');
        if (!$currUser) {
            return $this->di->response->redirect("user/login");
        }

        return $this->di->response->redirect("user/profile/{$currUser}");
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function profileAction(int $id) : object
    {
        $page = $this->di->get("page");
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find('id', $id);

        if ($user->score <= 0) {
            $rank = "html";
        } else if ($user->score <= 5) {
            $rank = "python";
        } else if ($user->score <= 20) {
            $rank = "javascript";
        } else if ($user->score <= 40) {
            $rank = "PHP";
        } else if ($user->score <= 60) {
            $rank = "Java";
        } else if ($user->score <= 100) {
            $rank = "R";
        };

        $votes = new UserVotes();
        $votes->setDb($this->di->get("dbqb"));
        $upVotes = $votes->selectWhere("COUNT(*) as num", "userId = ? AND voted = ?", [$id, "up"]);
        $downVotes = $votes->selectWhere("COUNT(*) as num", "userId = ? AND voted = ?", [$id, "down"]);

        $page->add("user/userProfile", [
            "userInfo" => $user,
            "rank" => $rank,
            "upVotes" => $upVotes ?? 0,
            "downVotes" => $downVotes ?? 0,
        ]);

        $page->add("user/userQuestionStart");

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $questions = $question->findAllWhere("userId = ?", $id);
        foreach ($questions as $quest) {
            $parsedText = $this->filter->markdown($quest->text);
            $page->add("user/userQuestion", [
                "question" => $quest,
                "userInfo" => $user,
                "parsedText" => $parsedText,
            ]);
        }

        $page->add("user/userAnswerStart");

        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answers = $answer->findAllWhere("userId = ?", $id);

        foreach ($answers as $answer) {
            $question = new Question();
            $question->setDb($this->di->get("dbqb"));
            $question->find("id", $answer->questionid);
            $parsedText = $this->filter->markdown($answer->text);
            $page->add("user/userAnswer", [
                "answer" => $answer,
                "userInfo" => $user,
                "question" => $question,
                "parsedText" => $parsedText,
            ]);
        }

        $page->add("user/userCommentStart");

        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comments = $comment->findAllWhere("userId = ?", $id);

        foreach ($comments as $comment) {
            $question = new Question();
            $question->setDb($this->di->get("dbqb"));
            $question->find("id", $comment->questionid);
            $parsedText = $this->filter->markdown($comment->text);
            $page->add("user/userAnswer", [
                "answer" => $comment,
                "userInfo" => $user,
                "question" => $question,
                "parsedText" => $parsedText,
            ]);
        }

        return $page->render([
            "title" => $user->acronym,
        ]);
    }



    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function loginAction() : object
    {
        if ($this->di->session->get('loggedIn')) {
            return $this->di->response->redirect("user");
        }

        $page = $this->di->get("page");
        $form = new UserLoginForm($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "A login page",
        ]);
    }



    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        if ($this->di->session->get('loggedIn')) {
            return $this->di->response->redirect("login");
        }

        $form = new CreateUserForm($this->di);
        $form->check();

        $page = $this->di->get("page");
        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Skapa en användare",
        ]);
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function editAction() : object
    {
        if (!$this->di->session->get('loggedIn')) {
            return $this->di->response->redirect("user/login");
        }

        $currUser = $this->di->session->get('loggedIn');
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $userInfo = $user->find('id', $currUser);

        $page = $this->di->get("page");
        $form = new EditUserForm($this->di, $userInfo);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Redigera användare",
        ]);
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function logoutAction() : object
    {
        if (!$this->di->session->get('loggedIn')) {
            return $this->di->response->redirect("user");
        }

        $this->di->session->delete("loggedIn");

        return $this->di->response->redirect("user/login");
    }
}
