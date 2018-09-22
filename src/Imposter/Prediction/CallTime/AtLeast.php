<?php
declare(strict_types=1);

namespace Imposter\Imposter\Prediction\CallTime;

/**
 * Class AtLeast
 * @package Imposter\Imposter\Prediction\CallTime
 */
class AtLeast extends AbstractCallTime
{
    /**
     * @param int $times
     */
    public function check(int $times)
    {
        if ($this->times < $times) {
            throw new \PHPUnit\Framework\ExpectationFailedException($this->getMessage($times));
        }
    }

    /**
     * @param $times
     * @return string
     */
    private function getMessage($times): string
    {
        return sprintf(
            "Expected at least %d calls that match:\n" .
            "- Method %s \n" .
            "- Path %s \n" .
            "- Body %s \n" .
            'but %d were made.',
            $this->times,
            $this->mock->getRequestMethod() ? $this->mock->getRequestMethod()->toString() : '(No data)',
            $this->mock->getRequestUriPath() ? $this->mock->getRequestUriPath()->toString() : '(No data)',
            $this->mock->getRequestBody() ? $this->mock->getRequestBody()->toString() : '(No data)',
            $times
        );
    }
}
