<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Je créée une class qui va permettre de gérer une authentification lors de la connexion sur le site
    // depuis un identifiant, un mot de passe, un rôle (administrateur,...)

    // En ligne de commande :

    // => php bin/console make:user (Je créer d'abord une class user, une table user qui sera générée dans ma BDD)
    // Elle me demande si je veux que Doctrine me gère des mots de passe pour y accéder depuis des identifiants?
    // Une liste d'identifiants m'est proposée : firstname, email...et celui qui se trouve entre crochet
    //est celui qui sera proposé en identifiant par défaut. Bin que je réponds "Oui  biensûr !":

    // => yes

    // A ce stade une nouvelle entité User a été crée par SF, une class UserRepository va gérér les requêtes
    // MsqL, et un fichier de configuration pour gérer les différents rôles 'user' de mon site.

    // => php bin/console make:migration (Je génère mes modifications à envoyer dans ma BDD)

    // => php bin/console doctrine:migrations:migrate (Je fais migrer les nouvelles données ajoutées avec doctrine dans ma BDD)
    // Ma BDD a donc été modifiée et contient maintent une table user.

    // =>php bin bin/console make:auth

    // => MyPASSWORD

    // Pour accéder à chaque profil (admin, utilisateur,...) il faudra y coupler un mot de passe.
    // Pour des questions de sécurité, on évitera de générer un tableau de mots de passe en BD.
    // On utilise donc un cryptage de mot passe, qui récupèrera le 'password' entré dans le formulaire ,
    // et le compare à celui inscrit dans la BDD.
    // Si les deux correspondent, l'accès au profil est donc autorisé.
    // Dans le cas où la BDD se fait pirater avec les mots de passes en 'clair', on aura donc grâce au cryptage, l'état
    // d'accès au profil défini : 'juste impossible d'y accéder', car ces mots de passe en 'clair' et cryptés
    // ne corresponderont pas.
    // L'ACCES AU SITE NE SERA EFFECTIF QUE SI LE PASSWORD SAISI EN FORMULAIRE CORRESPOND EXACTEMENT
    // AU CRYPTAGE DU PASSWORD EN BDD, COMME ON L'AURA PREDEFENI LORS DE LA SAISIE DU PASSWORD EN INSRCIPTION.
    //Tous ces mots pour dire que l'authentification se fait en ligne commande avec

    // =>php bin/console make : auth (On génère l'authentification)

    // =>php bin/console security:encode-password (Pour créer un PASSWORD crypté)

    // =>une clé apparaît : je saisie mon mot de passe

    // =>ENTREE (2 lignes me sont affichées : je copie la deuxième ligne, qui correspond à un cryptage en JSON
    // correspondant à mon mot de passe, dans le champs Password de ma BDD corresponadant à l'iditifiant du user que j'enregistre)



    //Ici je créée une route pour faire afficher la page de login  du  profil  :
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    // Puis je créée la route pour déconnecter le User de sa page de profil : "On pourra le faire afficher avec
    // un bouton de déconnexion et le rediriger vers la page d'accueil ou une autre page à notre convenance.
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
