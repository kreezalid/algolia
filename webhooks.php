<?php
require 'vendor/autoload.php';

if (empty($_POST)){
    die('No posted data');
}

// We need Algolia client to update Algolia Index
$client = Algolia\AlgoliaSearch\SearchClient::create(
    'algolia-app-id',
    'algolia-app-key'
);

try {
    if ($_POST['type'] === 'user.creation' || $_POST['type'] === 'user.update') {

        // Let's fetch the fresh data from Kreezalid API to have the most recent data
        $api = new \Kreezalid\KreezalidApi();
        $api->Config->setApiKey('kreezalid-api-key');
        $api->Config->setApiSecret('kreezalid-api-secret');
        $api->Config->setApiUrl('kreezalid-api-url');

        $user = $api->Users->get($_POST['data']['id'], [
            // Select only relevant fields
            'fields' => 'id,public_name,avatar,attributes,group_id,profile_url'
        ]);

        if (is_object($user) && isset($user->id)) {
            /**
             * Use Algolia methods to update the index
             * https://www.algolia.com/doc/api-reference/api-methods/save-objects/
             */
            $index = $client->initIndex('algolia-index');
            // $user is an object. Algolia works only with Arrays
            $res = $index->saveObject((array)$user, ['objectIDKey' => 'id']);
        }

    } else if ($_POST['type'] === 'user.delete') {
        /**
         * Use Algolia methods to delete the object
         * https://www.algolia.com/doc/api-reference/api-methods/delete-objects/
         */
        $index = $client->initIndex('algolia-index');
        $res = $index->deleteObject($_POST['data']['id']);
    }
} catch (\Kreezalid\Librairies\Exception $e) {
    die($e);
}

