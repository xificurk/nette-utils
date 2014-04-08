<?php

/**
 * Test: Nette\Utils\HtmlDataset
 *
 * @author     Petr Morávek <petr@pada.cz>
 */

use Nette\Utils\HtmlDataset,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function() { // standard usage
	$d = new HtmlDataset;
	$d->camelCase = 'string';
	$d->empty = '';
	$d->null = null;
	$d->true = true;
	$d->false = false;
	$d->int = 42;
	$d->float = 4.2;
	$d->list = array('one');
	$d->list[] = 2;
	$d->dict = array('a' => 'A');
	$d->dict[] = 2;
	$d->obj = new \stdClass;
	$d->obj->c = 'C';
	$d->obj->d = 2;

	Assert::false( isset($d->null) );
	Assert::same( 'data-camel-case="string" data-empty="" data-true="true" data-false="false" data-int="42" data-float="4.2" data-list=\'["one",2]\' data-dict=\'{"a":"A","0":2}\' data-obj=\'{"c":"C","d":2}\'', (string) $d );
	Assert::same( 9, count($d) );
	Assert::type( '\ArrayIterator', $d->getIterator() );

	$d->testAttr = 'test';
	Assert::true( isset($d->testAttr) );
	Assert::same( 'test', $d->testAttr );
	unset($d->testAttr);
	Assert::false( isset($d->testAttr) );
});


test(function() { // backward compatibility: array access
	$d = new HtmlDataset;

	Assert::error(function () use ($d) {
		$d['testAttr'] = 'test';
	}, E_USER_DEPRECATED, 'Array access to Html data attributes is deprecated, use $el->data->attrName instead.');

	Assert::true( isset($d->testAttr) );
	Assert::same( 'test', $d->testAttr );

	Assert::error(function () use ($d) {
		Assert::true( isset($d['testAttr']) );
	}, E_USER_DEPRECATED, 'Array access to Html data attributes is deprecated, use $el->data->attrName instead.');

	Assert::error(function () use ($d) {
		Assert::same( 'test', $d['testAttr'] );
	}, E_USER_DEPRECATED, 'Array access to Html data attributes is deprecated, use $el->data->attrName instead.');

	Assert::error(function () use ($d) {
		unset($d['testAttr']);
	}, E_USER_DEPRECATED, 'Array access to Html data attributes is deprecated, use $el->data->attrName instead.');

	Assert::false( isset($d->testAttr) );
	Assert::false( @isset($d['testAttr']) );
});


test(function() { // backward compatibility: dash-separated names
	$d = new HtmlDataset;

	Assert::error(function () use ($d) {
		$d->{'test-attr'} = 'test';
	}, E_USER_DEPRECATED, 'Access to Html data attributes via dash-separated names is deprecated, use $el->data->camelCase instead.');

	Assert::true( isset($d->testAttr) );
	Assert::same( 'test', $d->testAttr );

	Assert::error(function () use ($d) {
		Assert::true( isset($d->{'test-attr'}) );
	}, E_USER_DEPRECATED, 'Access to Html data attributes via dash-separated names is deprecated, use $el->data->camelCase instead.');

	Assert::error(function () use ($d) {
		Assert::same( 'test', $d->{'test-attr'} );
	}, E_USER_DEPRECATED, 'Access to Html data attributes via dash-separated names is deprecated, use $el->data->camelCase instead.');

	Assert::error(function () use ($d) {
		unset($d->{'test-attr'});
	}, E_USER_DEPRECATED, 'Access to Html data attributes via dash-separated names is deprecated, use $el->data->camelCase instead.');

	Assert::false( isset($d->testAttr) );
	Assert::false( @isset($d->{'test-attr'}) );
});
