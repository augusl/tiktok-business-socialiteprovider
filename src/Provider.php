<?php

namespace Augusl\TiktokBusinessSocialite;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends  AbstractProvider
{
    /**
     * The base TikTok Business API URL.
     *
     * @var string
     */
    protected string $businessUrl = 'https://business-api.tiktok.com';

    /**
     * The TikTok Business API version for the request.
     *
     * @var string
     */
    protected string $version = 'v1.3';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase("{$this->businessUrl}/portal/auth", $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl(): string
    {
        return "{$this->businessUrl}/open_api/{$this->version}/oauth2/access_token";
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get("{$this->businessUrl}/open_api/{$this->version}/user/info", [
            RequestOptions::HEADERS => ['Access-Token' => $token],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->map([
            'id' => Arr::get($user, 'data.core_user_id'),
            'name' => Arr::get($user, 'data.display_name'),
            'email' => Arr::get($user, 'data.email'),
            'token' => Arr::get($user, 'token'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function userInstance(array $response, array $user)
    {
        $this->user = $this->mapUserToObject($user);

        return $this->user->setToken(Arr::get($response, 'access_token'));
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            RequestOptions::HEADERS => $this->getTokenHeaders($code),
            RequestOptions::JSON => $this->getTokenFields($code),
        ]);

        return Arr::get(json_decode($response->getBody(), true), 'data');
    }

    /**
     * {@inheritdoc}
     */
    protected function getCode()
    {
        return $this->request->input('auth_code');
    }

    /**
     * {@inheritdoc}
     */
    protected function getCodeFields($state = null): array
    {
        $fields = parent::getCodeFields($state);

        $fields['app_id'] = $this->clientId;

        return Arr::only($fields, ['app_id', 'state', 'redirect_uri']);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code): array
    {
        return [
            'auth_code' => $code,
            'app_id' => $this->clientId,
            'secret' => $this->clientSecret,
        ];
    }
}