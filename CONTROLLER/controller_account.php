<?php

require_once __DIR__ . '/../MODEL/connect.php';
require_once __DIR__ . '/../MODEL/Account.php';
require_once __DIR__ . '/../MODEL/AccountProfile.php';

class controller_account
{
    private $conn;
    private $accountModel;
    private $profileModel;

    public function __construct($conn)
    {
        $this->conn = $conn;

        $this->accountModel = new Account($conn);
        $this->profileModel = new AccountProfile($conn);
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

    public function getAccountDetail($account_id)
    {
        $account = $this->accountModel->findById($account_id);

        $stmt = $this->conn->prepare("
            SELECT *
            FROM account_profiles
            WHERE account_id = ?
            LIMIT 1
        ");

        $stmt->bind_param("i", $account_id);
        $stmt->execute();

        $profile = $stmt->get_result()->fetch_assoc();

        return [
            'account' => $account,
            'profile' => $profile
        ];
    }

    public function updateAccount()
    {
        if (
            !isset($_POST['action']) ||
            $_POST['action'] !== 'update'
        ) {
            return;
        }

        $account_id = (int)$_POST['account_id'];
        $profile_id = (int)$_POST['profile_id'];

        $username = $_POST['username'];
        $email = $_POST['email'];
        $role_id = (int)$_POST['role_id'];

        $last_name = $_POST['last_name'];
        $middle_name = $_POST['middle_name'];
        $first_name = $_POST['first_name'];

        $gender_id = !empty($_POST['gender_id'])
            ? (int)$_POST['gender_id']
            : null;

        $date_of_birth = !empty($_POST['date_of_birth'])
            ? $_POST['date_of_birth']
            : null;

        $phone = $_POST['phone'];
        $bio = $_POST['bio'];

        $account = $this->accountModel->findById($account_id);

        $password_hash = $account['password_hash'];

        if (!empty($_POST['password'])) {
            $password_hash = password_hash(
                $_POST['password'],
                PASSWORD_DEFAULT
            );
        }

        $this->accountModel->update(
            $account_id,
            $username,
            $email,
            $password_hash,
            $role_id
        );

        $this->profileModel->update(
            $profile_id,
            $last_name,
            $middle_name,
            $first_name,
            $gender_id,
            $date_of_birth,
            $bio,
            $phone
        );

        return true;
    }
    public function getAccountById($accountId)
    {
        return $this->accountModel->getAccountById($accountId);
    }
}

$controller = new controller_account($conn);

if (
    isset($_GET['action']) &&
    $_GET['action'] == 'detail' &&
    isset($_GET['id'])
) {

    $id = (int)$_GET['id'];

    $detail = $controller->getAccountDetail($id);

    header('Content-Type: application/json');
    echo json_encode($detail);
    exit;
}
if (
    isset($_POST['action']) &&
    $_POST['action'] === 'update'
) {
    $controller->updateAccount();

    header("Location: ../VIEW/page/accounts.php");
    exit;
}
