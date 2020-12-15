<?php

namespace Pon\Question;

use Pon\Barm\BonusActiveRecordModel;

/**
 * A database driven model.
 */
class Question extends BonusActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Question";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $userId;
    public $title;
    public $text;
    public $votes;
    public $created;
    public $updated;
    public $deleted;


    /**
     * Save vote
     *
     *
     * @return void
     */
    public function saveVote($userId, $votedId, $votedType, $voted, $di)
    {
        $userVotes = new UserVotes();
        $userVotes->setDb($di->get("dbqb"));
        $userVotes->userId = $userId;
        $userVotes->votedId = $votedId;
        $userVotes->votedType = $votedType;
        $userVotes->voted = $voted;
        $userVotes->save();
    }
}
