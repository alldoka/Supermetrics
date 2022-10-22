<?php

declare(strict_types=1);

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

class NoopCalculator extends AbstractCalculator
{
    /** @var int total amount of all posts during a month */
    private int $postsCount = 0;
    /** @var int total amount of users active during a month */
    private int $usersCount = 0;
    /** @var array ids of users who posted during a month */
    private array $userIds = [];

    protected const UNITS = 'posts';

    /**
     * @inheritDoc
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        $userId = $postTo->getAuthorId();

        // I could use in_array($userId, $this->userIds, true), but O(1) is better than O(n)
        if (! isset($this->userIds[$userId])) {
            $this->userIds[$userId] = 0;
            $this->usersCount++;
        }

        $this->postsCount++;
    }

    /**
     * @inheritDoc
     */
    protected function doCalculate(): StatisticsTo
    {
        $value = $this->usersCount ? $this->postsCount / $this->usersCount : 0;

        return (new StatisticsTo())->setValue($value);
    }

}
