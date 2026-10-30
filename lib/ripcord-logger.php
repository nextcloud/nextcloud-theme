<?php

/**
 * Wraps Ripcord XML-RPC client to log all requests and responses.
 *
 * Usage:
 *   Replace: $client = ripcord::client($url);
 *   With:    $client = RipcordLogger::client($url, [
 *                'form_id'   => $form_id,
 *                'form_name' => $form_title,
 *            ]);
 *
 *   Everything else stays the same.
 */

class RipcordLogger {

	private static $log_file = __DIR__ . '/xmlrpc_requests.log';

	/**
	 * Create a logged Ripcord client wrapper.
	 *
	 * @param string $url XML-RPC endpoint URL
	 * @param array $options Accepts 'form_id', 'form_name', and any
	 *                       standard Ripcord options.
	 */
	public static function client($url, $options = []) {
		// Extract our custom context keys
		$context = [
			'form_id' => $options['form_id'] ?? null,
			'form_name' => $options['form_name'] ?? null,
			'request_uri' => $_SERVER['REQUEST_URI'] ?? null,
			'page_url' => $options['page_url'] ?? null,
		];

		// Remove custom keys before passing options to Ripcord
		unset($options['form_id'], $options['form_name'], $options['page_url']);

		//old line
		//$client = ripcord::client($url, $options);

		//new lines
		$options = array_merge([
			'output_type' => 'xml',
			'verbosity' => 'pretty',
			'escaping' => ['markup'],
			'version' => 'xmlrpc',
			'encoding' => 'utf-8',
		], $options);
		$client = ripcord::client($url, $options);
		

		return new RipcordLoggedClient($client, $url, self::$log_file, $context);
	}

	/**
	 * Set a custom log file path.
	 */
	public static function set_log_file($path) {
		self::$log_file = $path;
	}
}

class RipcordLoggedClient {

	private $client;
	private $url;
	private $log_file;
	private $context;

	public function __construct($client, $url, $log_file, $context = []) {
		$this->client = $client;
		$this->url = $url;
		$this->log_file = $log_file;
		$this->context = $context;
	}

	public function __call($method, $args) {
		$timestamp = date('Y-m-d H:i:s');
		$start = microtime(true);

		// Mask passwords in logged args
		$safe_args = $this->mask_sensitive($args);

		try {
			$response = call_user_func_array([$this->client, $method], $args);
			$duration = round((microtime(true) - $start) * 1000);
			$status = 'OK';
			$response_log = json_encode($response, JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
		} catch (\Exception $e) {
			$duration = round((microtime(true) - $start) * 1000);
			$status = 'ERROR';
			$response_log = $e->getMessage();
			$response = null;
		}

		// Truncate long responses
		if (strlen($response_log) > 2000) {
			$response_log = substr($response_log, 0, 2000) . '... [truncated]';
		}

		// Build context string
		$context_parts = [];
		if ($this->context['form_id']) {
			$context_parts[] = "Form: #{$this->context['form_id']}";
		}
		if ($this->context['form_name']) {
			$context_parts[] = "\"{$this->context['form_name']}\"";
		}
		if ($this->context['page_url']) {
			$context_parts[] = "URL: {$this->context['page_url']}";
		}
		if ($this->context['request_uri']) {
			$context_parts[] = "Request: {$this->context['request_uri']}";
		}

		$context_str = $context_parts ? implode(' | ', $context_parts) : 'No context';

		$log_entry = sprintf(
			"[%s] %s | %s | %s\n  Context:  %s\n  Request:  %s\n  Response: %s\n  Duration: %dms\n\n",
			$timestamp,
			$status,
			$method,
			$this->url,
			$context_str,
			json_encode($safe_args, JSON_UNESCAPED_SLASHES),
			$response_log,
			$duration
		);

		file_put_contents($this->log_file, $log_entry, FILE_APPEND | LOCK_EX);

		if ($status === 'ERROR') {
			throw $e;
		}

		return $response;
	}

	/**
	 * Replace password-like values with '***' for safe logging.
	 */
	private function mask_sensitive($args) {
		$masked = [];
		foreach ($args as $i => $arg) {
			if (is_string($arg) && ($i === 2 || preg_match('/pass|key|token|secret/i', (string)$arg))) {
				$masked[$i] = '***';
			} elseif (is_array($arg)) {
				$masked[$i] = $this->mask_sensitive($arg);
			} else {
				$masked[$i] = $arg;
			}
		}
		return $masked;
	}
}
