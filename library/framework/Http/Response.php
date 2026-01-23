<?php

namespace Library\Framework\Http;

class Response
{
    protected $content;
    protected int $status = 200;
    protected $headers = [];

    /** File streaming properties */
    protected ?string $filePath = null;
    protected ?string $fileDownloadName = null;
    protected ?string $xAccelRedirect = null; // for nginx internal location mapping
    protected bool $useXSendfile = false; // for apache X-Sendfile or simila

    public function __construct($content = '', int $status = 200)
    {
        $this->content = $content;
        $this->status  = $status;
    }

    public function header($key, $value)
    {
        $this->headers[$key] = $value;
    }

    public function asJson()
    {
        $this->content = json_encode($this->content);
        $this->header('Content-Type', 'application/json');
        return $this;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    private function sendFile()
    {
        // Option 1: X-Sendfile / X-Accel-Redirect handoff (no PHP streaming)
        if ($this->useXSendfile) {
            // Apache mod_xsendfile or similar
            header('X-Sendfile: ' . $this->filePath);
            // Some setups also expect Content-Disposition/Length which we already set.
            return;
        }

        if ($this->xAccelRedirect !== null) {
            // Nginx X-Accel-Redirect: map internal path (this header value is
            // usually a location path, not filesystem path)
            header('X-Accel-Redirect: ' . $this->xAccelRedirect);
            return;
        }

        // Option 2: PHP streaming fallback (works everywhere)
        // Disable output buffering and allow long execution time
        if (ob_get_level()) {
            // Clear buffers to avoid memory bloat or header conflicts
            while (ob_get_level()) {
                ob_end_clean();
            }
        }
        set_time_limit(0);

        $fp = fopen($this->filePath, 'rb');
        if ($fp === false) {
            // Failed to open — return 500
            http_response_code(500);
            echo 'Could not open file for reading';
            return;
        }

        // Stream the file in 8KB chunks
        while (!feof($fp)) {
            echo fread($fp, 8192);
            // flush to the client
            if (function_exists('flush')) {
                flush();
            }
        }
        fclose($fp);
    }

    public function send()
    {
        http_response_code($this->status);
        foreach ($this->headers as $k => $v) {
            header("$k: $v");
        }

        // If file download was prepared, handle it
        if ($this->filePath !== null) {
            $this->sendFile();    
            return;
        }

        echo $this->content;
    }

    public function file(
        string $path,
        ?string $downloadName = null,
        ?string $contentType = null,
        string $disposition = 'attachment',
        bool $useXSendfile = false,
        ?string $xAccelRedirect = null
    ): self {
        if (!is_file($path) || !is_readable($path)) {
            $this->setStatus(404);
            $this->content = 'File not found';
            return $this;
        }

        $this->filePath = $path;
        $this->fileDownloadName = $downloadName ?? basename($path);
        $this->useXSendfile = $useXSendfile;
        $this->xAccelRedirect = $xAccelRedirect;

        $contentType = $contentType ?: 'application/octet-stream';

        $this->header('Content-Description', 'File Transfer');
        $this->header('Content-Type', $contentType);

        // Content-Disposition with RFC5987-safe filename handling for non-ASCII names
        $safeName = $this->fileDownloadName;
        $this->header('Content-Disposition', sprintf(
            '%s; filename="%s"; filename*=UTF-8\'\'%s',
            $disposition,
            str_replace('"', "'", $safeName),
            rawurlencode($safeName)
        ));

        $this->header('Content-Length', (string)filesize($path));
        $this->header('Cache-Control', 'no-cache, must-revalidate');
        $this->header('Pragma', 'public');

        return $this;
    }

    public static function redirect(string $url, int $status = 302)
    {
        $response = new self('', $status);
        $response->header('Location', $url);
        return $response;
    }
}