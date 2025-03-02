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

        $this->generateDTContent(
            $this->amcsPackage,
            'mf/amcs/view',
            null,
            ['name', 'phone_number', 'website', 'contact_email'],
            true,
            ['name', 'phone_number', 'website', 'contact_email'],
            $controlActions,
            null,
            null,
            'name'
        );

        $this->view->pick('amcs/list');
    }

    /**
     * @acl(name=add)
     */
    public function addAction()
    {
        $this->requestIsPost();

        //$this->package->add{?}($this->postData());

        $this->addResponse(
            $this->package->packagesData->responseMessage,
            $this->package->packagesData->responseCode
        );
    }

    /**
     * @acl(name=update)
     */
    public function updateAction()
    {
        $this->requestIsPost();

        //$this->package->update{?}($this->postData());

        $this->addResponse(
            $this->package->packagesData->responseMessage,
            $this->package->packagesData->responseCode
        );
    }

    /**
     * @acl(name=remove)
     */
    public function removeAction()
    {
        $this->requestIsPost();

        //$this->package->remove{?}($this->postData());

        $this->addResponse(
            $this->package->packagesData->responseMessage,
            $this->package->packagesData->responseCode
        );
    }
}