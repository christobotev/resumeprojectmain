<?php
namespace Docs\MainBundle\Google\Service;

use Docs\MainBundle\Google\GoogleClient;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;

class CalendarService
{
    /**
     * @var \Google_Client
     */
    protected $client;

    /**
     * Check credentials on each call to getCalendar
     *
     * @var \Google_Client
     * @return \Google_Service_Calendar
     */
    public function getCalendar(OAuthToken $token, GoogleClient $googleClient)
    {
        $this->client = $googleClient->getClient();
        $this->client->setAccessToken([
            'access_token' => $token->getAccessToken(),
            'created' => $token->getCreatedAt(),
            'expires_in' => $token->getExpiresIn()
        ]);

        // Refresh the token if it's expired.
        if ($this->client->isAccessTokenExpired()) {
            // the refresh token is sent only the first time
            // when the app wants access
            // this token should be kept in the db for the user
            // and should be refreshed when needed - not going save it for now
            $this->client->revokeToken($token->getAccessToken());
            $this->client->refreshToken($this->client->getRefreshToken());
        }

        $calendarService = new \Google_Service_Calendar($googleClient->getClient());

        return $calendarService;
    }
}