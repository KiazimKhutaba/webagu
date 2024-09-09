<?php

namespace App\Common;

use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Device;
use Sinergi\BrowserDetector\Os;

class BrowserInfo
{
    public function __construct(
        private readonly Browser $browser,
        private readonly Os $os,
        private readonly Device $device
    )
    {
    }


    /**
     * @throws \Exception
     */
    public function getInfo(): array
    {
        $platform = "{$this->os->getName()} {$this->os->getVersion()}, {$this->browser->getName()} {$this->browser->getVersion()}";
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"] ?? self::getIp();

        $currentDateTime = new \DateTime('now', new \DateTimeZone('Europe/Moscow'));

        return [
            //'device_name' => $this->device->getUserAgent()->getUserAgentString(),
            'os' => $platform,
            'ip' => $ip,
            'referer' => $_SERVER['HTTP_REFERER'] ?? '',
            'requested_url' => $_SERVER['REQUEST_URI'] ?: '',
            'created_at' => $currentDateTime->format(DATE_W3C)
        ];
    }


    public function getIp(): string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
