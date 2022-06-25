<?php

namespace TotalCRM\MoySklad\Components\Query;

use TotalCRM\MoySklad\Components\Query\AbstractQuery;
use TotalCRM\MoySklad\Lists\RelationEntityList;

class RelationQuery extends AbstractQuery
{
    protected static string $entityListClass = RelationEntityList::class;
}
