<?php
require 'vendor/autoload.php';

if (
    !empty($_POST)
    && ($_POST['type'] == 'user.creation' || $_POST['type'] == 'user.update')
) {

    $api = new \Kreezalid\KreezalidApi();
    $api->Config->setApiKey('kreezalid-api-key');
    $api->Config->setApiSecret('kreezalid-api-secret');
    $api->Config->setApiUrl('kreezalid-api-url');

    try {
        $user = $api->Users->get($_POST['data']['id'], [
            'fields' => 'id,public_name,avatar,attributes,group_id,profile_url',
        ]);

        if (is_object($user) && isset($user->id)) {

            $client = Algolia\AlgoliaSearch\SearchClient::create(
                'algolia-app-id',
                'algolia-app-key'
            );

            $index = $client->initIndex('algolia-index');
            $res = $index->saveObject((array)$user, ['objectIDKey' => 'id']);
        }
    } catch (\Kreezalid\Librairies\Exception $e) {
        die($e);
    }
}

// TODO: user delete