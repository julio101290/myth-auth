<?php

namespace julio101290\Auth\Authentication\Activators;

use julio101290\Auth\Entities\User;

/**
 * Interface ActivatorInterface
 */
interface ActivatorInterface
{
    /**
     * Send activation message to user
     *
     * @param User $user
     */
    public function send(?User $user = null): bool;

    /**
     * Returns the error string that should be displayed to the user.
     */
    public function error(): string;
}
