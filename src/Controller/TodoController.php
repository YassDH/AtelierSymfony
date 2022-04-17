<?php

namespace App\Controller;

use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo')]
    public function index(RequestStack $request): Response
    {
        $session = $request->getSession();
        if(!$session->has('todos')){
            $todos =[];
            $session->set('todos',$todos);
        }
        return $this->render('todo/index.html.twig');
    }
    #[Route('/todo/add/{name}/{content}', name: 'add_todo')]
    public function addToDo(RequestStack $request,$name , $content){
        $session = $request->getSession();
        if(!$session->has('todos')){
            $todos =[
                $name => $content
            ];
            $session->set('todos',$todos);
        }else{
            $todos = $session->get('todos');
            if (isset($todos[$name])){
                $this->addFlash('error','Un ToDo du même Titre existe déjà !');
            }else{
                $todos[$name] = $content;
                $session->set('todos',$todos);
                $this->addFlash('sucess','Le ToDo a été ajouté avec succès !');
            }
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/todo/edit/{name}/{content}', name: 'edit_todo')]
    public function editToDo(RequestStack $request,$name,$content){
        $session = $request->getSession();
        if(!$session->has('todos')){
            $todos =[
                $name => $content
            ];
            $session->set('todos',$todos);
        }else{
            $todos = $session->get('todos');
            $todos[$name] = $content;
            $session->set('todos',$todos);
            $this->addFlash('sucess','Le contenu du ToDo a été modifié avec succès !');
        }
        return $this->redirectToRoute('app_todo');
    }

    #[Route('/todo/delete/{name}', name: 'delete_todo')]
    public function deleteToDo(RequestStack $request,$name){
        $session = $request->getSession();
        $todos = $session->get('todos');
        unset($todos[$name]);
        $session->set('todos',$todos);
        $this->addFlash('sucess','Le ToDo a été supprimé avec succès !');
        return $this->redirectToRoute('app_todo');
    }
}
