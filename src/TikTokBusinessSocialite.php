<?php

namespace Augusl\TiktokBusinessSocialite;

use SocialiteProviders\Manager\SocialiteWasCalled;

class TikTokBusinessSocialite
{
    public function handle(SocialiteWasCalled $socialiteWasCalled): void
    {
        $socialiteWasCalled->extendSocialite('tiktok-business', Provider::class);
    }
}