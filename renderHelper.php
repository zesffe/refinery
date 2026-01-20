<?php


function renderNotes(array $notes = []) {

	if (! $notes) return null;

	return implode("\n", [
		'<ul>',
		implode(' ', array_map(function($note) {return '<li>' . htmlspecialchars($note) . '</li>'; }, $notes)),
		'</ul>',
	]);
}


function renderFields(array $fields, array $values = [], $postValues = null) {

	$html = [];

	foreach ($fields as $key => $field) {

		$type = $field['type'] ?? 'text';

		$label = $field['label'] ?? $key;

		$class = $field['class'] ?? null;

		$value = $values[$key] ?? null;

		switch ($type){

			case 'textarea':

				$rows = $values['rows'] ?? 8;

				$html[] = "<textarea name=\"{$key}\" class=\"{$class}\" rows=\"{$rows}\" placeholder=\"{$label}\">{$value}</textarea>";

				break;

			default:

				$html[] = "<input type=\"{$type}\" name=\"{$key}\" value=\"{$value}\" class=\"{$class}\" placeholder=\"{$label}\"/>";
		}
	}

	if ($postValues) $html[] = "<input type=\"hidden\" name=\"_postpack\" value=\"{$postValues}\"/>";

	return implode("\n", $html);
}
