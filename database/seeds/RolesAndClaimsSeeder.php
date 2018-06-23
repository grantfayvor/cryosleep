<?php

use App\Services\RolesAndClaimsService;
use App\Services\UserService;
use Illuminate\Database\Seeder;

class RolesAndClaimsSeeder extends Seeder
{
    private $rolesAndClaimsService;

    public function __construct(RolesAndClaimsService $rolesAndClaimsService, UserService $userService)
    {
        $this->rolesAndClaimsService = $rolesAndClaimsService;
        $this->userService = $userService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = $this->userService->getById(1);
        $this->rolesAndClaimsService->createRoleWithClaims('ADMIN', ['create-crypto-account', 'edit-crypto-account', 'get-user-crypto-account', 'confirm-user-crypto-account', 'create-transaction', 'get-transaction', 'generate-payment-url', 'generate-withdrawal-url', 'get-user-transactions', 'get-user-confirmed-transactions', 'create-withdrawal-info', 'get-transaction-plans', 'get-transaction-plan', 'get-transaction-types', 'get-transaction-type', 'get-transactions-information', 'get-crypto-accounts', 'delete-crypto-account', 'get-transactions', 'delete-transaction', 'get-withdrawal-infos', 'unapproved-withdrawal-infos', 'approve-withdrawal-info', 'create-transaction-plan', 'delete-transaction-plan', 'create-transaction-type', 'delete-transaction-type', 'get-all-roles', 'create-role', 'assign-role', 'retract-role', 'retract-user-claims', 'retract-role-claims']);
        $this->rolesAndClaimsService->createRoleWithClaims('USER', ['create-crypto-account', 'edit-crypto-account', 'get-user-crypto-account', 'confirm-user-crypto-account', 'create-transaction', 'get-transaction', 'generate-payment-url', 'generate-withdrawal-url', 'get-user-transactions', 'get-user-confirmed-transactions', 'create-withdrawal-info', 'get-transaction-plans', 'get-transaction-plan', 'get-transaction-types', 'get-transaction-type', 'get-transactions-information']);
        $this->rolesAndClaimsService->assignRole($user, 'ADMIN');
    }
}
