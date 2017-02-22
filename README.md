# clarifai-php

[Clarifai API](https://developer.clarifai.com/docs/) client for PHP

This is a fully unit tested unofficial PHP client for Clarifai

> Clarifai is an artificial intelligence company that excels in visual
recognition, solving real-world problems for businesses and developers
alike.

## Basic use

The API is rather simple, and consists of Inputs, Concepts and Models.

### Definitions

#### Inputs

You send inputs (images) to the service and it returns predictions. In
addition to receiving predictions on inputs, you can also 'save' inputs
and their predictions to later search against. You can also 'save' inputs
with concepts to later train your own model.

#### Model

Clarifai provides many different models that 'see' the world differently.
A model contains a group of concepts. A model will only see the concepts
it contains.

There are times when you wish you had a model that sees the world the way
you see it. The API allows you to do this. You can create your own model
and train it with your own images and concepts. Once you train it to see
how you would like it to see, you can then use that model to make
predictions.

You do not need many images to get started. We recommend starting with 10
and adding more as needed.

#### Concepts

Concepts play an important role in creating your own models using your
own concepts. Concepts also help you search for inputs.

When you add a concept to an input, you need to indicate whether the
concept is present in the image or if it is not present.

## Features

This is the basic outline of the project and is a work in progress.

Checkboxes have been placed at each section, please check them off
in this readme when submitting a pull request for the features you
have covered.

### Application base

* Guzzle is used for the communications
* The library has 100% test coverage
* The library supports framework-agnostic caching so you don't have to
worry about which framework your package that uses this package is going
to end up in.

A basic structure is there, and all classes have comments for the methods
they need to support

The structure is heavily inspired by [The official JS client](https://github.com/Clarifai/clarifai-javascript)

---

- [ ] Authentication

Access is currently handled via oauth2.

You would need to initialise the client with your Client ID and Secret.

---

- [ ] Predict

This is the initial deliverable.

This is a basic library usage example that uses a predict call. The model name is `aaa03c23b3724a16a56b629203edc62c`

```php

include 'vendor/autoload.php';

$clarifai = new \DarrynTen\Clarifai\Clarifai(
    CLIENT_ID,
    CLIENT_SECRET
);

$modelResult = $clarifai->getModel()->predictUrl(
    'https://samples.clarifai.com/metro-north.jpg',
    \DarrynTen\Clarifai\Repository\Model::GENERAL
);

echo json_encode($modelResult);

```

The response (abridged) would be:

```js
{
  "status":{
     "code":10000,
     "description":"Ok"
  },
  "outputs":[
     {
        "id":"db1b183a95a042d3bd873f8ca69ae2e6",
        "status":{
           "code":10000,
           "description":"Ok"
        },
        "created_at":"2017-02-14T03:18:54.548733Z",
        "model":{
           "name":"general-v1.3",
           "id":"aaa03c23b3724a16a56b629203edc62c",
           "created_at":"2016-03-09T17:11:39.608845Z",
           "app_id":null,
           "output_info":{
              "message":"Show output_info with: GET \/models\/{model_id}\/output_info",
              "type":"concept"
           },
           "model_version":{
              "id":"aa9ca48295b37401f8af92ad1af0d91d",
              "created_at":"2016-07-13T01:19:12.147644Z",
              "status":{
                 "code":21100,
                 "description":"Model trained successfully"
              }
           }
        },
        "input":{
           "id":"db1b183a95a042d3bd873f8ca69ae2e6",
           "data":{
              "image":{
                 "url":"https:\/\/samples.clarifai.com\/metro-north.jpg"
              }
           }
        },
        "data":{
           "concepts":[
              {
                 "id":"ai_HLmqFqBf",
                 "name":"\u043f\u043e\u0435\u0437\u0434",
                 "app_id":null,
                 "value":0.9989112
              },
              // and several others
              {
                 "id":"ai_VSVscs9k",
                 "name":"\u0442\u0435\u0440\u043c\u0438\u043d\u0430\u043b",
                 "app_id":null,
                 "value":0.9230834
              }
           ]
        }
     }
  ]
}
```

This can happen either with an image URL:

```php
    $modelResult = $clarifai->getModel()->predictPath(
        '/user/images/image.png',
        \DarrynTen\Clarifai\Repository\Model::GENERAL
    );
```

or b64 encoded data:

```php
    $modelResult = $clarifai->getModel()->predictEncoded(
        ENCODED_IMAGE_HASH,
        \DarrynTen\Clarifai\Repository\Model::GENERAL
    );
```
---

- [ ] Inputs

Add an input using a publicly accessible URL:

```php
    $inputResult = $clarifai->getInput()->add(
        [
            'image' => 'https://samples.clarifai.com/metro-north.jpg',
        ],
    );
```

Add an input using local path to image:

```php
    $inputResult = $clarifai->getInput()->add(
        [
            'image' => '/samples.clarifai.com/metro-north.jpg',
            'method' => 'path'
        ],
    );
```

Add an input using bytes:

```php
    $inputResult = $clarifai->getInput()->add(
        [
            'image' => ENCODED_IMAGE_HASH,
            'method' => 'base64'
        ],
    );
```

Add multiple inputs with ids:

```php
    $inputResult = $clarifai->getInput()->addMultiple(
        [
            [
                'image' => '/samples.clarifai.com/metro-north.jpg',
                'method' => 'path'
                'id' => 'id1',
            ],
            [
                'image' => 'https://samples.clarifai.com/puppy.jpeg',
                'method' => 'url'
                'id' => 'id2',
            ],
        ]
    );
```

Add inputs with concepts:

```php
    $inputResult = $clarifai->getInput()->addMultiple(
        [
            [
                'image' => '/samples.clarifai.com/metro-north.jpg',
                'concepts' => ['boscoe' => true ],
                'id' => 'id1',
            ],
            [
                'image' => '/samples.clarifai.com/puppy.jpeg',
                'concepts' => ['water' => true, 'boscoe' => true],
                'id' => 'id2',
            ],
        ]
    );
```

Add input with metadata

```php
    $inputResult = $clarifai->getInput()->add(
        [
            'image' => '/samples.clarifai.com/metro-north.jpg',
            'metadata' => ['key' => 'value', 'list' => [1, 2, 3]],
            'id' => 'id1',
        ],
    );
```

Add input with a crop:

```php
    $inputResult = $clarifai->getInput()->add(
        [
            'image' => '/samples.clarifai.com/metro-north.jpg',
            'crop' => [0.2, 0.4, 0.3, 0.6],
            'id' => 'id1',
        ],
    );
```


---

Following the delivery of a prediction mechanism, this is the roadmap
for this project.

- [ ] Train
  - [ ] Add Image with Concepts
  - [ ] Create a Model
  - [ ] Train a Model
  - [ ] Predict with a Model
- [ ] Search
  - [ ] Add Image to Search
  - [ ] Search by Concept
  - [ ] Reverse Image Search
- [ ] Public Models
  - [ ] General
  - [ ] Food
  - [ ] Travel
  - [ ] NSFW
  - [ ] Weddings
  - [ ] Colour
  - [ ] Face Detection
  - [ ] Apparel
  - [ ] Celebrity
- [ ] Applications
- [ ] Languages

## Documentation

This will mimic the documentation available on the site.
https://developer.clarifai.com/guide

Each section will have a short explaination and some example code.

- [ ] Inputs
  - [ ] Add
  - [ ] Add with Concepts
  - [ ] Add with Custom Metadata
  - [ ] Add with Crop
  - [ ] Get Inputs
  - [ ] Get Input Status
  - [ ] Update Input with Concepts
  - [ ] Delete Concepts from Input
  - [ ] Bulk Update Inputs with Concepts
  - [ ] Bulk Delete Concepts from Input List
  - [ ] Delete Input by ID
  - [ ] Delete Input List
  - [ ] Delete All Inpits
- [ ] Models
  - [ ] Create Model
  - [ ] Create Model With Concepts
  - [ ] Add Concepts to a Model
  - [ ] Remove Concept from Model
  - [ ] Update Model Name and Configuration
  - [ ] Get Models
  - [ ] Geo Model by ID
  - [ ] Get Model Output Info by ID
  - [ ] List Model Versions
  - [ ] Get Model Version by ID
  - [ ] Get Model Training Inputs
  - [ ] Get Model Training Inputs by Version
  - [ ] Delete Model
  - [ ] Delete Model Version
  - [ ] Delete All Models
  - [ ] Train Model
  - [ ] Predict With Model
  - [ ] Search Model by Name and Type
- [ ] Searches
  - [ ] Search by Predicted Concepts
  - [ ] Search by User Supplied Concept
  - [ ] Search by Custom Metadata
  - [ ] Search by Reverse Image
  - [ ] Search Match URL
  - [ ] Search by Concept and Prediction
  - [ ] Search ANDing
- [ ] Pagination
- [ ] Patching
  - [ ] Merge
  - [ ] Remove
  - [ ] Overwrite
- [ ] Batch Requests
- [ ] Languages

## Public Model IDs

- General - aaa03c23b3724a16a56b629203edc62c
- Food - bd367be194cf45149e75f01d59f77ba7
- Travel - eee28c313d69466f836ab83287a54ed9
- NSFW - e9576d86d2004ed1a38ba0cf39ecb4b1
- Weddings - c386b7a870114f4a87477c0824499348
- Colour - eeed0b6733a644cea07cf4c60f87ebb7
- Face Detection - a403429f2ddf4b49b307e318f00e528b
- Apparel - e0be3b9d6a454f0493ac3a30784001ff
- Celebrity - e466caa0619f444ab97497640cefc4dc

## Supported Images

* JPEG
* PNG
* TIFF
* BMP

## Caching

Because these are expensive calls (time and money) some of them can
benefit from being cached. All caching should be off by default and only
used if explicity set.

These run through the `darrynten/any-cache` package, and no extra config
is needed. Please ensure that any features that include caching have it
be optional and initially set to `false` to avoid unexpected behaviour.

## Contributing and Testing

There is currently 100% test coverage in the project, please ensure that
when contributing you update the tests. For more info see CONTRIBUTING.md

## Acknowledgements

* [Dmitry Semenov](https://github.com/mxnr) for jumping on board.
