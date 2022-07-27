<?php

use Imega\DataReporting\Models\Audit;

class EloquentAuditRepository implements AuditRepositoryContract
{
    protected Audit $model;

    public function __construct(Audit $audit)
    {
        $this->model = $audit;
    }
}
