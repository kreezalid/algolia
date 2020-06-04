<?php

require 'vendor/autoload.php';

$api = new \Kreezalid\KreezalidApi();

// Kreezalid API credentials
$api->Config->setApiKey('kreezalid-api-key');
$api->Config->setApiSecret('kreezalid-api-secret');
$api->Config->setApiUrl('kreezalid-api-url');

$users = $api->Users->all([
    'fields' => 'id,public_name,avatar,attributes,group_id,profile_url'
]);

$client = Algolia\AlgoliaSearch\SearchClient::create(
    'algolia-app-id',
    'algolia-app-key'
);

$index = $client->initIndex('algolia-index');
$index->setSettings([
    'customRanking' => ['desc(created_at)'],
    'searchableAttributes' => [
        'group_name',
        'public_name',
        'attributes'
    ],
    'attributesForFaceting' => [
        'attributes'
    ]
]);

// Always clear entries to remove deleted objects
$index->clearObjects();

if(isset($users['users'])) {
    $index->saveObjects($users['users'], ['objectIDKey' => 'id']);
}
