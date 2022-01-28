<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/workflows", name="index")
     */
    public function index(ManagerRegistry $manager): Response
    {
        $workflows = $manager->getManager()->getRepository(BlogPost::class)->findAll();

        return $this->render('workflows/index.html.twig', [
            'workflows' => $workflows,
        ]);
    }

    /**
     * @Route("/workflows/add", name="workflowsAdd")
     */
    public function add(ManagerRegistry $manager): Response
    {
        $entityManager = $manager->getManager();

        $blogPost = new BlogPost();

        $blogPost->setCurrentPlace('draft');
        $blogPost->setTitle('Blog Publishing Workflow');
        $blogPost->setContent('Blog Publishing Workflow');

        $entityManager->persist($blogPost);
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/workflows/{id}", name="workflow")
     */
    public function workflow($id, ManagerRegistry $manager): Response
    {
        $entityManager = $manager->getManager();

        $blogPost = $entityManager->getRepository(BlogPost::class)->find($id);

        $curentPlace = [$blogPost->getCurrentPlace() => 1];

        $blogPost->setCurrentPlace($curentPlace);

        $workflowName = $this->blogPublishingWorkflow->getName($blogPost);
        $workflowPlaces = $this->blogPublishingWorkflow->getDefinition($blogPost)->getPlaces();
        $workflowInicialPlaces = $this->blogPublishingWorkflow->getDefinition($blogPost)->getInitialPlaces();
        $workflowCurrentPlace = array_key_first($blogPost->getCurrentPlace());
        $workflowEnabledTransactions = $this->blogPublishingWorkflow->getEnabledTransitions($blogPost);

        return $this->render('workflows/workflow.html.twig', [
            'workflowId' => $id,
            'workflowName' => $workflowName,
            'workflowPlaces' => $workflowPlaces,
            'workflowInicialPlaces' => $workflowInicialPlaces,
            'workflowCurrentPlace' => $workflowCurrentPlace,
            'workflowEnabledTransactions' => $workflowEnabledTransactions
        ]);
    }

    /**
     * @Route("/workflows/{id}/action/{transaction}", name="workflowAction")
     */
    public function action(ManagerRegistry $manager, $id, $transaction)
    {
        $entityManager = $manager->getManager();

        $blogPost = $entityManager->getRepository(BlogPost::class)->find($id);

        $curentPlace = [$blogPost->getCurrentPlace() => 1];

        $blogPost->setCurrentPlace($curentPlace);

        try {
            $this->blogPublishingWorkflow->apply($blogPost, $transaction);
        } catch (LogicException $exception) {
            throw new LogicException($exception);
        }

        $workflowName = $this->blogPublishingWorkflow->getName($blogPost);
        $workflowPlaces = $this->blogPublishingWorkflow->getDefinition($blogPost)->getPlaces();
        $workflowInicialPlaces = $this->blogPublishingWorkflow->getDefinition($blogPost)->getInitialPlaces();
        $workflowCurrentPlace = array_key_first($blogPost->getCurrentPlace());
        $workflowEnabledTransactions = $this->blogPublishingWorkflow->getEnabledTransitions($blogPost);

        $blogPost->setCurrentPlace(array_key_first($blogPost->getCurrentPlace()));

        $entityManager->persist($blogPost);
        $entityManager->flush();

        return $this->render('workflows/workflow.html.twig', [
            'workflowId' => $id,
            'workflowName' => $workflowName,
            'workflowPlaces' => $workflowPlaces,
            'workflowInicialPlaces' => $workflowInicialPlaces,
            'workflowCurrentPlace' => $workflowCurrentPlace,
            'workflowEnabledTransactions' => $workflowEnabledTransactions
        ]);
    }

    /**
     * @Route("/workflows/{id}/reset", name="workflowReset")
     */
    public function reset(ManagerRegistry $manager, $id)
    {
        $entityManager = $manager->getManager();

        $blogPost = $entityManager->getRepository(BlogPost::class)->find($id);

        $blogPost->setCurrentPlace('draft');

        $entityManager->persist($blogPost);
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }
}
