<?php

namespace ligm\Tags;

use ligm\Barm\BonusActiveRecordModel;

/**
 * A database driven model.
 */
class TagQuestion extends BonusActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "TagQuestion";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $text;
    public $questionid;
}
