<?php

namespace Pon\Question;

use Pon\Barm\BonusActiveRecordModel;
use Pon\Question\Answer;
use Pon\Question\Question;
use Pon\Question\Comment;

/**
 * A database driven model.
 */
class UserVotes extends BonusActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "UserVotes";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $userId;
    public $votedId;
    public $votedType;
    public $voted;

    /**
     * Check if has voted
     *
     *
     * @return boolean
     */
    public function hasVoted($userId, $votedId, $votedType)
    {
        $res = $this->findWhere("userId = ? AND votedId = ? AND votedType = ?", [$userId, $votedId, $votedType]);

        return $res->voted;
    }



    /**
     * Check if has voted
     *
     *
     * @return void
     */
    public function deleteVote($userId, $votedId, $votedType)
    {
        $this->deleteWhere("userId = ? AND votedId = ? AND votedType = ?", [$userId, $votedId, $votedType]);
    }

    // /**
    //  * Change the vote
    //  *
    //  *
    //  * @return void
    //  */
    // public function changeVote($userId, $votedId, $votedType)
    // {
    //     $hasVoted = $this->hasVoted($userId, $votedId, $votedType);
    //
    //     if ($votedType == "question") {
    //         $newVote = new Question();
    //     } else if ($votedType == "answer") {
    //         $newVote = new Answer();
    //     } else if ($votedType == "comment") {
    //         $newVote = new Comment();
    //     }
    //
    //     if ($votedType == "question") {
    //         $question = new Question();
    //         $question->setDb($this->di->get("dbqb"));
    //         $quest = $question->findWhere("id = ?", $votedId);
    //
    //         $this = new UserVotes();
    //         $this->setDb($this->di->get("dbqb"))
    //         $user = $question->findWhere("id = ?", $quest->userId);
    //
    //         if ($voted == "up" and $hasVoted == "up") {
    //             $quest->votes = $question->votes -1;
    //             $this->deleteVote($userId, $votedId, $votedType);
    //         } else if ($voted == "down" and $hasVoted == "down") {
    //             $quest->votes = $question->votes + 1;
    //             $this->deleteVote($userId, $votedId, $votedType);
    //         } else if ($voted == "up" and $hasVoted == "down") {
    //             $quest->votes = $question->votes + 2;
    //             $this->deleteVote($userId, $votedId, $votedType);
    //             $quest->saveVote($userId, $votedId, $votedType, $voted, $this->di);
    //         } else if ($voted == "down" and $hasVoted == "up") {
    //             $quest->votes = $question->votes -2;
    //             $this->deleteVote($userId, $votedId, $votedType);
    //             $quest->saveVote($userId, $votedId, $votedType, $voted, $this->di);
    //         } else if ($voted == "up") {
    //             $quest->votes = $question->votes + 1;
    //             $quest->saveVote($userId, $votedId, $votedType, $voted, $this->di);
    //         } else if ($voted == "down") {
    //             $quest->votes = $question->votes - 1;
    //             $quest->saveVote($userId, $votedId, $votedType, $voted, $this->di);
    //         }
    //         $quest->save();
    //     }
    // }
}
