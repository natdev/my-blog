<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\Comments;
use App\Entity\PostLike;
use App\Entity\User;
use App\Form\CommentsType;
use App\Repository\ArticleRepository;
use App\Repository\PostLikeRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog")
     */
    public function index()
    {

        $entityManager = $this->getDoctrine()->getManager();
        $articles= $entityManager->getRepository(Article::class)->findAll();
        return $this->render('blog/index.html.twig', [
            'articles'        => $articles
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */

    public function contact(){
        return $this->render('blog/contact.html.twig');
    }

    /**
     * @Route("/post/{id}", name="blog_show")
     */

    public function show(Article $article,Request $request,ObjectManager $manager){
        $comments = new Comments();
        $form = $this->createForm(CommentsType::class, $comments );
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $author = $form->get('author')->getData();

            $content  =  $form->get('content')->getData();
            $user = $this->getUser();
            $comments->setUser($user);
            $comments->setAuthor($author);
            $comments->setContent($content);
            $comments->setArticle($article);
            $comments->setCreatedAt(new \DateTime());
            $manager->persist($comments);
            $manager->flush();
        }

        return $this->render('/blog/show.html.twig',[
            'article' => $article,
            'form'    => $form->createView()
        ]);
    }

    /**
     * @Route("/recherche", name="blog_search")
     */

    public function search(Request $request, ArticleRepository $repo){
        $r = $request->query->get('keywords');

        $articles = $repo->findAllWithSearch($r);



        return $this->render('/blog/search.html.twig',[
            'articles' => $articles
        ]);
    }

    /**
     * @param Article $article
     * @Route("/post/{id}/like", name="post_like")
     * @param ObjectManager $manager
     * @param PostLikeRepository $postLikeRepo
     * @return Response
     */
    public function likes(Article $article, ObjectManager $manager, PostLikeRepository $postLikeRepo):Response{
        $user = $this->getUser();
        if(!$user){
            return $this->json(['code' => 403, 'message' => 'non autorisé'],403);
        }

        if($article->isLikedByUser($user)){
            $like = $postLikeRepo->findOneBy([
                'post' => $article,
                'user'    => $user
            ]);

            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like a bien été supprimé',
                'likes' => $postLikeRepo->count(['post' => $article])
            ],200);
        }

        $like = new PostLike();
        $like->setPost($article)
             ->setUser($user);

        $manager->persist($like);
        $manager->flush();


        return $this->json(['code' => 200, 'message' => 'Like bien ajouté', 'likes' => $postLikeRepo->count(['post' => $article])], 200);
    }
}
