<?php

require_once __DIR__ . '/../MODEL/connect.php';
require_once __DIR__ . '/../MODEL/Account.php';

class controller_account
{
    private $accountModel;

    public function __construct($conn)
    {
        $this->accountModel = new Account($conn);
    }

    public function dashboardData()
    {
        return [
            'topSeller'       => $this->accountModel->getTopSeller(),
            'topBuyer'        => $this->accountModel->getTopBuyer(),

            'topSellers'      => $this->accountModel->getTopSellers(),
            'topBuyers'       => $this->accountModel->getTopBuyers(),

            'totalSeller'     => $this->accountModel->countSellers(),
            'totalBuyer'      => $this->accountModel->countBuyers(),

            'deletedAccounts' => $this->accountModel->getDeletedAccounts(),
            'deletedTotal'    => $this->accountModel->countDeletedAccounts()
        ];
    }

    public function deleteAccount($accountId)
    {
        return $this->accountModel->delete($accountId);
    }

    public function restoreAccount($accountId)
    {
        return $this->accountModel->restore($accountId);
    }

    public function getDeletedAccounts()
    {
        return $this->accountModel->getDeletedAccounts();
    }
}
