<?php

namespace ligm19\Question;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use ligm19\Question\HTMLForm\CreateQuestionForm;
use ligm19\Question\HTMLForm\CreateAnswerForm;
use ligm19\Question\HTMLForm\CreateCommentForm;
use ligm19\User\User;
use ligm19\Question\Answer;
use ligm19\Tags\TagQuestion;
use ligm19\Question\UserVotes;
use ligm19\Filter\Filter;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class QuestionController implements ContainerInjectableInterface
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

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $allQuestions = $question->findAllOrderBy("created DESC");
        // $allQuestions = $question->findAll();

        $page->add("question/beforeQuestions", [
            "allQuestions" => $allQuestions,
        ]);

        $top3Tags = $question->selectWhere("COUNT(*) as sum", "id = ?", $question->id);

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

        return $page->render([
            "title" => "Alla frågor",
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
        if (!$this->di->session->get('loggedIn')) {
            return $this->di->response->redirect("user/login");
        }

        $page = $this->di->get("page");
        $form = new CreateQuestionForm($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Skapa en fråga",
        ]);
    }


    /**
     * Show one question.
     *
     * @param int $id The id of the question
     *
     * @return void
     */
    public function viewAction(int $id)
    {

        $page = $this->di->get("page");

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $theQuestion = $question->find('id', $id);

        $TagQuestion = new TagQuestion();
        $TagQuestion->setDb($this->di->get("dbqb"));
        $tags = $TagQuestion->findAllWhere("questionid = ?", $id);

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find('id', $theQuestion->userId);

        if (!$this->di->session->get('loggedIn')) {
            $noUser = "disabled";
        }
        $parsedText = $this->filter->markdown($question->text);
        $page->add("question/question", [
            "question" => $theQuestion,
            "userInfo" => $user,
            "tags" => $tags,
            "canVote" => $noUser ?? "",
            "parsedText" => $parsedText,
        ]);

        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $allComments = $comment->findAllWhere("questionid = ? and answerid = ?", [$id, '']);

        foreach ($allComments as $oneComment) {
            $user = new User();
            $user->setDb($this->di->get("dbqb"));
            $userInfo = $user->find('id', $oneComment->userId);
            $parsedText = $this->filter->markdown($oneComment->text);
            $page->add("question/showComment", [
                "comment" => $oneComment,
                "userInfo" => $userInfo,
                "canVote" => $noUser ?? "",
                "parsedText" => $parsedText,
            ]);
        };

        $page->add("question/answerSort", [
            "question" => $question
        ]);

        $sort = $this->di->request->getGet("sort") ?? "Votes";

        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $allAnswers = $answer->findAllWhereOrderBy("$sort DESC", "questionid = ?", $id);

        foreach ($allAnswers as $answer) {
            $user = new User();
            $user->setDb($this->di->get("dbqb"));
            $userInfo = $user->find('id', $answer->userId);
            $parsedText = $this->filter->markdown($answer->text);
            $page->add("question/answer", [
                "theQuestion" => $theQuestion,
                "answer" => $answer,
                "userInfo" => $userInfo,
                "canVote" => $noUser ?? "",
                "loggedIn" => $this->di->session->get('loggedIn'),
                "parsedText" => $parsedText,
            ]);
            $comment = new Comment();
            $comment->setDb($this->di->get("dbqb"));
            $allComments = $comment->findAllWhere("questionid = ? and answerid = ?", [$id, $answer->id]);
            foreach ($allComments as $oneComment) {
                $user = new User();
                $user->setDb($this->di->get("dbqb"));
                $userInfo = $user->find('id', $oneComment->userId);
                $parsedText = $this->filter->markdown($oneComment->text);
                $page->add("question/showComment", [
                    "comment" => $oneComment,
                    "userInfo" => $userInfo,
                    "canVote" => $noUser ?? "",
                    "parsedText" => $parsedText,
                ]);
            };
        };

        if ($this->di->session->get('loggedIn')) {
            $form = new CreateAnswerForm($this->di, $id);
            $form->check();
            $page->add("anax/v2/article/default", [
                "content" => $form->getHTML(),
            ]);
        }

        return $page->render([
            "title" => "$question->title",
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
    public function commentActionGet() : object
    {
        if (!$this->di->session->get('loggedIn')) {
            return $this->di->response->redirect("user/login");
        }

        $questionid = $this->di->request->getGet("question");
        $answerid = $this->di->request->getGet("answer") ?? null;

        $user = new User();
        $user->setDb($this->di->get("dbqb"));

        if ($answerid) {
            $answer = new Answer();
            $answer->setDb($this->di->get("dbqb"));
            $replyTo = $answer->findWhere('id = ? and questionid = ?', [$answerid, $questionid]);

            $userInfo = $user->find('id', $replyTo->userId);
        } else {
            $question = new Question();
            $question->setDb($this->di->get("dbqb"));
            $replyTo = $question->find('id', $questionid);

            $userInfo = $user->find('id', $replyTo->id);
        }

        $form = new CreateCommentForm($this->di, $questionid, $answerid);
        $form->check();

        $page = $this->di->get("page");
        $parsedText = $this->filter->markdown($replyTo->text);
        $page->add("question/giveComment", [
            "replyTo" => $replyTo,
            "userInfo" => $userInfo,
            "content" => $form->getHTML(),
            "parsedText" => $parsedText,
        ]);

        return $page->render([
            "title" => "Kommentera",
        ]);
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function commentActionPost() : void
    {
        $id        = $this->di->session->get('loggedIn');
        $text        = $this->di->request->getPost("Kommentera");
        $questionId  = $this->di->request->getPost("questionid");
        $answerId    = $this->di->request->getPost("answerid") ?? null;
        $createdDate = date("Y/m/d G:i:s", time());

        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comment->userId = $id;
        $comment->created = $createdDate;
        $comment->text = $text;
        $comment->votes = 0;
        $comment->questionid = $questionId;
        $comment->answerid = $answerId;
        $comment->save();

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("id", $comment->userId);
        $user->score = $user->score + 1;
        $user->save();

        $this->di->response->redirect("question/view/{$questionId}");
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function voteActionGet() : object
    {
        $userId      = $this->di->session->get('loggedIn');
        $votedId   = $this->di->request->getGet("votedId");
        $votedType = $this->di->request->getGet("votedType");
        $questId   = $this->di->request->getGet("questId") ?? $votedId;
        $voted = $this->di->request->getGet("vote");

        $userVotes = new UserVotes();
        $userVotes->setDb($this->di->get("dbqb"));
        $userVotes->hasVoted($userId, $votedId, $votedType);
        $hasVoted = $userVotes->hasVoted($userId, $votedId, $votedType);

        if ($votedType == "question") {
            $newVote = new Question();
        } else if ($votedType == "answer") {
            $newVote = new Answer();
        } else if ($votedType == "comment") {
            $newVote = new Comment();
        }
        $newVote->setDb($this->di->get("dbqb"));

        $nVote = $newVote->findWhere("id = ?", $votedId);

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $currUser = $user->findWhere("id = ?", $nVote->userId);

        if ($voted == "up" and $hasVoted == "up") {
            $nVote->votes = $nVote->votes - 1;
            $currUser->score = $currUser->score - 1;
            $userVotes->deleteVote($userId, $votedId, $votedType);
        } else if ($voted == "down" and $hasVoted == "down") {
            $nVote->votes = $nVote->votes + 1;
            $currUser->score = $currUser->score + 1;
            $userVotes->deleteVote($userId, $votedId, $votedType);
        } else if ($voted == "up" and $hasVoted == "down") {
            $nVote->votes = $nVote->votes + 2;
            $currUser->score = $currUser->score + 2;
            $userVotes->deleteVote($userId, $votedId, $votedType);
            $nVote->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        } else if ($voted == "down" and $hasVoted == "up") {
            $nVote->votes = $nVote->votes -2;
            $currUser->score = $currUser->score - 2;
            $userVotes->deleteVote($userId, $votedId, $votedType);
            $nVote->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        } else if ($voted == "up") {
            $nVote->votes = $nVote->votes + 1;
            $currUser->score = $currUser->score + 1;
            $nVote->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        } else if ($voted == "down") {
            $nVote->votes = $nVote->votes - 1;
            $currUser->score = $currUser->score - 1;
            $nVote->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        }
        $currUser->save();
        $nVote->save();





        // if ($votedType == "question") {
        //     $question = new Question();
        //     $question->setDb($this->di->get("dbqb"));
        //     $quest = $question->findWhere("id = ?", $votedId);
        //
        //     $userVotes = new UserVotes();
        //     $userVotes->setDb($this->di->get("dbqb"))
        //     $user = $question->findWhere("id = ?", $quest->userId);
        //
        //     if ($voted == "up" and $hasVoted == "up") {
        //         $quest->votes = $question->votes -1;
        //         $userVotes->deleteVote($userId, $votedId, $votedType);
        //     } else if ($voted == "down" and $hasVoted == "down") {
        //         $quest->votes = $question->votes + 1;
        //         $userVotes->deleteVote($userId, $votedId, $votedType);
        //     } else if ($voted == "up" and $hasVoted == "down") {
        //         $quest->votes = $question->votes + 2;
        //         $userVotes->deleteVote($userId, $votedId, $votedType);
        //         $quest->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        //     } else if ($voted == "down" and $hasVoted == "up") {
        //         $quest->votes = $question->votes -2;
        //         $userVotes->deleteVote($userId, $votedId, $votedType);
        //         $quest->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        //     } else if ($voted == "up") {
        //         $quest->votes = $question->votes + 1;
        //         $quest->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        //     } else if ($voted == "down") {
        //         $quest->votes = $question->votes - 1;
        //         $quest->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        //     }
        //     $quest->save();
        // }
        //
        // if ($votedType == "answer") {
        //     $answer = new Answer();
        //     $answer->setDb($this->di->get("dbqb"));
        //     $answ = $answer->findWhere("id = ?", $votedId);
        //
        //     if ($voted == "up" and $hasVoted == "up") {
        //         $answ->votes = $answer->votes -1;
        //         $userVotes->deleteVote($userId, $votedId, $votedType);
        //     } else if ($voted == "down" and $hasVoted == "down") {
        //         $answ->votes = $answer->votes + 1;
        //         $userVotes->deleteVote($userId, $votedId, $votedType);
        //     } else if ($voted == "up" and $hasVoted == "down") {
        //         $answ->votes = $answer->votes + 2;
        //         $userVotes->deleteVote($userId, $votedId, $votedType);
        //         $answ->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        //     } else if ($voted == "down" and $hasVoted == "up") {
        //         $answ->votes = $answer->votes -2;
        //         $userVotes->deleteVote($userId, $votedId, $votedType);
        //         $answ->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        //     } else if ($voted == "up") {
        //         $answ->votes = $answer->votes + 1;
        //         $answ->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        //     } else if ($voted == "down") {
        //         $answ->votes = $answer->votes - 1;
        //         $answ->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        //     }
        //     $answ->save();
        // }
        //
        //
        // if ($votedType == "comment") {
        //     $comment = new Comment();
        //     $comment->setDb($this->di->get("dbqb"));
        //     $cmt = $comment->findWhere("id = ?", $votedId);
        //
        //     if ($voted == "up" and $hasVoted == "up") {
        //         $cmt->votes = $comment->votes -1;
        //         $userVotes->deleteVote($userId, $votedId, $votedType);
        //     } else if ($voted == "down" and $hasVoted == "down") {
        //         $cmt->votes = $comment->votes + 1;
        //         $userVotes->deleteVote($userId, $votedId, $votedType);
        //     } else if ($voted == "up" and $hasVoted == "down") {
        //         $cmt->votes = $comment->votes + 2;
        //         $userVotes->deleteVote($userId, $votedId, $votedType);
        //         $cmt->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        //     } else if ($voted == "down" and $hasVoted == "up") {
        //         $cmt->votes = $comment->votes -2;
        //         $userVotes->deleteVote($userId, $votedId, $votedType);
        //         $cmt->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        //     } else if ($voted == "up") {
        //         $cmt->votes = $comment->votes + 1;
        //         $cmt->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        //     } else if ($voted == "down") {
        //         $cmt->votes = $comment->votes - 1;
        //         $cmt->saveVote($userId, $votedId, $votedType, $voted, $this->di);
        //     }
        //     $cmt->save();
        // }

        $this->di->response->redirect("question/view/{$questId}");
    }


    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function acceptActionGet() : object
    {
        $userId      = $this->di->session->get('loggedIn');
        $questId  = $this->di->request->getGet("questionId");
        $answerId = $this->di->request->getGet("answerId");

        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $currAnswer = $answer->find("id", $answerId);
        if ($currAnswer->accepted == 1) {
            $currAnswer->accepted = 0;
        } else {
            $currAnswer->accepted = 1;
        }

        $currAnswer->save();

        $this->di->response->redirect("question/view/{$questId}");
    }
}
