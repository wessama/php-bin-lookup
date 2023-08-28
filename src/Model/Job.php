<?php

namespace WessamA\BinLookup\Model;

use DateTime;

/**
 * Class Job
 * Represents a job entity.
 */
class Job extends BaseModel
{
    /**
     * @var array|string[]
     */
    protected static array $guarded = [
        'id',
    ];

    private string $jobName;

    private string $status;

    private DateTime $createdAt;

    private ?DateTime $completedAt;

    public function __construct(
        int $jobId,
        string $jobName,
        string $status,
        DateTime $createdAt,
        DateTime $completedAt = null
    ) {
        $this->setJobId($jobId);
        $this->setJobName($jobName);
        $this->setStatus($status);
        $this->setCreatedAt($createdAt);
        $this->setCompletedAt($completedAt);
    }

    public function getJobName(): string
    {
        return $this->jobName;
    }

    public function setJobName(string $jobName): Job
    {
        $this->jobName = $jobName;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): Job
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Job
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCompletedAt(): ?DateTime
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?DateTime $completedAt): Job
    {
        $this->completedAt = $completedAt;

        return $this;
    }
}
