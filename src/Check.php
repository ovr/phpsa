<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

/**
 * Statuses for check(s)
 *
 * We dont need to show ALPHA and BETA checks by default
 */
final class Check
{
    const CHECK_SAFE = 2;

    const CHECK_ALPHA = 4;

    const CHECK_BETA = 8;
}
