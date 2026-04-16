<?php

namespace Library\Framework\Logger;

class Logger
{
	private string $logFile;

	public function __construct(?string $logFile = null)
	{
		$this->logFile = $logFile ?? dirname(__DIR__, 3) . '/storage/logs/application.log';
		$this->ensureStorageExists();
	}

	public function path(): string
	{
		$this->ensureStorageExists();

		return $this->logFile;
	}

	public function log(string $level, string $message, array $context = []): bool
	{
		try {
			$this->ensureStorageExists();

			$entry = [
				'timestamp' => date('Y-m-d H:i:s'),
				'level' => strtolower($level),
				'message' => $message,
				'context' => $context,
			];

			$line = json_encode($entry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
			if ($line === false) {
				return false;
			}

			$handle = fopen($this->logFile, 'ab');
			if ($handle === false) {
				return false;
			}

			try {
				if (!flock($handle, LOCK_EX)) {
					return false;
				}

				$written = fwrite($handle, $line . PHP_EOL);
				fflush($handle);
				flock($handle, LOCK_UN);

				return $written !== false;
			} finally {
				fclose($handle);
			}
		} catch (\Throwable) {
			return false;
		}
	}

	public function debug(string $message, array $context = []): bool
	{
		return $this->log('debug', $message, $context);
	}

	public function info(string $message, array $context = []): bool
	{
		return $this->log('info', $message, $context);
	}

	public function warning(string $message, array $context = []): bool
	{
		return $this->log('warning', $message, $context);
	}

	public function error(string $message, array $context = []): bool
	{
		return $this->log('error', $message, $context);
	}

	public function all(): array
	{
		return $this->readEntries();
	}

	public function recent(int $limit = 10): array
	{
		$entries = $this->readEntries();

		if ($limit > 0) {
			$entries = array_slice($entries, -$limit);
		}

		return array_reverse($entries);
	}

	public function count(): int
	{
		return count($this->readEntries());
	}

	public function stats(): array
	{
		$stats = [
			'total' => 0,
			'debug' => 0,
			'info' => 0,
			'warning' => 0,
			'error' => 0,
		];

		foreach ($this->readEntries() as $entry) {
			$level = strtolower($entry['level'] ?? 'info');
			$stats['total']++;

			if (isset($stats[$level])) {
				$stats[$level]++;
			}
		}

		return $stats;
	}

	public function clear(): bool
	{
		try {
			$this->ensureStorageExists();

			return file_put_contents($this->logFile, '') !== false;
		} catch (\Throwable) {
			return false;
		}
	}

	private function ensureStorageExists(): void
	{
		$directory = dirname($this->logFile);

		if (!is_dir($directory)) {
			@mkdir($directory, 0775, true);
		}

		if (!file_exists($this->logFile)) {
			@touch($this->logFile);
		}
	}

	private function readEntries(): array
	{
		$this->ensureStorageExists();

		if (!is_file($this->logFile) || !is_readable($this->logFile)) {
			return [];
		}

		$lines = file($this->logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
		$entries = [];

		foreach ($lines as $line) {
			$decoded = json_decode($line, true);

			if (is_array($decoded)) {
				$entries[] = $decoded;
				continue;
			}

			$entries[] = [
				'timestamp' => null,
				'level' => 'info',
				'message' => $line,
				'context' => [],
			];
		}

		return $entries;
	}
}