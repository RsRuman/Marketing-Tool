<?php

namespace Moveon\Customer\Traits;

trait PaginationTrait
{
    /**
     * Get next page URL from shopify collection
     * @param $responseHeaders
     * @return string|null
     */
    public function getNextPageUrlFromResponseHeader($responseHeaders): ?string
    {
        $link = $responseHeaders['Link'][0];

        $pattern = '/<([^>]+)>; rel="next"/';

        preg_match($pattern, $link, $matches);

        return $matches[1] ?? null;
    }
}
