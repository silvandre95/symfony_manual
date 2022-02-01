<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Forms\WorkflowForms;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

        $blogPost->setCurrentPlace('planning');

        $entityManager->persist($blogPost);
        $entityManager->flush();

        $id = $blogPost->getId();

        return $this->redirectToRoute('workflow', ['id' => $id]);
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

        //dd($this->blogPublishingWorkflow->getEnabledTransitions($blogPost));
        //dd(array_key_last($this->blogPublishingWorkflow->getDefinition($blogPost)->getPlaces()));
        $workflowName = $this->blogPublishingWorkflow->getName($blogPost);
        $workflowPlaces = $this->blogPublishingWorkflow->getDefinition($blogPost)->getPlaces();
        $workflowInicialPlaces = $this->blogPublishingWorkflow->getDefinition($blogPost)->getInitialPlaces();
        $workflowCurrentPlace = array_key_first($blogPost->getCurrentPlace());
        $workflowEnabledTransactions = $this->blogPublishingWorkflow->getEnabledTransitions($blogPost);

        $form = $this->createForm(WorkflowForms::class, $blogPost, [
            'attr' => ['formName' => $workflowCurrentPlace],
            'action' => $this->generateUrl('workflowAction', ['id' => $id]),
            'method' => 'POST'
        ]);

        return $this->render('workflows/workflow.html.twig', [
            'workflowId' => $id,
            'workflowName' => $workflowName,
            'workflowPlaces' => $workflowPlaces,
            'workflowInicialPlaces' => $workflowInicialPlaces,
            'workflowCurrentPlace' => $workflowCurrentPlace,
            'workflowEnabledTransactions' => $workflowEnabledTransactions,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/workflows/{id}/action", name="workflowAction")
     */
    public function action(ManagerRegistry $manager, Request $request, $id)
    {
        $transaction = $request->request->get('transaction');

        $entityManager = $manager->getManager();

        $blogPost = $entityManager->getRepository(BlogPost::class)->find($id);

        $curentPlace = [$blogPost->getCurrentPlace() => 1];

        $blogPost->setCurrentPlace($curentPlace);

        if(isset($request->request->all('workflow_forms')['title']))
        {
            $blogPost->setTitle($request->request->all('workflow_forms')['title']);
        }
        if(isset($request->request->all('workflow_forms')['content']))
        {
            $blogPost->setContent($request->request->all('workflow_forms')['content']);
        }
        if(isset($request->request->all('workflow_forms')['rating']))
        {
            $blogPost->setRating($request->request->all('workflow_forms')['rating']);
        }
        if(isset($request->request->all('workflow_forms')['rejection']))
        {
            $blogPost->setRejection($request->request->all('workflow_forms')['rejection']);
        }

        try {
            $this->blogPublishingWorkflow->apply($blogPost, $transaction);
        } catch (LogicException $exception) {
            throw $exception;
        }

        $workflowName = $this->blogPublishingWorkflow->getName($blogPost);
        $workflowPlaces = $this->blogPublishingWorkflow->getDefinition($blogPost)->getPlaces();
        $workflowInicialPlaces = $this->blogPublishingWorkflow->getDefinition($blogPost)->getInitialPlaces();
        $workflowCurrentPlace = array_key_first($blogPost->getCurrentPlace());
        $workflowEnabledTransactions = $this->blogPublishingWorkflow->getEnabledTransitions($blogPost);

        $blogPost->setCurrentPlace(array_key_first($blogPost->getCurrentPlace()));

        $entityManager->persist($blogPost);
        $entityManager->flush();

        $form = $this->createForm(WorkflowForms::class, $blogPost, [
            'attr' => ['formName' => $workflowCurrentPlace],
            'action' => $this->generateUrl('workflowAction', ['id' => $id]),
            'method' => 'POST'
        ]);

        return $this->render('workflows/workflow.html.twig', [
            'workflowId' => $id,
            'workflowName' => $workflowName,
            'workflowPlaces' => $workflowPlaces,
            'workflowInicialPlaces' => $workflowInicialPlaces,
            'workflowCurrentPlace' => $workflowCurrentPlace,
            'workflowEnabledTransactions' => $workflowEnabledTransactions,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/workflows/{id}/reset", name="workflowReset")
     */
    public function reset(ManagerRegistry $manager, $id)
    {
        $entityManager = $manager->getManager();

        $blogPost = $entityManager->getRepository(BlogPost::class)->find($id);

        $blogPost->setCurrentPlace('planning');

        $entityManager->persist($blogPost);
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }
}
