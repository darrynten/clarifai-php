# clarifai-php

[Clarifai API](https://developer.clarifai.com/docs/) client for PHP

> Clarifai is an artificial intelligence company that excels in visual recognition, solving real-world problems for businesses and developers alike.

This is an unofficial PHP client for Clarifai

## Basic use

The API is rather simple, and consists of Inputs, Concepts and Models.

### Definitions

#### Inputs

You send inputs (images) to the service and it returns predictions. In addition to receiving predictions on inputs, you can also 'save' inputs and their predictions to later search against. You can also 'save' inputs with concepts to later train your own model.

#### Model

Clarifai provides many different models that 'see' the world differently. A model contains a group of concepts. A model will only see the concepts it contains.

There are times when you wish you had a model that sees the world the way you see it. The API allows you to do this. You can create your own model and train it with your own images and concepts. Once you train it to see how you would like it to see, you can then use that model to make predictions.

You do not need many images to get started. We recommend starting with 10 and adding more as needed.

#### Concepts

Concepts play an important role in creating your own models using your own concepts. Concepts also help you search for inputs.

When you add a concept to an input, you need to indicate whether the concept is present in the image or if it is not present.

## Features

This is the basic outline of the project and is a work in progress.

Checkboxes have been placed at each section, please check them off
in this readme when submitting a pull request for the features you
have covered.

- [ ] Application base

Guzzle is used for the communications

- [ ] Authentication

Access is currently handled via a Bearer token.

Authentication in this client should be handled via oauth2
You would need to initialise the client with your Client ID and Secret.

- [ ] Predict

This is the initial deliverable.

The Classes in question are `ClarifaiModels` and `ClarifaiModel`

This is an example curl request that shows a predict call. The model
name is `aaa03c23b3724a16a56b629203edc62c`

```bash
  curl -X POST \
    -H "Authorization: Bearer {access_token}" \
    -H "Content-Type: application/json" \
    -d '
    {
      "inputs": [
        {
          "data": {
            "image": {
              "url": "https://samples.clarifai.com/metro-north.jpg"
            }
          }
        }
      ]
    }'\
    https://api.clarifai.com/v2/models/aaa03c23b3724a16a56b629203edc62c/outputs
```

The response (abridged)

```js
{
  "status": {
    "code": 10000,
    "description": "Ok"
  },
  "outputs": [
    {
      "id": "ea68cac87c304b28a8046557062f34a0",
      "status": {
        "code": 10000,
        "description": "Ok"
      },
      "created_at": "2016-11-22T16:50:25Z",
      "model": {
        "name": "general-v1.3",
        "id": "aaa03c23b3724a16a56b629203edc62c",
        "created_at": "2016-03-09T17:11:39Z",
        "app_id": null,
        "output_info": {
          "message": "Show output_info with: GET /models/{model_id}/output_info",
          "type": "concept"
        },
        "model_version": {
          "id": "aa9ca48295b37401f8af92ad1af0d91d",
          "created_at": "2016-07-13T01:19:12Z",
          "status": {
            "code": 21100,
            "description": "Model trained successfully"
          }
        }
      },
      "input": {
        "id": "ea68cac87c304b28a8046557062f34a0",
        "data": {
          "image": {
            "url": "https://samples.clarifai.com/metro-north.jpg"
          }
        }
      },
      "data": {
        "concepts": [
          {
            "id": "ai_HLmqFqBf",
            "name": "train",
            "app_id": null,
            "value": 0.9989112
          },
          // and several others
          {
            "id": "ai_VSVscs9k",
            "name": "terminal",
            "app_id": null,
            "value": 0.9230834
          }
        ]
      }
    }
  ]
}
```

This can happen either with an image URL or b64 encoded data.

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

## Supported Images

* JPEG
* PNG
* TIFF
* BMP

## Caching

Because these are expensive calls some of them need to be cached.
