<?php

namespace App\Providers;


use App\Implementations\Repositories\AccountRepository;
use App\Interfaces\Repositories\IAccountRepository;
use App\Implementations\Services\AccountService;
use App\Interfaces\Services\IAccountService;

use App\Implementations\Repositories\AddressRepository;
use App\Interfaces\Repositories\IAddressRepository;

use App\Implementations\Repositories\AirtimeBeneficiaryRepository;
use App\Interfaces\Repositories\IAirtimeBeneficiaryRepository;

use App\Implementations\Repositories\AirtimeRepository;
use App\Interfaces\Repositories\IAirtimeRepository;
use App\Implementations\Services\AirtimeService;
use App\Interfaces\Services\IAirtimeService;


use App\Implementations\Services\AuthService;
use App\Interfaces\Services\IAuthService;

use App\Implementations\Repositories\BankBeneficiaryRepository;
use App\Interfaces\Repositories\IBankBeneficiaryRepository;
use App\Implementations\Services\BankBeneficiaryService;
use App\Interfaces\Services\IBankBeneficiaryService;

use App\Implementations\Repositories\BankRepository;
use App\Interfaces\Repositories\IBankRepository;
use App\Implementations\Services\BankService;
use App\Interfaces\Services\IBankService;

use App\Implementations\Repositories\BankTransferRepository;
use App\Interfaces\Repositories\IBankTransferRepository;

use App\Implementations\Repositories\BillRepository;
use App\Interfaces\Repositories\IBillRepository;
use App\Implementations\Services\BillService;
use App\Interfaces\Services\IBillService;

use App\Implementations\Repositories\BvnRepository;
use App\Interfaces\Repositories\IBvnRepository;
use App\Implementations\Services\BvnService;
use App\Interfaces\Services\IBvnService;

use App\Implementations\Repositories\CableBeneficiaryRepository;
use App\Interfaces\Repositories\ICableBeneficiaryRepository;

use App\Implementations\Repositories\CableRepository;
use App\Interfaces\Repositories\ICableRepository;
use App\Implementations\Services\CableService;
use App\Interfaces\Services\ICableService;

use App\Implementations\Repositories\CardRepository;
use App\Interfaces\Repositories\ICardRepository;
use App\Implementations\Services\CardService;
use App\Interfaces\Services\ICardService;

use App\Implementations\Repositories\CardTypeRepository;
use App\Interfaces\Repositories\ICardTypeRepository;
use App\Implementations\Services\CardTypeService;
use App\Interfaces\Services\ICardTypeService;

use App\Implementations\Repositories\CountryRepository;
use App\Interfaces\Repositories\ICountryRepository;
use App\Implementations\Services\CountryService;
use App\Interfaces\Services\ICountryService;

use App\Implementations\Repositories\DataBeneficiaryRepository;
use App\Interfaces\Repositories\IDataBeneficiaryRepository;

use App\Implementations\Repositories\DataRepository;
use App\Interfaces\Repositories\IDataRepository;
use App\Implementations\Services\DataService;
use App\Interfaces\Services\IDataService;

use App\Implementations\Repositories\DeviceRepository;
use App\Interfaces\Repositories\IDeviceRepository;
// use App\Implementations\Services\DeviceService;
// use App\Interfaces\Services\IDeviceService;

use App\Implementations\Repositories\DeviceTokenRepository;
use App\Interfaces\Repositories\IDeviceTokenRepository;
use App\Implementations\Services\DeviceService;
use App\Interfaces\Services\IDeviceService;

use App\Implementations\Repositories\ElectricityBeneficiaryRepository;
use App\Interfaces\Repositories\IElectricityBeneficiaryRepository;

use App\Implementations\Repositories\ElectricityRepository;
use App\Interfaces\Repositories\IElectricityRepository;
use App\Implementations\Services\ElectricityService;
use App\Interfaces\Services\IElectricityService;

use App\Implementations\Repositories\LevelRepository;
use App\Interfaces\Repositories\ILevelRepository;
// use App\Implementations\Services\LevelService;
// use App\Interfaces\Services\ILevelService;

use App\Implementations\Repositories\LogRepository;
use App\Interfaces\Repositories\ILogRepository;
// use App\Implementations\Services\LogService;
// use App\Interfaces\Services\ILogService;

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

use App\Implementations\Repositories\ProfileRepository;
use App\Interfaces\Repositories\IProfileRepository;
use App\Implementations\Services\ProfileService;
use App\Interfaces\Services\IProfileService;

use App\Implementations\Services\RegisterService;
use App\Interfaces\Services\IRegisterService;

use App\Implementations\Repositories\RegisterTokenRepository;
use App\Interfaces\Repositories\IRegisterTokenRepository;

use App\Implementations\Services\RoleService;
use App\Implementations\Repositories\RoleRepository;
use App\Interfaces\Repositories\IRoleRepository;
use App\Interfaces\Services\IRoleService;

use App\Implementations\Repositories\ServiceRepository;
use App\Interfaces\Repositories\IServiceRepository;

use App\Implementations\Repositories\ServiceUserRepository;
use App\Interfaces\Repositories\IServiceUserRepository;

use App\Implementations\Repositories\ServiceTypeRepository;
use App\Interfaces\Repositories\IServiceTypeRepository;

use App\Implementations\Services\TokenService;
use App\Implementations\Repositories\TokenRepository;
use App\Interfaces\Repositories\ITokenRepository;
use App\Interfaces\Services\ITokenService;

use App\Implementations\Services\TransactionService;
use App\Implementations\Repositories\TransactionRepository;
use App\Interfaces\Repositories\ITransactionRepository;
use App\Interfaces\Services\ITransactionService;

use App\Implementations\Services\TransferService;
use App\Interfaces\Services\ITransferService;

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

        $this->app->bind(IAirtimeRepository::class, AirtimeRepository::class);
        $this->app->bind(IAirtimeService::class, AirtimeService::class);

        $this->app->bind(IAirtimeBeneficiaryRepository::class, AirtimeBeneficiaryRepository::class);
        // $this->app->bind(IAirtimeBeneficiaryService::class, AirtimeBeneficiaryService::class);

        $this->app->bind(IAuthService::class, AuthService::class);

        $this->app->bind(IBankBeneficiaryRepository::class, BankBeneficiaryRepository::class);

        $this->app->bind(IBankRepository::class, BankRepository::class);
        $this->app->bind(IBankService::class, BankService::class);

        $this->app->bind(IBankTransferRepository::class, BankTransferRepository::class);

        $this->app->bind(IBillRepository::class, BillRepository::class);
        $this->app->bind(IBillService::class, BillService::class);

        $this->app->bind(IBvnRepository::class, BvnRepository::class);
        $this->app->bind(IBvnService::class, BvnService::class);

        $this->app->bind(ICableBeneficiaryRepository::class, CableBeneficiaryRepository::class);
        // $this->app->bind(ICableBeneficiaryService::class, CableBeneficiaryService::class);
        
        $this->app->bind(ICableRepository::class, CableRepository::class);
        $this->app->bind(ICableService::class, CableService::class);

        $this->app->bind(ICardRepository::class, CardRepository::class);
        $this->app->bind(ICardService::class, CardService::class);

        $this->app->bind(ICardTypeRepository::class, CardTypeRepository::class);
        $this->app->bind(ICardTypeService::class, CardTypeService::class);

        $this->app->bind(ICountryRepository::class, CountryRepository::class);
        $this->app->bind(ICountryService::class, CountryService::class);

        $this->app->bind(IDataBeneficiaryRepository::class, DataBeneficiaryRepository::class);
        // $this->app->bind(IDataBeneficiaryService::class, DataBeneficiaryService::class);
        
        $this->app->bind(IDataRepository::class, DataRepository::class);
        $this->app->bind(IDataService::class, DataService::class);

        $this->app->bind(IDeviceRepository::class, DeviceRepository::class);
        $this->app->bind(IDeviceService::class, DeviceService::class);

        $this->app->bind(IDeviceTokenRepository::class, DeviceTokenRepository::class);
        // $this->app->bind(IDeviceService::class, DeviceService::class);

        $this->app->bind(IElectricityBeneficiaryRepository::class, ElectricityBeneficiaryRepository::class);
        // $this->app->bind(IElectricityBeneficiaryService::class, ElectricityBeneficiaryService::class);
        
        $this->app->bind(IElectricityRepository::class, ElectricityRepository::class);
        $this->app->bind(IElectricityService::class, ElectricityService::class);

        $this->app->bind(ILevelRepository::class, LevelRepository::class);
        // $this->app->bind(ILevelService::class, LevelService::class);

        $this->app->bind(ILogRepository::class, LogRepository::class);
        // $this->app->bind(ILogService::class, LogService::class);

        $this->app->bind(IManagerRepository::class, ManagerRepository::class);
        $this->app->bind(IManagerService::class, ManagerService::class);

        $this->app->bind(INotificationRepository::class, NotificationRepository::class);
        $this->app->bind(INotificationService::class, NotificationService::class);

        $this->app->bind(IPasswordResetRepository::class, PasswordResetRepository::class);
        
        $this->app->bind(IPasswordService::class, PasswordService::class);

        $this->app->bind(IProfileRepository::class, ProfileRepository::class);
        $this->app->bind(IProfileService::class, ProfileService::class);

        $this->app->bind(IRoleRepository::class, RoleRepository::class);
        $this->app->bind(IRoleService::class, RoleService::class);
        
        $this->app->bind(IRegisterService::class, RegisterService::class);

        $this->app->bind(IRegisterTokenRepository::class, RegisterTokenRepository::class);

        $this->app->bind(IServiceRepository::class, ServiceRepository::class);

        $this->app->bind(IServiceUserRepository::class, ServiceUserRepository::class);

        $this->app->bind(IServiceTypeRepository::class, ServiceTypeRepository::class);

        $this->app->bind(ITokenRepository::class, TokenRepository::class);
        $this->app->bind(ITokenService::class, TokenService::class);

        $this->app->bind(ITransactionRepository::class, TransactionRepository::class);
        $this->app->bind(ITransactionService::class, TransactionService::class);

        $this->app->bind(ITransferService::class, TransferService::class);

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
