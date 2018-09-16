<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/16/18
 * Time: 5:00 PM
 */

namespace Imposter\Imposter\Prediction\CallTime;


class AtLeast extends AbstractCallTime
{
    public function check($times)
    {
        if ($this->times < $times) {
            throw new \PHPUnit\Framework\ExpectationFailedException($this->getMessage($times));
        }

    }

    private function getMessage($times)
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