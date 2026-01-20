<?php
namespace Refinery;


final class FormPost {

	private $secret;


	public function __construct($secret) {

		$this->secret = $secret;
	}


	public function encode(FormContext $context) {

		$post = json_encode(['step' => $context->step, 'data' => $context->data, ]);

		$hash = hash_hmac('sha256', $post, $this->secret);

		return base64_encode(json_encode(['post' => $post, 'hash' => $hash, ]));	}


	public function decode($encoded) {

		if (! $encoded) return new FormContext();

		$decoded = json_decode(base64_decode($encoded), true);

		if (! is_array($decoded) || ! isset($decoded['post'], $decoded['hash'])) return new FormContext();

		$calc = hash_hmac('sha256', $decoded['post'], $this->secret);

		if (! hash_equals($calc, $decoded['hash'])) return new FormContext();

		$data = json_decode($decoded['post'], true);

		return new FormContext([], isset($data['step']) ? $data['step'] : 0, isset($data['data']) ? $data['data'] : []);
	}

}
