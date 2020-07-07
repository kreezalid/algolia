<p align="center">
Kreezalid is a full online solution to create your marketplace. One of its strengths
is its large number of features 
How to integrate Algolia with Kreezalid ?
</p>

## ✨ Features

- Index users database.
- updates with webhooks.

## 💡 Getting Started

First, install Algolia PHP API Client via the [composer](https://getcomposer.org/) package manager:
```bash
composer require algolia/algoliasearch-client-php
```

Then, create objects on your index:
```php
$client = Algolia\AlgoliaSearch\SearchClient::create(
  'YourApplicationID',
  'YourAdminAPIKey'
);

$index = $client->initIndex('your_index_name');

$index->saveObjects(['objectID' => 1, 'name' => 'Foo']);
```

Finally, you may begin searching a object using the `search` method:
```php
$objects = $index->search('Fo');
```

For full documentation, visit the **[Algolia PHP API Client](https://www.algolia.com/doc/api-client/getting-started/install/php/)**.

## How can you send your data to Algolia ?

Kreezalid allows you to use an API to manage your data. It allows you to use webhooks when your data has change.
This script will show you an example to know where your data is updated, to get it with API and to send it to Algolia.

First, initialize the connection with the API:
```php
require 'vendor/autoload.php';
$api = new \Kreezalid\KreezalidApi();
$api->Config->setApiKey('kreezalid-api-key');
$api->Config->setApiSecret('kreezalid-api-secret');
$api->Config->setApiUrl('kreezalid-api-url'); 
```
Now that your connexion is done, you can make requests to the API. 
**[Here](https://github.com/kreezalid/php-sdk)** is Kreezalid SDK, where you can find all to help you to use API.

For example, to get your users and to send them to Algolia :
```php
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
```

