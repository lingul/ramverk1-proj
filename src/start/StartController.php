<?php

namespace Pon\Start;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Pon\Pon\ActiveRecordModelExtra;
use Pon\Question\Question;
use Pon\Question\Answer;
use Pon\Tags\TagQuestion;
use Pon\Tag\Tag;
use Pon\User\User;
use Pon\Filter\Filter;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class StartController implements ContainerInjectableInterface
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

        $page = $this->di->get("page");

        $page->add("start/startPage", [
        ]);

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $allQuestions = $question->findAllOrderBy("created DESC", 3);

        foreach ($allQuestions as $question) {
            $user = new User();
            $user->setDb($this->di->get("dbqb"));
            $userInfo = $user->find('id', $question->userId);
            $answer = new Answer();
            $answer->setDb($this->di->get("dbqb"));
            $answerSum = $answer->selectWhere("count(*) as num", "questionid = ?", $question->id);
            $parsedText = $this->filter->markdown($question->text);
            $page->add("question/questions", [
                "question" => $question,
                "userInfo" => $userInfo,
                "answerSum" => $answerSum,
                "parsedText" => $parsedText,
            ]);
        }

        $TagQuestion = new TagQuestion();
        $TagQuestion->setDb($this->di->get("dbqb"));
        $top3Tags = $TagQuestion->findAllOrderByGroupBy("count DESC", "text", 3, "text, count(text) as count");

        $page->add("start/topTags", [
            "allTags" => $top3Tags,
        ]);

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $top3Users = $user->findAllOrderBy("score DESC", 3);

        $page->add("start/topUsers", [
            "allUsers" => $top3Users,
        ]);

        return $page->render([
            "title" => "Startsida",
        ]);
    }

    /**
     * StartController messing with routes.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function questionActionPost() : void
    {
        $id        = $this->di->session->get('loggedIn');
        $text        = $this->di->request->getPost("Kommentera");
        $questionId  = $this->di->request->getPost("questionid");
        $answerId    = $this->di->request->getPost("answerid") ?? null;
        $createdDate = date("Y/m/d G:i:s", time());

        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comment->userId = $id;
        $comment->votes = 0;
        $comment->created = $createdDate;
        $comment->text = $text;
        $comment->questionid = $questionId;
        $comment->answerid = $answerId;
        $comment->save();

        $this->di->response->redirect("question/view/{$questionId}");
    }
}
