<?php

class UserAgentParser {
    
    public static function parse($userAgent = null) {
        if ($userAgent === null) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        }
        
        $result = [
            'original' => $userAgent,
            'browser' => self::getBrowser($userAgent),
            'os' => self::getOperatingSystem($userAgent),
            'device' => self::getDevice($userAgent),
            'is_mobile' => self::isMobile($userAgent),
            'is_tablet' => self::isTablet($userAgent),
            'is_desktop' => self::isDesktop($userAgent),
            'is_bot' => self::isBot($userAgent),
            'platform' => self::getPlatform($userAgent)
        ];
        
        return $result;
    }
    
    private static function getBrowser($userAgent) {
        $browsers = [
            'Edge' => '/Edge\/([0-9.]+)/',
            'Chrome' => '/Chrome\/([0-9.]+)/',
            'Firefox' => '/Firefox\/([0-9.]+)/',
            'Safari' => '/Version\/([0-9.]+).*Safari/',
            'Opera' => '/Opera\/([0-9.]+)/',
            'Internet Explorer' => '/MSIE ([0-9.]+)/',
            'Internet Explorer 11' => '/Trident.*rv:([0-9.]+)/'
        ];
        
        foreach ($browsers as $browser => $pattern) {
            if (preg_match($pattern, $userAgent, $matches)) {
                return [
                    'name' => $browser,
                    'version' => $matches[1] ?? 'Unknown'
                ];
            }
        }
        
        return ['name' => 'Unknown', 'version' => 'Unknown'];
    }
    
    private static function getOperatingSystem($userAgent) {
        $os_patterns = [
            'Windows 11' => '/Windows NT 10.0.*Build 22000/',
            'Windows 10' => '/Windows NT 10.0/',
            'Windows 8.1' => '/Windows NT 6.3/',
            'Windows 8' => '/Windows NT 6.2/',
            'Windows 7' => '/Windows NT 6.1/',
            'Windows Vista' => '/Windows NT 6.0/',
            'Windows XP' => '/Windows NT 5.1/',
            'macOS' => '/Mac OS X ([0-9._]+)/',
            'iOS' => '/iPhone OS ([0-9._]+)/',
            'Android' => '/Android ([0-9.]+)/',
            'Linux' => '/Linux/',
            'Ubuntu' => '/Ubuntu/',
            'Chrome OS' => '/CrOS/'
        ];
        
        foreach ($os_patterns as $os => $pattern) {
            if (preg_match($pattern, $userAgent, $matches)) {
                return [
                    'name' => $os,
                    'version' => $matches[1] ?? 'Unknown',
                ];
            }
        }
        
        return ['name' => 'Unknown', 'version' => 'Unknown'];
    }
    
    private static function getDevice($userAgent) {
        if (preg_match('/iPhone/', $userAgent)) {
            // Iphone
            return 2;
        } elseif (preg_match('/iPad/', $userAgent)) {
            // Ipad
            return 2;
            return 'iPad';
        } elseif (preg_match('/Android.*Mobile/', $userAgent)) {
             // Android
            return 1;
        } elseif (preg_match('/Android/', $userAgent)) {
            // Tablet Android
            return 1;
        } elseif (preg_match('/Windows Phone/', $userAgent)) {
            // 'Windows Phone'
            return 6;
        } elseif (preg_match('/Macintosh/', $userAgent)) {
            // 'Mac';
            return 4;
        } elseif (preg_match('/Windows/', $userAgent)) {
            // 'PC';
            return 3;
        } elseif (preg_match('/Linux/', $userAgent)) {
            // Linux
            return 5;
        }
        
        return 6;
    }
    
    private static function isMobile($userAgent) {
        return preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|Windows Phone/', $userAgent);
    }
    
    private static function isTablet($userAgent) {
        return preg_match('/iPad|Android(?!.*Mobile)/', $userAgent);
    }
    
    private static function isDesktop($userAgent) {
        return !self::isMobile($userAgent) && !self::isTablet($userAgent);
    }
    
    private static function isBot($userAgent) {
        $bots = [
            'Googlebot', 'Bingbot', 'Slurp', 'DuckDuckBot', 'Baiduspider',
            'YandexBot', 'facebookexternalhit', 'TwitterBot', 'LinkedInBot',
            'WhatsApp', 'Telegram', 'bot', 'crawl', 'spider'
        ];
        
        foreach ($bots as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    private static function getPlatform($userAgent) {
        if (self::isMobile($userAgent)) {
            return 1;
        } elseif (self::isTablet($userAgent)) {
            return 2;
        } else {
            return 3;
        }
    }
}

?>

