<?php


namespace App\Controller;


use App\Entity\ForgotPassword;
use App\Entity\Users;
use App\Form\UserType;
use App\Form\UserTypeResetPassword;
use App\Repository\RolesRepository;
use App\Repository\UsersRepository;
use App\Service\Basket\BasketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    public function __construct(UsersRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, BasketService $basketService): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            "current_menu" => 'user',
            'items' => $basketService->getFullCart(),
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, RolesRepository $rolesRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new Users();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $roles = $rolesRepository->findBy(['name' => 'ROLE_USER'])[0];
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles($roles);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('login');
        }

        return $this->render('security/register.html.twig', [
            "current_menu" => 'user',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/oubli-mot-de-passe", name="forgotten.password", methods="GET|POST")
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @return Response
     * @throws \Exception
     */
    public function forgottenPassword(Request $request, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        if ($request->isMethod('POST')) {

            $email = $request->request->get('_username');

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(Users::class)->findOneBy(['username' => $email]);
            /* @var $user Users */

            if ($user === null) {
                $this->addFlash('danger', 'Si votre email existe, un mail vous sera envoyé !');
                return $this->redirectToRoute('forgotten.password');
            }
            $token = $tokenGenerator->generateToken();

            $url = $this->generateUrl('reset.password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Oubli de mot de passe - Réinisialisation'))
                ->setFrom(['youn-29@hotmail.com' => 'BarberShop'])
                ->setTo($user->getUsername())
                ->setBody(
                    $this->renderView(
                        'security/emails/resetPasswordMail.html.twig',
                        [
                            'user' => $user,
                            'url' => $url
                        ]
                    ),
                    'text/html'
                );

            $date = date_create(date('Y-m-d H:i:s'));
            date_add($date, date_interval_create_from_date_string('2 hours'));


            $forgotPassword = new ForgotPassword();
            $forgotPassword
                ->setToken($token)
                ->setUser($user)
                ->setIsValid(true)
                ->setExpirationDate(new \DateTime(date_format($date, 'Y-m-d H:i:s')));


            $em->persist($forgotPassword);
            $em->flush();

            $mailer->send($message);

            $this->addFlash('notice', 'Si votre email existe, un mail vous sera envoyé !');

            return $this->redirectToRoute('login');
        }

        return $this->render('security/forgottenPassword.html.twig');
    }

    /** Réinisialiation du mot de passe par mail
     * @Route("/reset-password/{token}", name="reset.password")
     */
    public function resetPassword(ForgotPassword $forgotPassword, $token, Request $request, UserPasswordEncoderInterface $passwordEncoder, BasketService $basketService)
    {
        if ($forgotPassword->getToken() === null || $token !== $forgotPassword->getToken())
        {
            throw new AccessDeniedHttpException();
        }
        $form = $this->createForm(UserTypeResetPassword::class, $forgotPassword->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $password = $passwordEncoder->encodePassword($forgotPassword->getUser(), $forgotPassword->getUser()->getPassword());
            $forgotPassword->getUser()->setPassword($password);
            $em->persist($forgotPassword->getUser());
            $em->flush();
            $this->addFlash('Success', 'Votre mot de passe a été mis à jour');
            return $this->redirectToRoute('login');
        }

        return $this->render('security/resetPassword.html.twig', [
            'token' => $token,
            'form' => $form->createView(),
            'items' => $basketService->getFullCart(),
        ]);

    }

}