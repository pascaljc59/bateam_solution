<?php

namespace App\Controller;

use App\Form\NomFilmType;
use App\Form\RealisateurFilmType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MoviesController extends AbstractController
{

    private $obj_json;

    public function __construct(){
        $file = '../public/json/movies.json';
        $data = file_get_contents($file);
        $this->obj_json = json_decode($data);
    }

    function my_sort_nb_diffusion($a,$b){

        if($a->nb_diffusion == $b->nb_diffusion){
            return 0;
        }

        return ($a->nb_diffusion<$b->nb_diffusion)?-1:1;

    }

    function my_sort_ratio($a,$b){

        if(($a->nb_premiere_partie/$a->nb_diffusion) == ($b->nb_premiere_partie/$b->nb_diffusion)){
            return 0;
        }

        return (($a->nb_premiere_partie/$a->nb_diffusion)<($b->nb_premiere_partie/$b->nb_diffusion))?-1:1;

    }

    function my_sort_moyenne_diffusion($a,$b){

        if($a->moyenne_diffusion_par_an == $b->moyenne_diffusion_par_an){
            return 0;
        }

        return ($a->moyenne_diffusion_par_an<$b->moyenne_diffusion_par_an)?-1:1;
    }


    //Route permettant de lister l'ensemble des films
    /**
     * @Route("/movies", name="movies")
     */
    public function index(Request $request): Response
    {

        $array_nationalite = [];
        $top_5_diffusion = [];
        $top_5_ratio = [];
        $top_5_moyenne_diffusion = [];
        foreach ($this->obj_json as $movie) {
            if(!in_array($movie->nationalite, $array_nationalite)){
                $array_nationalite[] = $movie->nationalite;
            }
        }

        usort($this->obj_json, array($this,'my_sort_nb_diffusion'));
        $top_5 = false;
        foreach(array_reverse($this->obj_json) as $movie){
            if(count($top_5_diffusion) < 5){
                $top_5_diffusion[] = $movie;
                if(count($top_5_diffusion) == 5){
                    $top_5 = true;
                }
            }else if($top_5 && $top_5_diffusion[4]->nb_diffusion == $movie->nb_diffusion){
                $top_5_diffusion[] = $movie;
            }
        }

        usort($this->obj_json, array($this,'my_sort_ratio'));
        $top_5 = false;
        foreach(array_reverse($this->obj_json) as $movie){
            if(count($top_5_ratio) < 5){
                $top_5_ratio[] = $movie;
                if(count($top_5_ratio) == 5){
                    $top_5 = true;
                }
            }else if(
                $top_5 &&
                ($top_5_ratio[4]->nb_premiere_partie/$top_5_ratio[4]->nb_diffusion) == ($movie->nb_premiere_partie/$movie->nb_diffusion)
                ){
                $top_5_ratio[] = $movie;
            }
        }

        usort($this->obj_json, array($this,'my_sort_moyenne_diffusion'));
        $top_5 = false;
        foreach(array_reverse($this->obj_json) as $movie){
            if(count($top_5_moyenne_diffusion) < 5){
                $top_5_moyenne_diffusion[] = $movie;
                if(count($top_5_moyenne_diffusion) == 5){
                    $top_5 = true;
                }
            }else if($top_5 && $top_5_moyenne_diffusion[4]->moyenne_diffusion_par_an == $movie->moyenne_diffusion_par_an){
                $top_5_moyenne_diffusion[] = $movie;
            }
        }

        $form_nom = $this->createForm(NomFilmType::class);
        $form_nom->handleRequest($request);
        if($form_nom->isSubmitted() && $form_nom->isValid()){
            return $this->redirectToRoute('movies_nom', [
                'nom' => $form_nom->get('nom')->getData()
            ]);
        }
        $form_realisateur = $this->createForm(RealisateurFilmType::class);
        $form_realisateur->handleRequest($request);
        if($form_realisateur->isSubmitted() && $form_realisateur->isValid()){
            return $this->redirectToRoute('movies_realisateur', [
                'realisateur' => $form_realisateur->get('realisateur')->getData()
            ]);
        }
        return $this->render('movies/index.html.twig', [
            'controller_name' => 'MoviesController',
            'movies' => $this->obj_json,
            'form_nom' => $form_nom->createView(),
            'form_realisateur' => $form_realisateur->createView(),
            'nationalites' => $array_nationalite,
            'top_5_diffusion' => $top_5_diffusion,
            'top_5_ratio' => $top_5_ratio,
            'top_5_moyenne_diffusion' => $top_5_moyenne_diffusion
        ]);
    }

    //Route permettant de filtrer sur le nom d'un film
    /**
     * @Route("/movies_nom/{nom}", name="movies_nom")
     */
    public function movies_nom(string $nom): Response
    {

        $search = strtolower($nom);
        $array_movies = [];
        foreach ($this->obj_json as $movie) {
            if(preg_match('#^[a-zA-Z0-9]*$#', $search) && preg_match("/{$search}/i", strtolower($movie->nom))){
                $array_movies[] = $movie;
            }else{
                return $this->redirectToRoute('movies');
            }
        }

        return $this->render('movies/movies_nom.html.twig', [
            'controller_name' => 'MoviesController',
            'movies' => $array_movies
        ]);
    }

    //Route permettant de filtrer sur le réalisateur d'un film
    /**
     * @Route("/movies_realisateur/{realisateur}", name="movies_realisateur")
     */
    public function movies_realisateur(string $realisateur): Response
    {

        $search = strtolower($realisateur);
        $array_movies = [];
        foreach ($this->obj_json as $movie) {
            if(preg_match('#^[a-zA-Z0-9]*$#', $search) && preg_match("/{$search}/i", strtolower($movie->realisateur))){
                $array_movies[] = $movie;
            }else{
                return $this->redirectToRoute('movies');
            }
        }

        return $this->render('movies/movies_realisateur.html.twig', [
            'controller_name' => 'MoviesController',
            'movies' => $array_movies
        ]);
    }

    //Route permettant de filtrer sur le réalisateur d'un film
    /**
     * @Route("/movies_nationalite/{nationalite}", name="movies_nationalite", methods={"GET", "POST"})
     */
    public function movies_nationalite(string $nationalite): Response
    {

        $search = strtolower($nationalite);
        $array_movies = [];
        foreach ($this->obj_json as $movie) {
            if(preg_match("/{$search}/i", strtolower($movie->nationalite))){
                $array_movies[] = $movie;
            }
        }

        return $this->render('movies/movies_nationalite.html.twig', [
            'controller_name' => 'MoviesController',
            'movies' => $array_movies
        ]);
    }

}
