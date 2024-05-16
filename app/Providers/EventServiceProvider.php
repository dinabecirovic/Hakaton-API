<?php

namespace App\Providers;

use App\Events\ArtAssetDeleted;
use App\Events\ArtAssetCreated;
use App\Events\FileBeforeDeleted;
use App\Events\FileCreated;
use App\Events\FileUpdated;
use App\Events\FilePermissionsChanged;
use App\Events\FileShared;
use App\Events\Logout;
use App\Events\TokenBeforeDeleted;
use App\Events\TokenCreated;
use App\Events\TokenSSHKeyCreated;
use App\Events\UserBeforeDelete;
use App\Events\UserCreated;
use App\Events\UserProfileUpdated;
use App\Events\UserFirstSubscription;
use App\Events\UserEmailUpdated;
use App\Events\UserSubscribed;
use App\Events\FileCreatedAndLinked;
use App\Listeners\AssignUUIDToUser;
use App\Listeners\BroadcastFileChange;
use App\Listeners\CreateDeletedActivity;
use App\Listeners\CreateFileCreatedActivity;
use App\Listeners\CreateLinkedUserAccounts;
use App\Listeners\CreateSystemToken;
use App\Listeners\CreateSharedActivity;
use App\Listeners\CreateAssetActivity;
use App\Listeners\CreateTopLevelFolder;
use App\Listeners\DeleteLinkedUserAccounts;
use App\Listeners\PluginTokenAddSSHKey;
use App\Listeners\InvalidateSSHKey;
use App\Listeners\RemoveFileLinkedData;
use App\Listeners\RemoveMauticDoNotContact;
use App\Listeners\RemoveUserFiles;
use App\Listeners\SendProjectSharedEmail;
use App\Listeners\SendWelcomeEmail;
use App\Listeners\SetupLinkedData;
use App\Listeners\SyncMauticUser;
use App\Listeners\SyncMauticUserIfEntityCreated;
use App\Listeners\UpdateLinkedDataPermissions;
use App\Listeners\UpdateAppTokens;
use App\Listeners\SyncCDNPermissionsForUser;
use App\Listeners\UpdateWoocommerceUser;
use App\Listeners\QuickStartAssetCreated;
use App\Listeners\QuickStartFileCreated;
use App\Listeners\QuickStartFileUpdated;
use App\Listeners\QuickStartFileShared;
use App\Listeners\CreateDemoProjects;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Logout::class => [
            SyncCDNPermissionsForUser::class,
        ],
        UserCreated::class => [
            AssignUUIDToUser::class,
            CreateSystemToken::class,
            CreateTopLevelFolder::class,
            CreateLinkedUserAccounts::class,
            CreateDemoProjects::class,
        ],
        UserProfileUpdated::class => [
            UpdateWoocommerceUser::class,
            SyncMauticUser::class,
        ],
        UserEmailUpdated::class => [
            RemoveMauticDoNotContact::class,
        ],
        UserBeforeDelete::class => [
            RemoveUserFiles::class,
            DeleteLinkedUserAccounts::class,
        ],
        FileBeforeDeleted::class => [
            RemoveFileLinkedData::class,
            CreateDeletedActivity::class,
        ],
        FileCreated::class => [
            SetupLinkedData::class,
            SyncMauticUserIfEntityCreated::class,
            QuickStartFileCreated::class,
        ],
        FileCreatedAndLinked::class => [
            CreateFileCreatedActivity::class,
        ],
        FileUpdated::class => [
            QuickStartFileUpdated::class,
            BroadcastFileChange::class,
        ],
        FilePermissionsChanged::class => [
            UpdateLinkedDataPermissions::class,
            UpdateAppTokens::class,
        ],
        TokenBeforeDeleted::class => [
            InvalidateSSHKey::class
        ],
        TokenCreated::class => [
            SyncCDNPermissionsForUser::class,
        ],
        TokenSSHKeyCreated::class => [
            PluginTokenAddSSHKey::class,
        ],
        FileShared::class => [
            SendProjectSharedEmail::class,
            CreateSharedActivity::class,
            QuickStartFileShared::class
        ],
        ArtAssetCreated::class => [
            SyncMauticUserIfEntityCreated::class,
            CreateAssetActivity::class,
            QuickStartAssetCreated::class,
            BroadcastFileChange::class,
        ],
        ArtAssetDeleted::class => [
            BroadcastFileChange::class,
        ],
        UserFirstSubscription::class => [
            SendWelcomeEmail::class
        ],
        UserSubscribed::class => [
            SyncMauticUser::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
