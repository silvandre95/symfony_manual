<?php

namespace App\Controller;

use App\Entity\BlogPost;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Workflow\WorkflowInterface;

class WorkflowController extends AbstractController
{

    private $blogPublishingWorkflow;

    // Symfony will inject the 'blog_publishing' workflow configured before
    public function __construct(WorkflowInterface $blogPublishingWorkflow)
    {
        $this->blogPublishingWorkflow = $blogPublishingWorkflow;
    }

    /**
     * @Route("/workflows")
     */
    public function index(): Response
    {

        $post = new BlogPost();

        try {
            $this->blogPublishingWorkflow->apply($post, 'to_review');
        } catch (LogicException $exception) {
            // ...
        }

        // Verificar se pode ir para to_review
        dd($this->blogPublishingWorkflow->can($post, 'to_review'));

        // Verificar quais as transições possíveis
        dd($this->blogPublishingWorkflow->getEnabledTransitions($post));


        return new Response('Workflow bem sucedido!');
    }
}