jQuery(document).ready(function($) {
	$('#lang-toggle select').change(function(e) {
		$('form#lang-toggle').submit();
	});
});