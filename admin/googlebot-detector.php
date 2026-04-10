<?php
class VisitorDetector {
    public function detectVisitor() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        
        $isGoogleBotByUserAgent = $this->isGoogleBotUserAgent($userAgent);
        $result = [
            'ip' => $ipAddress,
            'user_agent' => $userAgent,
            'is_googlebot_by_user_agent' => $isGoogleBotByUserAgent,
            'is_valid_googlebot' => false,
            'type' => 'human'
        ];
        
        if ($isGoogleBotByUserAgent) {
            $isValidGoogleIP = $this->verifyGoogleBotIP($ipAddress);
            $result['is_valid_googlebot'] = $isValidGoogleIP;
            
            if ($isValidGoogleIP) {
                $result['type'] = 'googlebot';
            } else {
                $result['type'] = 'fake_googlebot';
            }
        }
        
        return $result;
    }
    
    private function isGoogleBotUserAgent($userAgent) {
        $googleBotPatterns = [
            'googlebot',
            'google-bot',
            'adsbot-google',
            'mediapartners-google',
            'chrome-lighthouse',
            'lighthouse',
            'google-read-aloud',
            'feedfetcher-google',
            'storebot-google',
            'google web preview',
            'google favicon',
            'facebookexternalhit',
            'google-speakr',
            'apis-google'
        ];
        
        foreach ($googleBotPatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    private function verifyGoogleBotIP($ip) {
        $googleIpRanges = [
            '66.249.',
            '64.233.',
            '72.14.',
            '209.85.',
            '216.239.',
            '74.125.',
            '108.177.',
            '172.217.',
            '142.250.',
            '192.178.',
            '35.235.',
            '35.192.',
        ];
        
        foreach ($googleIpRanges as $range) {
            if (strpos($ip, $range) === 0) {
                return true;
            }
        }
        
        $hostname = gethostbyaddr($ip);
        if ($hostname !== $ip) {
            if (preg_match('/(googlebot\.com|google\.com|googleusercontent\.com|googleapis\.com)$/i', $hostname)) {
                $forward_ip = gethostbyname($hostname);
                if ($forward_ip === $ip) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    public function ehGoogle() {
        $visitorInfo = $this->detectVisitor();
        return ($visitorInfo['type'] === 'googlebot');
    }
    
    public function logVisit($visitorInfo) {
        $logFile = 'access_log.txt';
        $timestamp = date('Y-m-d H:i:s');
        
        $logEntry = sprintf(
            "[%s] IP: %s | Type: %s | User-Agent: %s\n",
            $timestamp,
            $visitorInfo['ip'],
            $visitorInfo['type'],
            $visitorInfo['user_agent']
        );
        
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
}