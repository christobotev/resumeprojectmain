<?php
namespace Docs\MainBundle\RestClient;

use Docs\CommonBundle\RestClient\Cache\CacheClient;

/**
 * Docs doctors rest client
 */
class DocsUser extends CacheClient
{
    const CACHE_KEY = "USER_";
    const USERS_LIST_KEY = "USERS_LIST_KEY";
}
