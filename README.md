# Tiktok Business Socialite Provider

```bash
composer require augusl/tiktok-business-socialiteprovider
```

## Installation & Basic Usage

Please see the [Base Installation Guide](https://socialiteproviders.com/usage/), then follow the provider specific instructions below.

### Add configuration to `config/services.php`

```php
'tiktok-business' => [
  'client_id' => env('TIKTOK_CLIENT_ID'),
  'client_secret' => env('TIKTOK_CLIENT_SECRET'),
  'redirect' => env('TIKTOK_REDIRECT_URI')
],
```

### Add provider event listener

Configure the package's listener to listen for `SocialiteWasCalled` events.

Add the event to your `listen[]` array in `app/Providers/EventServiceProvider`. See the [Base Installation Guide](https://socialiteproviders.com/usage/) for detailed instructions.

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        \Augusl\TiktokBusinessSocialite\TikTokBusinessSocialite::class.'@handle',
    ],
];
```

### Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('tiktok-business')->redirect();
```

# Returned User Fields

- id
- name
- email
- token

# Reference

- [TikTok Business Documentation](https://business-api.tiktok.com/portal/docs)