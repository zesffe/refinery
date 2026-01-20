<?php
namespace Pipelines;

use Refinery\FormContext;
use Refinery\FormPage;
use Refinery\FormResult;


class InitializeForm implements FormPage {

	public function fields(FormContext $context): array {

		return [
			'name' => ['type'  => 'text', 'label' => 'name', ],
			'email' => ['type'  => 'text', ],
			'subject' => ['type'  => 'text', 'label' => 'subject line', 'class' => 'max-width', ],
		];
	}


	public function handle(FormContext $context): FormResult {

		$post = array_map('trim', $context->post); 

		extract($post);

		$notes = [];


		if (empty($name)) $notes[] = 'At least a name is required.';

		if (empty($email)) $notes[] = 'An email address is required.';

		elseif (! filter_var($email, FILTER_VALIDATE_EMAIL)) $notes[] = 'A valid email address is required!';

		if (empty($subject) || strlen($subject) < 3) $notes[] = 'Please provide a subject line that\'s min 3 chars.';


		if ($notes) return FormResult::fail($notes);

		$context->data = compact('name', 'email', 'subject') + $context->data;

		return FormResult::pass(['Please provide the message body.']);
	}

}



class ComposeBody implements FormPage {

	public function fields(FormContext $context): array {

		return [
			'body' => ['type'  => 'textarea', 'label' => 'the content of this email', 'class' => 'max-width', ],
		];
	}


	public function handle(FormContext $context): FormResult {

		$post = array_map('trim', $context->post); 

		extract($post);

		$notes = [];


		if (empty($body) || strlen($body) < 3) $notes[] = 'Please provide content that\'s min 3 chars.';


		if ($notes) return FormResult::fail($notes);

		$context->data['body'] = $body;

		$notes = array_map(function($key, $value) {

			return strtoupper($key) . ": {$value}";

		}, array_keys((array) $context->data), (array) $context->data);

		$notes = array_merge($notes, ['---', 'This message looks great!']);

		return FormResult::pass($notes);
	}

}



class Send implements FormPage {

	public function fields(FormContext $context): array {

		return [];
	}


	public function handle(FormContext $context): FormResult {

		$message = $context->data;

		exit('<pre style="font-size:17px">' . str_replace('\r\n', "\n", json_encode($message, JSON_PRETTY_PRINT)) . '</pre>');

		return FormResult::redirect('thanksBud?_loveYou=1');
	}

}
