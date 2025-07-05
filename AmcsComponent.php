<?php

namespace Apps\Fintech\Components\Mf\Amcs;

use Apps\Fintech\Packages\Adminltetags\Traits\DynamicTable;
use Apps\Fintech\Packages\Mf\Amcs\MfAmcs;
use System\Base\BaseComponent;

class AmcsComponent extends BaseComponent
{
    use DynamicTable;

    protected $amcsPackage;

    public function initialize()
    {
        $this->amcsPackage = $this->usePackage(MfAmcs::class);
    }

    /**
     * @acl(name=view)
     */
    public function viewAction()
    {
        if (isset($this->getData()['id'])) {
            if ($this->getData()['id'] != 0) {
                $amc = $this->amcsPackage->getById((int) $this->getData()['id']);

                if (!$amc) {
                    return $this->throwIdNotFound();
                }

                $this->view->amc = $amc;
            }

            $this->view->pick('amcs/view');

            return;
        }

        $controlActions =
            [
                // 'disableActionsForIds'  => [1],
                'actionsToEnable'       =>
                [
                    'edit'      => 'mf/amcs',
                    'remove'    => 'mf/amcs/remove'
                ]
            ];

        $replaceColumns =
            function ($dataArr) {
                if ($dataArr && is_array($dataArr) && count($dataArr) > 0) {
                    return $this->replaceColumns($dataArr);
                }

                return $dataArr;
            };

        $this->generateDTContent(
            $this->amcsPackage,
            'mf/amcs/view',
            null,
            ['name', 'phone_number', 'contact_email', 'turn_around_time'],
            true,
            ['name', 'phone_number', 'contact_email', 'turn_around_time'],
            $controlActions,
            ['turn_around_time' => 'Turn Around Time (Days)'],
            $replaceColumns,
            'name'
        );

        $this->view->pick('amcs/list');
    }

    protected function replaceColumns($dataArr)
    {
        foreach ($dataArr as $dataKey => &$data) {
            $data = $this->formatTurnAroundTime($dataKey, $data);
        }

        return $dataArr;
    }

    protected function formatTurnAroundTime($rowId, $data)
    {
        if (!isset($data['turn_around_time'])) {
            $data['turn_around_time'] = '-';
        }

        return $data;
    }

    /**
     * @acl(name=add)
     */
    public function addAction()
    {
        //
    }

    /**
     * @acl(name=update)
     */
    public function updateAction()
    {
        $this->requestIsPost();

        $this->amcsPackage->updateMfAmcs($this->postData());

        $this->addResponse(
            $this->amcsPackage->packagesData->responseMessage,
            $this->amcsPackage->packagesData->responseCode
        );
    }

    /**
     * @acl(name=remove)
     */
    public function removeAction()
    {
        //
    }
}