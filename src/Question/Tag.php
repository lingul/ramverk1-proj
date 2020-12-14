<?php

namespace ligm19\Question;

use ligm19\Barm\BonusActiveRecordModel;

/**
 * A database driven model.
 */
class Tag extends BonusActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Tag";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $text;
}
