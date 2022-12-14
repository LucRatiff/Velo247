<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserEditionType;
use App\Service\Constants;
use App\Service\UserEdition;
use App\Service\UserEditionValidation;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    #[Route('/profile/{name}', name: 'user_profile')]
    public function profile(ManagerRegistry $registry, string $name): Response
    {
        $userProfile = $registry->getRepository(User::class)->findOneBy(['name' => $name]);
        
        if (!$userProfile) {
            throw $this->createNotFoundException('Ce profil n\'existe pas');
        }
        
        $ownProfile = false;
        
        if ($this->isGranted("ROLE_USER")) {
            /** @var User $user */
            $user = $this->getUser();
            $ownProfile = $userProfile->getId() == $user->getId();
        }
        
        return $this->render('profile.html.twig', [
            'own' => $ownProfile,
            'user' => $userProfile
        ]);
    }
    
    #[Route('/profile/', name: 'own_profile')]
    public function ownProfile(): Response
    {
        if (!$this->isGranted("ROLE_USER")) {
            return $this->redirectToRoute('root');
        }
        
        /** @var User $user */
        $user = $this->getUser();
        
        return $this->redirectToRoute('user_profile', ['name' => $user->getName()]);
    }
    
    #[Route('/profile/edit/{name}', name: 'edit_profile')]
    public function editProfile(Request $request, ManagerRegistry $registry,
            UserEditionValidation $u, string $name): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        if ($name != $user->getName()) {
            return $this->redirectToRoute('user_profile', ['name' => $name]);
        }
        
        $userEdition = new UserEdition();
        $form = $this->createForm(UserEditionType::class, $userEdition);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $userEdition = $form->getData();
            $result = $user->editSafely($userEdition, $u);
            
            if ($result != null) {
                return $this->renderForm('profile_edition.html.twig', [
                    'name' => $user->getName(),
                    'error' => $result,
                    'form' => $form,
                ]);
            }
            
            $manager = $registry->getManager();
            $manager->persist($user);
            $manager->flush();
            
            return $this->redirectToRoute('user_profile', ['name' => $user->getName()]);
        }
        
        return $this->renderForm('profile_edition.html.twig', [
            'name' => $user->getName(),
            'form' => $form,
        ]);
    }
    
    /**
     * 
     * @param ManagerRegistry $registry
     * @param string $name
     * @param string $category : topics, messages, galleries, gallery_messages
     * @return Response
     */
    #[Route('/profile/{name}/posts/{category}', name: 'profile_posts')]
    public function profilePosts(ManagerRegistry $registry, string $name, string $category = null): Response
    {
        $user = $registry->getRepository(User::class)->findOneBy(['name' => $name]);
        
        if (!$user) {
            throw $this->createNotFoundException();
        }
        
        $array = array();
        $title = '';
        
        switch ($category) {
            case 'topics':
                $title = 'Liste des sujets';
                $topics = $user->getTopics();
                
                foreach ($topics as $t) {
                    $subCategory = $t->getSubCategory();
                    $array[] = [
                        'id' => $t->getId(),
                        'title' => $t->getTitle(),
                        'slug' => $t->getSlug(),
                        'sub_category' => $subCategory->getName(),
                        'category' => $subCategory->getCategory()->getName(),
                        'date' => (new DateTime())->setTimestamp($t->getDate())->format(Constants::DATE_FORMAT_SLASHES_MINUTES_SENTENCE),
                        'content' => $t->getFirstMessage()->getContent()
                    ];
                }
                break;
            case 'messages':
                $title = 'Liste des messages';
                $messages = $user->getMessages();
                
                foreach ($messages as $m) {
                    $topic = $m->getTopic();
                    $subCategory = $topic->getSubCategory();
                    $array[] = [
                        'topic_title' => $topic->getTitle(),
                        'topic_id' => $topic->getId(),
                        'topic_slug' => $topic->getSlug(),
                        'sub_category' => $subCategory->getName(),
                        'category' => $subCategory->getCategory()->getName(),
                        'id' => $m->getId(),
                        'date' => (new DateTime())->setTimestamp($m->getDate())->format(Constants::DATE_FORMAT_SLASHES_MINUTES_SENTENCE),
                        'content' => $m->getContent()
                    ];
                }
                break;
            case 'galleries':
                $title = 'Liste des galleries photo';
                $galleries = $user->getGalleries();
                
                foreach ($galleries as $g) {
                    $array[] = [
                        'id' => $g->getId(),
                        'title' => $g->getName(),
                        'slug' => $g->getSlug(),
                        'date' => (new DateTime())->setTimestamp($g->getDate())->format(Constants::DATE_FORMAT_SLASHES_MINUTES_SENTENCE),
                        'description' => $t->getDescription()
                    ];
                }
                break;
            case 'gallery_messages':
                $title = 'Liste des commentaires sur les galleries photo';
                $galleryMessages = $user->getMessageGalleries();
                
                foreach ($galleryMessages as $m) {
                    $gallery = $m->getGallery();
                    $array[] = [
                        'gallery_title' => $gallery->getName(),
                        'gallery_id' => $gallery->getId(),
                        'gallery_slug' => $gallery->getSlug(),
                        'id' => $m->getId(),
                        'date' => (new DateTime())->setTimestamp($m->getDate())->format(Constants::DATE_FORMAT_SLASHES_MINUTES_SENTENCE),
                        'content' => $m->getContent()
                    ];
                }
                break;
            default:
                return $this->redirectToRoute('user_profile', ['name' => $user->getName()]);
        }
        
        return $this->render('profile_posts.html.twig', [
            'user' => $user,
            'title' => $title,
            'category' => $category,
            'datas' => $array,
        ]);
    }
}
