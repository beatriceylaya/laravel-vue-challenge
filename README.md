# Coffee machine challenge
The goal of this assesment is to try to understand your coding skills, architectural decisions and the overall problem-solving abilities.

## Challenge 
The challenge is to build a simple API to interact with a coffee machine, using the Laravel framework, and a Vue interface to interact with the API.

We provided an Interface to implement and use on the API.

The API should have endpoints to allow the following actions:
- Make one Espresso
- Make one Double Espresso
- Make one Americano
- Check the status of the machine
- Fill the water container
- Fill the coffee container

The Vue interface should allow the following actions:
- Press a button to make one Espresso
- Press a button to make one Double Espresso
- Press a button to make one Americano
- Press a button to display the status
- Provide an input to fill the water container
- Provide an input to fill the coffee container
- Display any messages received from the API

## Coffee machine requirements
- The standard WaterContainer contains 2 litres but other sizes can be attached.
- The standard CoffeeContainer holds 500 grams of coffee but other sizes can be attached.
- A single espresso uses 8 grams of coffee and 24 ml of water.
- A double espresso uses 16 grams of coffee and 48 ml of water.
- A single ristretto used 8 grams of coffee and 16 ml of water.
- A single americano uses 16 grams of coffee and 148 ml of water.

## Requirements
- Return human readable error messages:
    - when at least one of the containers is empty when trying to make coffee
    - when a container will overflow if more liters/grams are added
    - any other exception occures
- You are free to use whatever you want to save the state of the coffee machine between requests.
- The naming of API endpoints and returned data is up to you.
- Use a docker container for the development.
- Please include clear instructions on how to run your solution and list any assumptions that you have made.
- If you have any questions or need any clarifications, feel free to make assumptions, but make sure to write down which assumptions you made.

## To easy? Nice to have then
- Multiple storage types for the state and an easy you to switch between them.
- Unit tests.
- A Postman collection for interacting with the API.
- A nice design for the Vue interface.

## Limitations
You MUST NOT change any of the existing methods defined in the interface except to use another namespace.
