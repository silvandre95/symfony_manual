
## SYMFONY FRAMEWORK

## Symfony é uma framework de PHP que utiliza o padrão MVC

Este padrão divide a aplicação em três camadas. `Model`, `Viewer` e `Controller`. O pedido do utilizador é efetuado ao `Controller` que a envia para o `Model` e logo de seguida recebe uma resposta. Esta resposta é entregue ao `Viewer` para apresentar ao utilizador.

## Installing

Para correr o Symfony, precisamos de ter instalado na nossa máquina o `PHP` e o `Composer` (usado para instalar packages PHP).

## DOCUMENTAÇÃO

## Getting Started
> [Criar a nossa primeira página](https://symfony.com/doc/current/page_creation.html)

Para criarmos as nossas páginas, precisamos de lhes definir uma rota:
> [Routing](https://symfony.com/doc/current/routing.html)

`Controllers` é uma class onde definimos métodos e rotas:
> [Controllers](https://symfony.com/doc/current/controller.html)

`Templates` é onde colocamos o nosso código HTML
> [Templates](https://symfony.com/doc/current/templates.html)

## Security

O Symfony dispõe de um bundle de segurança bastante útil no que toca a utilizadores/autenticação.

> [Security](https://symfony.com/doc/current/security.html)

## The basics
Configurar bases de dados
> [Database](https://symfony.com/doc/current/doctrine.html)

Criar formulários
> [Forms](https://symfony.com/doc/current/forms.html)

Testar código
> [Testing with PHPUnit](https://symfony.com/doc/current/testing.html)

## Events

Fonte: https://symfony.com/doc/current/event_dispatcher.html

Os eventos são compostos por quatro elementos, `Event`, `Listener` ou `Subscriber` e `Dispatcher` e servem para os objetos interagirem entre sí. São muito usados para executar ações quando um evento é despoletado.

`Event` é uma ação que decorre em sistema.

`Listener` e `Subscriber` ambos estão à escuta dos eventos para saber quando executar novos eventos.

Diferença entre `Listener` e `Subscriber`:

* `Listener` é mais fléxivel porque os bundles conseguem ativar ou inativar cada um.

* `Subscriber` são mais fáceis de usar porque mantêm na classe o conhecimento dos eventos.


`Dispatcher` é responsável por notificar todos os listener através do nome do evento.

Para utilizarmos esta ferramenta precisamos de intalar o componente EventDispatcher.

```
composer require symfony/event-dispatcher
```

O primeiro passo é criar uma `Class`:
>App/Entity/User.php

Segundo, criar `Event`:
>App/Event/UserRegisteredEvent.php

Terceiro, criar `Listener` ou `Subscriber`
>App/Event/UserRegisteredEventSubscriber.php

Exemplo:
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

Para ver este exemplo a correr, executar comando:
 ```
 docker-compose up
 ```

 Ver exemplo [EVENTS](http://localhost:8000/events)

## Workflows

Fonte: https://symfony.com/doc/current/workflow.html

Um `Workflow` é um componente que permite definir o processo/ciclo de um objeto.


