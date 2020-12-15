<?php

namespace Pon\Tags;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Pon\Question\Question;
use Pon\Question\Answer;
use Pon\User\User;
use Pon\Filter\Filter;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class TagsController implements ContainerInjectableInterface
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

        $TagQuestion = new TagQuestion();
        $TagQuestion->setDb($this->di->get("dbqb"));
        $dupTags = $TagQuestion->findAll();
        $allTags = array();

        foreach ($dupTags as $Tag) {
            if (!in_array($Tag->text, $allTags)) {
                $allTags[] = $Tag->text;
            }
        }

        $page->add("tags/tags", [
            "allTags" => $allTags,
        ]);

        return $page->render([
            "title" => "Alla taggar",
        ]);
    }



    /**
     * Show one question.
     *
     * @param int $id The id of the question
     *
     * @return void
     */
    public function viewAction($tag)
    {

        $page = $this->di->get("page");

        $TagQuestion = new TagQuestion();
        $TagQuestion->setDb($this->di->get("dbqb"));

        $fltrQst = $TagQuestion->findAllWhere("text = ?", $tag);

        foreach ($fltrQst as $qst) {
            $user = new User();
            $Question = new Question();
            $user->setDb($this->di->get("dbqb"));
            $Question->setDb($this->di->get("dbqb"));
            $question = $Question->find('id', $qst->questionid);
            $userInfo = $user->find('id', $question->userId);
            $answer = new Answer();
            $answer->setDb($this->di->get("dbqb"));
            $answerSum = $answer->selectWhere("count(*) as num", "questionid = ?", $qst->id);
            $parsedText = $this->filter->markdown($qst->text);
            $page->add("question/questions", [
                "question" => $question,
                "userInfo" => $userInfo,
                "answerSum" => $answerSum,
                "parsedText" => $parsedText,
            ]);
        };

        return $page->render([
            "title" => "Visar frågor till taggen {$tag}",
        ]);
    }
}
