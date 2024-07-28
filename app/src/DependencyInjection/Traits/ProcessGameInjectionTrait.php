<?php

namespace App\DependencyInjection\Traits;

use App\Service\ProcessGameService;

trait ProcessGameInjectionTrait
{
    /**
     * @var ProcessGameService
     */
    protected ProcessGameService $processGameService;

    /**
     * @required
     *
     * @param ProcessGameService $processGameService
     */
    public function setProcessGameService(ProcessGameService $processGameService): void
    {
        $this->processGameService = $processGameService;
    }

    /**
     * @return ProcessGameService
     */
    public function getProcessGameService(): ProcessGameService
    {
        return $this->processGameService;
    }
}