<?php
namespace App\Core;

class ExceptionHandler
{
    private string $logFile;

    public function __construct()
    {
      $this->logFile = __DIR__ . '/../../storage/logs/error.log';

        if (!file_exists($this->logFile)) file_put_contents($this->logFile, '');

        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function handleException($exception) {
        $this->log($exception);
        $this->displayUserFriendlyMessage();
    }

    public function handleError($errno, $errstr, $errfile, $errline) {
        $this->log(new \ErrorException($errstr, 0, $errno, $errfile, $errline));
        $this->displayUserFriendlyMessage();
    }

    public function handleShutdown() {
        $error = error_get_last();
        if ($error) {
            $this->log(new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
        }
    }

    protected function log($exception) {
        $message = sprintf("[%s] %s in %s on line %s\nStack trace:\n%s\n\n",
            date('Y-m-d H:i:s'),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );
        error_log($message, 3, $this->logFile);
    }

    protected function displayUserFriendlyMessage() {
        if (php_sapi_name() === 'cli') return;
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['status'=>'error','message'=>'حدث خطأ داخلي، يرجى المحاولة لاحقاً']);
        exit;
    }
    public function getlogFile(){
        return $this->logFile;
    }
}
