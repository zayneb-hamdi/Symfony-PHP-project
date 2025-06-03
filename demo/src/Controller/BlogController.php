<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Article; 
use App\Repository\ArticleRepository; 
use Doctrine\Persistence\ManagerRegistry; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\ArticleType;

final class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(ArticleRepository $repo): Response
    {
        $articles=$repo->findAll();

        return $this->render('blog/index.html.twig', [
           "articles"=>$articles,
        ]);
    }

    #[Route('/home',name:"home")]
    public function home()
    {
       return $this->render('blog/home.html.twig',['x'=>20]);
    }
  
    #[Route('/blog/new', name: 'new_form') ]     
    public function new(Request $request,EntityManagerInterface $entityManager): Response 
{ 

           $article = new Article();    
           $article->setCreatedat(new 
\DateTimeImmutable('tomorrow')); 
           $form = $this->createFormBuilder($article) 
         ->add('title', TextType::class) 
         ->add('image',TextType::class) 
         ->add('content', TextType::class) 
   ->add('save', SubmitType::class, ['label' => 'Create Article']) 
   ->getForm(); 
   $form->handleRequest($request); 
   if ($form->isSubmitted() && $form->isValid()) { 
   // $form->getData() holds the submitted values 
   // but, the original `$article` variable has also been updated 
   $article = $form->getData(); 
   $entityManager->persist($article);
   $entityManager->flush(); 
// ... perform some action, such as saving the article to the database   
return $this->redirectToRoute('app_blog'); 
} 
return $this->render('/blog/create.html.twig', ['form' => $form,]); 
} 

    #[Route('/blog/{id}',name:'blog_show')]
    public function article($id,ArticleRepository $repo):Response
    {
        $article=$repo->find($id);
       return  $this->render('/blog/show.html.twig',['article'=>$article]);
    }
    
    #[Route('/code',name:'code')]
    public function code():Response
    {
        
       return  $this->render('/blog/code.html.twig');
    }
    

 

    
}
