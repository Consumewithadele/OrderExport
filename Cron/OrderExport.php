<?php
namespace Adele\OrderExport\Cron;

use Adele\OrderExport\Model\Export;

class OrderExport
{
    /**
     * @var Export
     */
    private $export;

    /**
     * OrderExport constructor.
     *
     * @param Export $export
     */
    public function __construct(
        Export $export
    ) {
        $this->export = $export;
    }

    public function execute()
    {
        /**
         * @TODO
         * We should get collection of orders that had problem with export and try to reexport them.
         */
    }
}
