<?php

namespace App\Composers\Partner;

use App\Repositories\Partner\PartnerRepositoryInterface;

class PartnerComposer {

    protected $partner;

    public function __construct(PartnerRepositoryInterface $partner)
    {
        $this->partner = $partner;
    }

    public function compose($view)
    {
        $view->with('partners', $this->partner->all());
    }

}