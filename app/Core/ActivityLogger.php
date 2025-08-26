<?php
namespace App\Core;

trait ActivityLogger
{
    private string $logFile;

    public function initLogger(string $file = null)
    {
        
        $this->logFile = $file ?? __DIR__ . '/../../storage/logs/activity.log';
        if (!file_exists($this->logFile)) {
            file_put_contents($this->logFile, ""); 
        }
    }

   
    protected function logActivity(string $action, string $details = ''): void
    {
        $user = $_SESSION['user'] ?? 'Guest';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown Agent';
        $time = date('Y-m-d H:i:s');

       
        $logEntry = json_encode([
            'time'    => $time,
            'user'    => $user,
            'agent'   => $agent,
            'action'  => $action,
            'details' => $details
        ]) . PHP_EOL;

       
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}
