
## SYMFONY MANUAL

This project is a Symfony manual with some theory and examples of code.

## Installing

To run symfony, we must have `PHP` and `Composer` (used to install PHP packages) installed.

Run project:
```
 docker-compose up
 ```

## Microservices
* [EVENTS](http://localhost:8000/events)

* [WORKFLOWS](http://localhost:8001/workflows)

___

## Symfony is a PHP framework with MVC pattern

The `Model-View-Controller (MVC)` framework is an architectural pattern that separates an application into three main logical components `Model, View, and Controller`.

### ``Model``
The model component stores data and its related logic. It represents data that is being transferred between controller components or any other related business logic.

### ``View``
A View is that part of the application that represents the presentation of data. It is the frontend.

### ``Controller``
The Controller is that part of the application that handles the user interaction. The controller interprets the mouse and keyboard inputs from the user, informing model and the view to change as appropriate.

___
## DOCUMENTAÇÃO

## The basics
Our first page
> [Criar a nossa primeira página](https://symfony.com/doc/current/page_creation.html)

To create our first pages, we must configure a route:
> [Routing](https://symfony.com/doc/current/routing.html)

`Controllers` it's a class where we define methods for our routes:
> [Controllers](https://symfony.com/doc/current/controller.html)

`Templates` it's where we put ou HTML code.
> [Templates](https://symfony.com/doc/current/templates.html)

## Security
Symfony uses a secutiry bundle very usefull for authentication.

> [Security](https://symfony.com/doc/current/security.html)

## Database
Install and config database settings
> [Database](https://symfony.com/doc/current/doctrine.html)

Create forms
> [Forms](https://symfony.com/doc/current/forms.html)

Testing our code
> [Testing with PHPUnit](https://symfony.com/doc/current/testing.html)

## Events

Source: https://symfony.com/doc/current/event_dispatcher.html

The events are made up of four elements, `Event`, `Listener` or `Subscriber` and `Dispatcher`. Those are used to interact with objects.

`Event` is an action that happens in our system.

`Listener` and `Subscriber` are listening to events to know when to execute something.

Difference between `Listener` and `Subscriber`:

* `Listener` are more flexible because bundles can enable or disable each of them conditionally depending on some configuration value.

* `Subscriber` are easier to reuse because the knowledge of the events is kept in the class rather than in the service definition. This is the reason why Symfony uses subscribers internally.

`Dispatcher` is responsible to notify the listener by the event name.

To use this bundle we have to intall the `event-dispatcher`.

```
composer require symfony/event-dispatcher
```

First step is to create a `Class`:
>App/Entity/User.php

Second, create `Event`:
>App/Event/UserRegisteredEvent.php

Third, create `Listener` ou `Subscriber`
>App/Event/UserRegisteredEventSubscriber.php

Example:
```php
public function index(EventDispatcherInterface $dispatcher): Response
    {

        // User
        $user = new User('John', 25);

        // Event
        $event = new UserRegisteredEvent($user);

        // Dispatch
        $dispatcher->dispatch($event, UserRegisteredEvent::EVENT_NAME);

        return new Response('Evento bem sucedido!');
    }
```



Check out the result [EVENTS](http://localhost:8000/events)

## Workflows

Source: https://symfony.com/doc/current/workflow.html

`Workflow` is a component that allow us to define a process/cicle of an object.


