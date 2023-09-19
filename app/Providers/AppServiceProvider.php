<?php

namespace App\Providers;


use App\Implementations\Repositories\AccountRepository;
use App\Interfaces\Repositories\IAccountRepository;
use App\Implementations\Services\AccountService;
use App\Interfaces\Services\IAccountService;

use App\Implementations\Repositories\AddressRepository;
use App\Interfaces\Repositories\IAddressRepository;

use App\Implementations\Services\AuthService;
use App\Interfaces\Services\IAuthService;

use App\Implementations\Repositories\BankRepository;
use App\Interfaces\Repositories\IBankRepository;
use App\Implementations\Services\BankService;
use App\Interfaces\Services\IBankService;

use App\Implementations\Repositories\BvnRepository;
use App\Interfaces\Repositories\IBvnRepository;

use App\Implementations\Repositories\CountryRepository;
use App\Interfaces\Repositories\ICountryRepository;
use App\Implementations\Services\CountryService;
use App\Interfaces\Services\ICountryService;

use App\Implementations\Services\ManagerService;
use App\Implementations\Repositories\ManagerRepository;
use App\Interfaces\Repositories\IManagerRepository;
use App\Interfaces\Services\IManagerService;

use App\Implementations\Repositories\NotificationRepository;
use App\Implementations\Services\NotificationService;
use App\Interfaces\Repositories\INotificationRepository;
use App\Interfaces\Services\INotificationService;

use App\Implementations\Repositories\PasswordResetRepository;
use App\Interfaces\Repositories\IPasswordResetRepository;
use App\Implementations\Services\PasswordService;
use App\Interfaces\Services\IPasswordService;

// use App\Implementations\Services\ProfileService;
use App\Implementations\Repositories\ProfileRepository;
use App\Interfaces\Repositories\IProfileRepository;
// use App\Interfaces\Services\IProfileService;

use App\Implementations\Services\RoleService;
use App\Implementations\Repositories\RoleRepository;
use App\Interfaces\Repositories\IRoleRepository;
use App\Interfaces\Services\IRoleService;

use App\Implementations\Services\UserService;
use App\Implementations\Repositories\UserRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Services\IUserService;


use App\Implementations\Repositories\VerificationRepository;
use App\Implementations\Services\VerificationService;
use App\Interfaces\Repositories\IVerificationRepository;
use App\Interfaces\Services\IVerificationService;


use App\Implementations\Repositories\WalletRepository;
use App\Implementations\Services\WalletService;
use App\Interfaces\Repositories\IWalletRepository;
use App\Interfaces\Services\IWalletService;


use App\Implementations\Repositories\WalletTypeRepository;
use App\Implementations\Services\WalletTypeService;
use App\Interfaces\Repositories\IWalletTypeRepository;
use App\Interfaces\Services\IWalletTypeService;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        

        $this->app->bind(IAccountRepository::class, AccountRepository::class);
        $this->app->bind(IAccountService::class, AccountService::class);

        $this->app->bind(IAddressRepository::class, AddressRepository::class);

        $this->app->bind(IAuthService::class, AuthService::class);

        $this->app->bind(IBankRepository::class, BankRepository::class);
        $this->app->bind(IBankService::class, BankService::class);

        $this->app->bind(IBvnRepository::class, BvnRepository::class);
        // $this->app->bind(IBvnService::class, BvnService::class);

        $this->app->bind(ICountryRepository::class, CountryRepository::class);
        $this->app->bind(ICountryService::class, CountryService::class);

        $this->app->bind(IManagerRepository::class, ManagerRepository::class);
        $this->app->bind(IManagerService::class, ManagerService::class);

        $this->app->bind(INotificationRepository::class, NotificationRepository::class);
        $this->app->bind(INotificationService::class, NotificationService::class);

        $this->app->bind(IPasswordResetRepository::class, PasswordResetRepository::class);
        
        $this->app->bind(IPasswordService::class, PasswordService::class);

        $this->app->bind(IProfileRepository::class, ProfileRepository::class);
        // $this->app->bind(IProfileService::class, ProfileService::class);

        $this->app->bind(IRoleRepository::class, RoleRepository::class);
        $this->app->bind(IRoleService::class, RoleService::class);

        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IUserService::class, UserService::class);

        $this->app->bind(IVerificationRepository::class, VerificationRepository::class);
        $this->app->bind(IVerificationService::class, VerificationService::class);

        $this->app->bind(IWalletRepository::class, WalletRepository::class);
        $this->app->bind(IWalletService::class, WalletService::class);

        $this->app->bind(IWalletTypeRepository::class, WalletTypeRepository::class);
        $this->app->bind(IWalletTypeService::class, WalletTypeService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // JsonResource::withoutWrapping();
    }
}
